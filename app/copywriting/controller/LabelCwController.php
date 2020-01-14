<?php


namespace app\copywriting\controller;

use cmf\controller\AdminBaseController;
use app\copywriting\model\LabelCwModel;

class LabelCwController extends AdminBaseController
{


    //主页面，列表显示
    public function index(){

        $key_name = input('keyword_name');
        $model = new LabelCwModel();
        $data = $model->getLabelName($key_name);
        $this->assign('page', $data->render());
        $this->assign('data', $data);
        return $this -> fetch();
    }

    //位置添加
    public function add()
    {
        if(request() ->isPost()){
            $label_name = input('label_name');
            $model = new LabelCwModel();
            $model ->addLabel($label_name);
            return $this->success('位置添加成功',url("index"));

        }else{
            return $this -> fetch();
        }


    }

    //位置删除
    public function del()
    {
        $id = input('id');
        $model = new LabelCwModel();
        $model ->delLabel($id);
        return $this->success('位置删除成功',url("index"));

    }


    //位置修改
    public function edit()
    {
        $model = new LabelCwModel();
        $id = input('id');
        if(request()->isPost()){
            $label_name = input('label_name');
            $model ->editLabel($id,$label_name);
            return $this->success('位置修改成功',url("index"));
        }else{

            $data = $model ->findEdit($id);
            $this->assign('data', $data);
            return $this -> fetch();
        }

    }





}