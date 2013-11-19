<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of user_profile
 *
 * @author dariush
 */
class Userprofile extends ActiveRecord\Model 
{
    static $belongs_to = array(array('user'));
    
    public static function CreateProfileFromArray($user_id, array $profile)
    {
        if(!User::exists($user_id))
            throw new iMVC\Exceptions\InvalideOperationException("The user with id `$user_id` does not exists...");
        // first delete current profile if exists
        Userprofile::delete_all(array('conditions'=>array('user_id = ?', $user_id)));
        // create a new one
        $up = new \Userprofile;
        // add the user_id 
        $up->user_id = $user_id;
        // create a profile based on passed array
        foreach ($profile as $key => $value) {
            $up->$key = $value;
        }
        // save the profile
        return $up->save();
    }
}

?>
