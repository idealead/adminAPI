<?php
namespace app\api\controller;

use cmf\controller\HomeBaseController;
use think\Db;

class ClientController extends HomeBaseController
{
    protected $template_model;
    protected $label_model;
    protected $collection_model;
    protected $qa_system_model;
    protected $file_model;

	protected $template_rendering = "http://cyrd.intech.gdinsight.com/html/editor/#/canvas?userType=render";

	public function _initialize() {
		parent::_initialize();
        $this->template_model   = Db::name('template');
        $this->label_model      = Db::name('label');
        $this->collection_model = Db::name('collection');
        $this->qa_system_model = Db::name('QaSystem');
		$this->file_model = Db::name('File');
	}

    // 问题对应标签 - 临时版
    protected $label_list = [
        [
            [39],
            [44],
            [43],
            [40],
            [41],
            [42],
        ],
        [
            [34],
            [35],
            [36],
            [37],
            [38],
        ],
        [
            [46],
            [47],
            [48],
            [49],
            [50],
        ],
        [
            [21],
            [22],
            [23],
        ],
        [
            [30],
            [33],
            [31],
            [45],
        ],
    ];

	/**
     *  QA问答系统，返回模版列表
     *  @author HB
     *  2019-08-23
     */
    public function qa_get_template_list()
    {
        $answer = json_decode(input("answer"),1);
        if(count($answer) !== 5){
            $this->backto(10004);
        }

        $user      = input("user");
        if(!$user) $this->backto( 40001 );

        // 收集用户上送内容
        $title      = input("title");
        $subtitle   = input("subtitle");
        $logo       = input("logo");
        $product_img= input("product_img");
        if($title){
            $template_where['t.has_title'] = 1;
            $template_not_in_where['has_title'] = 1;
        }
        if($subtitle){
            $template_where['t.has_subtitle'] = 1;
            $template_not_in_where['has_subtitle'] = 1;
        }
        if($logo){
            $template_where['t.has_logo'] = 1;
            $template_not_in_where['has_logo'] = 1;
        }
        if($product_img){
            $template_where['t.has_product'] = 1;
            $template_not_in_where['has_product'] = 1;
        }

        // 标签匹配 - 筛查所有标签集
        $label_ids = [];
        foreach ($answer as $answer_key => $answer_value) {
            if(is_array($answer_value)){
                foreach ($answer_value as $answer_sub_value) {
                    $label_ids = array_merge($label_ids , $this->label_list[$answer_key][$answer_sub_value-1]);
                }
            }else{
                $label_ids = array_merge($label_ids , $this->label_list[$answer_key][$answer_value-1]);
            }
        }
        $label_ids = array_unique($label_ids);

        // 标签匹配 - 匹配模版信息
        $template_field = "
            t.id, t.template_name, t.template_width, t.template_height, t.has_product,
            f.path as preview_img, f.type as preview_type
        ";
        $template_model_sql = $this->template_model
                                ->alias("t")
                                ->field($template_field)
                                ->join( ['in_file'=>'f'] , 'f.id = t.preview' , 'LEFT' );
        $template_where_complex = "";
        foreach ($label_ids as $label_ids_key => $label_ids_value) {
            // $template_model_sql = $template_model_sql->whereOr(" find_in_set('$label_ids_value', t.label_id) ");
            if($label_ids_key !== 0 ){
                $template_where_complex .= " or find_in_set('$label_ids_value', t.label_id) ";
            }else{
                $template_where_complex .= " find_in_set('$label_ids_value', t.label_id) ";
            }
        }
        $template_where['t.status'] = 1;
        $template_where['t.level'] = 'permanent';
        $template_lists = $template_model_sql->where($template_where_complex)->where($template_where)->select()->toArray();

        $choosed_id = [];
        foreach ($template_lists as $key => $value) {
            $template_lists[$key]['preview_path'] = files_truth_path( $value['preview_img'] , $value['preview_type'] );
            $choosed_id[] = $value['id'];
        }

        // 排查出品数量是否达标
            $criterion_number = 5;
            if(count($choosed_id) < $criterion_number ){   // 出品数量不达标时，随机抽样填补空缺
                $add_choose_number = $criterion_number - count($choosed_id);  // 随机抽样数

                $template_not_in_where['status'] = 1;
                $template_not_in_where['level'] = 'permanent';
                $template_not_in_ids = $this->template_model
                                    ->where($template_not_in_where)
                                    ->where(['id'=>["not in",$choosed_id] ])
                                    ->field('id')
                                    ->select()
                                    ->toArray();
                if($template_not_in_ids && !empty($template_not_in_ids) )
                {
                    $con = '';
                    foreach($template_not_in_ids as $k=>$val){
                        if($k === 0){
                            $con.= $val['id'];
                        }else{
                            $con.= '|'.$val['id'];
                        }
                    }
                    $ids_array = explode("|",$con);// 拆分
                    $ids_array_change = [];
                    foreach ($ids_array as $key => $value) {
                        $ids_array_change[$value] = $value;
                    }
                    if(count($ids_array_change) > $add_choose_number){
                        $where_rand_array = array_rand($ids_array_change,$add_choose_number) ;//随机数组
                    }else{
                        $where_rand_array = $ids_array_change;
                    }

                    // 根据ID数组查找数据
                    $template_lists_add = $this->template_model
                                        ->alias("t")
                                        ->field($template_field)
                                        ->join( ['in_file'=>'f'] , 'f.id = t.preview' , 'LEFT' )
                                        ->where(['t.id'=>["in",$where_rand_array] ])
                                        ->limit($add_choose_number)
                                        ->select()
                                        ->toArray()
                                        ;
                    foreach ($template_lists_add as $template_lists_add_key => $template_lists_add_value) {
                        $template_lists_add[$template_lists_add_key]['preview_path'] = files_truth_path( $template_lists_add_value['preview_img'] , $template_lists_add_value['preview_type'] );
                        $template_lists[] = $template_lists_add[$template_lists_add_key];
                    }
                }
            }

        // 记录QA日志
        // $template_id = implode( "," , array_keys( array_column($template_lists , null , 'id') ) );
        // $token = $this->create_token($user);
        // $qa_log = [
        //     "title"         => $title,
        //     "subtitle"      => $subtitle,
        //     "logo"          => $logo,
        //     "product_img"   => $product_img,
        //     "author"        => $user,
        //     "template_id"   => $template_id,
        //     "token"         => $token,
        //     "createtime"    => date("Y-m-d H:i:s"),
        // ];
        // $this->qa_system_model->insert($qa_log);

        // 尺寸对比 - 数组
        foreach ($template_lists as $key => $value) {
            $template_lists[$key]['size_contrast'] = [];
        }

        // 内容对比 - 数组
        // if($logo){
        //     $logo_field = $this->file_model->where(['id'=>$logo])->column("path,type","id");
        //     $logo_field = files_truth_path( $logo_field[$logo]['path'] , $logo_field[$logo]['type'] );
        // }
        // if($product_img){
        //     $product_img_field = $this->file_model->where(['id'=>$product_img])->column("path,type","id");
        //     $product_img_field = files_truth_path( $product_img_field[$product_img]['path'] , $product_img_field[$product_img]['type'] );
        // }

        // $render_url = $this->template_rendering;
        // if($title) $render_url .= "&changeTitle=".$title;
        // if($subtitle) $render_url .= "&changeSubtitle=".$subtitle;
        // if($logo) $render_url .= "&logoFileId=".$logo;
        // if($logo_field) $render_url .= "&changeLogo=".$logo_field;
        // if($product_img_field) $render_url .= "&changeMain=".$product_img_field;
        // if($product_img) $render_url .= "&mainFileId=".$product_img;
        // if($token) $render_url .= "&token=".$token;

        // foreach ($template_lists as $key => $value) {
        //     $template_lists[$key]['content_contrast'] = [];
        //     $new_url = $render_url."&tempId=".$value['id'];
        //     // httpGet($new_url);
        // }

        $this->backto(0,"OK",['list'=>$template_lists,'criterion'=>count($choosed_id)]);
    }

