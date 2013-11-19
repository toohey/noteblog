<?php
require_once 'IItem.php';
class Item extends ActiveRecord\Model implements IITem
{
    const ROOT_PARENT = -1;
    /**
     * @var ActiveRecord\Model
     */
    protected $target_class;
    /**
     *
     * @var string
     */
    protected $name_prefix;
    /**
     *
     * @var string
     */
    protected $parent_class;
    
    static $belongs_to = array(
        array('folder', 'foreign_key' => 'parent_id'),
        array('user')
    );
    
    public function __construct ($class_name, $parent_class = "Folder")
    {
        if(  !class_exists ( $class_name ))
            throw new InvalidArgumentException("`$class_name` does not exist!");

        if(  !class_exists ( $parent_class ))
            throw new InvalidArgumentException("`$parent_class` does not exist!");
        
        $this->target_class = $class_name;
        $this->parent_class = $parent_class;
        $this->name_prefix = strtolower($class_name);
        
    }
    /**
     * 
     * @param int $user_id
     * @param int $item_id
     * @return Item
     * @throws Exception if user_id or parent_id does not exists
     */
    public function GetItem($user_id, $item_id)
    {
        if(!User::exists($user_id))
            throw new iMVC\Exceptions\NotFoundException("The user does not exists...");
        
        $f = new $this->target_class();
        $cond = array(
            'conditions' => array("user_id = ? AND {$this->name_prefix}_id = ?", $user_id, $item_id), 
            'include'=>array('folder', 'user')
        );
        $f = $f->first($cond);
        if(!$f)
            throw new iMVC\Exceptions\NotFoundException("The {$this->name_prefix} does not exists");
        return $f;
    }
    
    public function GetRoots($user_id, $uplimit_count = -1, $down_limit = 0)
    {
        return self::GetChildren($user_id, self::ROOT_PARENT, $uplimit_count, $down_limit);
    }
    
    public function GetByParentId($user_id, $parent_id, $uplimit_count = -1, $down_limit = 0)
    {
        return Item::GetChildren($user_id, $parent_id, $uplimit_count, $down_limit);
    }
    
    public function GetByName($user_id, $parent_id, $name)
    {
        $f = new $this->target_class;
        
        $cond = array(
            'conditions' => array("user_id = ? AND parent_id = ? AND {$this->name_prefix}_name = ?", $user_id, $parent_id, $name), 
            'include'=>array('folder','user')
        );
        return $f->first($cond);
    }
    
    public function ItemExists($user_id, $parent_id, $item_name)
    {
        $f = new $this->target_class;
        $cond = array('conditions' => array("user_id = ? AND parent_id = ? AND {$this->name_prefix}_name like ?", $user_id, $parent_id,"%$item_name%"));
        return $f->count($cond);
    }
    
    public function GetChildren($user_id, $parent_id, $uplimit_count = -1, $down_limit = 0)
    {
        if(!is_numeric ( $down_limit) || !is_numeric ( $uplimit_count))
            throw new InvalidArgumentException("limits should be numeric!");
        $f = new $this->target_class;
        
        $cond = array(
            'conditions' => array("user_id = ? AND parent_id = ? AND is_trashed = 0 AND is_archived <> 1 ORDER BY  `{$this->name_prefix}_name` ASC ".($uplimit_count>$down_limit?" LIMIT $down_limit, $uplimit_count":""), $user_id, $parent_id), 
            'include'=>array('folder','user')
        );
        $r = $f->all($cond);
        
        if(!count($cond))
            return NULL;
        
        return $r;
    }
    
    
    public function Restore($user_id, $item_id)
    {
        $item = new Item($this->target_class);
        $item = $item->GetItem($user_id, $item_id);
        $item->is_trashed = 0;
        $item->save();
        return $this->UpdateModifiedDate($user_id, $item_id);
    } 
    
    public function Remove($user_id, $item_id, $put_trash = 1)
    {
        // this will be a shallow removal on trashing, we may retrun to our trash thing from time to time :)
        // in-complete implementation here!
        # throw new \iMVC\Exceptions\NotImplementedException;
        if($user_id==Item::ROOT_PARENT && $item_id==Item::ROOT_PARENT)
            throw new iMVC\Exceptions\InvalideOperationException("Cannot remove 'root' items");
        
        $cond = array(
            'conditions' => array("user_id = ? AND {$this->name_prefix}_id = ?", $user_id, $item_id)
            );
        $f = new $this->target_class;
        
        $f = $f->find($cond);
        
        if(!$put_trash)
            $f->delete();
        else
        {
            $f->is_trashed = 1;
            $f->save();
            return $this->UpdateModifiedDate($user_id, $item_id);
        }
        return $f;
    }
    
