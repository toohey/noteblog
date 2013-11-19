<?php
class Attach extends ActiveRecord\Model
{
    const ROOT_PARENT = Item::ROOT_PARENT;
    
    static $belongs_to = array(
        array('folder', 'foreign_key' => 'parent_id'),
        array('user')
    );
    
    /**
     * 
     * @param int $user_id
     * @param int $attach_id
     * @return Attach
     * @throws Exception if user_id or parent_id does not exists
     */
    public static function GetAttach($user_id, $attach_id)
    {
        $i = new Item(__CLASS__);
        return $i->  GetItem($user_id, $attach_id);
    }
    
    public static function GetRoots($user_id, $uplimit_count = -1, $down_limit = 0)
    {
        $i = new Item(__CLASS__);
        return $i-> GetRoots($user_id, $uplimit_count, $down_limit);
    }
    
    public static function GetByParentId($user_id, $parent_id, $uplimit_count = -1, $down_limit = 0)
    {
        $i = new Item(__CLASS__);
        return $i-> GetByParentId($user_id, $parent_id, $uplimit_count, $down_limit);
    }
    
    public static function GetByBody($user_id, $parent_id, $body)
    {
        $f = new Attach;
        
        $cond = array(
            'conditions' => array("user_id = ? AND parent_id = ? AND attach_body = ?", $user_id, $parent_id, $body), 
            'include'=>array('folder','user')
        );
        return $f->first($cond);
    }
    
    public function AttachExists($user_id, $parent_id, $attach_name)
    {
        $i = new Item(__CLASS__);
        return $i-> ItemExists($user_id, $parent_id, $attach_name);
    }
    
    public static function GetChildren($user_id, $parent_id, $uplimit_count = -1, $down_limit = 0)
    {
        $i = new Item(__CLASS__);
        return $i->GetChildren($user_id, $parent_id, $uplimit_count, $down_limit);
    }
    
    public static function Remove($user_id, $attach_id, $put_trash = 1)
    {
        $i = new Item(__CLASS__);
        return $i-> Remove($user_id, $attach_id, $put_trash);
    }
    
    public static function GetRouteMap($user_id, $attach_id)
    {
        $i = new Item(__CLASS__);
        return $i-> GetRouteMap($user_id, $attach_id);
    }
    public static function GetCount($user_id, $parent_id)
    {
        $i = new Item(__CLASS__);
        return $i-> GetCount($user_id, $parent_id);
    }
    
    public static function GetWith_SharedStatus_Children($user_id, $parent_id, $is_shared = 1, $uplimit_count = -1, $down_limit = 0)
    {
        $i = new Item(__CLASS__);
        return $i-> GetWith_SharedStatus_Children($user_id, $parent_id, $is_shared, $uplimit_count, $down_limit);
    }
    
    public static function UpdateViewedDate($user_id, $attach_id)
    {
        $i = new Item(__CLASS__);
        return $i-> UpdateViewedDate($user_id, $attach_id);
    }
    
    public static function GetRecentUsed($user_id, $uplimit_count = -1, $down_limit = 0)
    {
        $i = new Item(__CLASS__);
        return $i-> GetRecentUsed($user_id, $uplimit_count, $down_limit);
    }
    
    public static function GetTrashes($user_id, $uplimit_count = -1, $down_limit = 0)
    {
        $i = new Item(__CLASS__);
        return $i-> GetTrashes($user_id, $uplimit_count, $down_limit);    
    }
    
    public static function GetArchives($user_id, $uplimit_count = -1, $down_limit = 0)
    {
        $i = new Item(__CLASS__);
        return $i-> GetArchives($user_id, $uplimit_count, $down_limit);   
    }
    public static function SetArchive($user_id, $attach_id, $archive_status)
    {
        $i = new Item(__CLASS__);
        return $i->SetArchive($user_id, $attach_id, $archive_status);   
    }
    public function Restore($user_id, $attach_id)
    {
        $i = new Item(__CLASS__);
        return $i->Restore($user_id, $attach_id);   
    }
    public static function GetStars($user_id, $uplimit_count = -1, $down_limit = 0)
    {
        $i = new Item(__CLASS__);
        return $i->GetStars($user_id, $uplimit_count, $down_limit);   
    }
    public static function SetStar($user_id, $attach_id, $star_status)
    {
        $i = new Item(__CLASS__);
        return $i->SetStars($user_id, $attach_id, $star_status);           
    }
    public static function GetShared($user_id, $uplimit_count = -1, $down_limit = 0)
    {
        $i = new Item(__CLASS__);
        return $i->GetShared($user_id, $uplimit_count, $down_limit);     
    }
    public static function GetAll($user_id, $uplimit_count = -1, $down_limit = 0)
    {
        $i = new Item(__CLASS__);
        return $i->GetAll($user_id, $uplimit_count, $down_limit);     
    }
    
    public static function MoveAttach($user_id, $parent_id, $attach_id, $target_folder_id)
    {
        $i = new Item(__CLASS__);
        return $i->MoveItem($user_id, $parent_id, $attach_id, $target_folder_id);
    }
    
    public static function Edit($user_id, $attach_id, $new_name, $new_body)
    {
        $f = self::GetAttach($user_id, $attach_id);
        $f->attach_name = $new_name;
        $f->attach_body = $new_body;
        $f->save();
        $i = new Item(__CLASS__);
        return $i->UpdateModifiedDate($user_id, $attach_id);
    }
    
    public static function CreateAttach($user_id, $parent_id, $attach_name, $attach_body)
    {
        $f = new Attach();
        $f->attach_name = $attach_name;
        $f->attach_body = $attach_body;
        $f->parent_id = $parent_id;
        $f->user_id = $user_id;
        $f->save();
        $i = new Item(__CLASS__);
        return $i->UpdateModifiedDate($user_id, $f->attach_id);
    }
    
    public static function Share($user_id, $attach_id, $share = true)
    {
        try{
            $n = self::GetAttach($user_id, $attach_id);
            $n->is_shared = $share?1:0;
        }
        catch(Exception $e)
        {return 0;}
        $n->save();
        $i = new Item(__CLASS__);
        return $i->UpdateModifiedDate($user_id, $attach_id);
    }
}