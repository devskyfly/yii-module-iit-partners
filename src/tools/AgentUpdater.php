<?php
namespace devskyfly\yiiModuleIitPartners\tools;

use devskyfly\php56\types\Vrbl;
use devskyfly\yiiModuleIitPartners\models\Agent;
use devskyfly\yiiModuleIitPartners\models\Region;
use Yii;
use yii\base\BaseObject;
use yii\httpclient\Client;
use yii\helpers\BaseConsole;
use devskyfly\yiiModuleIitPartners\models\Settlement;
use devskyfly\yiiModuleIitPartners\Module;

class AgentUpdater extends BaseObject
{
    /**
     * 
     * @var \yii\base\Module
     */
    protected $module;
    
    /**
     * 
     * @var \yii\httpclient\Client
     */
    protected $client;
    
    /**
     * 
     * @var Status
     */
    protected $status=null;
    
    /**
     * 
     */
    protected $lk_agents=[];
    
    public function init()
    {
        $this->status=new Status();
        $this->module=Module::getInstance();
        
        if (Vrbl::isNull($this->module)) {
            throw new \Exception('Module "iit-partners" does not exist.');
        }
        
        $this->initClient();
        
        $request=$this->createRequest();
        $response=$request->send();
        $this->lk_agents=$response->getData();
    }
    
    public function clear()
    {
        $lk_agents=$this->lk_agents;
        $db=Yii::$app->db;
        $transaction=$db->beginTransaction();
        try{
            $query=Agent::find();
            
            foreach ($query->each(10) as $agent){
                $match=false;
                foreach ($lk_agents as $lk_agent_item){
                    if($lk_agent_item['guid']==$agent->lk_guid){
                        $match=true;
                        break;
                    }
                }
                if(!$match){
                    $this->status->addDeleteItem(['name'=>$agent->name,'guid'=>$agent->lk_guid]);
                    $agent->deleteLikeItem();
                }
            }
            
        }catch(\Exception $e){
            $transaction->rollBack();
            BaseConsole::stdout($e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL);
            
        }catch (\Throwable $e){
            $transaction->rollBack();
            BaseConsole::stdout($e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL);
            
        }
        $transaction->commit();
        return ['status'=>$this->status->getStrInfo()];
    }
    
    public function update()
    {
        $agents=$this->lk_agents;
        $db=Yii::$app->db;
        $transaction=$db->beginTransaction();
        
        try{
            foreach ($agents as $agent_item){
                
                /* if($this->module->upload_public_agents){
                    if($agent_item['point_type']!=8){
                        continue;
                    }
                } */
                
                $agent=Agent::findByGuid($agent_item['guid']);
                
                if(Vrbl::isNull($agent)){
                    $this->addAgent($agent_item);
                }else{
                    $this->updateAgent($agent,$agent_item);
                }
            }
        }catch(\Exception $e){
            $transaction->rollBack();
            BaseConsole::stdout($e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL);
            
        }catch (\Throwable $e){
            $transaction->rollBack();
            BaseConsole::stdout($e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL);
            
        }
        $transaction->commit();
        
        return ['status'=>$this->status->getStrInfo()];
    }
    
