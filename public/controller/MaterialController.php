<?php
namespace app\api\controller;

use cmf\controller\HomeBaseController;
use think\Db;

class MaterialController extends HomeBaseController
{
	protected $material_model;

	public function _initialize() {
		parent::_initialize();
		$this->material_model = Db::name('material');
	}

	// 上传素材接口
	public function upload_material()
	{
		$material_type = input('material_type');
		if(!$material_type) $this->backto( 10001 );
		if($material_type !== 'img' && $material_type !== 'whole' && $material_type !== 'text'){
			$this->backto( 10002 );
		}

		$author = input('author');
		if(!$author) $this->backto( 10001 );

		$file_id = input('file_id');
		if(!$file_id && $material_type !== 'text') $this->backto( 10001 );

		$preview = input('preview');
		if(!$preview && $material_type !== 'text') $this->backto( 10001 );

		$text_content = input('text_content');
		if(!$text_content && $material_type === 'text') $this->backto( 10003 );

		$dimension_id = input('dimension_id') ? (int)input('dimension_id') : 0;
		// if(!$dimension_id) $this->backto( 10001 );

		$content = input('content');
		$attribute_type = input('attribute_type') ? (int)input('attribute_type') : 1;

		$add_data = [
			'material_type' => $material_type,
			'text_content'  => $text_content,
			'dimension_id'	=> $dimension_id,
			'file_id'		=> $file_id,
			'content'		=> htmlspecialchars_decode($content),
			'attribute_type'=> $attribute_type,
			'preview'		=> $preview,
			'author'		=> $author,
			'createtime'	=> date("Y-m-d H:i:s"),
			'modify'		=> date("Y-m-d H:i:s"),
		];

		$new_material_id = $this->material_model->insertGetId($add_data);	//	素材信息入库
		if(!$new_material_id){
			$this->backto( 20002 );
		}else{
			$this->backto( 0 ,"OK",["materialId"=>$new_material_id]);
		}
	}

	// 根据素材ID 查询素材接口
	public function find_material_by_id(){
		$_id = input('material_id');
		if(!$_id) $this->backto( 10001 );

		$field = 'm.id ,dimension_name ,dimension_name_en ,material_type ,content ,author ,dimension_id ,modify ,file_id as files';
		$child_select = Db::name('dimension')->field("id,dimension_name,dimension_name_en,status")->where(['status'=>1])->buildSql();
		$data = $this->material_model
				->alias("m")
				->join([$child_select=>'d'] , 'd.id = m.dimension_id' , 'LEFT')
				->field($field)
				->where(['m.id'=>$_id,'m.status'=>1])
				->find();
		if(!$data){
			$this->backto( 20003 );
		}else{
			$_files = json_decode($data['files']);
			$_files = Db::name('file')->field("id ,path ,type")->where(['id'=>["in",$_files],'status'=>1])->select();
			foreach ($_files as $k => $v) {
				$file_array[$v['id']] = $v;
				$file_array[$v['id']]['path'] = files_truth_path( $v['path'] , $v['type'] );
			}
			$data['files'] = $file_array;
			$this->backto( 0 , "OK" , $data );
		}
	}
}
