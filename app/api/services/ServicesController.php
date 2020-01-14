<?php

namespace  app\webApi\services;

use cmf\controller\BaseController;

/**
 * 获取全部部门数据
 *
 * date:2017年3月27日
 * author: EK_熊
 */


class ServicesController extends BaseController
{

    protected $code = '5885aeeda19e7';
    protected $base_url = "";
    protected $errmsg = '';
    protected $cookie_key = 'user';

    protected $cookie_expire = 7776000; //默认三个月
    protected $session_key = 'user';
    protected $debug = '';

    public function __construct()
    {
        $this->base_url = "http://account.intech.gdinsight.com/index.php?g=Home&m=Api&code=$this->code&a=";
    }


    //登录，email、password。 返回值id  or false
    public function login($email, $password, $field = '', $remember = true, $illegal = false)
    {
        $account = trim($email);
        $password = trim($password);
        if (empty($account)) {
            $this->errmsg = '请输入账户名';
            return false;
        }
        if (empty($password)) {
            $this->errmsg = '请输入密码';
            return false;
        }
        $userInfo = $this->_request('login_email', ['account' => $account . '@gdinsight.com', 'password' => $password, 'field' => $field, 'illegal' => $illegal]);
        if (!$userInfo){
            return false;
        }else{
            unset($userInfo['status']);
            unset($userInfo['errcode']);

            //获取到用户数据，记录session
            session($this->session_key, $userInfo);
            //是否开启浏览自动记住用户信息
            if ($remember) {
                cookie($this->cookie_key, $userInfo, array('expire' => $this->cookie_expire));
            }
            $id = $userInfo['id'];
            return $id;
        }

    }

    /**
     * 获取全部部门数据
     *
     * date:2017年3月27日
     */
    public function get_department(){
        $request = $this->_request('get_department');
        if ($request['errcode'] !==0) return false;

        return $request['data'];
    }

    public function _request($actionname,$fields=[]){
        $apiUrl = $this->base_url.$actionname;
//        $curl = new \Curl();
        $result = $this->post($apiUrl, $fields);
        if (!$result) return '接口请求错误！';
        $resultAry =  json_decode($result,true);
        if ($resultAry['errcode'] !== 0) {
            $this->errmsg = $resultAry['info'];
            return false;
        }
        return $resultAry;
    }


    public function post($url, $post_data = '', $timeout = 5){//curl
        $ch= curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_POST, 1);
        if($post_data != ''){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }

    /**
     * 获取单条用户信息
     * @param array $where
     * @param string $field
     * date:2017年2月27日
     * author: EK_熊
     */
    public function get_user_by_field($where=[],$field=''){

        $baseField = "ep_id,dep_id,dep_name,sn,ep_name";
        $field = rtrim($baseField.','.$field,',');
        $request = $this->_request('get_user_by_field',['where'=>urlencode(json_encode($where)),'field'=>$field]);
        if ($request['errcode'] !==0) return false;

        return $request['ret'];
    }


    /**
     * 批量获取领导
     * @param unknown $key
     * @param unknown $val
     * @param string $field
     * @return boolean
     * date:2017年5月27日
     * author: EK_熊
     */
    public function get_leader_info_all($where=[],$field=''){

        $request = $this->_request('get_leader_info_all',['where'=>urlencode(json_encode($where)),'field'=>$field]);
        if ($request['errcode'] !==0) return false;
        return $request['data'];
    }





}
