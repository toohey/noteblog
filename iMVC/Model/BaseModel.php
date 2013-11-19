<?php
namespace iMVC\Model;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModelBase
 *
 * @author dariush
 */
require_once 'BaseMVC.php';
class BaseModel extends \iMVC\BaseMVC
{
    public function Initiate() {}
    
    /**
     * holds loaded models's hash tables
     * @var array
     */
    public static $load_table;
    
    public static function LoadModel($model_name, $module_name = NULL)
    {
        $mb = new BaseModel;
        
        if(!isset($model_name))
            throw new InvalidArgumentException("\$model_name is not setted!");
        
        if(!isset($module_name))
            $module_name = $mb->GetRequest()->module;
        
        $model_name = str_replace(".php", "", $model_name);
        $model_name = str_replace("Model", "", $model_name);
        $model_name = "{$model_name}Model";
        
        if(self::IsModelLoaded($model_name, $module_name))
        {
            return;
        }
        $mp = MODULE_PATH."/{$module_name}/Models/$model_name.php";
        
        if(!file_exists($mp))
            throw new \iMVC\Exceptions\NotFoundException("Model '{$model_name}.php' not found at '/{$module_name}/Models/'");
        
        require_once $mp;
        
        if(!class_exists("{$model_name}"))
            throw new ErrorException("Model '{$model_name}.php' found but class '{$model_name}' does not exists.");
            
        self::MarkAsLoaded($model_name, $module_name);
    }
    
    public static function LoadGlobalModel($model_name, $module_name = "__GLOBAL")
    {
        if(!\iMVC\Tools\String::startsWith($module_name, '__'))
                throw new InvalidArgumentException("Global module names should 
                    start with '__' indicator, but provided module name 
                    '$module_name' does not have such signature!");
        
        self::LoadModel($model_name, $module_name);
    }
    
    public static function IsGlobalModelLoaded($model_name, $module_name = "__GLOBAL")
    {
        if(!\iMVC\Tools\String::startsWith($module_name, '__'))
                throw new InvalidArgumentException("Global module names should 
                    start with '__' indicator, but provided module name 
                    '$module_name' does not have such signature!");
        return self::IsModelLoaded($model_name, $module_name);
    }
    public static function IsModelLoaded($model_name, $module_name = NULL)
    {
        $mb = new BaseModel;
        
        $model_name = str_replace(".php", "", $model_name);
        $model_name = str_replace("Model", "", $model_name);
        $model_name = "{$model_name}Model";
        
        if(!isset($module_name))
            $module_name = $mb->GetRequest()->module;
        
        return isset(self::$load_table[$module_name."::".$model_name]);
    }
    
    public static function MarkAsLoaded($model_name, $module_name)
    {
        $model_name = str_replace(".php", "", $model_name);
        $model_name = str_replace("Model", "", $model_name);
        $model_name = "{$model_name}Model";
        self::$load_table[$module_name."::".$model_name] = "LOADED";
    }
}

?>
