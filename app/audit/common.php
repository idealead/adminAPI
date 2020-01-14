<?php
use think\Request;
defined('IS_POST') or define('IS_POST', Request::instance()->isPost());
defined('IS_AJAX') or define('IS_AJAX', Request::instance()->isAjax());
defined('IS_GET') or define('IS_GET', Request::instance()->isGet());