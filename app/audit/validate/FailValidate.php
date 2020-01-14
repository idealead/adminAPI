<?php

namespace app\audit\validate;

use think\Validate;

class FailValidate extends  Validate
{

    public function failAudit($data)
    {
        $rule = [
            'template_failure_reason'     => 'require',

        ];
        $msg = [
            'template_failure_reason.require'    => '审核失败原因不能为空',

        ];

        $validate = new Validate($rule ,$msg);
        $result   = $validate->check($data);
        if(!$result){
            echo $validate->getError();
        }



}

}