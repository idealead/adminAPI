<?php
namespace app\common\model;

use think\Model;
use tree\Tree;

class TokenModel extends Model
{
	/**
	 * token入库
	 * @param  string $token  token值
	 * @param  string $author 创建者ID
	 */
	public function create_token($token = '', $author = '')
	{
		if(!$token || !$author){
			return false;
		}
		if($this->check_token($token) !== "OK" ){
			return false;
		}
		$add = [
			'token'  => $token,
			'author' => $author,
			'createtime'  => date("Y-m-d H:i:s"),
		];
		$this->insert($add);
		return true;
	}

	/**
	 * token查重
	 * @param  string $token  token值
	 */
	public function check_token($token = '')
	{
		if(!$token){
			return false;
		}
		if($this->where(['token'=>$token])->find()){
			return 1;
		}
		return "OK";
	}
}