<?php
namespace app\Substance\controller;

use cmf\controller\AdminBaseController;
use app\substance\model\ClassifyModel;
use think\Db;

class TemplateController extends AdminBaseController
{
    // 素材状态
    protected $template_status = [
        0 => "<span class='btn btn-xs btn-danger'>未审核/待审核</span>",
        1 => "<span class='btn btn-xs btn-success'>正常</span>",
        2 => "<span class='btn btn-xs btn-danger'>已删除</span>",
    ];
    // 模版等级
    protected $template_level = [
        "temporary" => "临时模版",
        "storage"   => "用户储存模版",
        "permanent" => "<span class='btn btn-xs btn-success'>永久模版</span>",
    ];

    /**
     *  模板列表页
     *  @author HB
     *  2019-08-19
     */
    public function index()
    {
        $TemplateModel = model('template');
        $template_where['t.status'] = ["neq",2];
        $template_where['t.level'] = "permanent";
        $template_field = "
            t.id, t.template_name, t.label_id, t.author, t.classify_id, t.modify, t.status, t.material_content, t.level, 
            f.path, f.type
        ";
        $template_order = "t.modify DESC";

        // 名称模糊搜索
            $keyword = input("keyword");
            $this->assign('keyword',$keyword);
            if($keyword){
                $template_where['t.template_name'] = ['like',"%".$keyword."%"];
            }
        // 状态
            $search_status = input("search_status");
            if($search_status){
                if($search_status === "status1"){
                    $this->assign('search_status_1',"selected");
                    $this->assign('search_status_0',"");
                }else{
                    $this->assign('search_status_1',"");
                    $this->assign('search_status_0',"selected");
                }
                $template_where['t.status'] = str_replace("status","",$search_status);
            }else{
                $this->assign('search_status_1',"");
                $this->assign('search_status_0',"");
            }
        // 时间
            $this->assign('start_time', input('start_time'));
            $this->assign('end_time', input('end_time'));
            if(input('start_time') && input('end_time')){
                $template_where['t.modify'] = ["between", [ input('start_time'),input('end_time') ] ];
            }else if(input('start_time')){
                $template_where['t.modify'] = ["egt",input('start_time')];
            }else if(input('end_time')){
                $template_where['t.modify'] = ["elt",input('end_time')];
            }
        // 模板分类
            $search_classify = input("search_classify") ? input("search_classify") : 0;
            $ClassifyModel = new ClassifyModel();
            $search_classify_arr = $ClassifyModel->FindMenu("template",$search_classify);
            $this->assign('search_classify_arr', $search_classify_arr);
        // // 模板等级
        //     $search_level = input("search_level") ? input("search_level") : 0;
        //     if($search_level){
        //         if($search_level === "temporary"){
        //             $this->assign('search_level_1',"selected");
        //             $this->assign('search_level_0',"");
        //         }else{
        //             $this->assign('search_level_1',"");
        //             $this->assign('search_level_0',"selected");
        //         }
        //         $template_where['t.level'] = $search_level;
        //     }else{
        //         $this->assign('search_level_1',"");
        //         $this->assign('search_level_0',"");
        //     }

        // 查询模版
        $template_data = $TemplateModel
                        ->alias("t")
                        ->join(['in_file'=>'f'] , 'f.id = t.preview' , 'LEFT')
                        ->where($template_where)
                        ->field($template_field)
                        ->order($template_order)
                        ->paginate(20);
        // 查询模板分类
        $ClassifyModel = new ClassifyModel();
        $template_classify_data = $ClassifyModel->FindMenuByTypeAndRuturnArray("template");
        // 查询模版标签列表
        $label_lists = get_label_name_lists();

        foreach ($template_data as $key => $value) {
            // 默认值，去除空值处理
            if(!$value['label_id']) $template_data[$key]['label_id'] = "━";
            if(!$value['classify_id']) $template_data[$key]['classify_id'] = "━";

            $template_data[$key]['status'] = $this->template_status[$value['status']];
            $template_data[$key]['level'] = $this->template_level[$value['level']];
            // 模版分类处理
            if($value['classify_id'] && $value['classify_id'] !== "━"){
                $value['classify_id'] = json_decode($value['classify_id'] , 1);
                if($search_classify){
                    if(!in_array($search_classify,$value['classify_id'])){
                        unset($template_data[$key]);
                        continue;
                    }
                }
                $new_classify_id = "";
                foreach ($value['classify_id'] as $classify_key => $classify_value) {
                    if($new_classify_id){
                        $new_classify_id .= " ， ".$template_classify_data[$classify_value]['classify_name'];
                    }else{
                        $new_classify_id = $template_classify_data[$classify_value]['classify_name'];
                    }
                }
                $template_data[$key]['classify_id'] = $new_classify_id;
            }
            // 模版标签处理
            if($template_data[$key]['label_id'] !== "━"){
                $template_data[$key]['label_id'] = implode("，", get_label_name_by_array( explode("," , $template_data[$key]['label_id']) , $label_lists ) );
            }
        }

        $this->assign('template_data',$template_data);
        $template_data->appends(input());
        $this->assign('page', $template_data->render());
        return $this->fetch();
    }

