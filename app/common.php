<?php
/**
* 取汉字的第一个字的首字母
* @param type $str
* @return string|null
*/
use think\Db;

function _getFirstCharter($str){
    if(empty($str)){return 'ZZ';}
    $fchar=ord($str{0});
    if($fchar>=ord('A')&&$fchar<=ord('z')) return strtoupper($str{0});
    $s1=iconv('UTF-8','gb2312',$str);
    $s2=iconv('gb2312','UTF-8',$s1);
    $s=$s2==$str?$s1:$str;
    $asc=ord($s{0})*256+ord($s{1})-65536;
    if($asc>=-20319&&$asc<=-20284) return 'A';
    if($asc>=-20283&&$asc<=-19776) return 'B';
    if($asc>=-19775&&$asc<=-19219) return 'C';
    if($asc>=-19218&&$asc<=-18711) return 'D';
    if($asc>=-18710&&$asc<=-18527) return 'E';
    if($asc>=-18526&&$asc<=-18240) return 'F';
    if($asc>=-18239&&$asc<=-17923) return 'G';
    if($asc>=-17922&&$asc<=-17418) return 'H';
    if($asc>=-17417&&$asc<=-16475) return 'J';
    if($asc>=-16474&&$asc<=-16213) return 'K';
    if($asc>=-16212&&$asc<=-15641) return 'L';
    if($asc>=-15640&&$asc<=-15166) return 'M';
    if($asc>=-15165&&$asc<=-14923) return 'N';
    if($asc>=-14922&&$asc<=-14915) return 'O';
    if($asc>=-14914&&$asc<=-14631) return 'P';
    if($asc>=-14630&&$asc<=-14150) return 'Q';
    if($asc>=-14149&&$asc<=-14091) return 'R';
    if($asc>=-14090&&$asc<=-13319) return 'S';
    if($asc>=-13318&&$asc<=-12839) return 'T';
    if($asc>=-12838&&$asc<=-12557) return 'W';
    if($asc>=-12556&&$asc<=-11848) return 'X';
    if($asc>=-11847&&$asc<=-11056) return 'Y';
    if($asc>=-11055&&$asc<=-10247) return 'Z';
    return "ZZ";
}

/**
* 删除系统
* @param int 	$id 			需要修改的ID
* @param string $model 			需要操作的数据库
* @param string	$change_name	特定字段的名字，默认：status
* @param int 	$change_status	特定字段需要改变到的值，默认：2
*/
function _delete($id , $model , $change_name = 'status' , $change_status = 2) {
	if(!$id || !$model){
		return false;
	}
	$model = Db::name($model);
	
	$data = $model->where(['id'=>$id])->find();
	if(!$data){
		return false;
	}

	$data[$change_name] = $change_status;

	if ($model->update($data)!==false) {
		return true;
	} else {
		return false;
	}
}

/**
* 排序系统
* @param array 	$ids 			需要修改的ID集，以及对应的值
* @param string $model 			需要操作的数据库
* @param string	$change_name	特定字段的名字，默认：listorder
*/
function _listorder($ids , $model , $change_name = 'listorder') {
	if(!$ids || !is_array($ids) || !$model){
		return false;
	}
	$model = Db::name($model);
	
	$pk = $model->getPk(); //获取主键名称
    foreach ($ids as $key => $r) {
        $data[$change_name] = $r;
        $model->where(array($pk => $key))->update($data);
    }
	return true;
}

/**
 * 生成文件正式地址
 * @param string $files_path 	文件数据存储地址
 */
function files_truth_path($files_path , $type = 'jpg'){
	return '/uploadFiles/' . files_type($type) . "/" . $files_path ;
}

/**
 * 根据文件类型生成文件所在目录地址
 * @param string $type 	文件类型
 */
function files_type($type){
	$types = [
		'images' => [
			'jpg' ,'png' ,'gif' ,'imgae/png' ,'imgae/jpg' ,'imgae/gif' ,'images'
		],
		'file' => [
			'txt'
		]
	];

	foreach ($types as $key => $value) {
		if(in_array($type,$value)){
			return $key;
		}
	}
	return 'no_found';
}

function get_category_tree($model, $name, $selectId = 0, $where = ['status'=>1], $order = "")
{
	if(!$model || !$name){
		return false;
	}
	$model = model($model);
    $categories = $model
	    ->order($order)
        ->where($where)
        ->select()
        ->toArray();

    $str = "";
    foreach ($categories as $item) {
        $item['selected'] = $selectId == $item['id'] ? "selected" : "";
        $item['out_name'] = $item[$name] ? $item[$name] : "不存在该字段";
        $str .= "<option";
        $str .= " value='".$item['id']."' ";
        $str .= $item['selected'];
        $str .= ">";
        $str .= $item['out_name'];
        $str .= "</option>";
    }

    return $str;
}

// get请求
function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
}

//计算模板的通过率
function CalculatePassRate( $PassNum , $failNum )
{
    if (($PassNum+$failNum) ==0){
        return 0;
    }else{
        $res = $PassNum/($PassNum+$failNum);
        $res = round($res,4)*100;
        return $res;
    }

}


//求和并返回结果
function getSum($pass,$fail)
{
    $res = $pass+$fail;
    return $res;
}

//根据用户id取用户user_name
function getUserName($user_id){
    $model = Db::name('user');
    $model_where['id'] =["eq",$user_id];
    $model_field = "  id, user_name";
    $data =  $model->where($model_where)
                   ->field($model_field)
                   ->select();
    $result = $data[0]["user_name"];
    return $result;
}

//根据id 获取审核状态名称 in_audit_name 表
function getTemplateStatusName($id)
{
    $model = Db::name('audit_name');
    $model_where['id'] =["eq",$id];
    $model_field = "id,status_name";
    $data =  $model->where($model_where)
        ->field($model_field)
        ->find();
    $result = $data["status_name"];
    return $result;
}


//根据id获取状态名称  in_user_status表
function getUserStatusName($id)
{
    $model = Db::name('user_status');
    $model_where['id'] =["eq",$id];
    $model_field = "id,status_name";
    $data =  $model->where($model_where)
        ->field($model_field)
        ->find();
    $result = $data["status_name"];
    return $result;
}

//根据id获取状态名称  in_draw_name表
function getDrawStatusName($id)
{
    $model = Db::name('draw_name');
    $model_where['id'] =["eq",$id];
    $model_field = "id,status_name";
    $data =  $model->where($model_where)
        ->field($model_field)
        ->find();
    $result = $data["status_name"];
    return $result;
}

//根据id获取状态名称  in_assign_name表
function getAssignName($id)
{
    $model = Db::name('in_assign_name');
    $model_where['id'] =["eq",$id];
    $model_field = "id,assign_name";
    $data =  $model->where($model_where)
        ->field($model_field)
        ->find();
    $result = $data["assign_name"];
    return $result;
}

//查看对应文案标签
function findLabel($copyeriting_id)
{
    $model = Db::name("copywriting_label");
    $model_where['c.copywriting_id'] = ["eq",$copyeriting_id];
    $model_field = "l.label_name";
    $data = $model
        ->alias("c")
        ->join(['in_label_cw' => 'l'], 'c.label_id = l.id', 'LEFT')
        ->where($model_where)
        ->field($model_field)
        ->select()->toArray();

    $arr = [];
    foreach ($data as $k =>$v)
    {
        $arr[$k] = $v['label_name'];
    }

    $res = implode("/",$arr);
    return $res;

}
