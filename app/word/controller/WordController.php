<?php


namespace app\word\controller;


use app\word\model\TemplateModel;
use app\word\model\WordModel;
use app\word\model\WordRuleModel;
use app\word\model\WordTemplateModel;
use cmf\controller\AdminBaseController;



class WordController extends AdminBaseController
{

    //获取模板数据进行展示
    public function index()
    {
        $model = new TemplateModel();
        $data = $model ->findTemplate();
        $this->assign('data', $data);

      //  $this->assign('page', $data->render());
        return $this -> fetch();
    }

    //添加文案操作
    public function add()
    {
        $id = input('id');
        $model = new TemplateModel();
        $model_rule = new WordRuleModel();
        $data = $model ->findTemplateOnce($id);
        $this->assign('data', $data);
        $word = $model_rule->getTemplateRule($id);
        $this->assign('word', $word);
        return $this -> fetch();

    }

    //添加跳转
    public function addWord()
    {
        $model_rule = new WordRuleModel();
        $model_word = new WordModel();
        $model_template = new WordTemplateModel();
        $template_id = input('template_id');
        $res = input();
        $i=[];
        $str='';
        $rule= $model_rule->getTemplateRule($template_id);

        //按条数，插入文案 ，拼接$i ,为插入word_template准备数据
        foreach ($rule as $k=>$v){

            $word_id = $model_word ->addWord($res[$k],$v['id'],$template_id);
            $i[$k] = $word_id;
        }

        foreach ($i as $k=>$v){
            if ($k == 0){
                $str.=$v;
            }else{
                $str.=','.$v;
            }
        }
        //插入word_template
        $res = $model_template ->addWordTemplate($template_id,$str);
        if ($res){
            return $this->success('文案添加成功',url("index"));
        }else{
            return $this->error('文案添加失败',url("index"));
        }





    }


    //单个文案列表
    public function wordList()
    {
        $template_id = input('id');
        $model = new WordTemplateModel();

        $data = $model ->findWordTemplateAll($template_id);
        $this->assign('data', $data);
        return $this -> fetch();
    }


    //成套文案删除
    public function del()
    {
        $word_tmp_id = input('word_tmp_id');
        $word_id = input('word_id');
        $template_id = input('template_id');

        $model = new WordModel();
        $model_wt = new WordTemplateModel();

        $id = $tmp = explode(',',$word_id);
        //删除文案
        foreach ($id as $k =>$v){
            $model ->deleteWord($v);
        }
        //删除组合数据
        $model_wt ->del($word_tmp_id);

        return $this->success('文案删除成功',url("index"));
      //  return $this->success('文案删除成功',url("wordList"),['id'=>$template_id]);
    }

    //文案修改
    public function edit()
    {
       if (request()->isPost())
       {
           $word_ids = input('word_ids');
           $word_ids = explode(',',$word_ids);
           $data = input();
           $model = new WordModel();
           foreach ($word_ids as $k =>$v)
           {
                $model ->updateContent($v ,$data[$k]);
           }
           return $this->success('文案修改',url("index"));
       }else{
           $id = input('id');
           $model = new WordTemplateModel();
           $model_t = new TemplateModel();
           $model_word = new WordModel();
           $words = $model ->findWords($id);

           $template_id = $words['template_id'];
           $word_tmp = $words['word_id'];
           $this->assign('ids', $word_tmp);

           $word_id = explode(',',$word_tmp);
           $data = $model_t ->findTemplateOnce($template_id);
           $this->assign('data', $data);
           $word =  $model_word -> findWordSelf($word_id);
           $this->assign('word', $word);
           return $this -> fetch();


       }




    }


}