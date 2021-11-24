<?php
namespace devskyfly\yiiModuleIitPartners;

use devskyfly\php56\types\Str;
use devskyfly\php56\types\Vrbl;
use Yii;
use yii\filters\AccessControl;

class Module extends \yii\base\Module
{
    const CSS_NAMESPACE='devskyfly-yii-iit-partners';
    const TITLE="Модуль \"Партнеры\"";
    
    /**
     * 
     * @var string
     */
     public $lk_login="";
     /**
      * 
      * @var string
      */
     public $lk_pass="";
     
     /**
      * 
      * @var string
      */
     public $lk_url="";
     
     /**
      * 
      * @var string
      */
     public $lk_org_url="";
     
     public function init()
     {
         parent::init();
         Yii::setAlias("@devskyfly/yiiModuleIitPartners", __DIR__);
         $this->checkProperties();
         if(Yii::$app instanceof \yii\console\Application){
             $this->controllerNamespace='devskyfly\yiiModuleIitPartners\console';
         }
     }
     
     public function behaviors()
     {
         if(!(Yii::$app instanceof \yii\console\Application)){
             
                 return [
                     'access' => [
                         'class' => AccessControl::className(),
                         'except'=>[
                             'rest/*/*',
                         ],
                         'rules' => [
                             [
                                 'allow' => true,
                                 'roles' => ['@'],
                             ],
                         ],
                     ]
                 ];
            
         }else{
             return [];
         }
     }
     
     protected function checkProperties()
     {
         if(Vrbl::isEmpty($this->lk_login)){
             throw new \InvalidArgumentException('Property $lk_login is empty.');
         }
         
         if(!Str::isString($this->lk_login)){
             throw new \InvalidArgumentException('Property $lk_login is is not string type.');
         }
         
         if(Vrbl::isEmpty($this->lk_pass)){
             throw new \InvalidArgumentException('Property $lk_pass is empty.');
         }
         
         if(!Str::isString($this->lk_pass)){
             throw new \InvalidArgumentException('Property $lk_login is is not string type.');
         }
         
         if(Vrbl::isEmpty($this->lk_url)){
             throw new \InvalidArgumentException('Property $lk_url is empty.');
         }
         
         if(Vrbl::isEmpty($this->lk_org_url)){
             throw new \InvalidArgumentException('Property $lk_org_url is empty.');
         }
         
         if(!Str::isString($this->lk_pass)){
             throw new \InvalidArgumentException('Property $lk_url is not string type.');
         }
     }
}