<?php


namespace app\webapi\services;


use cmf\controller\BaseController;

class BaduServicesController extends BaseController
{

    //访问图片测试
    public function image($path,$token)
    {

        $url = 'https://aip.baidubce.com/rest/2.0/image-classify/v1/object_detect?access_token=';
        $url = $url.$token;
        $new_path = '/home/wwwroot/ht.idealead.hbindex.com/public/uploadFiles/images/'.$path;
        $res =  $this ->imgToBase64($new_path);
        $data = [
            'image'=>$res,
        ];
        $res = $this ->send_post($url,$data);
        $res = json_decode($res);
        $res  = collection($res)->toArray();

        return $res;
    }


    //图片发送post
    public function send_post($url, $post_data) {
            $postdata = http_build_query($post_data);
            $options = array(
                'http' => array(
                    'method' => 'POST',
                    'header' => 'Content-type:application/x-www-form-urlencoded',
                    'content' => $postdata,
                    'timeout' => 15 * 60 // 超时时间（单位:s）
                )
            );
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            return $result;
        }


    //请求post
    public function  request_post($url = '', $param = '')
    {
        if (empty($url) || empty($param)) {
            return false;
        }
        $postUrl = $url;
        $curlPost = $param;
        $curl = curl_init();//初始化curl
        curl_setopt($curl, CURLOPT_URL,$postUrl);//抓取指定网页
        curl_setopt($curl, CURLOPT_HEADER, 0);//设置header
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_POST, 1);//post提交方式
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($curl);//运行curl
        curl_close($curl);
        return $data;
    }


    //图片转base64
    public function imgToBase64($img_file) {
        $file_content = '';
        if (file_exists($img_file)) {
            $app_img_file = $img_file; // 图片路径
            $fp = fopen($app_img_file, "r"); // 图片是否可读权限

            if ($fp) {
                $filesize = filesize($app_img_file);
                $content = fread($fp, $filesize);
                $file_content = (base64_encode($content)); // base64编码
            }
            fclose($fp);
        }
        return $file_content; //返回图片的base64
    }


    //获取token值
    public function getToken()
    {
        $url = 'https://aip.baidubce.com/oauth/2.0/token';
        $post_data['grant_type']       = 'client_credentials';
        $post_data['client_id']      = 'TBdCG87NCDh0I50YMQgqyYcG';
        $post_data['client_secret'] = 'dQlIPT0dmzGxQoikjGmYUtlsG13c8nCK';
        $o = "";
        foreach ( $post_data as $k => $v )
        {
            $o.= "$k=" . urlencode( $v ). "&" ;
        }
        $post_data = substr($o,0,-1);

        $res = $this->request_post($url, $post_data);

        $res = json_decode($res,1);
        $token = $res['access_token'];

        return $token;
    }







}