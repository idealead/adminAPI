<?php
namespace app\Substance\controller;

use cmf\controller\AdminBaseController;
use app\substance\model\ClassifyModel;
use think\Db;

class MaterialController extends AdminBaseController
{
    // 素材类型
    protected $material_type = [
        'img'   => '单图',
        'whole' => '多素材组合',
        'text' => '纯文本',
    ];
    // 素材状态
    protected $material_status = [
        0 => "<span class='btn btn-xs btn-danger'>未审核/待审核</span>",
        1 => "<span class='btn btn-xs btn-success'>正常</span>",
        2 => "<span class='btn btn-xs btn-danger'>已删除</span>",
    ];
    // 素材等级
    protected $material_attribute_type = [
        1 => "普通素材/独立模板素材",
        2 => "<span class='btn btn-xs btn-success'>通用素材/共享素材</span>",
    ];

    /**
     *  素材列表页
     *  @author HB
     *  2019-07-12
     */
    public function index()
    {
        $MaterialModel = model('material');
        $material_where['m.status'] = ["neq",2];
        $material_field = "
            m.id, m.material_type, m.label_id, m.author, m.modify, m.status, m.attribute_type, m.listorder, m.text_content,
            f.path, f.type,
            d.dimension_name
        ";
        $material_order = "
            m.listorder DESC, m.modify DESC
        ";

        // 前端条件查询处理
        // 所属维度
            $dimension_select_bad_value = "";
            $search_dimension_id = input('search_dimension_id');
            if($search_dimension_id){
                if($search_dimension_id === "bad_value"){
                    $dimension_select_bad_value = "selected";
                    $material_where['d.dimension_name'] = null;
                }else{
                    $material_where['m.dimension_id'] = $search_dimension_id;
                }
            }
        // 素材类型
            $search_material_type = input('search_material_type');
            if($search_material_type){
                if($search_material_type === "img"){
                    $this->assign('search_material_type_img',"selected");
                    $this->assign('search_material_type_whole',"");
                    $this->assign('search_material_type_text',"");
                }
                if($search_material_type === "whole"){
                    $this->assign('search_material_type_img',"");
                    $this->assign('search_material_type_whole',"selected");
                    $this->assign('search_material_type_text',"");
                }
                if($search_material_type === "text"){
                    $this->assign('search_material_type_img',"");
                    $this->assign('search_material_type_whole',"");
                    $this->assign('search_material_type_text',"selected");
                }
                $material_where['m.material_type'] = $search_material_type;
            }else{
                $this->assign('search_material_type_img',"");
                $this->assign('search_material_type_whole',"");
                $this->assign('search_material_type_text',"");
            }
        // 素材分类
            $search_attribute_type = input('search_attribute_type');
            if($search_attribute_type){
                if($search_attribute_type === "1"){
                    $this->assign('search_attribute_type_1',"selected");
                    $this->assign('search_attribute_type_2',"");
                }else{
                    $this->assign('search_attribute_type_1',"");
                    $this->assign('search_attribute_type_2',"selected");
                }
                $material_where['m.attribute_type'] = $search_attribute_type;
            }else{
                $this->assign('search_attribute_type_1',"");
                $this->assign('search_attribute_type_2',"");
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
                $material_where['m.status'] = str_replace("status","",$search_status);
            }else{
                $this->assign('search_status_1',"");
                $this->assign('search_status_0',"");
            }
        // 时间
            $this->assign('start_time', input('start_time'));
            $this->assign('end_time', input('end_time'));
            if(input('start_time') && input('end_time')){
                $material_where['m.modify'] = ["between", [ input('start_time'),input('end_time') ] ];
            }else if(input('start_time')){
                $material_where['m.modify'] = ["egt",input('start_time')];
            }else if(input('end_time')){
                $material_where['m.modify'] = ["elt",input('end_time')];
            }
        // 所属维度列表
            $dimension_select = get_category_tree("dimension", "dimension_name",$search_dimension_id);
            $this->assign('dimension_select',$dimension_select);
            $this->assign('dimension_select_bad_value',$dimension_select_bad_value);


        // 查询维度SQL
        $DimensionModel = model('dimension');
        $child_select_dimension = $DimensionModel->field("id,dimension_name,dimension_name_en,status")->where(['status'=>1])->buildSql();
        $material_data = $MaterialModel
                        ->alias("m")
                        ->join([$child_select_dimension=>'d'] , 'd.id = m.dimension_id' , 'LEFT')
                        ->join(['in_file'=>'f'] , 'f.id = m.preview' , 'LEFT')
                        ->where($material_where)
                        ->field($material_field)
                        ->order($material_order)
                        ->paginate(20);
        foreach ($material_data as $key => $value) {
            // 默认值，去除空值处理
            if(!$value['dimension_name']) unset($material_data[$key]['dimension_name']);
            if(!$value['label_id']) unset($material_data[$key]['label_id']);

            $material_data[$key]['material_type'] = $this->material_type[$value['material_type']];
            $material_data[$key]['status'] = $this->material_status[$value['status']];
            $material_data[$key]['attribute_type'] = $this->material_attribute_type[$value['attribute_type']];
        }
        $this->assign('material_data',$material_data);
        $material_data->appends(input());
        $this->assign('page', $material_data->render());

        
        return $this->fetch();
    }

}
