<?php
namespace app\Pub\controller;

use cmf\controller\AdminBaseController;

class PubController extends AdminBaseController
{
    /**
     *  删除系统
     *  @author HB
     *  2019-07-11
     */
    public function delete() {
        $id = input("id");
        $model = input("delet_model");

        $ret = _delete($id , $model);

        if ($ret) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }

    /**
     *  排序系统
     *  @author HB
     *  2019-07-16
     */
    public function list_orders() {
        $ids = input("list_orders/a");
        $model = input("listorder_model");

        $ret = _listorder($ids , $model);

        if ($ret) {
            $this->success("排序成功！");
        } else {
            $this->error("排序失败！");
        }
    }
}
