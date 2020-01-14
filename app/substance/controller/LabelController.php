<?php
namespace app\Substance\controller;

use cmf\controller\AdminBaseController;
use app\substance\model\ClassifyModel;

class LabelController extends AdminBaseController
{

    /**
     *  标签列表页
     *  @author HB
     *  2019-07-12
     */
    public function index()
    {
        $select_category_id = input("select_category_id") ? (int)input("select_category_id") : 0 ;
        $ClassifyModel = new ClassifyModel();
        $selectCategory = $ClassifyModel->FindMenu("label",$select_category_id);
        $this->assign("select_category", $selectCategory);
        if($select_category_id){
            $where['l.classify_id'] = $select_category_id;
        }

        $LabelModel = model('label');
        $field = 'l.id, l.classify_id, l.label_name, l.remark, l.listorder, l.status, l.createtime, c.classify_name';
        $child_select = model('classify')->field("id,classify_name,status")->where(['status'=>1])->buildSql();
        $where['l.status'] = ["<>",2];
        $LabelData = $LabelModel
                    ->alias('l')
                    ->field($field)
                    ->join([$child_select=>'c'], 'l.classify_id = c.id', 'LEFT')
                    // ->where(['l.status'=>["<>",2]])
                    ->where($where)
                    ->order("l.status DESC, c.status DESC, l.listorder DESC, l.classify_id DESC")
                    // ->select()
                    // ->toArray();
                    ->paginate(20);
        // echo $LabelModel->getLastSQL(); die;
        foreach ($LabelData as $k => $v) {
            $LabelData[$k]['status'] = $v['status'] === 1 ? "<span class='btn btn-xs btn-success'>已上线</span>" : "<span class='btn btn-xs btn-danger'>待审核</span>";
        }
        $this->assign('dimension_data', $LabelData);
        $LabelData->appends(input());
        $this->assign('page', $LabelData->render());

        return $this->fetch();
    }

    /**
     *  标签分类管理页
     *  @author HB
     *  2019-07-12
     */
    public function classify()
    {    
        $ClassifyModel = new ClassifyModel();
        $category = $ClassifyModel->FindMenuList("label");
        $this->assign("category", $category);

        return $this->fetch();
    }

    /**
     *  新增、post提交维度页
     *  @author HB
     *  2019-07-09
     */
    public function classify_add()
    {
        if(IS_POST){
            $_post = input();
            if(!$_post['classify_name']){
                $this->error('请填写分类名称');
            }
            $ClassifyModel = new ClassifyModel();
            $ret = $ClassifyModel->AddPost($_post,"label");
            if($ret){
                $this->success($ret);
            }else{
                $this->error('保存失败，请刷新重试');
            }
        }else{
            $ClassifyModel = new ClassifyModel();
            $parent_id = input("parent_id");
            $selectCategory = $ClassifyModel->FindMenu("label",$parent_id);
            $this->assign("select_category", $selectCategory);
            return $this->fetch();
        }
    }

    /**
     *  编辑、post提交标签页
     *  @author HB
     *  2019-07-15
     */
    public function classify_edit()
    {
        $_id = input("id");
        if(!$_id){
            $this->redirect('Label/classify_add');
        }

        $ClassifyModel = new ClassifyModel();

        $ClassifyData = $ClassifyModel->where(['id'=>$_id])->find()->toArray();
        if(!$ClassifyData){
            $this->redirect('Label/classify_add');
        }

        $selectCategory = $ClassifyModel->FindMenu("label",$ClassifyData['parent_id']);
        $this->assign("select_category", $selectCategory);
        
        $this->assign('data', $ClassifyData);
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
            if(!$_post['label_name']){
                $this->error('请填写维度名称');
            }

            $LabelModel = model('label');
            $_time = date("Y-m-d H:i:s");
            if(isset($_post['id'])){
                $_post['modify'] = $_time;
                $LabelModel->update($_post);
                $this->success('修改成功');
            }else{
                $_post['createtime'] = $_time;
                $LabelModel->insert($_post);
                $this->success('新增成功');
            }
        }else{
            $ClassifyModel = new ClassifyModel();
            $selectCategory = $ClassifyModel->FindMenu("label");
            $this->assign("select_category", $selectCategory);
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
            $this->redirect('Label/add');
        }

        $LabelModel = model('label');
        $LabelData = $LabelModel->where(['id'=>$_id])->find()->toArray();
        if(!$LabelData){
            $this->redirect('Label/add');
        }
        $this->assign('data', $LabelData);

        $ClassifyModel = new ClassifyModel();
        $selectCategory = $ClassifyModel->FindMenu("label",$LabelData['classify_id']);
        $this->assign("select_category", $selectCategory);

        return $this->fetch();
    }

}