    public function GetAll($user_id, $uplimit_count = -1, $down_limit = 0)
    {
        if(!User::exists($user_id))
            throw new iMVC\Exceptions\NotFoundException("The user does not exists...");
        
        $f = new $this->target_class();
        $cond = array(
            'conditions' => array("user_id = ?  ORDER BY  `{$this->name_prefix}_name` ASC ".($uplimit_count>$down_limit?" LIMIT $down_limit, $uplimit_count":""), $user_id, ), 
            'include'=>array('folder', 'user')
        );
        $f = $f->all($cond);
        if(!$f)
            throw new iMVC\Exceptions\NotFoundException("The {$this->name_prefix} does not exists");
        return $f;
    }
    
    public function Rename($user_id, $item_id, $new_name)
    {
        $field_name = "{$this->name_prefix}_name";
        $f = self::GetItem($user_id, $item_id);
        $f->$field_name = $new_name;
        $f->save();
        return $this->UpdateModifiedDate($user_id, $item_id);
    }
    
    public function GetRouteMap($user_id, $item_id)
    {
        $rm = array();
        while($item_id != Item::ROOT_PARENT)
        {
            $f = Item::GetItem($user_id, $item_id);
            array_push($rm, $f);
            $item_id = $f->parent_id;
        }
        return array_reverse($rm);
    }
    
    public function GetCount($user_id, $parent_id)
    {
        return count(self::GetChildren($user_id, $parent_id));
    }
    
    public function GetWith_SharedStatus_Children($user_id, $parent_id, $is_shared = 1, $uplimit_count = -1, $down_limit = 0)
    {
            if(!User::exists($user_id))
                    throw new iMVC\Exceptions\NotFoundException("The user does not exists...");
            if(!is_numeric ( $down_limit) || !is_numeric ( $uplimit_count))
                    throw new InvalidArgumentException("limits should be numeric!");
            
            $f = new $this->target_class();
            
            $cond = array(
                    'conditions' => array("user_id = ? AND parent_id = ? AND is_shared = ? AND is_trashed = 0 AND is_archived <> 1 ORDER BY  `{$this->target_class}_name` ASC".($uplimit_count>$down_limit?" LIMIT $down_limit, $uplimit_count":""), $user_id, $parent_id, $is_shared), 
                    'include'=>array('folder','user')
                );
                    
            $f = $f->all($cond);
            
            if(!count($cond))
                    return NULL;
            
            return $f;
    }
    
    public function UpdateViewedDate($user_id, $item_id)
    {
            $f = self::GetItem($user_id, $item_id);
            
            $f->last_viewed_at = new ActiveRecord\DateTime('now');
            $f->save();
    }
    
    public function UpdateModifiedDate($user_id, $item_id, $recursive = 1)
    {
            $f = self::GetItem($user_id, $item_id);
            
            $f->updated_at = new ActiveRecord\DateTime('now');
            
            $f->save();
            
            if(!$recursive) return $f;
            
            $p = array($f->parent_id);
            
            while(($c = array_shift($p)) != Item::ROOT_PARENT)
            {
                $parent = new Item($this->parent_class);
                $parent = $parent->GetItem($user_id, $c);
                $parent->updated_at = new ActiveRecord\DateTime('now');
                $parent->save();
                $p[] = $parent->parent_id;
            }
            return $f;
    }
    
    public function GetRecentUsed($user_id, $uplimit_count = -1, $down_limit = 0)
    {
            if(!is_numeric ( $down_limit) || !is_numeric ( $uplimit_count))
                    throw new InvalidArgumentException("limits should be numeric!");
            
            $f = new $this->target_class;
            
            $cond = array(
                    'conditions' => array("user_id = ? AND is_trashed = 0 ORDER BY  `last_viewed_at` DESC ".($uplimit_count>$down_limit?" LIMIT $down_limit, $uplimit_count":""),  $user_id), 
                    'include'=>array('folder','user')
                );
            
            $f = $f->  find('all', $cond);
            
            if(!count ( $f))
                    return null;
            
            return $f;
    }
    
