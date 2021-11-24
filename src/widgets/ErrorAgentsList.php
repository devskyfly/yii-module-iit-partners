<?php
namespace devskyfly\yiiModuleIitPartners\widgets;

use devskyfly\php56\types\Vrbl;
use devskyfly\yiiModuleIitPartners\models\Agent;
use yii\base\Widget;
use devskyfly\yiiModuleIitPartners\models\Settlement;
use devskyfly\php56\types\Nmbr;

class ErrorAgentsList extends Widget
{
    public $data=[];
    
    public function init()
    {
        parent::init();
        $agents=Agent::find()->each();
        foreach ($agents as $agent){
            $result=false;
            
            if(($agent['flag_is_public']=='Y')
            ){
                if (!Nmbr::isNumeric($agent['lng'])) {
                    $this->data[]=["obj"=>$agent,"msg"=>"Долгота не числового типа"];
                }

                if (!Nmbr::isNumeric($agent['lat'])) {
                    $this->data[]=["obj"=>$agent,"msg"=>"Широта не числового типа"];
                }
            }
            
            //Проблема с населенным пунктом
            if(Vrbl::isNull($agent['_settlement__id'])
                ||Vrbl::isEmpty($agent['_settlement__id'])){
                    $result=true;
            }

            if($result){
                $this->data[]=["obj"=>$agent,"msg"=>"Нет привязки к городу"];
            }
            
            continue;
;
            $settlement=Settlement::find()->where(['id'=>$agent->_settlement__id])->one();
            if(!$settlement){
                $result=true;
            }
            
            if($result){
                $this->data[]=["obj"=>$agent,"msg"=>"Нет города указанного в привязке"];
            }
        }
    }
    
    public function run()
    {
        $data=$this->data;
        return $this->render('error-agents-list',compact("data"));
    }
}