<?php
    include __DIR__.'./../public/defines.inc';
    
    define("RUNNING_ENV", TEST);
    
    define("TEST_PATH", realpath(dirname(__DIR__))."/");
    
    require_once __DIR__.'/../iMVC/Application/Application.php';
    
    $CONF_FILE = realpath(__DIR__."/../Config/config.ini");
    
    $p = new \iMVC\APP\Application;
    
    ob_start();
        $p->Startup($CONF_FILE);
        
        $_SERVER['REQUEST_URI'] = $GLOBALS[CONFIGS]['init']['uri'];
        
        $p->Run();
        
        $p->Dispose();
    ob_end_clean();