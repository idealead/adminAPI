<?php


namespace app\webapi\controller;


use cmf\controller\HomeBaseController;

class DemoController extends HomeBaseController
{


    public function demo()
    {
        if (request()->isPut()){
            $res = input();
            dump($res);
            die;
        }else{
            $res = '11111';
            return $res;
        }
    }

}