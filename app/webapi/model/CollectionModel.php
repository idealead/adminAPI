<?php


namespace app\webapi\model;


use think\Model;

class CollectionModel extends Model
{

    //添加用户收藏
    public function addCollection($user_id,$template_id)
    {
        $time= date("Y-m-d H:i:s");
        $model = new CollectionModel();
        $data = [
            "user_id"      => $user_id,
            "template_id"  => $template_id,
            "create_time"  => $time,
        ];
        $collection_id = $model->insertGetId($data);
        return $collection_id;

    }


    //取消收藏
    public function changeCollection($user_id,$template_id)
    {
        $model = new CollectionModel();
        $where["user_id"] =["eq",$user_id];
        $where["template_id"] = ["eq",$template_id];
        $data = $model ->where($where)->delete();
        if($data){
            $res = [
                'code'    => '200',
                'message' => '取消收藏成功',
            ];
            $res = json_encode($res);
            return $res;
        }else{
            $res = [
                'code'    => '201',
                'message' => '没有找到该收藏文件请刷新',
            ];
            $res = json_encode($res);
            return $res;
        }
    }


    //获取个人收藏的所有图片
    public function findCollectionList($user_id)
    {
        $model = new CollectionModel();
        $where['c.user_id'] =["eq",$user_id];
        $where_status['c.status'] = ["eq",1];
        $model_field = "
                    c.id,c.template_id,c.user_id,path,t.level,t.price,template_name
        ";
        $data =$model ->where($where)
                ->where($where_status)
                ->alias('c')
                ->join(['in_template'=>'t'] , 'c.template_id = t.id' , 'LEFT')
                ->join(['in_file'=>'file'] , 't.preview = file.id' , 'LEFT')
                ->field($model_field)
                ->select()->toArray();

        if ($data){
            $res = [
                'code'    => '200',
                'message' => '访问成功',
                'data'    => $data
            ];
            $res = json_encode($res);
            return $res;
        }else{
            $res = [
                'code'    => '201',
                'message' => '目前没有任何收藏',
                'data'    => '',
            ];
            $res = json_encode($res);
            return $res;
        }


    }





}