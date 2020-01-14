<?php
namespace app\api\controller;

use cmf\controller\HomeBaseController;
use app\api\model\TemplateModel;
use think\Db;

class TemplateController extends HomeBaseController
{
	protected $template_model;
	protected $contrast_model;

	public function _initialize() {
		parent::_initialize();
		$this->template_model = Db::name('template');
		$this->contrast_model = Db::name('contrast');
	}


	// 上传模板接口
	public function upload_template()
	{
		$name = input('name');
		if(!$name) $this->backto( 10001 );

		$preview = input('preview');
		if(!$preview) $this->backto( 10001 );

		$author = input('author');
		if(!$author) $this->backto( 10001 );

		$template_width = input('t_width');
		if(!$template_width) $this->backto( 10001 );

		$template_height = input('t_height');
		if(!$template_height) $this->backto( 10001 );

		$material_content = input('material_content');
		if(!$material_content) $this->backto( 10001 );

		$new_template_id = input('id');
		$level 			 = input('level');
		$has_title 		 = input('has_title') ? input('has_title') : 0;
		$has_subtitle 	 = input('has_subtitle') ? input('has_subtitle') : 0;
		$has_logo 		 = input('has_logo') ? input('has_logo') : 0;
		$has_product 	 = input('has_product') ? input('has_product') : 0;

		$add_data = [
			'template_name'		=> $name,
			'preview'			=> $preview,
			'author'			=> $author,
			'template_width'	=> $template_width,
			'template_height'	=> $template_height,
			'material_content'	=> $material_content,
			'level'				=> $level,
			'has_title'			=> $has_title,
			'has_subtitle'		=> $has_subtitle,
			'has_logo'			=> $has_logo,
			'has_product'		=> $has_product,
			'modify'			=> date("Y-m-d H:i:s"),
		];
		if(!$new_template_id){
			$add_data['createtime'] = date("Y-m-d H:i:s");
			$new_template_id = $this->template_model->insertGetId($add_data);	//	模板信息入库
		}else{
			$ret = $this->template_model->where(['id'=>$new_template_id])->update($add_data);
		}
		if(!$new_template_id && !$ret){
			$this->backto( 20002 );
		}else{
			$this->backto( 0 ,"OK",["templateId"=>$new_template_id]);
		}
	}

	// 根据模板ID 查询模板接口
	public function find_template_by_id(){
		$_id = input('template_id');
		if(!$_id) $this->backto( 10001 );

		$template_field = 't.id, template_name, template_width, template_height, label_id, material_content, f.path as preview_img, f.type as preview_type, t.author, t.level';
		$template_where = ['t.id'=>$_id,'t.status'=>1];
		$data = $this->template_model
				->alias("t")
				->join( ['in_file'=>'f'] , 'f.id = preview' , 'LEFT' )
				->field($template_field)
				->where($template_where)
				->find();
		if(!$data || !$data['material_content']){
			// 找不到该模板
			$this->backto( 20003 );
		}else{
			// 模板信息处理
			$material_content_ids = json_decode($data['material_content']);
			$data['preview_img'] = files_truth_path($data['preview_img'] ,$data['preview_type']);
			$material_ids = [];
			foreach ($material_content_ids as $k => $v) {
				if(is_array($v)){
					foreach ($v as $i => $j) {
						$material_ids[] = $j;
					}
				}else{
					$material_ids[] = $v;
				}
			}

			// 查询莫版内的所有素材内容
			$material_field = "
				m.id, m.material_type, m.text_content, m.label_id, m.content, m.author, m.attribute_type, m.file_id as file_content, 
				d.dimension_name, d.dimension_name_en, 
				f.path as preview_img, f.type as preview_type";
			$material_where = ["m.id"=>["in",$material_ids]];
			$material_content = Db::name('material')
							->alias("m")
							->join( ['in_file'=>'f'] , 'f.id = preview' , 'LEFT' )
							->join( ['in_dimension'=>'d'] , 'd.id = m.dimension_id' , 'LEFT' )
							->field($material_field)
							->where($material_where)
							->select()
							->toArray()
							;
			$material_content = array_column($material_content , null , "id");

			// 查询文件数据库的ID集
			$file_ids = [];
			// 整理素材内容
			foreach ($material_content_ids as $k => $v) {
				if( $v === null ){
					$material_content_ids[$k] = "";
					continue;
				}
				if(is_array($v)){
					foreach ($v as $i => $j) {
						if(!$material_content[$j]['file_content']){
							$material_content_ids[$k][$i] = $material_content[$j];
							continue;
						}
						$material_content[$j]['file_content'] = json_decode($material_content[$j]['file_content'],1);
						// 提取文件ID
						if(is_array( $material_content[$j]['file_content'] )){
							foreach ($material_content[$j]['file_content'] as $file_content_key => $file_content_value) {
								if( !in_array( $file_content_value , $file_ids) ){
									$file_ids[] = $file_content_value;
								}
							}
						}else{
							if( !in_array( $material_content[$j]['file_content'] , $file_ids) ){
								$file_ids[] = $material_content[$j]['file_content'];
							}
						}
						
						$material_content_ids[$k][$i] = $material_content[$j];
						$material_content_ids[$k][$i]['preview_img'] = files_truth_path($material_content[$j]['preview_img'] ,$material_content[$j]['preview_type']);
					}
				}else{
					if(!$material_content[$v]['file_content']){
						$material_content_ids[$k] = $material_content[$v];
						continue;
					}
					$material_content[$v]['file_content'] = json_decode($material_content[$v]['file_content'],1);
					// 提取文件ID
					if(is_array( $material_content[$v]['file_content'] )){
						foreach ($material_content[$v]['file_content'] as $file_content_key => $file_content_value) {
							if( !in_array( $file_content_value , $file_ids) ){
								$file_ids[] = $file_content_value;
							}
						}
					}else{
						if( !in_array( $material_content[$v]['file_content'] , $file_ids) ){
							$file_ids[] = $material_content[$v]['file_content'];
						}
					}

					$material_content_ids[$k] = $material_content[$v];
					$material_content_ids[$k]['preview_img'] = files_truth_path($material_content[$v]['preview_img'] ,$material_content[$v]['preview_type']);

				}
			}
			$data['material_content'] = $material_content_ids;

			// 根据文件ID集，查找所有文件详情
			$file_content_array = Db::name('file')->where(['id'=>['in',$file_ids] ])->select()->toArray();

			if($file_content_array){
				foreach ($file_content_array as $file_content_array_key => $file_content_array_value) {
					$file_content_array[$file_content_array_key]['path'] = files_truth_path($file_content_array_value['path'] ,$file_content_array_value['type']);
				}
			}
			$file_content_array = array_column($file_content_array , null , "id");

			foreach ($data["material_content"] as $k => $v) {
				if( isset($v['file_content']) && $v['file_content'] )
				{
					if(is_array($v['file_content'])){
						foreach ($v['file_content'] as $i => $j) {
							$data["material_content"][$k]['file_content'][$i] = $file_content_array[$j];
						}
					}else{
						$data["material_content"][$k]['file_content'] = $file_content_array[$v];
					}
				}
				// else
				// {
					// foreach ($v as $k_2 => $v_2) {
					// 	if(is_array($v_2['file_content'])){
					// 		foreach ($v_2['file_content'] as $i => $j) {
					// 			$data["material_content"][$k][$k_2]['file_content'][$i] = $file_content_array[$j];
					// 		}
					// 	}else{
					// 		$data["material_content"][$k][$k_2]['file_content'] = $file_content_array[$v_2];
					// 	}
					// }
				// }
			}
			// dump($data);die;
			// unset($data["material_content"]);
			$this->backto( 0 , "OK" , $data );
		}
	}

