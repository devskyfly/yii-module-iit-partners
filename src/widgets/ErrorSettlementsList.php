<?php
namespace devskyfly\yiiModuleIitPartners\widgets;

use yii\base\Widget;
use devskyfly\php56\types\Vrbl;
use devskyfly\yiiModuleIitPartners\models\Region;
use devskyfly\yiiModuleIitPartners\models\Settlement;

class ErrorSettlementsList extends Widget
{
    public $data=[];
    
    public function init()
    {
        parent::init();
        $settlements=Settlement::find()->each();
        foreach ($settlements as $settlement){
            $result=false;
            
            if(Vrbl::isNull($settlement['_region__id'])
                ||Vrbl::isEmpty($settlement['_region__id'])){
                    $result=true;
            }
            
            $region=Region::find()->where(['id'=>$settlement->_region__id])->one();
            if(!$region){
                $result=true;
            }
            
            if($result){
                $this->data[]=$settlement;
            }
        }
    }
    
    public function run()
    {
        $data=$this->data;
        return $this->render('error-settlements-list',compact("data"));
    }
}