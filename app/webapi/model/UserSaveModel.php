<?php


namespace app\webapi\model;

use app\webapi\model\TamplateModel;
use think\Model;

class UserSaveModel extends  Model
{

    //增加保存模板
    public function addSave($template_id,$user_id)
    {
        $time = date("Y-m-d H:i:s");
        $model = new UserSaveModel();
        $data = [
            'template_id' => $template_id,
            'user_id'     => $user_id,
            "create_time" => $time,
        ];
        $save_id = $model->insertGetId($data);
        if($save_id){
            $res = [
                'code'    => '200',
                'message' => '模板保存成功',
                'save_id'    => $save_id
            ];
            $res = json_encode($res);
            return $res;
        }else{
            $res = [
                'code'    => '202',
                'message' => '模板保存失败',
            ];
            $res = json_encode($res);
            return $res;
        }
    }


    //删除用户保存模板
    public function delSave($id)
    {
        $model = new UserSaveModel();
        $data = $model ->get($id);
        if($data){
            $data-> is_delete = 0;
            $data->save();
            $res = [
                'code'    => '200',
                'message' => '取消保存成功',
            ];
            $res = json_encode($res);
            return $res;
        }else{
            $res = [
                'code'    => '201',
                'message' => '没有找到该保存文件请刷新',
            ];
            $res = json_encode($res);
            return $res;
        }



    }


    //获取用户保存列表

    public function findSaveList($user_id)
    {
        $model = new TemplateModel();
        $where['author'] =$user_id;

        $model_field = "
                   t.id,path,t.level,t.price,t.author
        ";
        $data = $model ->where($where)
                    ->alias('t')
                    ->join(['in_file'=>'f'] , 't.preview = f.id' , 'LEFT')
                    ->field($model_field)
                    ->select()->toArray();
               if ($data){
            $res = [
                'code'    => '200',
                'message' => '返回成功',
                'data'    => $data
            ];
            $res = json_encode($res);
            return $res;
        }else{
            $res = [
                'code'    => '201',
                'message' => '目前没有任何保存模板',
                'data'    =>'',
            ];
            $res = json_encode($res);
            return $res;
        }

    }




}