<?php


namespace app\copywriting\controller;

use app\copywriting\model\CopywritingLabelModel;
use cmf\controller\AdminBaseController;
use app\copywriting\model\CopywritingModel;
use app\copywriting\validate\CopywritingValidate;


class CopywritingController extends AdminBaseController
{

    //文本列表显示

    public function index()
    {
        $key_name = input('keyword_name');
        $model = new CopywritingModel;
        $data = $model->getLabelName($key_name);
        $this->assign('page', $data->render());
        $this->assign('data', $data);
        return $this -> fetch();
    }


    //文本修改

    public function edit()
    {
        $id = input('id');
        $model = new CopywritingModel();
        if(request()->isPost()){

            $content = input('content');
            $model -> edit($id,$content);
            return $this->success('修改文案成功',url("index"));

        }else{
            $data = $model ->get($id);
            $this->assign('data', $data);
            return $this -> fetch();
        }

    }

    //文本删除

    public function del($id)
    {

        $model = new CopywritingModel();
        $model ->del($id);
        $model_label = new CopywritingLabelModel();
        $model_label->del($id);
        return $this->success('文案删除成功',url("index"));

    }

    //文本添加

    public function add()
    {
        if(request()->isPost()){
            $data = input();
            $validate = new CopywritingValidate();
            $result = $validate ->checkCopywriting($data);
            if ($result !== null) {
                $this->error($result);
            }
            //插入文案
            $model = new CopywritingModel();
            $copywriting_id = $model ->add($data);
            //插入文案标签
            $model_label = new CopywritingLabelModel();
            $model_label ->add($data,$copywriting_id);
            return $this->success('添加文本成功',url("index"));

        }else{

            $model = new CopywritingModel();

            $data_position = $model ->getPositionList();

            $this->assign('data_position', $data_position);

            $data =$model ->getLabelList();
            $this->assign('data', $data);
            return $this->fetch();
        }
    }



    //请求返回的html，做foreach便利，点击加号
    public function returnAddHtml()
    {
        $model = new CopywritingModel();
        $data = $model ->getLabelList();
        $this->assign('data', $data);
        $html = $this->fetch('template_add');
        $res = json($html);
        return $res;
    }


    //请求返回的html，做foreach便利，点击减号
    public function returnReHtml()
    {
        $model = new CopywritingModel();
        $data = $model ->getLabelList();
        $this->assign('data', $data);
        $html = $this->fetch('template_re');
        $res = json($html);
        return $res;
    }

}