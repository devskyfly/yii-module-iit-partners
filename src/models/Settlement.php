<?php
namespace devskyfly\yiiModuleIitPartners\models;

use devskyfly\php56\types\Str;
use devskyfly\php56\types\Vrbl;
use devskyfly\yiiModuleAdminPanel\models\contentPanel\AbstractEntity;
use yii\helpers\ArrayHelper;

/**
 * 
 * @author devskyfly
 * 
 * @property string $type
 * @property number $_region__id
 */
class Settlement extends AbstractEntity
{
    const TYPE_NOT_DEFINED='NOT_DEFINED';
    const TYPE_GOROD='GOROD';
    const TYPE_STANICA='STANICA';
    const TYPE_XUTOR='XUTOR';
    const TYPE_SELO='SELO';
    const TYPE_POS='POS';
    const TYPE_PGT='PGT';
    const TYPE_RBP='RBP';
    
    public static $types=[
        self::TYPE_NOT_DEFINED,
        self::TYPE_GOROD,
        self::TYPE_STANICA,
        self::TYPE_XUTOR,
        self::TYPE_SELO,
        self::TYPE_POS,
        self::TYPE_PGT,
        self::TYPE_RBP,
        
    ];
    
    public static $hash_types=[
        self::TYPE_NOT_DEFINED=>'',
        self::TYPE_GOROD=>'г.',
        self::TYPE_STANICA=>'ст.',
        self::TYPE_XUTOR=>'х.',
        self::TYPE_SELO=>'с.',
        self::TYPE_POS=>'п.',
        self::TYPE_PGT=>'пгт.',
        self::TYPE_RBP=>'р/п'
    ];
    
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
            [['type'],'string'],
            [['_region__id'],'number']
        ];
        
        return ArrayHelper::merge($parent_rules, $new_rules);
    }
    
    public static function selectListRoute()
    {
        return "settlements/entity-select-list";
    }
    
    /**********************************************************************/
    /** Extension **/
    /**********************************************************************/
    
    /**
     * 
     * @param string $name
     * @return Settlement|NULL
     */
    public static function findByNameAndType($name,$type)
    {
        if(!Str::isString($name)){
            throw new \InvalidArgumentException('Param $name is not string type.');
        }
        
        if(!in_array($type, self::$types)){
            throw new \OutOfRangeException('Param $type is not from $types array.');
        }
        
        return self::find()->where(['name'=>$name,'type'=>$type])->one();
    }
    
    /**
     * Parse settlement name and type.
     *
     * @param string $str
     * @return ['name'=>'','type'=>''] | false
     */
    public static function parseSettlementNameAndType($str)
    {
        $name='';
        $type='';
        $matches=[];
        
        if(preg_match('/(г|пос|с|ст|х|пгт|рп|п)\.[ ]*(.*?),/',$str,$matches)){
            $name=$matches[2];
            
            $type='';
            if($matches[1]=='г'){
                $type='GOROD';
            }elseif($matches[1]=='ст'){
                $type='STANICA';
            }
            elseif($matches[1]=='с'){
                $type='SELO';
            }
            elseif($matches[1]=='х'){
                $type='XUTOR';
            }
            elseif($matches[1]=='пос'){
                $type='POS';
            }
            elseif($matches[1]=='п'){
                $type='POS';
            }
            elseif($matches[1]=='рп'){
                $type='POS';
            }
            elseif($matches[1]=='пгт'){
                $type='PGT';
            }else{
                $type='NOT_DEFINED';
            }
        }
        
        if((!Vrbl::isEmpty($name))&&
            (!Vrbl::isEmpty($type))){
                return ['name'=>$name,'type'=>$type];
        }else{
            return false;
        }
    }
    
    /**********************************************************************/
    /** Redeclaration **/
    /**********************************************************************/
    
    public static function tableName()
    {
        return "iit_partners_settlement";
    }
}