<?php


namespace app\copywriting\validate;

use think\Validate;

class CopywritingValidate extends Validate
{

    //文案验证
    public function checkCopywriting($data)
    {
        $rule = [
            'content'     => 'require|max:25',
        ];

        $msg = [
            'content.require'    => '文案不能为空',
            'content.max'        => '文案最多不能超过25个字符',
        ];

        $validate = new Validate($rule ,$msg);
        $result   = $validate->check($data);
        if(!$result){
            $res = $validate->getError();
            return $res;
        }
    }

}