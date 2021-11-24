<?php
namespace devskyfly\yiiModuleIitPartners\controllers\rest;

use devskyfly\php56\types\Str;
use devskyfly\php56\types\Vrbl;
use devskyfly\php56\types\Nmbr;
use devskyfly\yiiModuleIitPartners\models\Region;
use devskyfly\yiiModuleIitPartners\models\Settlement;
use devskyfly\yiiModuleIitPartners\components\AgentsManager;
use yii\web\BadRequestHttpException;
use yii\helpers\ArrayHelper;

class AgentsController extends CommonController
{
    public function actionIndex($license = null, $bundle = null, $iit_offices = null)
    {
       if (!in_array($license, ['Y','N',null])) {
            throw new BadRequestHttpException('Query parameter $license is out of range.');
       }

       if (!in_array($bundle, ['Y','N',null])) {
            throw new BadRequestHttpException('Query parameter $bundle is out of range.');
       }

       if (!in_array($iit_offices, ['Y','N',null])) {
            throw new BadRequestHttpException('Query parameter $iit_offices is out of range.');
       }

       $callback = function($item,$arr_item){
           $settlement_id = Nmbr::toInteger($item['_settlement__id']);
           $settlement = Settlement::getById($settlement_id);
           if (Vrbl::isNull($settlement)) {
                return null;
               //throw new \InvalidArgumentException('Parameter $settlment is null.');               
           }
           
           $region_id = $settlement['_region__id'];
           $region = Region::find()
           ->where(['id'=>$region_id])
           ->one();
           
           $settlement = Settlement::find()->where(['id'=>$item['_settlement__id']])->one();
           
           $arr_item['region_id'] = $region->str_nmb;
           $arr_item['settlement_id'] = Nmbr::toIntegerStrict($item['_settlement__id']);
           $arr_item['fast_release'] = $arr_item['fast_release']=='Y'?true:false;
           $arr_item['locality_name'] = Str::toString($settlement->name);
           $arr_item['locality_type'] = Settlement::$hash_types[$settlement['type']];

           if ((!Nmbr::isNumeric($item['lng']))
           ||(!Nmbr::isNumeric($item['lat']))) {
                return null;
           }

           return $arr_item;
       };
       
       $query = AgentsManager::getAll($license,'Y', $bundle, $iit_offices, null, false);
       
       $fields = [
           "name" => "title",
           "lk_guid" =>"guid",
           "flag_is_license" => "license",
           "flag_is_own" => "is_own",
           "flag_is_fast_release" => "fast_release",
           "lng" => "longitude",
           "lat" => "latitude",
           "email" => "email",
           "phone" => "phone",
           "lk_address" => "address",
           "_settlement__id" => "settlement_id",
           "locality_name" => "locality_name",
           "locality_type" => "locality_type",
           "comment" => "comment",
           "open" => "open_hours",
           "close" => "closed_time"
       ];

       $data = $this->formData($query, $fields, $callback);

       /*$data=array_map(function($item){
            $item['settlement_id']=Nmbr::toIntegerStrict($item['settlement_id']);
            return $item;
       },$data);*/

       $this->asJson($data); 
    }
    
