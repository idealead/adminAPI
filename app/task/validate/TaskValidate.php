<?php

namespace app\task\validate;

use think\Validate;

class TaskValidate extends Validate
{

    public function taskCategoryAdd($data)
    {
        $rule = [
            'name'     => 'require|max:25',
            'number'   => 'number|between:1,2000',
            'sort'     =>'number|between:1,10000',
            'style'    =>'require|max:25',
            'industry' =>'require|max:25',
            'tone'     =>'require|max:25',
            'label'    =>'require|max:25',
            'note'     =>'require',
        ];
        $msg = [
            'name.require'    => '名称不能为空',
            'name.max'        => '名称最多不能超过25个字符',
            'number.number'   => '数量必须是数字',
            'number.between'  => '数量必须在1~2000之间',
            'sort.number'     => '数量必须是数字',
            'sort.between'    => '数量必须在1~2000之间',
            'sort.require'    => '数量不能为空',
            'style.require'   => '风格不能为空',
            'style.max'       => '风格最多不能超过25个字符',
            'industry.require'=> '行业不能为空',
            'industry.max'    => '行业最多不能超过25个字符',
            'tone.require'    => '色调不能为空',
            'tone.max'        => '色调最多不能超过25个字符',
            'label.require'   => '标签不能为空',
            'label.max'       => '标签最多不能超过25个字符',
            'note.require'    => '备注不能为空',
        ];

        $validate = new Validate($rule ,$msg);
        $result   = $validate->check($data);
        if(!$result){
          $res = $validate->getError();
            return $res;
        }

    }


}