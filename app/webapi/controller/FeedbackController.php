<?php


namespace app\webapi\controller;


use app\webapi\model\FeedbackClassModel;
use app\webapi\model\FeedbackModel;
use cmf\controller\HomeBaseController;

class FeedbackController extends HomeBaseController
{

    //用户反馈添加
    public function add_feedback()
    {
        if(request()->isPost()){
            $user_id = input('user_id');
            if(!$user_id) $this->backto( 10001,'user_id为空' );
            $feedback_class = input('feedback_class');
            if(!$user_id) $this->backto( 10001,'返回问题类别不能为空' );
            $content = input('content');
            if(!$content) $this->backto( 10002 ,"反馈信息为空");
            $model = new FeedbackModel();
            $res = $model ->addFeedback($user_id,$content,$feedback_class);
            return $res;
        }else{
            $res=[
                'code'=>201,
                'message'=>'请求方式有误'
            ];
            $res = json_encode($res);
            return $res;
        }

    }

    //查询分类
    public function get_feedback_class()
    {
        if (request()->isGet()){
            $model = new FeedbackClassModel();
            $data = $model ->findClass();

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


    //添加用户反馈
    public function text(){
        if(request()->isPost()){
            $user_id = input('user_id');
            if(!$user_id) $this->backto( 10001,'user_id不能为空' );
            $content = input('content');
            if(!$content) $this->backto( 10002 ,"反馈信息为不能空");
            $feedback_class_id = input('feedback_class_id');
            if(!$feedback_class_id) $this->backto( 10003 ,"问题分类为不能空");
            $score = input('score');
            if(!$score) $this->backto( 10004 ,"评分");

            $data = [
                'user_id' =>$user_id,
                'content' =>$content,
                'feedback_class_id'=>$feedback_class_id,
                'score' =>$score
            ];
            $model = new FeedbackModel();
            $res = $model ->addFeedback($data);
            return $res;
        }else{
            $res=[
                'code'=>201,
                'message'=>'请求方式有误'
            ];
            $res = json_encode($res);
            return $res;
        }
    }
}