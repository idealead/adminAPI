<?php
namespace app\api\controller;

use cmf\controller\HomeBaseController;
use think\Db;

class DimensionController extends HomeBaseController
{
	protected $dimension_model;

	public function _initialize() {
		parent::_initialize();
		$this->dimension_model = Db::name('dimension');
	}


	// 查询维度接口
	public function select_dimension()
	{
		$ret = $this->dimension_model->where(['status'=>1])->field(" id ,dimension_name ,dimension_des ,dimension_name_en ")->select();
		if($ret){
			$this->backto( 0 , "OK!" , $ret );
		}else{
			$this->backto(20003);
		}
	}
}
