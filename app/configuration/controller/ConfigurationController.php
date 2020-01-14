<?php


namespace app\configuration\controller;


use app\configuration\model\NormallyModel;
use cmf\controller\AdminBaseController;

class ConfigurationController extends AdminBaseController
{

    //获取常量显示
    public function index()
    {
        $model = new NormallyModel();
        $data  = $model ->getList();
        $this->assign('data', $data);
        return $this -> fetch();
    }


    //修改页面展示
    public function edit()
    {
        $id = input('id');
        $model = new NormallyModel();
        $data = $model ->getContent($id);

        $this->assign('data',$data);
        return $this -> fetch();
    }

    //处理修改，返回列表
    public function editUp()
    {
        $id = input('id');
        $number = input('number');
        $model = new NormallyModel();
        $model->updateNumber($id,$number);

        $this ->success('修改成功',url('Configuration/index'));

    }


}