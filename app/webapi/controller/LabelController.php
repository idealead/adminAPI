<?php
/**
 * Created by PhpStorm.
 * User: 15161
 * Date: 2019/12/17
 * Time: 22:35
 */

namespace app\webapi\controller;


use app\webapi\model\LabelAnswerModel;
use app\webapi\model\LabelClassModel;
use app\webapi\model\LabelTitleModel;
use cmf\controller\HomeBaseController;

class LabelController extends HomeBaseController
{

    //获取标签题目类别
    public function get_label_class()
    {
       if(request()->isGet()){
           $model = new LabelClassModel();
           $data = $model ->getClass();
           $res = [
               'code' =>200,
               'message'=>'成功',
               'data' =>$data,
           ];
           $res = json_encode($res);
           return $res;
       }else{
           $res = [
               'code' =>201,
               'message'=>'请求方式错误',
           ];
           $res = json_encode($res);
           return $res;
       }
    }



    //获取所选标签类别的所有题目级选项，并组合，返回
    public function get_label_title()
    {
        $tmp = [];
        $data_five = [];
        if (request()->isGet()){
            $label_class_id = input('label_class_id');
            // $label_class_id = 1;
            $model = new LabelTitleModel();
            $model_a = new LabelAnswerModel();
            $data_title = $model ->getTitle($label_class_id);
            $data_answer = $model_a->getAllAnswer();

            foreach ($data_title as $k =>$v){
                $answer_ids =explode(',',$v['label_answer_id']);
                foreach ( $answer_ids as $ak =>$av){
                    foreach ($data_answer as $ank =>$anv){
                        if($av == $anv['id'] ){
                            $data_title[$k]['answer'][$ak] =$anv;
                        }
                    }
                }
            }
            //获取第三题人群
            $data_person = $model ->getPerson();
            foreach ($data_person as $k =>$v){
                $answer_ids =explode(',',$v['label_answer_id']);
                foreach ( $answer_ids as $ak =>$av){
                    foreach ($data_answer as $ank =>$anv){
                        if($av == $anv['id'] ){
                            $tmp[$ak] =$anv;
                        }
                    }
                }
            }
            $res = [
                'code' =>200,
                'message'=>'成功',
                'data' =>$data_title,
                'data_person'=>$tmp
            ];
            $res = json_encode($res);
            return $res;
        } else{
            $res = [
                'code' =>201,
                'message'=>'请求方式错误',
            ];
            $res = json_encode($res);
            return $res;
        }

    }


    //前端接口：获取用户选择标签

    public function get_user_label()
    {
        $label = input();
        dump($label);
        die;

    }


    //返回所有QA图片路径
    public function get_qa_path()
    {
        if (request()->isGet()){
            $model_an = new LabelAnswerModel();
            $model_class = new LabelClassModel();
            $data_an = $model_an ->findAllPath();
            $data_class = $model_class->findAllPath();
            $data = array_merge($data_class,$data_an);
            $res = [
                'code'=>200,
                'data'=>$data,
                'message' => '成功'
            ];
            $res = json_encode($res);
            return $res;
        }else{
            $res = [
                'code'=>201,
                'message' => '请求方式错误'
            ];
            $res = json_encode($res);
            return $res;
        }

    }
}