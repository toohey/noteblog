<?php
class Bookmark extends ActiveRecord\Model
{
    const ROOT_PARENT = Item::ROOT_PARENT;
    
    static $belongs_to = array(
        array('folder', 'foreign_key' => 'parent_id'),
        array('user')
    );
    
    /**
     * 
     * @param int $user_id
     * @param int $bookmark_id
     * @return Bookmark
     * @throws Exception if user_id or parent_id does not exists
     */
    public static function GetBookmark($user_id, $bookmark_id)
    {
        $i = new Item(__CLASS__);
        return $i->  GetItem($user_id, $bookmark_id);
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
    
    public static function GetByName($user_id, $parent_id, $name)
    {
        $i = new Item(__CLASS__);
        return $i-> GetByName($user_id, $parent_id, $name);
    }
    
    public function BookmarkExists($user_id, $parent_id, $bookmark_name)
    {
        $i = new Item(__CLASS__);
        return $i-> ItemExists($user_id, $parent_id, $bookmark_name);
    }
    
    public static function GetChildren($user_id, $parent_id, $uplimit_count = -1, $down_limit = 0)
    {
        $i = new Item(__CLASS__);
        return $i->GetChildren($user_id, $parent_id, $uplimit_count, $down_limit);
    }
    
    public static function Remove($user_id, $bookmark_id, $put_trash = 1)
    {
        $i = new Item(__CLASS__);
        return $i-> Remove($user_id, $bookmark_id, $put_trash);
    }
    
    public static function GetRouteMap($user_id, $bookmark_id)
    {
        $i = new Item(__CLASS__);
        return $i-> GetRouteMap($user_id, $bookmark_id);
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
    
    public static function UpdateViewedDate($user_id, $bookmark_id)
    {
        $i = new Item(__CLASS__);
        return $i-> UpdateViewedDate($user_id, $bookmark_id);
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
    public static function SetArchive($user_id, $bookmark_id, $archive_status)
    {
        $i = new Item(__CLASS__);
        return $i->SetArchive($user_id, $bookmark_id, $archive_status);   
    }
    public function Restore($user_id, $bookmark_id)
    {
        $i = new Item(__CLASS__);
        return $i->Restore($user_id, $bookmark_id);   
    }
    public static function GetStars($user_id, $uplimit_count = -1, $down_limit = 0)
    {
        $i = new Item(__CLASS__);
        return $i->GetStars($user_id, $uplimit_count, $down_limit);   
    }
    public static function SetStar($user_id, $bookmark_id, $star_status)
    {
        $i = new Item(__CLASS__);
        return $i->SetStars($user_id, $bookmark_id, $star_status);           
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
    
    public static function MoveBookmark($user_id, $parent_id, $bookmark_id, $target_folder_id)
    {
        $i = new Item(__CLASS__);
        return $i->MoveItem($user_id, $parent_id, $bookmark_id, $target_folder_id);
    }
    
    public static function Edit($user_id, $bookmark_id, $new_name, $new_body)
    {
        $f = self::GetBookmark($user_id, $bookmark_id);
        $f->bookmark_name = $new_name;
        $f->bookmark_body = $new_body;
        $f->save();
        $i = new Item(__CLASS__);
        return $i->UpdateModifiedDate($user_id, $f->bookmark_id);
    }
    
    public static function CreateBookmark($user_id, $parent_id, $bookmark_name, $bookmark_body)
    {
        $f = new Bookmark();
        $f->bookmark_name = $bookmark_name;
        $f->bookmark_body = $bookmark_body;
        $f->parent_id = $parent_id;
        $f->user_id = $user_id;
        $f->save();
        $i = new Item(__CLASS__);
        return $i->UpdateModifiedDate($user_id, $f->bookmark_id);
    }
    
    public static function Share($user_id, $bookmark_id, $share = true)
    {
        try{
            $n = self::GetBookmark($user_id, $bookmark_id);
            $n->is_shared = $share?1:0;
        }
        catch(Exception $e)
        {return NULL;}
        $n->save();
        $i = new Item(__CLASS__);
        return $i->UpdateModifiedDate($user_id, $bookmark_id);
    }
}