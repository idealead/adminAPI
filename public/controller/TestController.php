<?php
namespace app\api\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class TestController extends AdminBaseController
{
	protected $dimension_model;

	public function _initialize() {
		parent::_initialize();
		$this->dimension_model = Db::name('dimension');
	}

	/**
     *  测试上传素材
     *  @author HB
     *  2019-07-22
     */
    public function index()
    {
        $dimension_ret = $this->dimension_model->where(['status'=>1])->field(" id ,dimension_name ,dimension_des ")->select();
        $this->assign( "dimension" , $dimension_ret);
        return $this->fetch();
    }
}