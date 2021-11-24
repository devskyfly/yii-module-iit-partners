<?php
namespace devskyfly\yiiModuleIitPartners\tools;

use yii\base\BaseObject;

class Status extends BaseObject
{
    const INSERT='insert';
    const DELETE='delete';
    const UPDATE='update';
    const ERROR='error';
    
    public $info=[];

    public function init()
    {
        parent::init();
        
        $this->info[self::INSERT]=[];
        $this->info[self::DELETE]=[];
        $this->info[self::UPDATE]=[];
        $this->info[self::ERROR]=[];
    }
    
    protected $category=[
        'insert',
        'delete',
        'update',
        'error'
    ];
    
    public function addInsertItem($info)
    {
        $this->addItem(self::INSERT, $info);
    }
    
    public function addDeleteItem($info)
    {
        $this->addItem(self::DELETE, $info);
    }
    
    public function addUpdateItem($info)
    {
        $this->addItem(self::UPDATE, $info);
    }
    
    public function addErrorItem($info)
    {
        $this->addItem(self::ERROR, $info);
    }
    
    /**
     * 
     * @param string $category
     * @param mixed $item
     * @throws \OutOfRangeException
     */
    protected function addItem($category,$item){
        if(!in_array($category, $this->category)){
            throw new \OutOfRangeException('Param $category is not from category list');
        }
        $this->info[$category][]=$item;
    }
    
    /**
     * 
     * @return array
     */
    public function getInfo()
    {
        return $this->info;
    }
    
    /**
     * 
     * @return array
     */
    public function getStrInfo()
    {
        return print_r($this->info,true);
    }
}