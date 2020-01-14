<?php
namespace app\api\controller;

use cmf\controller\HomeBaseController;
use think\Db;

class FilesController extends HomeBaseController
{
	protected $file_model;

	public function _initialize() {
		parent::_initialize();
		$this->file_model = Db::name('file');
	}

	// 上传参数测试接口
	public function test_upload()
	{
		header("Access-Control-Allow-Origin:*");
		header("Access-Control-Allow-Methods:GET,POST");
		dump(input());
		echo "———————————————— 分割线 ————————————————"."<br>";
		dump(request()->file('upload_file_once'));
		echo "———————————————— 分割线 ————————————————"."<br>";
		dump(request()->file());
	}


	// 上传单个文件接口
	public function upload_file_once()
	{
		header("Access-Control-Allow-Origin:*");
		header("Access-Control-Allow-Methods:GET,POST");
		$file = request()->file('upload_file_once');
		// $file_size	= 1048576;
		$file_size	= 5242880;
		$file_ext	= 'jpg,png,gif';
		$file_root	= ROOT_PATH . 'public';
		$file_path	= 'uploadFiles' . DS . 'images';

		// 文件上传 - 核心代码
		$info = $file->rule('date')->validate(['size'=>$file_size,'ext'=>$file_ext])->move( $file_root . DS . $file_path );

		if($info){
			$save_path = str_replace("\\", "/", $info->getSaveName() );

			// 储存文件信息
			$save_data = [
				'type'		=> $info->getExtension(),
				'path'		=> $save_path,
				'author'	=> input("author_id"),
				'createtime'=> date("Y-m-d H:i:s")
			];
			$new_file_id = $this->file_model->insertGetId($save_data);	//	文件信息入库
			if(!$new_file_id){
				$this->backto( 20002 );
			}

			// 返回数据
			$ret_data = [
				'ext'		=> $info->getExtension(),
				'savepath'	=> files_truth_path($save_path , $info->getExtension()),
				'savename'	=> $info->getFilename(),
				'file_id'	=> $new_file_id
			];

			$this->backto( 0 , "上传成功!" , $ret_data );
		}else{
			$this->backto( 30001 , $file->getError() );
		}
	}
}