    /**
     *  编辑、post提交模板页
     *  @author HB
     *  2019-08-22
     */
    public function edit()
    {
        if(IS_POST){
            $_post = input();
            foreach ($_post['classify_id'] as $key => $value) {
                $_post['classify_id'][$key] = (int)$value;
            }
            $_post['classify_id'] = array_flip($_post['classify_id']);
            $_post['classify_id'] = array_values(array_flip($_post['classify_id']));
            $_post['classify_id'] = json_encode($_post['classify_id'],1);
            $_post['modify'] = date("Y-m-d H:i:s");
            $TemplateModel = model('template');

            $ret = $TemplateModel->update($_post);
            if($ret){
                $this->success('修改成功');
            }else{
                $this->error('保存失败，请刷新重试');
            }
        }else{
            $_id = input("id");
            if(!$_id){
                $this->redirect('Template/index');
            }

            $template_field = "
                t.id, t.template_name, t.label_id, t.author, t.classify_id, t.modify, t.status, t.material_content, t.createtime, t.level, 
                f.path, f.type
            ";
            $TemplateModel = model('template');
            $TemplateData = $TemplateModel
                            ->alias("t")
                            ->join(['in_file'=>'f'] , 'f.id = t.preview' , 'LEFT')
                            ->where(['t.id'=>$_id])
                            ->field($template_field)
                            ->find()
                            ->toArray();
            if(!$TemplateData){
                $this->redirect('Template/index');
            }
            $TemplateData['classify_id'] = json_decode($TemplateData['classify_id'],1);

            $ClassifyModel = new ClassifyModel();
            $new_classify_id = [];
            if($TemplateData['classify_id']){
                foreach ($TemplateData['classify_id'] as $classify_key => $classify_value) {
                    $new_classify_id[] = $ClassifyModel->FindMenu("template",$classify_value);
                }
            }else{
                $new_classify_id[] = $ClassifyModel->FindMenu("template");
            }
            $TemplateData['classify_id'] = $new_classify_id;
            $this->assign('data', $TemplateData);

            return $this->fetch();
        }
    }

    /**
     *  标签分类管理页
     *  @author HB
     *  2019-08-19
     */
    public function classify()
    {    
        $ClassifyModel = new ClassifyModel();
        $category = $ClassifyModel->FindMenuList("template");
        $this->assign("category", $category);

        return $this->fetch();
    }

    /**
     *  新增、post提交标签分类页
     *  @author HB
     *  2019-08-19
     */
    public function classify_add()
    {
        if(IS_POST){
            $_post = input();
            if(!$_post['classify_name']){
                $this->error('请填写分类名称');
            }
            $ClassifyModel = new ClassifyModel();
            $ret = $ClassifyModel->AddPost($_post,"template");
            if($ret){
                $this->success($ret);
            }else{
                $this->error('保存失败，请刷新重试');
            }
        }else{
            $ClassifyModel = new ClassifyModel();
            $parent_id = input("parent_id");
            $selectCategory = $ClassifyModel->FindMenu("template",$parent_id);
            $this->assign("select_category", $selectCategory);
            return $this->fetch();
        }
    }

    /**
     *  编辑、post提交标签分类页
     *  @author HB
     *  2019-08-19
     */
    public function classify_edit()
    {
        $_id = input("id");
        if(!$_id){
            $this->redirect('Template/classify_add');
        }

        $ClassifyModel = new ClassifyModel();

        $ClassifyData = $ClassifyModel->where(['id'=>$_id])->find()->toArray();
        if(!$ClassifyData){
            $this->redirect('Template/classify_add');
        }

        $selectCategory = $ClassifyModel->FindMenu("template",$ClassifyData['parent_id']);
        $this->assign("select_category", $selectCategory);
        
        $this->assign('data', $ClassifyData);
        return $this->fetch();
    }
}
