<?php
namespace devskyfly\yiiModuleIitPartners\controllers\rest;

use devskyfly\php56\types\Arr;
use devskyfly\php56\types\Vrbl;
use yii\db\ActiveQuery;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;


abstract class CommonController extends Controller
{
    public function behaviors()
    {
        return ArrayHelper::merge([
            [
                'class' => Cors::className(),
            ],
        ], parent::behaviors());
    }
    
    /**
     *
     * @param ActiveQuery $query
     * @return Generator
     */
    public function getItems(ActiveQuery $query)
    {
        foreach ($query->each() as $item){
            yield $item;
        }
    }
    
    /**
     *
     * @param ActiveQuery $query
     * @param [] $fields - where keys are model fields names and values are json fields names
     * @param callable $callback - customize
     * @return []
     */
    public function formData(ActiveQuery $query,$fields,$callback=null){
        $arr=[];
        $keys=[];
        
        foreach ($fields as $key){
            $keys[]=$key;
        }
        
        $generator=$this->getItems($query);
        foreach ($generator as $item){
            $item_arr=$item->toArray();
            $arr_item=array_fill_keys($keys, '');
            foreach ($fields as $key=>$field){
                
                if(Arr::keyExists($item_arr,$key)){
                    $arr_item[$field]=$item[$key];
                }else{
                    new \OutOfBoundsException('There is no such field "'.$field.'" in $item_arr');
                }
            }
            if(Vrbl::isCallable($callback)){
                $arr_item=$callback($item,$arr_item);
            }
            if(!Vrbl::isNull($arr_item)){
                $arr[]=$arr_item;
            }
        }
        return $arr;
    }
}