	/**
	 * 上传临时模板接口
	 */
	public function auto_upload_template()
	{
		$token = input('token');
		if(!$token) $this->backto( 10001 );

		$name = input('name');
		// if(!$name) $this->backto( 10001 );

		$preview = input('preview');
		if(!$preview) $this->backto( 10001 );

		$author = input('author');
		if(!$author) $this->backto( 10001 );

		$template_width = input('t_width');
		if(!$template_width) $this->backto( 10001 );

		$template_height = input('t_height');
		if(!$template_height) $this->backto( 10001 );

		$material_content = input('material_content');
		if(!$material_content) $this->backto( 10001 );

		$template_id = input('id');
		if(!$template_id) $this->backto( 10001 );

		$level = input('level');
		$_time = date("Y-m-d H:i:s");

		$add_data = [
			'template_name'		=> $name,
			'preview'			=> $preview,
			'author'			=> $author,
			'template_width'	=> $template_width,
			'template_height'	=> $template_height,
			'material_content'	=> $material_content,
			'level'				=> $level,
			'modify'			=> $_time,
			'createtime'		=> $_time,
		];
		$new_template_id = $this->template_model->insertGetId($add_data);	//	模板信息入库

		if(!$new_template_id){
			$this->backto( 20002 );
		}else{
			// 对比模版的结果入库
			$contrast_add = [
				"token" 		=> $token,
				"old_id" 		=> $template_id,
				"new_id" 		=> $new_template_id,
				"createtime" 	=> $_time,
			];
			$this->contrast_model->insert($contrast_add);

			$this->backto( 0 ,"OK",["templateId"=>$new_template_id]);
		}
	}



    /**
     * 查看不同状态核模板信息接口
     */
    public function template_audit_list()
    {
        $user_id = input('user_id');
        $status  = input('status');
        $model   =  new TemplateModel();
        $data    =  $model ->templateAuditList($user_id,$status);
        return $data;
    }

    /**
     * 给模板添加标签
     */

    public function add_template_label()
    {
        $id = input('id');
        $label_id = input('label_id');
        if(is_array($label_id)){
            $label_id = implode(",", $label_id);
        }else{
            $res = [
                'code'    => '201',
                'message' => "标签格式有问题",
            ];
            $res = json_encode($res);
            return $res;
        }
        $model = new TemplateModel();
        $res = $model -> addTemplateLabel($id,$label_id);
        return $res;
    }


    //前端：临时模板   未提交审核接口 提交后，变为审核通过，需打标签
    public function temp_change_status()
    {
        $id = input('id');
        if($id){
            $model = new TemplateModel();
            $res = $model->tempChangeStatus($id);
            return $res;
        }else{
            $res = [
                'code'    => '201',
                'message' => '没有找到到模板id',
            ];
            return $res;
        }

    }

    //前端：审核通过模板，打标签
    public function complete_template_audit()
    {
        $id = input('id');
        if($id){
            $model = new TemplateModel();
            $res = $model->completeTemplateAudit($id);
            return $res;
        }else{
            $res = [
                'code'    => '201',
                'message' => '没有找到到模板id',
            ];
            return $res;
        }
    }

}
