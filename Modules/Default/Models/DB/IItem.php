<?php

interface IItem
{
    public function GetItem($user_id, $item_id);
    public function GetRoots($user_id, $uplimit_count = -1, $down_limit = 0);
    public function GetByParentId($user_id, $parent_id, $uplimit_count = -1, $down_limit = 0);
    public function GetByName($user_id, $parent_id, $name);
    public function ItemExists($user_id, $parent_id, $item_name);
    public function GetChildren($user_id, $parent_id, $uplimit_count = -1, $down_limit = 0);
    public function Remove($user_id, $item_id, $put_trash = 1);
    public function Rename($user_id, $item_id, $new_name);
    public function GetRouteMap($user_id, $item_id);
    public function GetCount($user_id, $parent_id);
    public function GetWith_SharedStatus_Children($user_id, $parent_id, $is_shared = 1, $uplimit_count = -1, $down_limit = 0);
    public function UpdateViewedDate($user_id, $item_id);
    public function GetRecentUsed($user_id, $uplimit_count = -1, $down_limit = 0);
    public function GetTrashes($user_id, $uplimit_count = -1, $down_limit = 0);
    public function GetArchives($user_id, $uplimit_count = -1, $down_limit = 0);
    public function GetShared($user_id, $uplimit_count = -1, $down_limit = 0);
    public function SetArchive($user_id, $item_id, $archive_status);
    public function Restore($user_id, $item_id);
    public function SetStar($user_id, $item_id, $star_status);
    public function GetStars($user_id, $uplimit_count = -1, $down_limit = 0);
}