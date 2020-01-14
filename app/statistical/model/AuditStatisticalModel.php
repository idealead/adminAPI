<?php


namespace app\statistical\model;

use think\Model;

use app\task\model\TaskDrawModel;
use app\task\model\TempUserModel;
use app\substance\model\TemplateModel;
use app\audit\model\AuditModel;
use app\audit\model\FailureAuditModel;
use app\audit\model\FailureTemplateModel;
use think\Db;

class AuditStatisticalModel extends Model
{

    // 计算总审核量、通过率返回
    public function allStatistical()
    {
        $model = new AuditStatisticalModel();
        $passNum = $model ->getPassNum();
        $failNum = $model -> getFailNum();
        $allNum = $passNum+$failNum;
        if( $allNum==0){
            $passRate =0;
            $data=array("allNum"=>$passNum,"allRate"=>$passRate);
            return $data;
        }else{
            $passRate = $passNum/($passNum+$failNum);
            $passRate = round($passRate,4)*100;
            $data=array("allNum"=>$allNum,"passRate"=>$passRate,"failNum"=>$failNum);
            return $data;

        }
    }



    //获取通过的数量
    public function getPassNum()
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

    //获取审核失败的数量
    public function getFailNum()
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


    //便利每个审核人员的信息
    public function getSelfInformation($keyword_user_id,$keyword_user_name)
    {
        $time = date('Ym');
        $up_time = date('Ym', strtotime('-1 month'));

        $day_time = date('Ymd');

        if($keyword_user_id){
            $result = Db::query("
                select u.id, u.user_name, u.user_status, a.pass_months, f.fail_months, ad.pass_days, fd.fail_days, role_id,up_pass,up_fail
                from in_user as u
                left join (select ru.role_id,ru.user_id 
                                     from in_role_user as ru
                )as ru
                on u.id = ru.user_id
                left join(select DATE_FORMAT( a.template_passtime,'%Y%m')as p_months, count(a.id) as pass_months,a.template_user_id
                                    from in_audit as a
                                    where DATE_FORMAT( a.template_passtime,'%Y%m') = $time
                                    group by a.template_user_id
                ) as a
                on u.id = a.template_user_id
                
                left join(select DATE_FORMAT( upa.template_passtime,'%Y%m')as p_months, count(upa.id) as up_pass,upa.template_user_id
                                    from in_audit as upa
                                    where DATE_FORMAT( upa.template_passtime,'%Y%m') = $up_time
                                    group by upa.template_user_id
                ) as upa
                on u.id = upa.template_user_id                
                
                left join(select DATE_FORMAT( ad.template_passtime,'%Y%m%d')as days, count(ad.id) as pass_days,ad.template_user_id
                                    from in_audit as ad
                                    where DATE_FORMAT( ad.template_passtime,'%Y%m%d') = $day_time
                                    group by ad.template_user_id
                ) as ad
                on u.id = ad.template_user_id
                left join(select DATE_FORMAT( upf.createtime,'%Y%m')as upf_months, count(upf.id) as up_fail,upf.template_user_id
                                    from in_failure_audit as upf
                                    where DATE_FORMAT( upf.createtime,'%Y%m') = $time
                                    group by upf.template_user_id
                ) as upf
                on u.id = upf.template_user_id
                
                left join(select DATE_FORMAT( f.createtime,'%Y%m')as f_months, count(f.id) as fail_months,f.template_user_id
                                    from in_failure_audit as f
                                    where DATE_FORMAT( f.createtime,'%Y%m') = $up_time
                                    group by f.template_user_id
                ) as f
                on u.id = f.template_user_id                
                
                left join(select DATE_FORMAT( fd.createtime,'%Y%m%d')as f_days, count(fd.id) as fail_days,fd.template_user_id
                            from in_failure_audit as fd
                             where DATE_FORMAT( fd.createtime,'%Y%m%d') = $day_time
                            group by fd.template_user_id
                ) as fd
                on u.id = fd.template_user_id
                where role_id =3 and u.id = $keyword_user_id
        
        ");
        }elseif ($keyword_user_name){
            $result = Db::query("
                select u.id, u.user_name, u.user_status, a.pass_months, f.fail_months, ad.pass_days, fd.fail_days, role_id,up_pass,up_fail
                from in_user as u
                left join (select ru.role_id,ru.user_id 
                                     from in_role_user as ru
                )as ru
                on u.id = ru.user_id
                left join(select DATE_FORMAT( a.template_passtime,'%Y%m')as p_months, count(a.id) as pass_months,a.template_user_id
                                    from in_audit as a
                                    where DATE_FORMAT( a.template_passtime,'%Y%m') = $time
                                    group by a.template_user_id
                ) as a
                on u.id = a.template_user_id
                
                left join(select DATE_FORMAT( upa.template_passtime,'%Y%m')as p_months, count(upa.id) as up_pass,upa.template_user_id
                                    from in_audit as upa
                                    where DATE_FORMAT( upa.template_passtime,'%Y%m') = $up_time
                                    group by upa.template_user_id
                ) as upa
                on u.id = upa.template_user_id                
                
                left join(select DATE_FORMAT( ad.template_passtime,'%Y%m%d')as days, count(ad.id) as pass_days,ad.template_user_id
                                    from in_audit as ad
                                    where DATE_FORMAT( ad.template_passtime,'%Y%m%d') = $day_time
                                    group by ad.template_user_id
                ) as ad
                on u.id = ad.template_user_id
                left join(select DATE_FORMAT( upf.createtime,'%Y%m')as upf_months, count(upf.id) as up_fail,upf.template_user_id
                                    from in_failure_audit as upf
                                    where DATE_FORMAT( upf.createtime,'%Y%m') = $time
                                    group by upf.template_user_id
                ) as upf
                on u.id = upf.template_user_id
                
                left join(select DATE_FORMAT( f.createtime,'%Y%m')as f_months, count(f.id) as fail_months,f.template_user_id
                                    from in_failure_audit as f
                                    where DATE_FORMAT( f.createtime,'%Y%m') = $up_time
                                    group by f.template_user_id
                ) as f
                on u.id = f.template_user_id                
                
                left join(select DATE_FORMAT( fd.createtime,'%Y%m%d')as f_days, count(fd.id) as fail_days,fd.template_user_id
                            from in_failure_audit as fd
                             where DATE_FORMAT( fd.createtime,'%Y%m%d') = $day_time
                            group by fd.template_user_id
                ) as fd
                on u.id = fd.template_user_id
                where role_id =3 and user_name like '%$keyword_user_name%'
        
        ");
        }else{
            $result = Db::query("
                select u.id, u.user_name, u.user_status, a.pass_months, f.fail_months, ad.pass_days, fd.fail_days, role_id,up_pass,up_fail
                from in_user as u
                left join (select ru.role_id,ru.user_id 
                                     from in_role_user as ru
                )as ru
                on u.id = ru.user_id
                left join(select DATE_FORMAT( a.template_passtime,'%Y%m')as p_months, count(a.id) as pass_months,a.template_user_id
                                    from in_audit as a
                                    where DATE_FORMAT( a.template_passtime,'%Y%m') = $time
                                    group by a.template_user_id
                ) as a
                on u.id = a.template_user_id
                
                left join(select DATE_FORMAT( upa.template_passtime,'%Y%m')as p_months, count(upa.id) as up_pass,upa.template_user_id
                                    from in_audit as upa
                                    where DATE_FORMAT( upa.template_passtime,'%Y%m') = $up_time
                                    group by upa.template_user_id
                ) as upa
                on u.id = upa.template_user_id                
                
                left join(select DATE_FORMAT( ad.template_passtime,'%Y%m%d')as days, count(ad.id) as pass_days,ad.template_user_id
                                    from in_audit as ad
                                    where DATE_FORMAT( ad.template_passtime,'%Y%m%d') = $day_time
                                    group by ad.template_user_id
                ) as ad
                on u.id = ad.template_user_id
                left join(select DATE_FORMAT( upf.createtime,'%Y%m')as upf_months, count(upf.id) as up_fail,upf.template_user_id
                                    from in_failure_audit as upf
                                    where DATE_FORMAT( upf.createtime,'%Y%m') = $time
                                    group by upf.template_user_id
                ) as upf
                on u.id = upf.template_user_id
                
                left join(select DATE_FORMAT( f.createtime,'%Y%m')as f_months, count(f.id) as fail_months,f.template_user_id
                                    from in_failure_audit as f
                                    where DATE_FORMAT( f.createtime,'%Y%m') = $up_time
                                    group by f.template_user_id
                ) as f
                on u.id = f.template_user_id                
                
                left join(select DATE_FORMAT( fd.createtime,'%Y%m%d')as f_days, count(fd.id) as fail_days,fd.template_user_id
                            from in_failure_audit as fd
                             where DATE_FORMAT( fd.createtime,'%Y%m%d') = $day_time
                            group by fd.template_user_id
                ) as fd
                on u.id = fd.template_user_id
                where role_id =3
        ");
        }
        return $result;

    }



}