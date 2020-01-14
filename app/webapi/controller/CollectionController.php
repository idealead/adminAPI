<?php


namespace app\webapi\controller;


use app\webapi\model\CollectionModel;
use app\webapi\model\TemplateModel;
use cmf\controller\HomeBaseController;

class CollectionController extends HomeBaseController
{

    //添加个人收藏
    public function add_collection()
    {
        if (request()->isPost()){
            $model_c = new CollectionModel();
            $model_t = new TemplateModel();
            $is_user = input('is_user');
            if(!$is_user) $this->backto( 10001 );
            $template_id = input('template_id');
            if(!$template_id) $this->backto( 10001 );
            $user_id = input('user_id');
            if(!$user_id) $this->backto( 10001 );


            $check = $model_c ->addCollection($user_id,$template_id);
            if ($check){
                $res = $model_t ->changTemplate($template_id,$is_user);
                return $res;
            }else{
                $res = [
                    'code'    => '201',
                    'message' => '添加收藏失败',
                ];
                $res = json_encode($res);
                return $res;
            }
        }else{
            $res = [
                'code'    => '204',
                'message' => '请求方式有误',
            ];
            $res = json_encode($res);
            return $res;
        }

    }

    //取消收藏
    public function change_collection()
    {
       if (request()->isGet()){
           $model_c = new CollectionModel();
           $model_t = new TemplateModel();

           $is_user = input('is_user');
           if(!$is_user) $this->backto( 10001 );

           $user_id = input('user_id');
           if(!$user_id) $this->backto( 10001 );

           $template_id = input('template_id');
           if(!$template_id) $this->backto( 10001 );

           $model_c ->changeCollection($user_id,$template_id);
           if($is_user == 1){
               $res = $model_t ->delTemplateCollection($template_id);
               return $res;
           }else{
               $res = [
                   'code'    => '200',
                   'message' => '取消成功',
               ];
               $res = json_encode($res);
               return $res;
           }
       }else{
           $res = [
               'code'    => '204',
               'message' => '请求方式有误',
           ];
           $res = json_encode($res);
           return $res;
       }

    }

    //获取个人收藏列表
    public function find_collection()
    {
        if (request()->isGet()){
            $user_id = input('user_id');
            if(!$user_id) $this->backto( 10001 );

            $model = new CollectionModel();
            $res = $model ->findCollectionList($user_id);
            return $res;
        }else{
            $res = [
                'code'    => '204',
                'message' => '请求方式有误',
            ];
            $res = json_encode($res);
            return $res;
        }

    }

}