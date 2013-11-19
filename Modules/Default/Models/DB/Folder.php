<?php
class Folder extends ActiveRecord\Model
{       
    const ROOT_PARENT = Item::ROOT_PARENT;
    
    static $belongs_to = array(
        array('folder', 'foreign_key' => 'parent_id'),
        array('user')
    );
    
    /**
     * 
     * @param int $user_id
     * @param int $folder_id
     * @return Folder
     * @throws Exception if user_id or parent_id does not exists
     */
    public static function GetFolder($user_id, $folder_id)
    {
        $i = new Item(__CLASS__);
        return $i->  GetItem($user_id, $folder_id);
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
    
    public function FolderExists($user_id, $parent_id, $folder_name)
    {
        $i = new Item(__CLASS__);
        return $i-> ItemExists($user_id, $parent_id, $folder_name);
    }
    
    public static function GetChildren($user_id, $parent_id, $uplimit_count = -1, $down_limit = 0)
    {
        $i = new Item(__CLASS__);
        return $i->GetChildren($user_id, $parent_id, $uplimit_count, $down_limit);
    }
    
    public static function Remove($user_id, $folder_id, $put_trash = 1)
    {
        $i = new Item(__CLASS__);
        return $i-> Remove($user_id, $folder_id, $put_trash);
    }
    
    public static function Rename($user_id, $folder_id, $new_name)
    {
        $i = new Item(__CLASS__);
        return $i-> Rename($user_id, $folder_id, $new_name);
    }
    
    public static function GetRouteMap($user_id, $folder_id)
    {
        $i = new Item(__CLASS__);
        return $i-> GetRouteMap($user_id, $folder_id);
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
    
    public static function UpdateViewedDate($user_id, $folder_id)
    {
        $i = new Item(__CLASS__);
        return $i-> UpdateViewedDate($user_id, $folder_id);
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
    public static function SetArchive($user_id, $folder_id, $archive_status)
    {
        $i = new Item(__CLASS__);
        return $i->SetArchive($user_id, $folder_id, $archive_status);   
    }
    public function Restore($user_id, $note_id)
    {
        $i = new Item(__CLASS__);
        return $i->Restore($user_id, $note_id);   
    }
    public static function GetStars($user_id, $uplimit_count = -1, $down_limit = 0)
    {
        $i = new Item(__CLASS__);
        return $i->GetStars($user_id, $uplimit_count, $down_limit);   
    }
    public static function SetStar($user_id, $note_id, $star_status)
    {
        $i = new Item(__CLASS__);
        return $i->SetStars($user_id, $note_id, $star_status);           
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
    
    public static function MoveFolder($user_id, $parent_id, $folder_id, $target_folder_id)
    {
        $i = new Item(__CLASS__);
        return $i->MoveItem($user_id, $parent_id, $folder_id, $target_folder_id);
    }
    
    public static function CreateFolder($user_id, $parent_id, $folder_name)
    {
        $f = new Folder;
        $f->folder_name = $folder_name;
        $f->parent_id = $parent_id;
        $f->user_id = $user_id;
        $f->save();
        $i = new Item(__CLASS__);
        return $i->UpdateModifiedDate($user_id, $f->folder_id);
    }
    
    public static function GetRoots_RootFolder()
    {
        $f = new Folder();
        $cond = array(
            'conditions' => array('folder_id = ?', Folder::ROOT_PARENT)
        );
        $f = $f->first($cond);
        if(!$f)
            throw new iMVC\Exceptions\NotFoundException("The folder does not exists");
        return $f;
    }
}