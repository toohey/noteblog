<?php
class Blog extends ActiveRecord\Model
{
    static $belongs_to = array(array('user'));
    
    static $primary_key = 'user_id';
    
    public static function CreateRandomBlogName($user_id)
    {
        $bn = "";
        while(true)
        {
            srand(time());
            $bn = substr(\iMVC\Security\Hash::Generate($user_id.time().rand(-10000,10000).$bn), 0, 10);
            if(!Blog::exists(array('conditions'=>array('blog_name' => $bn))))
            {
                return self::UpdateBlogName($user_id, $bn);
            }
        }
    }
    
    public static function UpdateBlogName($user_id, $blog_name)
    {
        $b = null;
        if(!Blog::exists($user_id))
            $b = new Blog(array(self::$primary_key => $user_id));
        else
            $b = Blog::find($user_id);
        $blog_name = str_replace(" ", ".", $blog_name);
        $blog_name = str_replace("#", ".", $blog_name);
        $blog_name = str_replace("<", ".", $blog_name);
        $blog_name = str_replace(">", ".", $blog_name);
        $blog_name = str_replace("!", ".", $blog_name);
        if(iMVC\Tools\String::startsWith($blog_name, "."))
            $blog_name = substr($blog_name, 1);
        if(iMVC\Tools\String::endsWith($blog_name, "."))
            $blog_name = substr($blog_name, 0,  strlen($blog_name)-1);
        $blog_name = str_replace(".", "_", $blog_name);
        $b->blog_name = $blog_name;
        $b->save();
        return $b;
    }
}