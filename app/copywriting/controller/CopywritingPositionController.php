<?php


namespace app\copywriting\controller;

use app\copywriting\model\CopywritingPositionModel;
use cmf\controller\AdminBaseController;

class CopywritingPositionController extends AdminBaseController
{

    //主页面，列表显示
    public function index(){
        $key_name = input('keyword_name');
        $model = new CopywritingPositionModel();
        $data = $model->getPotionsName($key_name);
        $this->assign('page', $data->render());
        $this->assign('data', $data);
        return $this -> fetch();
    }

    //位置添加
    public function add()
    {
        if(request() ->isPost()){
            $position_name = input('position_name');
            $model = new CopywritingPositionModel();
            $model ->addPosition($position_name);
            return $this->success('位置添加成功',url("index"));

        }else{
            return $this -> fetch();
        }


    }

    //位置删除
    public function del()
    {
        $id = input('id');
        $model = new CopywritingPositionModel();
        $model ->delPosition($id);
        return $this->success('位置删除成功',url("index"));

    }


    //位置修改
    public function edit()
    {
        $model = new CopywritingPositionModel();
        $id = input('id');
        if(request()->isPost()){
            $position_name = input('position_name');
            $model ->editPosition($id,$position_name);
            return $this->success('位置修改成功',url("index"));
        }else{

            $data = $model ->findEdit($id);
            $this->assign('data', $data);
            return $this -> fetch();
        }

    }



}
