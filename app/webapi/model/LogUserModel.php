<?php
/**
 * Created by PhpStorm.
 * User: 15161
 * Date: 2019/12/22
 * Time: 21:10
 */

namespace app\webapi\model;


use think\Model;

class LogUserModel extends Model
{

    //写入用户登录日志
    public function addLog($user_id)
    {

        $time= date("Y-m-d H:i:s");
        $model = new LogUserModel();
        $model -> data([
           'user_id' =>$user_id,
            'create_time' => $time,
        ]);
        $model->save();
        return 200 ;
    }

}