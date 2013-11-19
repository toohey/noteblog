<?php

/**
 * Bootstrap for current module
 *
 * @author dariush
 */
class DefaultBootstrap
{
    public function AuthInit(iMVC\Routing\Request &$request)
    {
        if(!\User::IsLoggedIn() && strtolower($request->controller) != 'authcontroller')
        {
            header("location: /blog/public");
            exit;
        }
    }
}
