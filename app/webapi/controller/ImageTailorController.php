<?php


namespace app\webapi\controller;


use app\webapi\services\BaduServicesController;
use cmf\controller\HomeBaseController;

class ImageTailorController extends HomeBaseController
{


    //获取access_token
    public function get_token()
    {
        $url = "https://aip.baidubce.com/oauth/2.0/token";
        $post_data['grant_type']       = 'client_credentials';
        $post_data['client_id']      = 'IyHWhGgxApF0kQvY34fvqdf0';
        $post_data['client_secret'] = 'XxEdoRQdilR0qmtaH0CHNNuEy8ZpZrnK';
        $o = "";
        foreach ( $post_data as $k => $v )
        {
            $o.= "$k=" . urlencode( $v ). "&" ;
        }
        $post_data = substr($o,0,-1);
        $services = new BaduServicesController();
        $res = $services ->request_post($url, $post_data);
        $res = json_decode($res ,1);
        $token = $res['access_token'];
        return $token;

    }


}