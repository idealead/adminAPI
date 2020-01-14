<?php


namespace app\webapi\services;


use cmf\controller\BaseController;

class PyServicesController extends BaseController
{


    //调取python接口，获取图片网络地址
    public function GetUrl($path)
    {
        $tmp = [
            "path" =>$path,
        ];
        $url = "http://papi.idealead.hbindex.com/getImage";
        $result = $this->post($url, $tmp);
        if ($result){
            $res = json_decode($result);
            $check = json_decode($result,true);

            if(is_array($check)){
                return 201;
            }else{
                $res = json_decode($res,true);

                $path = $res['path'];
                return $path;
            }

        }else{
            return $result;
        }

    }

    //传入标签，获取排序
    public function getOrder($labels)
    {
        $tmp = [
            "tagArr" =>$labels,
        ];
        $data = [];
        $url = "http://papi.idealead.hbindex.com/match";
        $res = $this->post($url, $tmp);

        $res = json_decode($res,1);

        if ($res){

            $data = $res['templist'];

            return $data;
        }else{
            return 201;
        }

    }



    public function post($url, $path = '', $timeout = 5){//curl
        $ch= curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_POST, 1);
        if($path != ''){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $path);
        }
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }

}