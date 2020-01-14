<?php


namespace app\statistical\model;

use app\substance\model\TemplateModel;
use app\audit\model\FailureAuditModel;
use think\Model;
use think\Db;

class DrawStatisticalModel extends Model
{

    //返回总统计字段
    public function allStatistical()
    {
        $model = new DrawStatisticalModel();
        $passNum = $model ->getAllPassNum();
        $failNum = $model ->getAllFailureNum();
        $user    = $model ->getPassUser();
        $allNum = $passNum+$failNum;
        if ( $allNum == 0){
            $data=array("passNum"=>$passNum,"passRate"=>0,"user"=>$user);
        }else{
            $passRate = $passNum/($passNum+$failNum);
            $passRate = round($passRate,4)*100;
            $data=array("passNum"=>$passNum,"passRate"=>$passRate,"user"=>$user);
        }
        return $data;
    }


    //取通过的的全部模板数量
    public function getAllPassNum()
    {
        $templateModel = new TemplateModel();
        $model_where_status['status'] =['eq',1];
        $model_field = "count(*) as num";

        $num = $templateModel->where($model_where_status)
                             ->field($model_field)
                             ->find();

        $res = $num['num'];
        return $res;

    }


    //取出审核失败的并没有被删除的模板数量
    public function getAllFailureNum()
    {
        $model = new FailureAuditModel();
        $model_where_status['is_delete'] =['eq',1];
        $model_field = "count(*) as num";

        $num = $model->where($model_where_status)
            ->field($model_field)
            ->find();
        $res = $num['num'];
        return $res;
    }

    //取出模板中，取出上传数量最多的人
    public function getPassUser()
    {
        $templateModel = new TemplateModel();
        $model_where_status['status'] =['eq',1];
        $model_field = " author ,count(*) as num, u.user_name ";

        $num = $templateModel->where($model_where_status)
            ->field($model_field)
            ->group('author')
            ->join(['in_user'=>'u'] , 'author = u.id' , 'LEFT')
            ->order('num','DESC')
            ->find();
        $res = $num['user_name'];

        return $res;
    }

