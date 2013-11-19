<?php
class Setting extends ActiveRecord\Model
{    
    static $belongs_to = array(array('user'));
    
    static $before_save = array('serialize_setting');
    
    static $primary_key = 'user_id';
    
    public function __construct(array $attributes = array(),
        $guard_attributes = true, $instantiating_via_find = false,
        $new_record = true)
    {
        parent::__construct($attributes, $guard_attributes,
            $instantiating_via_find, $new_record);
        
        if(strlen($this->settings))
            $this->settings = unserialize($this->settings);
        else
            $this->settings = new stdClass();
    }
    
    public function UpdateSetting($name , $value)
    {
        $this->settings->$name = $value;
    }
    
    public function RemoveSetting($name)
    {
        unset($this->settings->$name);
    }
    
    public function GetSetting($name)
    {
        if(!isset($this->settings->$name))
            return NULL;
        return $this->settings->$name;
    }


    public function __destruct()
    {
        $this->save();
    }
    
    public function serialize_setting()
    {
        $this->settings = serialize($this->settings);
    }
    
    public static function InitialSettings($user_id)
    {
        $s = null;
        if(!Setting::exists($user_id))
            $s = new Setting(array(self::$primary_key => $user_id));
        else
            $s = Setting::find($user_id);
        $s->UpdateSetting('has_blog_named', 0);
        return $s;
    }
}