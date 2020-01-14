<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2019 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 小夏 < 449134904@qq.com>
// +----------------------------------------------------------------------
namespace app\Substance\controller;

use cmf\controller\AdminBaseController;
use app\substance\model\DimensionModel;

class DimensionController extends AdminBaseController
{

    /**
     *  维度列表页
     *  @author HB
     *  2019-07-09
     */
    public function index()
    {
        $DimensionModel = model('dimension');
        $DimensionData = $DimensionModel->where(['status'=>["<>",2]])->order("initials ASC")->select()->toArray();
        // dump($DimensionData);die;
        foreach ($DimensionData as $k => $v) {
            $DimensionData[$k]['status'] = $v['status'] === 1 ? '<span class="label label-success">显示</span>' : '<span class="label label-warning">隐藏</span>' ;
        }
        $this->assign('dimension_data', $DimensionData);

        return $this->fetch();
    }

    /**
     *  新增、post提交维度页
     *  @author HB
     *  2019-07-09
     */
    public function add()
    {
        if(IS_POST){
            $_post = input();
            if(!$_post['dimension_name']){
                $this->error('请填写维度名称');
            }else{
                $_post['initials'] = _getFirstCharter($_post['dimension_name']);
            }

            $DimensionModel = new DimensionModel();
            $_time = date("Y-m-d H:i:s");
            if(isset($_post['id'])){
                $_post['modify'] = $_time;
                $DimensionModel->update($_post);
                $this->success('修改成功');
            }else{
                $_post['createtime'] = $_time;
                $DimensionModel->insert($_post);
                $this->success('新增成功');
            }
        }else{
            return $this->fetch();
        }
    }

    /**
     *  编辑、post提交维度页
     *  @author HB
     *  2019-07-11
     */
    public function edit()
    {
        $_id = input("id");
        if(!$_id){
            $this->redirect('Dimension/add');
        }

        $DimensionModel = model('dimension');
        $DimensionData = $DimensionModel->where(['id'=>$_id])->find()->toArray();
        if(!$DimensionData){
            $this->redirect('Dimension/add');
        }
        $this->assign('data', $DimensionData);
        return $this->fetch();
    }

}