    public function GetTrashes($user_id, $uplimit_count = -1, $down_limit = 0)
    {
            if(!is_numeric ( $down_limit) || !is_numeric ( $uplimit_count))
                    throw new InvalidArgumentException("limits should be numeric!");
            
            $f = new $this->target_class;
            
            $cond = array(
                    'conditions' => array("user_id = ? AND is_trashed = 1 ORDER BY  `{$this->name_prefix}_name` ASC ".($uplimit_count>$down_limit?" LIMIT $down_limit, $uplimit_count":""),  $user_id), 
                    'include'=>array('folder','user')
                );
            $f = $f->  find('all', $cond);
            if(!count ( $f))
                    return null;
            return $f;        
    }
    
    public function GetArchives($user_id, $uplimit_count = -1, $down_limit = 0)
    {
            if(!is_numeric ( $down_limit) || !is_numeric ( $uplimit_count))
                    throw new InvalidArgumentException("limits should be numeric!");
            
            $f = new $this->target_class;
            
            $cond = array(
                    'conditions' => array("user_id = ? AND is_archived = 1 AND is_trashed<>1 ORDER BY  `{$this->name_prefix}_name` ASC".($uplimit_count>$down_limit?" LIMIT $down_limit, $uplimit_count":""),  $user_id), 
                    'include'=>array('folder','user')
                );
                    
            $f = $f->  find('all', $cond);
            
            if(!count ( $f))
                    return null;
            
            return $f;        
    }
    
    public function SetArchive($user_id, $item_id, $archive_status)
    {
        if(!is_numeric($archive_status))
            throw new InvalidArgumentException;
        $f = new Item($this->target_class);
        $f  = $f->GetItem($user_id, $item_id);
        $f->is_archived = $archive_status;
        $f->save();
        return $this->UpdateModifiedDate($user_id, $item_id);
    }
    public function GetStars($user_id, $uplimit_count = -1, $down_limit = 0)
    {
            if(!is_numeric ( $down_limit) || !is_numeric ( $uplimit_count))
                    throw new InvalidArgumentException("limits should be numeric!");
            
            $f = new $this->target_class;
            
            $cond = array(
                    'conditions' => array("user_id = ? AND is_starred = 1 AND is_trashed<>1 ORDER BY  `{$this->name_prefix}_name` ASC".($uplimit_count>$down_limit?" LIMIT $down_limit, $uplimit_count":""),  $user_id), 
                    'include'=>array('folder','user')
                );
                    
            $f = $f->  find('all', $cond);
            
            if(!count ( $f))
                    return null;
            
            return $f;        
    }
    
    public function SetStar($user_id, $item_id, $star_status)
    {
        if(!is_numeric($star_status))
            throw new InvalidArgumentException;
        $f = new Item($this->target_class);
        $f  = $f->GetItem($user_id, $item_id);
        $f->is_starred = $star_status;
        $f->save();
        return $this->UpdateModifiedDate($user_id, $item_id);
    }
    public function GetShared($user_id, $uplimit_count = -1, $down_limit = 0)
    {
            if(!is_numeric ( $down_limit) || !is_numeric ( $uplimit_count))
                    throw new InvalidArgumentException("limits should be numeric!");
            
            $f = new $this->target_class;
            
            $cond = array(
                    'conditions' => array("user_id = ? AND is_shared = 1 AND is_trashed<>1 ORDER BY  `{$this->name_prefix}_name` ASC".($uplimit_count>$down_limit?" LIMIT $down_limit, $uplimit_count":""),  $user_id), 
                    'include'=>array('folder','user')
                );
                    
            $f = $f->  find('all', $cond);
            
            if(!count ( $f))
                    return null;
            
            return $f;        
    }
    
    public function MoveItem($user_id, $parent_id, $item_id, $target_folder_id)
    {
        $c = $this->target_class;
        
        if(!Folder::exists($target_folder_id))
            throw new iMVC\Exceptions\NotFoundException("The target {$this->name_prefix} does not exists!");
        
        $f = $c::find(array('conditions'=>array("{$this->name_prefix}_id = ? AND user_id = ? AND parent_id = ?", $item_id, $user_id, $parent_id)));
        
        if(!$f)
            throw new iMVC\Exceptions\NotFoundException("The folder does not exist!");
        
        $f->parent_id = $target_folder_id;
        return $f->save();
    }
}