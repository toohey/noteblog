<?php
class User extends ActiveRecord\Model 
{
    static $has_one = array('userprofile', 'blog', 'setting');
    
    static $_catch = array();
    
    public function name() {
        return $this->userprofile->first_name .' '. $this->userprofile->last_name;
    }
    
    protected static function CanLogin($email, $password)
    {
        $cond =array('include'=>array('userprofile'),'conditions'=>array("email = ? AND password = ?", $email, $password));
        $u = User::first($cond);
        return $u;
    }
    
    public static function Relog($u, $set_cookie = 0)
    {
        return self::Login($u->email, $u->password, 0, $set_cookie, 1);
    }
    
    public static function Login($email, $password, $gen_hash_pass = 1, $set_cookie = 0, $force = 0)
    {
        if(!$force && self::IsLoggedIn())
        {
            header('location: /');
            exit;
        }
        
        if(!($u = self::CanLogin($email, $gen_hash_pass?self::Hashgen($password):$password)))
            return false;
        
        $u->userprofile->options = unserialize($u->userprofile->options);
        
        self::SigninSession($u);
        
        if($set_cookie)
            self::SigninCookie($u);
        
        self::$_catch = array();
        
        return $u;
    }
    
    protected static function AutoLogin()
    {
        if(\iMVC\Security\SecureCookie::Contains(__CLASS__))
        {
            if(!($u = self::find($_COOKIE[__CLASS__])))
            {
                \iMVC\Security\SecureCookie::Delete(__CLASS__);
                return false;
            }
            self::SigninSession($u);
            return $u;
        }
        return false;
    }
    
    protected  static function SigninCookie($u)
    {
        \iMVC\Security\SecureCookie::Delete(__CLASS__);
        \iMVC\Security\SecureCookie::Set(__CLASS__, $u->user_id, 3600*24*30*12); // 1 year
    }
    
    protected static function SigninSession($u)
    {
        $_SESSION['app']['security']['user_info']['user'] = serialize($u);
    }
    
    public static function Logout()
    {
        \iMVC\Security\SecureCookie::Delete(__CLASS__);
        unset($_SESSION['app']['security']['user_info']);
        return true;
    }
    
    public static function IsLoggedIn()
    {
        // try session
        if(isset($_SESSION['app']['security']['user_info']))
            return true;
        // if not! try cookie
        self::AutoLogin();
        // return ultimate result
        return isset($_SESSION['app']['security']['user_info']);
    }
    /**
     * Get session instance of current user
     * @return User The user instance
     * @throws iMVC\Exceptions\SecurityException if user not logged in
     */
    public static function GetInstance()
    {
        if(!self::IsLoggedIn())
            throw new iMVC\Exceptions\SecurityException("User is not logged in.");
        
        if(!isset(self::$_catch[__METHOD__]) || !self::$_catch[__METHOD__])
            self::$_catch[__METHOD__] = isset($_SESSION['app']['security']['user_info']['user'])?unserialize($_SESSION['app']['security']['user_info']['user']):NULL;
        
        $u = self::$_catch[__METHOD__];
        
        return $u;
    }
    
    public function Join($email, $password)
    {
        $this->password = self::Hashgen($password);
        $this->email = $email;
        $this->save();
        Setting::InitialSettings($this->user_id);
        Blog::CreateRandomBlogName($this->user_id);
        $this->Login($email, $password);
    }
    public static function Hashgen($content)
    {
        return \iMVC\Security\Hash::Generate($content);
    }
}