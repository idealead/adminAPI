<?php


namespace app\webapi\controller;


use app\webapi\model\TemplateModel;
use app\webapi\model\UserSaveModel;
use cmf\controller\HomeBaseController;
use think\Db;
class SaveController extends HomeBaseController
{

    //用户添加保存模板
    public function add_save()
    {
        if (request()->isPost()){
            $model_u = new UserSaveModel();
            $model_t = new TemplateModel();

            $is_user = input('is_user');
            if(!$is_user) $this->backto( 10001 );

            $template_id = input('template_id');
            if(!$template_id) $this->backto( 10001 );

            $user_id = input('user_id');
            if(!$user_id) $this->backto( 10001 );

            if($is_user == 1){
                $check=$model_u->addSave($template_id,$user_id);
                $res = $model_t ->changeSave($template_id,1);
                return $res;
            }elseif($is_user == 2){
                $res = $model_u->addSave($template_id,$user_id);
                return $res;
            }else{
                $res = [
                    'code'    => '203',
                    'message' => 'is_user参数有误',
                ];
                $res = json_encode($res);
                return $res;
            }
        }else{
            $res = [
                'code'    => '204',
                'message' => '请求方式错误',
            ];
            $res = json_encode($res);
            return $res;
        }



    }


  //  用户删除以保存的模板
    public function del_save()
    {
        if (request()->isPost()) {
            $model = new TemplateModel();
            $user_id = input('user_id');
            $template_id = input('template_id');
            $res= $model ->delSaveTemplate($user_id,$template_id);
            return $res;

        }else{
            $res = [
                'code'    => '204',
                'message' => '请求方式错误',
            ];
            $res = json_encode($res);
            return $res;
        }


    }


    //用户获取模板列表
    public function find_save_list()
    {
        if (request()->isGet()){
            $user_id = input('user_id');
            if(!$user_id) $this->backto( 10001 );

            $model = new TemplateModel();
            $res = $model ->findSaveList($user_id);
            return $res;
        }else{
            $res = [
                'code'    => '204',
                'message' => '请求方式错误',
            ];
            $res = json_encode($res);
            return $res;
        }

    }


}