<?php
namespace cmf\controller;

use think\Db;
use app\admin\model\ThemeModel;
use think\View;
use app\common\model\TokenModel;

class HomeBaseController extends BaseController
{

    protected function initialize()
    {
        // 监听home_init
        hook('home_init');
        parent::initialize();
        $siteInfo = cmf_get_site_info();
        View::share('site_info', $siteInfo);
    }

    protected function _initializeView()
    {
        $cmfThemePath    = config('template.cmf_theme_path');
        $cmfDefaultTheme = cmf_get_current_theme();

        $themePath = "{$cmfThemePath}{$cmfDefaultTheme}";

        $root = cmf_get_root();
        //使cdn设置生效
        $cdnSettings = cmf_get_option('cdn_settings');
        if (empty($cdnSettings['cdn_static_root'])) {
            $domain=cmf_get_domain();
            $viewReplaceStr = [
                '__ROOT__'     => $domain.$root,
                '__TMPL__'     => $domain."{$root}/{$themePath}",
                '__STATIC__'   => $domain."{$root}/static",
                '__WEB_ROOT__' => $domain.$root
            ];
        } else {
            $cdnStaticRoot  = rtrim($cdnSettings['cdn_static_root'], '/');
            $viewReplaceStr = [
                '__ROOT__'     => $root,
                '__TMPL__'     => "{$cdnStaticRoot}/{$themePath}",
                '__STATIC__'   => "{$cdnStaticRoot}/static",
                '__WEB_ROOT__' => $cdnStaticRoot
            ];
        }

        $viewReplaceStr = array_merge(config('view_replace_str'), $viewReplaceStr);
        config('template.view_base', WEB_ROOT . "{$themePath}/");
        config('view_replace_str', $viewReplaceStr);

        $themeErrorTmpl = "{$themePath}/error.html";
        if (file_exists_case($themeErrorTmpl)) {
            config('dispatch_error_tmpl', $themeErrorTmpl);
        }

        $themeSuccessTmpl = "{$themePath}/success.html";
        if (file_exists_case($themeSuccessTmpl)) {
            config('dispatch_success_tmpl', $themeSuccessTmpl);
        }


    }

    /**
     * 加载模板输出
     * @access protected
     * @param string $template 模板文件名
     * @param array  $vars     模板输出变量
     * @param array  $replace  模板替换
     * @param array  $config   模板参数
     * @return mixed
     */
    protected function fetch($template = '', $vars = [], $replace = [], $config = [])
    {
        $template = $this->parseTemplate($template);
        $more     = $this->getThemeFileMore($template);
        $this->assign('theme_vars', $more['vars']);
        $this->assign('theme_widgets', $more['widgets']);
        $content = parent::fetch($template, $vars, $replace, $config);

        $designingTheme = cookie('cmf_design_theme');

        if ($designingTheme) {
            $app        = $this->request->module();
            $controller = $this->request->controller();
            $action     = $this->request->action();

            $output = <<<hello
<script>
var _themeDesign=true;
var _themeTest="test";
var _app='{$app}';
var _controller='{$controller}';
var _action='{$action}';
var _themeFile='{$more['file']}';
if(parent && parent.simulatorRefresh){
  parent.simulatorRefresh();  
}
</script>
hello;

            $pos = strripos($content, '</body>');
            if (false !== $pos) {
                $content = substr($content, 0, $pos) . $output . substr($content, $pos);
            } else {
                $content = $content . $output;
            }
        }


        return $content;
    }

    /**
     * 自动定位模板文件
     * @access private
     * @param string $template 模板文件规则
     * @return string
     */
    private function parseTemplate($template)
    {
        // 分析模板文件规则
        $request = $this->request;
        // 获取视图根目录
        if (strpos($template, '@')) {
            // 跨模块调用
            list($module, $template) = explode('@', $template);
        }

        $viewBase = config('template.view_base');

        if ($viewBase) {
            // 基础视图目录
            $module = isset($module) ? $module : $request->module();
            $path   = $viewBase . ($module ? $module . DIRECTORY_SEPARATOR : '');
        } else {
            $path = isset($module) ? APP_PATH . $module . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR : config('template.view_path');
        }

        $depr = config('template.view_depr');
        if (0 !== strpos($template, '/')) {
            $template   = str_replace(['/', ':'], $depr, $template);
            $controller = cmf_parse_name($request->controller());
            if ($controller) {
                if ('' == $template) {
                    // 如果模板文件名为空 按照默认规则定位
                    $template = str_replace('.', DIRECTORY_SEPARATOR, $controller) . $depr . cmf_parse_name($request->action(true));
                } elseif (false === strpos($template, $depr)) {
                    $template = str_replace('.', DIRECTORY_SEPARATOR, $controller) . $depr . $template;
                }
            }
        } else {
            $template = str_replace(['/', ':'], $depr, substr($template, 1));
        }
        return $path . ltrim($template, '/') . '.' . ltrim(config('template.view_suffix'), '.');
    }