    /**
     * 
     * @param [] $data
     * @return bool
     */
    protected function addAgent($data)
    {
        /* if($this->module->upload_public_agents){
            if($data['point_type']!=8){
                return;
            }
        } */
        
        /* if($data['blocked']==true){
            return;
        } */
        
        $error_info=[];
        
        $model=new Agent();
        $model->active=$data['blocked']==1?'N':'Y';
        $model->initCreateAndChangeDateTime();
        $model->name=$data['plain_title'];
        
        $model->manager_in_charge=$data['manager_in_charge'];
        
        $model->custom_address=$data['address'];
        $model->lk_guid=$data['guid'];
        $model->lk_address=$data['address'];
        $model->phone=$data['phone'];
        $model->email=$data['email'];
        
        $model->lng=$data['longitude'];
        $model->lat=$data['latitude'];
        
        $model->comment=$data['comment'];
        $model->open=$data['open_hours'];
        
        $closed_period=$data['closed_period'];
        if(is_array($closed_period)){
            $closed_period='Не работает с '.$closed_period[0].' по '.$closed_period[1];
        }
        $model->close=$closed_period;
           
        $model->flag_is_fast_release = $data['supports_urgent_release']?'Y':'N';
        $model->flag_is_need_to_custom='Y';
        
        if(mb_ereg_match('^[ \s\S]*?инфотекс[ \s\S]*?$',$data['plain_title'],'i')===true){
            $model->flag_is_own='Y';
        }else{
            $model->flag_is_own='N';
        }
        
        $model->flag_is_license=$data['point_licensee_type']==16?'Y':'N';
        $model->flag_is_public=$data['point_type']==8?'Y':'N';
        
        $model->partner_code=$data['partner_code'];
        
        /**********************************************************************/
        /** Region **/
        /**********************************************************************/
        
        $region_id=$data['region'];
        
        if(($region_id<10)
            &&(mb_strlen($region_id)==1)){
            $region_id='0'.$region_id;
        }
        $region_id=''.$region_id;
        
        if(!$region=Region::findByStrNmb($region_id)){
            $error_info[]='Регион неопределен: '.$region_id;
        }
        
        /**********************************************************************/
        /** Settlement **/
        /**********************************************************************/
        
         if($parse_result=Settlement::parseSettlementNameAndType($data['address'])){
             if(!($settlement=Settlement::findByNameAndType($parse_result['name'], $parse_result['type']))){
                $settlement=new Settlement();
                $settlement->initCreateAndChangeDateTime();
                $settlement->enableActive();
                $settlement->name=$parse_result['name'];
                $settlement->type=$parse_result['type'];
                
                if($region){
                    $settlement->_region__id=$region->id;
                }
                
                if(!$settlement->insertLikeItem()){
                    throw new \Exception('Can\'t insert Settlement item. '.PHP_EOL.print_r($settlement->errors,true));
                }
            }
            $model->_settlement__id=$settlement->id;
        }else{
            $error_info[]='Город не разобран: '.$data['address'];
        } 
        
        if($result=$model->saveLikeItem()){
            $this->status->addInsertItem(['name'=>$data['plain_title'],'guid'=>$data['guid']]);
        }else{
            throw new \Exception('Can\'t update item.'.PHP_EOL.print_r($model->errors,true));
        }
        
        if(!Vrbl::isEmpty($error_info)){
            $this->status->addErrorItem([
                'name'=>$data['plain_title'],
                'guid'=>$data['guid'],
                'info'=>$error_info
            ]);
        }
        
    }
    