    public function actionGetNearest($lng, $lat, $license = null, $bundle = null, $iit_offices = null)
    {
        $resultFormFct = function ($nearest,$del=0)
        {
            $result=[];
            foreach ($nearest as $nearestItm) {
                $item=$nearestItm['link'];

                if (($nearestItm['del'] < $del)
                    || ($del == 0)
                ) {
                    if (empty($item->_settlement__id)) continue;
                    $settlement_id = Nmbr::toIntegerStrict($item->_settlement__id);
                    $settlement = Settlement::getById($settlement_id);
                    if (empty($settlement->_region__id)) continue;
                    $region = Region::find()
                        ->where(['id' => $settlement->_region__id])
                        ->one();
                    $region_id = $region->str_nmb;
                    $result[] = [
                        "title" => $item->name,
                        "guid" => $item->lk_guid,
                        "license" => $item->flag_is_license == 'Y' ? true : false,
                        "fast_release" => $item->flag_is_fast_release == 'Y' ? true : false,
                        "is_own" => $item->flag_is_own,
                        "longitude" => $item->lng,
                        "latitude" => $item->lat,
                        "email" => $item->email,
                        "phone" => $item->phone,
                        "address" => $item->lk_address,
                        "settlement_id" => $settlement_id,
                        "region_id" => $region_id,
                        "del" => $nearestItm['del'],
                        "comment"=>$item->comment,
                        "open_hours" => $item->open,
                        "closed_time" => $item->close
                    ];
                }
            }
            return $result;
        };

        if (!in_array($license, ['Y', 'N', null])) {
            throw new BadRequestHttpException('Query parameter $license is out of range.');
        }

        if (!in_array($bundle, ['Y','N', null])) {
            throw new BadRequestHttpException('Query parameter $license is out of range.');
        }

        if (!in_array($iit_offices, ['Y','N', null])) {
            throw new BadRequestHttpException('Query parameter $license is out of range.');
        }
     
        $result = [];

        $nearest = AgentsManager::getNearest($lng, $lat, $license, $bundle, $iit_offices, null, 'Y', true);
    
        if (Vrbl::isNull($nearest)) {
            throw NotFoundHttpException();
        }

        $result = $resultFormFct($nearest, 6);

        if (empty($result)) {
            $result = $resultFormFct($nearest);
            $result = array_splice($result, 0, 15);
        } else {
            if (count($result) > 10) {
                $result = array_splice($result, 0, 15);
            }
        }

        //$result = static::mvDownNotFastRelease($result);
        $result = static::mvDownClosed($result);
        $result = static::mvUpperByOwn($result, 3);

        $this->asJson($result);
    }
   
    protected function mvUpperByOwn($arr,$del=0)
    {
        if (!is_array($arr)) {
            throw new \InvalidArgumentException('Param $arr is not array type.');
        }

        if (!Nmbr::isNumeric($del)) {
            throw new \InvalidArgumentException('Param $del is not numeric type.');
        }

        $own = [];
        $size = count($arr);
        for ($i = 0; $i < $size; $i++) {
            $itm = $arr[$i];

            if ($del == 0) {
                if ($itm['is_own'] == 'Y') {
                    $own[] = $itm;
                    unset($arr[$i]);
                }
            } else {
                if (($itm['is_own'] == 'Y')
                    && ($itm['del'] <= $del)
                ) {
                    $own[] = $itm;
                    unset($arr[$i]);
                }
            }
        }

        $arr = ArrayHelper::merge($own, $arr);
        $arr = array_values($arr);

        return $arr;
    }

    protected function mvDownClosed($arr)
    {
        if (!is_array($arr)) {
            throw new \InvalidArgumentException('Param $arr is not array type.');
        }

        $partion = [];
        $size = count($arr);
        
        for ($i = 0; $i < $size; $i++) {
            $itm = $arr[$i];
            if (!empty($itm['closed_time'])) {
                $partion[] = $itm;
                unset($arr[$i]);
            }
        }

        $arr = ArrayHelper::merge($arr, $partion);
        $arr = array_values($arr);

        return $arr;
    }

    protected function mvDownNotFastRelease($arr)
    {
        if (!is_array($arr)) {
            throw new \InvalidArgumentException('Param $arr is not array type.');
        }
        
        $partion = [];
        $size = count($arr);
        
        for ($i = 0; $i < $size; $i++) {
            $itm = $arr[$i];
            if (!$itm['fast_release']) {
                $partion[] = $itm;
                unset($arr[$i]);
            }
        }

        $arr = ArrayHelper::merge($arr, $partion);
        $arr = array_values($arr);

        return $arr;
    }
}