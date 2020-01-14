<?php
/**
 * Created by PhpStorm.
 * User: 15161
 * Date: 2019/12/26
 * Time: 10:35
 */

namespace app\feedback\controller;


use app\feedback\model\FeedbackModel;
use cmf\controller\AdminBaseController;

class FeedbackController extends AdminBaseController
{

    //获取列表展示
    public function index()
    {

        $where['f.is_delete'] = ["eq",1];
        // 名称模糊搜索
        $dep_name = input('dep_name');
        $keyword = input("keyword");
        $this->assign('keyword',$keyword);
        $this->assign('dep_name',$dep_name);
        if($keyword) {
            $where['u.user_name'] = ['like', "%" . $keyword . "%"];
        }
        if($dep_name) {
            $where['dep_name'] = ['like', "%" . $dep_name . "%"];
        }

        $model = new FeedbackModel();
        $data = $model ->getList($where);
        $this->assign('data', $data);
        $num = $model ->getNum();
        $this->assign('num', $num);
        return $this->fetch();
    }

}