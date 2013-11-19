<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * /blog
 */
class Blog_IndexController extends \iMVC\Controller\BaseController
{
    public function Initiate(){}
    
    public function IndexAction()
    {
        if(!Blog::exists(User::GetInstance()->user_id) || !isset(User::GetInstance()->blog->blog_name))
        {
            User::GetInstance()->setting->UpdateSetting('has_blog_named', 0);
            header('location: /');
            exit;
        }
    }
    public function CreateAction()
    {
        $this->IsGETSecured(array(User::GetInstance()->user_id));
        $this->ToRespond(function($type, $t)
        {
            switch($type)
            {
                case 'template':
                    if(isset($t->request->GET['suppress_layout']))
                        $t->layout->SuppressLayout();
                    $u = User::GetInstance();
                    if(!isset($u->blog->blog_name))
                    {
                        Blog::CreateRandomBlogName(User::GetInstance()->user_id);
                        User::Relog($u);
                    }
                    break;
                case 'action':
                    $t->IsSecure($_POST, array('blog_name'));
                    if(strlen($_POST['blog_name'])<5)
                    {
                        $m = "<div class='alert alert-error'>Your blog's name's length <b>cannot</b> less than 5 characters</div>";
                        if(!isset($_GET['ajax']))
                            throw new iMVC\Exceptions\InvalideArgumentException($m);
                        else
                            echo $m;
                    }
                    Blog::UpdateBlogName(User::GetInstance()->user_id, $_POST['blog_name']);
                    User::GetInstance()->setting->UpdateSetting('has_blog_named', 1);
                    User::Relog(User::GetInstance());
                    if(!isset($_GET['ajax']))
                        header('location: /');
                    else
                    {
                        $u = User::GetInstance();
                        echo "<div class='success alert alert-success'>Your <a href='/blog/{$u->blog->blog_name}'>blog</a> is ready.</div>";
                    }
                    exit;
            }
        });
    }
}
