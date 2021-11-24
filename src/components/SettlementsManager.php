<?php
namespace devskyfly\yiiModuleIitPartners\components;

use yii\base\BaseObject;
use devskyfly\php56\types\Arr;
use devskyfly\php56\types\Obj;
use devskyfly\yiiModuleIitPartners\models\Agent;
use devskyfly\yiiModuleIitPartners\models\Region;
use devskyfly\yiiModuleIitPartners\models\Settlement;

class SettlementsManager extends BaseObject
{
    public static function getAll($license = null, $public = null, $bundle = null, $iit_offices = null, $flag_is_need_to_custom = null, $get_query_result=true)
    {
        if (!in_array($license, ['Y','N', null])){
            throw new \InvalidArgumentException('Parameter $license is out of range.');
        }
        
        if (!in_array($public, ['Y','N', null])){
            throw new \InvalidArgumentException('Parameter $public is out of range.');
        }
        
        if (!in_array($flag_is_need_to_custom, ['Y', 'N', null])){
            throw new \InvalidArgumentException('Parameter $flag_is_need_to_custom is out of range.');
        }

        if (!in_array($bundle, ['Y', 'N', null])) {
            throw new BadRequestHttpException('Query parameter $bundle is out of range.');
        }

        if (!in_array($iit_offices, ['Y', 'N', null])) {
            throw new BadRequestHttpException('Query parameter $iit_offices is out of range.');
        }
        
        $query = AgentsManager::getAll($license, $public, $bundle, $iit_offices, $flag_is_need_to_custom, false);
        $agents = $query->asArray()->all();
        
        $settlements_ids = Arr::getColumn($agents, '_settlement__id');
        $settlements_ids = array_unique($settlements_ids);
        
        $query = Settlement::find()
        ->where(['active'=>'Y','id'=>$settlements_ids])
        ->orderBy(['name'=>SORT_ASC]);
        
        if ($get_query_result) {
            return $query->all();
        } else {
            return $query;
        }
    }
    
    /**
     *
     * @param string $str_nmb
     * @throws \InvalidArgumentException
     * @return \devskyfly\yiiModuleIitPartners\models\Region
     */
   /*  public static function getByRegion($region)
    {
        if(Obj::isA($region,Region::class)){
            throw new \InvalidArgumentException('Parameter $region is not string type.');
        }
        return Settlement::find()->where(['active'=>'Y','str_nmb'=>$str_nmb])->one();
    } */
}