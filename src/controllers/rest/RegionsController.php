<?php
namespace devskyfly\yiiModuleIitPartners\controllers\rest;

use yii\web\BadRequestHttpException;
use yii\helpers\ArrayHelper;
use devskyfly\yiiModuleIitPartners\models\Agent;
use devskyfly\yiiModuleIitPartners\components\RegionsManager;

class RegionsController extends CommonController
{
    public function actionIndex($license = null, $bundle = null, $iit_offices = null)
    {
        $data=[];
        $head=[];
        
        if(!in_array($license, ['Y', 'N', null])){
            throw new BadRequestHttpException('Query parameter $license is out of range.');
        }

        if (!in_array($bundle, ['Y', 'N', null])) {
            throw new BadRequestHttpException('Query parameter $bundle is out of range.');
        }

        if (!in_array($iit_offices, ['Y', 'N', null])) {
            throw new BadRequestHttpException('Query parameter $iit_offices is out of range.');
        }
        
        $query = RegionsManager::getAll($license, 'Y', $bundle, $iit_offices,  null, false);
        
        $fields=[
            "id"=>"id",
            "name"=>"name",
            "str_nmb"=>"code"
        ];
        
        $data=$this->formData($query, $fields);
        
        $head=[];
        $head_codes=[77,78];
        
        foreach($data as $key=>$item){
            if(in_array($item['code'], $head_codes)){
                $head[]=$item;
                unset($data[$key]);
            }
        }
        
        $data = ArrayHelper::merge($head, $data);
        $this->asJson($data);
    }
}

