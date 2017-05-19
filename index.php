<?php

    define('PHP_PATH','php/');
    define('HTML_PATH', 'html/');

    #0 - turn on error logging !
    //require_once(PHP_PATH . 'errorlog.php');

    #0 - requires
    require_once(PHP_PATH . 'Mobile_Detect.php');
    require_once(PHP_PATH . 'template.php');

    #1 - read site config
    require_once(PHP_PATH . 'utils.php');

    $config = readJson('site.json');    // $config is GLOBAL IMPORTANT VARIABLE !!!
    $_GET['sc_config'] = $config;  // save $config in superglobal, after this line must use $_GET only !!!

    #2 - work with sessions, timezones and locales 
    @session_cache_expire($_GET['sc_config']['session_limit']);
    @session_start();

    @setlocale(LC_ALL, $_GET['sc_config']['locale']);
    @date_default_timezone_set($_GET['sc_config']['timezone']);

    #3 - now working with params
    $raw_query = $_SERVER['REQUEST_URI']; 
	$query_parts = array_slice(explode('/', $raw_query), 1);   // first element always empty
	
    if($_GET['sc_config']['site_path'] !== '') $query_parts = array_slice($query_parts, 1); // if site_path specified - remove it from $query_parts

    if(sizeof($query_parts) == 1) {
        if($query_parts[0] === '') $controller_name = $_GET['sc_config']['home_controller'];  // no params controller = home (it's for production)
        else $controller_name = $query_parts[0];
        $_GET['sc_params'] = array();
    } else {
        $controller_name = $query_parts[0];
        // now save other params in superglobal $_GET
        $_GET['sc_params'] = array_slice($query_parts, 1, sizeof($query_parts) - 1); 
    }

    $_GET['sc_controller'] = $controller_name;

    #4 - work with controller & models
    require_once(PHP_PATH . 'icontroller.php');
    require_once(PHP_PATH . 'imodel.php');

    $md = new Mobile_Detect;
    $mobile = $md->isMobile();

    $controller_file = PHP_PATH . 'c-' . $controller_name . ($mobile ? '-m' : '-d') . '.php';
    $controller_file = file_exists($controller_file) ? $controller_file : PHP_PATH . 'c-default.php';

    require_once($controller_file);
    $controller = new Controller($_GET);

    $_GET['sc_config']['content'] = $controller->html();

    #5 - adjust %self% variable
    $host = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'];
    if($_GET['sc_config']['site_path'] !== '')  {
        $host .= '/' . $_GET['sc_config']['site_path'];
    }
    
    $_GET['sc_config']['self'] = $host;
 
    #5 - work with master template
    $html = workUpTemplate(HTML_PATH . ($mobile ? 'master-m.html' : 'master-d.html'), $_GET['sc_config']);

    // ----->>>>> OUTPUT !!!
    echo $html;

?>