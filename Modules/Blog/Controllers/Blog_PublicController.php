<?php

/**
 * /blog/public
 */
class Blog_PublicController extends \iMVC\Controller\BaseController
{
    public function Initiate(){}
    
    public function IndexAction()
    {
        $this->layout->SetLayout('public');
    }
}