    /**
     * 获取模板文件变量
     * @param string $file
     * @param string $theme
     * @return array
     */
    private function getThemeFileMore($file, $theme = "")
    {

        //TODO 增加缓存
        $theme = empty($theme) ? cmf_get_current_theme() : $theme;

        // 调试模式下自动更新模板
        if (APP_DEBUG) {
            $themeModel = new ThemeModel();
            $themeModel->updateTheme($theme);
        }

        $themePath = config('template.cmf_theme_path');
        $file      = str_replace('\\', '/', $file);
        $file      = str_replace('//', '/', $file);
        $webRoot   = str_replace('\\', '/', WEB_ROOT);
        $themeFile = str_replace(['.html', '.php', $themePath . $theme . "/", $webRoot], '', $file);

        $files = Db::name('theme_file')->field('more')->where('theme', $theme)
            ->where(function ($query) use ($themeFile) {
                $query->where('is_public', 1)->whereOr('file', $themeFile);
            })->select();

        $vars    = [];
        $widgets = [];
        foreach ($files as $file) {
            $oldMore = json_decode($file['more'], true);
            if (!empty($oldMore['vars'])) {
                foreach ($oldMore['vars'] as $varName => $var) {
                    $vars[$varName] = $var['value'];
                }
            }

            if (!empty($oldMore['widgets'])) {
                foreach ($oldMore['widgets'] as $widgetName => $widget) {

                    $widgetVars = [];
                    if (!empty($widget['vars'])) {
                        foreach ($widget['vars'] as $varName => $var) {
                            $widgetVars[$varName] = $var['value'];
                        }
                    }

                    $widget['vars']       = $widgetVars;
                    $widgets[$widgetName] = $widget;
                }
            }
        }

        return ['vars' => $vars, 'widgets' => $widgets, 'file' => $themeFile];
    }

    public function checkUserLogin()
    {
        $userId = cmf_get_current_user_id();
        if (empty($userId)) {
            if ($this->request->isAjax()) {
                $this->error("您尚未登录", cmf_url("user/Login/index"));
            } else {
                $this->redirect(cmf_url("user/Login/index"));
            }
        }
    }

    /**
     * 创建token
     * @param  string  $user_id           用户ID
     * @param  integer $length            随机字符串的长度
     * @param  string  $randomString_star 随机字符串的前缀
     * @param  string  $characters        随机字符串的字符串内容
     * @param  integer $time              重复次数
     * @return [type]                     token值
     */
    public function create_token( $user_id = "" , $length = 20 , $randomString_star = "cyrd_" , $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' , $time = 0){
        if(!$user_id){
            $this->backto( 40001 );
        }

        $randomString = $randomString_star . date("Ymd")."_";
        $randomString .= sprintf( '%011s' , $user_id)."_";
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        if($time < 3 ){
        $randomString = "cyrd_20190827_00000000002_k5ERLqR4huxfvgEpfnz6";
    }
        $TokenModel = new TokenModel();
        $check = $TokenModel->check_token( $randomString);
        if($check === false){
            $this->backto( 20005 );
        }
        if($check !== "OK"){
            if($time >= 3 ){
                $this->backto( 90002 );
            }else{
                $time ++;
                $randomString = $this->create_token($user_id , $length , $randomString_star , $characters , $time);
                return $randomString;
            }
        }
        $ret = $TokenModel->create_token( $randomString , $user_id );
        if($ret){
            return $randomString;
        }else{
            return false;
        }
    }

    // 回调函数
    public function backto($code,$msg="",$data="",$type="json",$callback=""){
        if(!$msg){
            if($this->error_code[$code]){
                $msg = $this->error_code[$code];
            }else{
                $msg = "系统错误，请刷新重试";
            }
        }
        if($code){
            $log['url'] = $_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"];
            $log['param'] = json_encode(input());
            $log['code'] = $code;
            $log['msg'] = $msg;
            $log['createTime'] = date('Y-m-d H:i:s');
            model("ErrorLog")->insert($log);
        }

        $back = array(
            'code'=>$code,
            'msg'=>$msg,
            'data'=>$data
        );
        if($type === "jsonp" || $callback || input('callback')){
            $cb = input('callback') ? input('callback') : "back";
            echo htmlspecialchars($cb)."(".json_encode($back).")";
            exit;
        }
        echo json_encode($back);
        exit;
    }

    private $error_code = [
        10001     => "参数缺失",
            10002 => "参数格式错误",
            10003 => "纯文本格式时，文本内容不允许为空",
            10004 => "缺失回答内容，请检查是否所有问题已经作答",
        20001     => "请求失败",    // 系统错误，未知具体错误信息
            20002 => "请求失败",    // 数据库操作失败
            20003 => "没有查询结果",    // 数据库操作失败
            20004 => "内容已存在，请勿重复操作",    // 数据库已有相同数据
            20005 => "生成token失败，请刷新重试",
        30001     => "请求失败",    // 自定义错误信息
            30002 => "1",
        40001     => "请先登录",    // 缺失用户信息
        90001     => "系统错误，请刷新重试",  // 未知错误信息
            90002 => "系统错误，请刷新重试",  // token连续创建3次失败
    ];
}