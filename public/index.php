<?php
    include "defines.inc";
	    
    try
    {
        session_start();
        // this should be enabled
        define('RUNNING_ENV', DEVELOPMENT);
        // error-show on if not production
        if(RUNNING_ENV!=PRODUCTION)
        {
            ini_set('display_errors','On');
            error_reporting(E_ALL);
        }
        else
        {
            ini_set('display_errors','Off');
        }
        // require application's class's file
        require '../iMVC/Application/Application.php';
        // create a new instance of application
        $app = new \iMVC\APP\Application();
        // start application up
        $app->Startup('../Config/config.ini');
        // run the application
        $app->Run();
        // shut it donw
        $app->Shutdown();
    }
    catch(Exception $e)
    {
        # if it is ajax call
        if(isset($_GET['ajax']))
        {
            # just print the error message
            echo "<div class='alert alert-error text-center'><b>{$e->getMessage()}!</b></div>";
            exit;
        }
        # otherwise
        try
        {
            $_SESSION['app']['tmp']['exception'] ['e']= serialize($e);
            $_SESSION['app']['tmp']['exception'] ['r']= serialize(new \iMVC\Routing\Request());
        }
        catch(Exception $_e)
        {
            require_once 'general.error.php';
            exit;
        }
        if(headers_sent())
            echo "<div class='alert alert-error'>{$e->getMessage()}</div>";
        else
            header('location: /blog/error');
        exit;
        // catch if any thing happened 
        \iMVC\Tools\Debug::_var($e->getMessage());
    }
