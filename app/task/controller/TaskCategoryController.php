<?php


namespace app\task\controller;

use cmf\controller\AdminBaseController;
use app\task\model\TaskCategoryModel;
use app\task\model\TaskDrawModel;
use app\task\model\TempUserModel;
use app\task\validate\TaskValidate;

class TaskCategoryController extends AdminBaseController
{
    //已发布任务列表
    public function index()
    {
        $keyWord = input("keyword");
        $model = new TaskCategoryModel();

        if($keyWord){
            $data = $model ->KeyReleaseList($keyWord);
        }else{
            $data = $model ->releaseList();
        }
        $this->assign('data', $data);

        $this->assign('page', $data->render());
        return $this -> fetch();
    }


    //增加
    public function add()
    {
        if(IS_POST){
            $data = input();
            $validate = new TaskValidate();
            $result = $validate ->taskCategoryAdd($data);
            if ($result !== null) {
                $this->error($result);
            }
            $status = input('status');
            $num = input('number');
           if($status == 1){
               $_post = input();
               $model = new TaskCategoryModel();
               $task_category_id    = $model ->add($_post);
               $this ->automaticTaskAssignment($task_category_id,$num);
               return $this->success('发布任务成功',url("index"));
           }elseif ($status == 2)
           {    $_post = input();
                $person = $_post['user_id'];
                $number = $_post['user_num'];
                $lang= count($person);
                $dr_model = new  TaskDrawController();
                $model = new TaskCategoryModel();
                $task_category_id    = $model ->add($_post);
                for ($i=0;$i<$lang;$i++){
                    $user_id = $person[$i];
                    $num = $number[$i];
                    $dr_model->assignedTasks($user_id,$task_category_id,$num);

                }
               return $this->success('任务分配成功',url("index"));
           }


        }
        $user_model = new TempUserModel();
        $data =$user_model ->getUserList(7);
        $this->assign('data', $data);
        return $this->fetch();
    }

    //修改
    public function edit()
    {
        $id = input("id");
        if(!$id){
            $this->error('这条记录不存在，请刷新！');
        }
        $model = new TaskCategoryModel();
        if(IS_POST){
            $data = input();
            $model ->editSubmit($data);
            return $this->success('修改成功',url('TaskCategory/index'));

        }else{
            $data = $model->edit($id);
            $this->assign('data', $data);
            return $this->fetch();
        }


    }


    //添加任务自动选择系统自动分配。1获取可用的人，没删除，没请假。 2查询每个人该任务的数量。3开始取最少的分配
    public function  automaticTaskAssignment($task_category_id,$num)
    {
            $model = new TaskCategoryModel();
            $drawModel = new TaskDrawModel();
            for ($i=1;$i<=$num;$i++) {
                $user_id = $model->templateReviewer($task_category_id,7);
                $drawModel->add($user_id,$task_category_id);
            }

    }

    //请求返回的html，做foreach便利，点击加号
    public function returnAddHtml()
    {
        $model = new TempUserModel();
        $data = $model ->getUserList(7);
        $this->assign('data', $data);
        $html = $this->fetch('template_add');

        $res = json($html);
        return $res;
    }


    //请求返回的html，做foreach便利，点击减号
    public function returnReHtml()
    {
        $model = new TempUserModel();
        $data = $model ->getUserList(7);
        $this->assign('data', $data);
        $html = $this->fetch('template_re');

        $res = json($html);
        return $res;
    }
}