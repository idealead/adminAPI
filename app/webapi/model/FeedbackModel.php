<?php


namespace app\webapi\model;


use think\Model;

class FeedbackModel extends Model
{

    //添加用户反馈
    public function addFeedback($user_id,$content,$feedback_class)
    {
        $model = new FeedbackModel();
        $time = date("Y-m-d H:i:s");
        $data = [
            'user_id'=>$user_id,
            "content"=>$content,
            'create_time'=>$time,
            'feedback_class'=>$feedback_class,
        ];
        $feedback_id = $model->insertGetId($data);
        if ($feedback_id) {
            $res=[
                'code'=>200,
                'message'=>'反馈成功'
            ];
            $res = json_encode($res);
            return $res;
        }else{
            $res=[
                'code'=>201,
                'message'=>'反馈失败'
            ];
            $res = json_encode($res);
            return $res;
        }

    }


}