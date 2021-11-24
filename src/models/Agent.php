<?php
namespace devskyfly\yiiModuleIitPartners\models;

use devskyfly\yiiModuleAdminPanel\models\contentPanel\AbstractEntity;
use yii\helpers\ArrayHelper;

/**
 * 
 * @author devskyfly
 *
 * @property string $info
 * @property string $lk_guid
 * 
 * @property string $lng
 * @property string $lat
 * 
 * @property string $lk_address
 * @property string $custom_address
 * @property string $phone
 * @property string $email
 * 
 * @property string $flag_is_license
 * @property string $flag_is_own
 * @property string $flag_is_public
 * @property string $flag_is_need_to_custom
 * @property string $flag_exclude_bundle
 * 
 * @property string $manager_in_charge
 * @property string $_settlement__id
 * @property string $partner_code
 * 
 * @property string $open
 * @property string $close
 * @property string $comment
 */
class Agent extends AbstractEntity
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
            [['info','lk_guid'],'string'],
            [['comment','open','close'],'string'],
            [['lng','lat'],'string'],
            [['lk_address','custom_address'],'string'],
            [['manager_in_charge'],'string'],
            [['phone','email'],'string'],
            [['_settlement__id'],'integer'],
            [['flag_is_license',
            'flag_is_own',
            'flag_is_public',
            'flag_is_need_to_custom',
            'flag_exclude_bundle'],'string'],
            [['partner_code'],'string']
        ];
        
        return ArrayHelper::merge($parent_rules, $new_rules);
    }
    
    /**********************************************************************/
    /** Extension **/
    /**********************************************************************/
    
    /**
     * Returm agent record by guid
     *
     * @param string $guid
     *
     * @return AbstractEntity | null
     */
    public static function findByGuid($guid)
    {
        return static::find()->where(['lk_guid'=>$guid])->one();
    }
    
    /**********************************************************************/
    /** Queries **/
    /**********************************************************************/
    
    public function queryAllActivePublic()
    {
        return static::find()->andWhere(['active'=>'Y','public'=>Y]);
    }
    
    /**********************************************************************/
    /** Redeclaration **/
    /**********************************************************************/
    
    public static function tableName()
    {
        return "iit_partners_agent";
    }
}