<?php


namespace app\task\controller;

use app\api\model\TemplateModel;
use app\api\model\WordRuleModel;
use app\webapi\controller\DepartmentController;
use app\webapi\controller\LabelController;
use app\webapi\controller\TemplateController;
use app\webapi\controller\UserImagesController;
use app\configuration\model\CutoutNumberModel;
use app\webapi\model\DepartmentModel;
use app\webapi\model\LabelAnswerModel;
use app\webapi\model\LabelClassModel;
use app\webapi\model\LabelTitleModel;
use app\webapi\model\NormallyModel;
use app\webapi\model\UserClientModel;
use app\webapi\model\UserCutoutModel;
use app\webapi\model\UserOrderModer;
use app\webapi\services\BaduServicesController;
use app\webapi\services\ServicesController;
use app\word\model\WordTemplateModel;
use cmf\controller\AdminBaseController;
use app\api\model\UserModel;
use Imagick;
use app\webapi\services\PyServicesController;
use app\webapi\controller\UserCutoutController;
use app\webapi\model\CollectionModel;
use think\Session;
class TaskTestController extends  AdminBaseController
{

    public function index()
    {
        $model = new DepartmentController();
        $model ->addDepartment();
    }

    public  function addPost(){
        $user_id = input('user_id');
        $status  = input('status');
        $model   =  new TemplateModel();
        $data    =  $model ->templateAuditList($user_id,$status);
        dump($data);
        die;
        return $data;

    }




    //图片转base64
    public function imgToBase64($img_file) {

        $img_base64 = '';
        if (file_exists($img_file)) {
            $app_img_file = $img_file; // 图片路径
            $img_info = getimagesize($app_img_file); // 取得图片的大小，类型等

            //echo '<pre>' . print_r($img_info, true) . '</pre><br>';
            $fp = fopen($app_img_file, "r"); // 图片是否可读权限

            if ($fp) {
                $filesize = filesize($app_img_file);
                $content = fread($fp, $filesize);
                $file_content = chunk_split(base64_encode($content)); // base64编码
                switch ($img_info[2]) {           //判读图片类型
                    case 1: $img_type = "gif";
                        break;
                    case 2: $img_type = "jpg";
                        break;
                    case 3: $img_type = "png";
                        break;
                }

                $img_base64 = 'data:image/' . $img_type . ';base64,' . $file_content;//合成图片的base64编码

            }
            fclose($fp);
        }

        return $img_base64; //返回图片的base64
    }











}