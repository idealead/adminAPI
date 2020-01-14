<?php


namespace app\webapi\model;


use think\Model;
use think\Session;

class TemplateModel extends Model
{

    //添加模板收藏状态

    public function changTemplate($template_id,$is_user)
    {

        $model = new TemplateModel();
        if($is_user == 1){
            $where['id'] = ["eq",$template_id];
            $data = $model ->get($template_id);
            if($data){
                $data-> is_collection = 1;
                $data->save();
                $res = $res = [
                    'code'    => '200',
                    'message' => '收藏成功',
                ];
                $res = json_encode($res);
                return $res;
            }else{
                $res = $res = [
                    'code'    => '201',
                    'message' => '没有发现模板,无法收藏',
                ];
                $res = json_encode($res);
                return $res;
            }

        }elseif($is_user == 2){
            $where['id'] = ["eq",$template_id];
            $data = $model ->where($where)->find()->toArray();
            $res = $model ->get($template_id);
            if($data){
                $collection_num = $data['collection_num'] +1 ;
                $res-> collection_num = $collection_num;
                $res->save();

                $res = [
                    'code'    => '200',
                    'message' => '收藏成功',
                ];
                $res = json_encode($res);
                return $res;

            }else{
                $res = [
                    'code'    => '201',
                    'message' => '没有发现模板,无法收藏',
                ];
                $res = json_encode($res);
                return $res;
            }
        }else{
            $res = [
                'code'    => '202',
                'message' => 'is_user:参数有误请确认',
            ];
            $res = json_encode($res);
            return $res;
        }


    }


    //取消收藏
    public function delTemplateCollection($template_id)
    {
        $model = new TemplateModel();
        $data = $model ->get($template_id);
        if($data){
            $data-> is_collection = 0;
            $data->save();
            $res = [
                'code'    => '200',
                'message' => '取消收藏成功',
            ];
            $res = json_encode($res);
            return $res;
        }else{
            $res = [
                'code'    => '201',
                'message' => '取消收藏失败',
            ];
            $res = json_encode($res);
            return $res;
        }
    }


    //修改模板保存状态
    public function changeSave($template_id,$status)
    {
        $model = new TemplateModel();
        $data = $model ->get($template_id);
        if($data){
            $data-> is_save = $status;
            $data->save();
            $res = [
                'code'    => '200',
                'message' => '模板状态改变成功',
            ];
            $res = json_encode($res);
            return $res;
        }else{
            $res = [
                'code'    => '201',
                'message' => '模板状态改变失败',
            ];
            $res = json_encode($res);
            return $res;
        }
    }

    //获取全部模板
    public function getAllTemplate($user_id,$template_id)
    {
        $model = new TemplateModel();
        $model_field = "
                    t.id, f.path, t.level ,t.author,t.price
        ";
        //$where['t.status'] = ["eq",1];
        $where['t.level'] = ["eq","permanent"];
        $data = $model
            ->where($where)
            ->alias("t")
            ->join(['in_file'=>'f'] , 't.preview = f.id' , 'LEFT')
            ->field($model_field)
            ->select()->toArray();
        $res = [];
//        foreach ($data as $k =>$v){
//            foreach ($template_id as $tk =>$tv){
//                if ($v["id"] ==$tv ){
//                    $res[$k] = $v;
//                }
//            }
//        }
        foreach ($template_id as $k =>$v){
            foreach ($data as $dk =>$dv){
                if ($v == $dv['id']){
                    $res[$k] = $dv;
                }
            }
        }


        Session::set($user_id,$res);

        return $data;

    }

    //查看用户保存模板
    public function findSaveList($user_id)
    {
        $where['t.author'] = ["eq",$user_id];
        $model = new TemplateModel();
        $model_field = "
                    t.id, f.path, t.level ,t.author,t.price,template_name
        ";
        $data = $model
            ->where($where)
            ->alias("t")
            ->join(['in_file'=>'f'] , 't.preview = f.id' , 'LEFT')
            ->field($model_field)
            ->select()->toArray();
        if($data) {
            $res = [
                'code'=>200,
                'message'=>'成功',
                'data'=>$data,
            ];
            $res = json_encode($res);
            return $res;
        }else{
            $res = [
                'code'=>200,
                'message'=>'没有数据',
                'data'=>'',
            ];
            $res = json_encode($res);
            return $res;
        }
    }


    //删除用户保存模板
    public function delSaveTemplate($user_id,$template_id)
    {
        $model = new TemplateModel();
        $where['id'] = ["eq",$template_id];
        $where['author'] = ["eq",$user_id];
        $data = $model ->where($where)->find( );
        if($data){
            $data-> author = 1;
            $data-> level = "temporary";
            $data->save();
            $res = $res = [
                'code'    => '200',
                'message' => '取消成功',
            ];
            $res = json_encode($res);
            return $res;
        }else{
            $res = $res = [
                'code'    => '201',
                'message' => '该模板不属于您，不可删除',
            ];
            $res = json_encode($res);
            return $res;
        }

    }

    //根据页数获取值
    public function getTemplatePage($user_id,$page)
    {
        $data = Session::get($user_id);
        $long = count($data);
        $max_pages =  floor($long/6);
        if($page>$max_pages){
            $res = [
                'code'=>201,
                'message' =>'页数超出',
            ];
            $res= json_encode($res);
            return $res;
        }
        $content = [];
        if ($page == 0){
            $start =  $page*5;
            $end = $start +6;
        }else{
            $start =  $page*5+1;
            $end = $start +5;
        }
        foreach ($data as $k =>$v){
            if ($k>=$start){
                if ($k<$end){
                    $content[$k] = $v;
                }
            }
        }
        $res = [
            'code'=>200,
            'message' =>'成功',
            'max_pages'=>$max_pages,
            'data' =>$content,
        ];
        $res= json_encode($res);
        return $res;
    }


    //获取订单其他信息数据
    public function orderInfo($template_id)
    {
        $model = new TemplateModel();
        $model_field = "
                    t.id, f.path,t.price
        ";
        $where['t.id'] = ["eq",$template_id];
        //$where['t.level'] = ["eq","permanent"];
        $data = $model
            ->where($where)
            ->alias("t")
            ->join(['in_file'=>'f'] , 't.preview = f.id' , 'LEFT')
            ->field($model_field)
            ->find();
        if ($data){
            $data = $data ->toArray();
            return $data;
        }else{
            return $data;
        }


    }

    public function a ()
    {
        $user_id = 10262;
        $data = Session::get($user_id);
        dump($data);
        die;
    }





}