    //便利出模板审核列表
    public function getSelfInformation($keyword_user_id = '',$keyword_user_name = '')
    {
        $time = date('Ym');
        $up_time = date('Ym',strtotime('-1 month'));
        $day_time = date('Ymd');
        if($keyword_user_id){
            $result = Db::query("
                            select u.id ,user_name, ru.role_id, pass_months, pass_days,allt,failNum,up_months,u.user_status
                            from in_user as u
                                left join(select ru.user_id,ru.role_id
                                                    from in_role_user as ru
                                                 )as ru
                                on u.id = ru.user_id
                                
                                left join(select DATE_FORMAT( t.modify,'%Y%m')as t_months, count(t.id) as pass_months,t.author 
                                                    from in_template as t
                                                    where DATE_FORMAT( t.modify,'%Y%m') = $time
                                                    group by t.author
                                                    )as t
                                on u.id = t.author
                                
                                left join(select DATE_FORMAT( upt.modify,'%Y%m')as upt_months, count(upt.id) as up_months,upt.author 
                                                    from in_template as upt
                                                    where DATE_FORMAT( upt.modify,'%Y%m') = $up_time
                                                    group by upt.author
                                                    )as upt
                                on u.id = upt.author
                                
                                left join(select DATE_FORMAT( te.modify,'%Y%m%d')as days, count(te.id) as pass_days,te.author 
                                                    from in_template as te
                                                    where DATE_FORMAT( te.modify,'%Y%m%d') = $day_time
                                                    group by te.author
                                                    )as te
                                                    
                                on u.id = te.author
                                
                                left join (select  allt.author ,count(allt.id) as allt 
                                                        from in_template as allt
                                                        group by allt.author 
                                                        )as allt
                                on u.id = allt.author
                                
                                left join (select  f.author ,count(f.id) as  failNum
                                                        from in_failure_template as f
                                                        group by f.author 
                                                        )as f
                                on u.id = f.author
                            
                            where user_status = 1 and ru.role_id =7 and u.id = $keyword_user_id
                            group by u.id
                            
                            ");
        }elseif ($keyword_user_name){
            $result = Db::query("
                            select u.id ,user_name, ru.role_id, pass_months, pass_days,allt,failNum,up_months,u.user_status
                            from in_user as u
                                left join(select ru.user_id,ru.role_id
                                                    from in_role_user as ru
                                                 )as ru
                                on u.id = ru.user_id
                                
                                left join(select DATE_FORMAT( t.modify,'%Y%m')as t_months, count(t.id) as pass_months,t.author 
                                                    from in_template as t
                                                    where DATE_FORMAT( t.modify,'%Y%m') = $time
                                                    group by t.author
                                                    )as t
                                on u.id = t.author
                                
                                left join(select DATE_FORMAT( upt.modify,'%Y%m')as upt_months, count(upt.id) as up_months,upt.author 
                                                    from in_template as upt
                                                    where DATE_FORMAT( upt.modify,'%Y%m') = $up_time
                                                    group by upt.author
                                                    )as upt
                                on u.id = upt.author
                                
                                left join(select DATE_FORMAT( te.modify,'%Y%m%d')as days, count(te.id) as pass_days,te.author 
                                                    from in_template as te
                                                    where DATE_FORMAT( te.modify,'%Y%m%d') = $day_time
                                                    group by te.author
                                                    )as te
                                                    
                                on u.id = te.author
                                
                                left join (select  allt.author ,count(allt.id) as allt 
                                                        from in_template as allt
                                                        group by allt.author 
                                                        )as allt
                                on u.id = allt.author
                                
                                left join (select  f.author ,count(f.id) as  failNum
                                                        from in_failure_template as f
                                                        group by f.author 
                                                        )as f
                                on u.id = f.author
                            
                            where user_status = 1 and ru.role_id =7 and user_name like '%$keyword_user_name%'
                            group by u.id
                            
                            ");
        }else{
            $result = Db::query("
                            select u.id ,user_name, ru.role_id, pass_months, pass_days,allt,failNum,up_months,u.user_status
                            from in_user as u
                                left join(select ru.user_id,ru.role_id
                                                    from in_role_user as ru
                                                 )as ru
                                on u.id = ru.user_id
                                
                                left join(select DATE_FORMAT( t.modify,'%Y%m')as t_months, count(t.id) as pass_months,t.author 
                                                    from in_template as t
                                                    where DATE_FORMAT( t.modify,'%Y%m') = $time
                                                    group by t.author
                                                    )as t
                                on u.id = t.author
                                
                                left join(select DATE_FORMAT( upt.modify,'%Y%m')as upt_months, count(upt.id) as up_months,upt.author 
                                                    from in_template as upt
                                                    where DATE_FORMAT( upt.modify,'%Y%m') = $up_time
                                                    group by upt.author
                                                    )as upt
                                on u.id = upt.author
                                
                                left join(select DATE_FORMAT( te.modify,'%Y%m%d')as days, count(te.id) as pass_days,te.author 
                                                    from in_template as te
                                                    where DATE_FORMAT( te.modify,'%Y%m%d') = $day_time
                                                    group by te.author
                                                    )as te
                                                    
                                on u.id = te.author
                                
                                left join (select  allt.author ,count(allt.id) as allt 
                                                        from in_template as allt
                                                        group by allt.author 
                                                        )as allt
                                on u.id = allt.author
                                
                                left join (select  f.author ,count(f.id) as  failNum
                                                        from in_failure_template as f
                                                        group by f.author 
                                                        )as f
                                on u.id = f.author
                            
                            where user_status = 1 and ru.role_id =7
                            group by u.id
                            
                            ");
        }

        return $result;
    }

}