    /**
     * 
     * @param Agent $model
     * @param [] $data
     */
    protected function updateAgent(Agent $model,$data)
    {
        
        $info=[];
        $error_info=[];
        
        $modifined=false;
        
        $model->change_date_time=(new \DateTime())->format(\DateTime::ATOM);
        
        
        $license=$model->flag_is_license=='Y'?16:8;
        if($license!=$data['point_licensee_type']){
            $model->flag_is_license=$data['point_licensee_type']==16?'Y':'N';
            $info[]=['Изменился тип лицензии.'];
            $modifined=$modifined||true;
        }
        
        $active=$model->active=='Y'?'':'1';
        
        if($data['blocked']!=$active){
            $model->active=$data['blocked']==1?'N':'Y';
            $info[]=['Изменилась активность.'];
            $modifined=$modifined||true;
        }
        
        if($model->name!=$data['plain_title']){
            $model->name=$data['plain_title'];
            $info[]=['Изменилось наименование.'];
            $modifined=$modifined||true;
        }
        
        if($model->manager_in_charge!=$data['manager_in_charge']){
            $model->manager_in_charge=$data['manager_in_charge'];
            $info[]=['Изменился менеджер.'];
            $modifined=$modifined||true;
        }
        
        if($model->lk_address!=$data['address']){
            $model->lk_address=$data['address'];
            $info[]=['Изменился адрес.'];
            $modifined=$modifined||true;
        }
        

        if($model->phone!=$data['phone']){
            $model->phone=$data['phone'];
            $info[]=['Изменился телефон.'];
            $modifined=$modifined||true;
        }
        
        if($model->email!=$data['email']){
            $model->email=$data['email'];
            $info[]=['Изменилась почта.'];
            $modifined=$modifined||true;
        }
        
        if($model->lng!=$data['longitude']){
            $model->lng=$data['longitude'];
            $info[]=['Изменилась долгота.'];
            $modifined=$modifined||true;
        }
        
        if($model->lat!=$data['latitude']){
            $model->lat=$data['latitude'];
            $info[]=['Изменилась широта.'];
            $modifined=$modifined||true;
        }
        
        if($model->comment!=$data['comment']){
            $model->comment=$data['comment'];
            $info[]=['Изменился комментарий.'];
            $modifined=$modifined||true;
        }
        
        if($model->open!=$data['open_hours']){
            $model->open=$data['open_hours'];
            $info[]=['Изменился график работы.'];
            $modifined=$modifined||true;
        }
        
        $closed_period=$data['closed_period'];
        
        if(is_array($closed_period)){
            $closed_period='Не работает с '.$closed_period[0].' по '.$closed_period[1];
        }
        
        if($model->close!=$closed_period){
            $model->close=$closed_period;
        $info[]=['Изменился период закрытия.'];
        $modifined=$modifined||true;
        }
        
        if($model->partner_code!=$data['partner_code']){
            $model->partner_code=$data['partner_code'];
            $info[]=['Изменился партнер код.'];
            $modifined=$modifined||true;
        }
        
        $fast_release=$model->flag_is_fast_release=='Y'?true:false;
        if($fast_release!=$data['supports_urgent_release']){
            $model->flag_is_fast_release=$data['supports_urgent_release']?'Y':'N';
            $info[]=['Изменился признак срочного  выпуска.'];
            $modifined=$modifined||true;
        }
        
        $license=$model->flag_is_license=='Y'?16:8;
        if($license!=$data['point_licensee_type']){
            $model->flag_is_license=$data['point_licensee_type']==16?'Y':'N';
            $info[]=['Изменился тип лицензии.'];
            $modifined=$modifined||true;
        }
        
        $public=$model->flag_is_public=='Y'?8:16;
        if($public!=$data['point_type']){
            $model->flag_is_public=$data['point_type']==8?'Y':'N';
            $info[]=['Изменилась публичность.'];
            $modifined=$modifined||true;
        }
        
        
        /**********************************************************************/
        /** Region **/
        /**********************************************************************/
        
        /* $region_id=$data['region'];
        
        if(($region_id<10)
            &&(mb_strlen($region_id)==1)){
                $region_id='0'.$region_id;
        }
        $region_id=''.$region_id;
        
        
        if($model->_region__id){
            
        }
        
        
        if($region=Region::findByStrNmb($region_id)){
            $model->_region__id=$region->id;
        }else{
            $error_info[]='Регион неопределен: '.$region_id;
        } */
        
        /**********************************************************************/
        /** Settlement **/
        /**********************************************************************/
        
        /* if($parse_result=Settlement::parseSettlementNameAndType($data['address'])){
            if(!($settlement=Settlement::findByNameAndType($parse_result['name'], $parse_result['type']))){
                $settlement=new Settlement();
                $settlement->initCreateAndChangeDateTime();
                $settlement->enableActive();
                $settlement->name=$parse_result['name'];
                $settlement->type=$parse_result['type'];
                if(!$settlement->insertLikeItem()){
                    throw new \Exception('Can\'t insert Settlement item. '.PHP_EOL.print_r($settlement->errors,true));
                }
            }
            $model->_settlement__id=$settlement->id;
        }else{
            $error_info[]='Город не разобран: '.$data['address'];
        } */
        
        if($modifined){
            $model->flag_is_need_to_custom='Y';
            if($result=$model->saveLikeItem()){
                $this->status->addUpdateItem(['name'=>$data['plain_title'],'guid'=>$data['guid'],'info'=>$info]);
            }else{
                throw new \Exception('Can\'t update item.'.PHP_EOL.print_r($model->errors,true));
            }
            
            if(!Vrbl::isEmpty($error_info)){
                $this->status->addErrorItem([
                    'name'=>$data['plain_title'],
                    'guid'=>$data['guid'],
                    'info'=>$error_info]);
            }
        }
    }
    
    protected function createRequest()
    {
        $request=$this->client
        ->createRequest()
        ->setMethod('GET')
        ->setHeaders([
            'Accept' => 'application/json;odata=verbose'
        ])
        ->addHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->module->lk_login . ':' . $this->module->lk_pass)
        ])->setUrl($this->module->lk_url);
        return $request;
    }
    
    protected function initClient()
    {
        $this->client=new Client();
    }
    
    
}