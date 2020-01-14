<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2019 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\Substance\model;

use think\Model;
use tree\Tree;

class ClassifyModel extends Model
{

	/**
	 * 分类新增、编辑的提交方法
	 * @param [array]  $_post 数据集
	 * @param [string] $type  类型，常见type值有：label-标签，template-模板，material-素材
	 */
    public function AddPost($_post, $type){
    	if(!$type || !$_post){
    		return false;
    	}
    	$_post['classify_type'] = $type;
    	$_time = date("Y-m-d H:i:s");

    	if(isset($_post['id'])){
            $_post['modify'] = $_time;
            $this->update($_post);
            $ret = "修改成功";
        }else{
            $_post['createtime'] = $_time;
            $this->insert($_post);
            $ret = "添加成功";
        }
    	return $ret;
    }

    /**
     * 根据父级ID查询出分类列表
     * @param string  $type      类型，常见type值有：label-标签，template-模板，material-素材
     * @param integer $parent_id 父级ID
     */
    public function FindMenu($type, $parent_id = 0){
    	if(!$type){
    		return false;
    	}

    	$tree     = new Tree();
        $result   = $this->where(['classify_type'=>$type,'status'=>["<>",2] ])->order(["listorder" => "ASC"])->select()->toArray();
        $array    = [];
        foreach ($result as $r) {
            $r['selected'] = $r['id'] == $parent_id ? 'selected' : '';
            $array[]       = $r;
        }
        $str = "<option value='\$id' \$selected>\$spacer \$classify_name</option>";
        $tree->init($array);
        $selectCategory = $tree->getTree(0, $str);
        return $selectCategory;
    }

    /**
     * 查询出分类列表
     * @param string  $type      类型，常见type值有：label-标签，template-模板，material-素材
     */
    public function FindMenuList($type){
    	if(!$type){
    		return false;
    	}

    	$tree     = new Tree();
        $result   = $this->where(['classify_type'=>$type,'status'=>["<>",2] ])->order(["listorder" => "DESC"])->select()->toArray();
        $tree->icon = ['&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─', '&nbsp;&nbsp;&nbsp;└─ '];
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';

        $newMenus = [];
        foreach ($result as $m) {
            $newMenus[$m['id']] = $m;
        }
        foreach ($result as $key => $value) {

            $result[$key]['parent_id_node'] = ($value['parent_id']) ? ' class="child-of-node-' . $value['parent_id'] . '"' : '';
            $result[$key]['style']          = empty($value['parent_id']) ? '' : 'display:none;';
            $result[$key]['str_manage']     = '<a class="btn btn-xs btn-primary" href="' . url($type."/classify_add", ["parent_id" => $value['id'] ]) . '">添加子分类</a> 
                                               <a class="btn btn-xs btn-primary" href="' . url($type."/classify_edit", ["id" => $value['id'] ]) . '">编辑</a>  
                                               <a class="btn btn-xs btn-danger js-ajax-delete" href="' . url("pub/Pub/delete", ["id" => $value['id'], "delet_model" => 'classify' ]) . '">删除</a> ';
            $result[$key]['status']         = $value['status'] ? '<span class="label label-success">显示</span>' : '<span class="label label-warning">隐藏</span>';
        }

        $tree->init($result);
    	$str 	= "<tr id='node-\$id' \$parent_id_node style='\$style'>
                    <td style='padding-left:20px;'><input name='list_orders[\$id]' type='text' size='3' value='\$listorder' class='input input-order'></td>
                    <td>\$id</td>
                    <td>\$spacer\$classify_name</td>
                    <td>\$classify_remark</td>
                    <td>\$status</td>
                    <td>\$str_manage</td>
                </tr>";
        $category = $tree->getTree(0, $str);
        return $category;
    }

    /**
     * 查询分类，可以以ID作为索引
     * @param string  $type      类型，常见type值有：label-标签，template-模板，material-素材
     * @param int 	  $state     是否开启返回以ID为索引，默认1-是，0否
     */
    public function FindMenuByTypeAndRuturnArray($type, $state = 1 )
    {
    	if(!$type){
    		return false;
    	}
    	$result = $this->where(['classify_type'=>$type,'status'=>["<>",2] ])->order(["listorder" => "ASC"])->select()->toArray();
    	if($state){
	    	$result = array_column($result , null , "id");
    	}
    	return $result;
    }
}