    /**
     *  收藏模版接口
     *  @author HB
     *  2019-08-25
     */
    public function collect_template(){
        $user_id  = input('user');
        if(!$user_id) $this->backto( 10001 );
        $templateId = input('templateId');
        if(!$templateId) $this->backto( 10001 );

        $add_data = [
            'template_id'   => $templateId,
            'user_id'       => $user_id,
            'status'        => 1
        ];
        // 排查重复收藏
        $collect_check = $this->collection_model->where($add_data)->find();
        if($collect_check) $this->backto( 20004 );

        $add_data['create_time'] = date("Y-m-d H:i:s");
        $add_data['modify']      = date("Y-m-d H:i:s");

        $collection_model_id = $this->collection_model->insertGetId($add_data);   //  素材信息入库
        if(!$collection_model_id){
            $this->backto( 20002 );
        }else{
            $this->backto( 0 ,"OK",["collectionId"=>$collection_model_id]);
        }
    }

    /**
     *  获取已收藏模版接口
     *  @author HB
     *  2019-08-25
     */
    public function get_collect_list(){
        $user_id  = input('user');
        if(!$user_id) $this->backto( 10001 );

        $collect_where['c.status']  = 1;
        $collect_where['c.user_id'] = $user_id;

        $collect_field = "t.template_name, t.id, f.path as preview_img, f.type as preview_type";

        $collect_list = $this->collection_model
                                ->alias("c")
                                ->join(['in_template'=>'t'] , 't.id = c.template_id' , 'LEFT')
                                ->join(['in_file'=>'f'] , 'f.id = t.preview' , 'LEFT')
                                ->where($collect_where)
                                ->field($collect_field)
                                ->order("c.create_time DESC")
                                ->select()
                                ->toArray()
                                ;
        if(!$collect_list){
            $this->backto( 20003 );
        }
        foreach ($collect_list as $collect_list_key => $collect_list_value) {
            $collect_list[$collect_list_key]['preview_path'] = files_truth_path($collect_list_value['preview_img'] , $collect_list_value['preview_type']);
        }

        $this->backto( 0 ,"OK",$collect_list);
    }
}