<?php
namespace devskyfly\yiiModuleIitPartners\models;

use devskyfly\php56\types\Str;
use devskyfly\yiiModuleAdminPanel\models\contentPanel\AbstractEntity;
use yii\helpers\ArrayHelper;

/**
 * 
 * @author devskyfly
 * @property string $str_nmb
 */
class Region extends AbstractEntity
{
    /**********************************************************************/
    /** Implementation **/
    /**********************************************************************/
    
    protected static function sectionCls()
    {
        return null;
    }
    
    public function extensions()
    {
        return [];
    }
    
    public function rules()
    {
        $parent_rules=parent::rules();
        $new_rules=[
            [['str_nmb'],'string']
        ];
        
        return ArrayHelper::merge($parent_rules, $new_rules);
    }
    
    public static function selectListRoute()
    {
        return "regions/entity-select-list";
    }
    
    /**********************************************************************/
    /** Extension **/
    /**********************************************************************/
    
    /**
     * 
     * @param string $nmb
     * @return Region|NULL
     */
    public static function findByStrNmb($nmb)
    {
        if(!Str::isString($nmb)){
            throw new \InvalidArgumentException('Param $nmb is not string type.');
        }
        
        if(mb_strlen($nmb)!=2){
            throw new \InvalidArgumentException('Param $nmb is 2 simbole length.');
        }
        
       return static::find()->where(['str_nmb'=>$nmb])->one();
    }
    
    /**********************************************************************/
    /** Redeclaration **/
    /**********************************************************************/
    
    public static function tableName()
    {
        return "iit_partners_region";
    }
}