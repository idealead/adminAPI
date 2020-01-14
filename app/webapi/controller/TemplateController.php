<?php


namespace app\webapi\controller;


use app\webapi\model\TemplateModel;
use app\webapi\services\PyServicesController;
use cmf\controller\HomeBaseController;
use think\Session;

class TemplateController extends HomeBaseController
{
    //获取所有模板
    public function get_all_template()
    {
        $all = input();
        $labels = $all['labels'];
        $services = new PyServicesController();
        $template_id = $services->getOrder($labels);

        $user_id = input('user_id');
        $page = input('page');
        if (!$page){
            $page = 0;
        }
        $model = new TemplateModel();
        $model ->getAllTemplate($user_id,$template_id);
        $res =$model ->getTemplatePage($user_id,$page);
        echo $res;
        die;

    }


    //分页获取
    public function get_paging()
    {
        $model = new TemplateModel();
        $user_id = input('user_id');
        $page = input('page');
        $res =$model ->getTemplatePage($user_id,$page);
        echo $res;
        die;
    }


    //
    public function a ()
    {
        $res = Session::get("10262");
        dump($res);
        die;
    }




}