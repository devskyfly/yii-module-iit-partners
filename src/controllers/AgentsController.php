<?php
namespace devskyfly\yiiModuleIitPartners\controllers;

use devskyfly\yiiModuleAdminPanel\controllers\contentPanel\AbstractContentPanelController;
use devskyfly\yiiModuleAdminPanel\widgets\contentPanel\ItemSelector;

use devskyfly\yiiModuleIitPartners\models\Agent;
use devskyfly\yiiModuleIitPartners\models\AgentFilter;
use devskyfly\yiiModuleIitPartners\models\Region;
use devskyfly\yiiModuleIitPartners\models\Settlement;
use devskyfly\yiiModuleIitPartners\tools\AgentUpdater;

class AgentsController extends AbstractContentPanelController
{
    /**
     *
     * {@inheritDoc}
     * @see \devskyfly\yiiModuleAdminPanel\controllers\AbstractContentPanelController::sectionItem()
     */
    public static function sectionCls()
    {
        //Если иерархичность не требуется, то вместо названия класса можно передать null
        return null;
    }
    
    /**
     *
     * {@inheritDoc}
     * @see \devskyfly\yiiModuleAdminPanel\controllers\AbstractContentPanelController::entityItem()
     */
    public static function entityCls()
    {
        return Agent::class;
    }
    
    public static function entityFilterCls()
    {
        return AgentFilter::class;
    }
    
    /**
     *
     * {@inheritDoc}
     * @see \devskyfly\yiiModuleAdminPanel\controllers\AbstractContentPanelController::entityEditorViews()
     */
    public function entityEditorViews()
    {
        return function($form,$item)
        {
            return [
                [
                    "label"=>"main",
                    "content"=>
                    $form->field($item,'name')
                     .ItemSelector::widget([
                        "form"=>$form,
                        "master_item"=>$item,
                        "slave_item_cls"=>Settlement::class,
                        "property"=>"_settlement__id"
                    ])
                    .$form->field($item,'create_date_time')
                    .$form->field($item,'change_date_time')
                    .$form->field($item,'active')
                    ->checkbox(['value'=>'Y','uncheck'=>'N','checked'=>$item->active=='Y'?true:false])
                    .$form->field($item,'partner_code')
                    .$form->field($item,'flag_is_license')
                    ->checkbox(['value'=>'Y','uncheck'=>'N','checked'=>$item->flag_is_license=='Y'?true:false])
                    .$form->field($item,'flag_is_own')
                    ->checkbox(['value'=>'Y','uncheck'=>'N','checked'=>$item->flag_is_own=='Y'?true:false])
                    .$form->field($item,'flag_is_public')
                    ->checkbox(['value'=>'Y','uncheck'=>'N','checked'=>$item->flag_is_public=='Y'?true:false])
                    .$form->field($item,'flag_is_need_to_custom')
                    ->checkbox(['value'=>'Y','uncheck'=>'N','checked'=>$item->flag_is_need_to_custom=='Y'?true:false])
                    .$form->field($item,'flag_exclude_bundle')
                    ->checkbox(['value'=>'Y','uncheck'=>'N','checked'=>$item->flag_exclude_bundle=='Y'?true:false])
                    .$form->field($item,'flag_is_fast_release')
                    ->checkbox(['value'=>'Y','uncheck'=>'N','checked'=>$item->flag_is_fast_release=='Y'?true:false])
                    .$form->field($item,'custom_address')
                    .$form->field($item,'lk_address')
                    .$form->field($item,'phone')
                    .$form->field($item,'email')
                    .$form->field($item,'lat')
                    .$form->field($item,'lng')
                    .$form->field($item,'manager_in_charge')
                    .$form->field($item,'info')->textarea(['rows'=>5])
                    .$form->field($item,'comment')->textarea(['rows'=>5])
                    .$form->field($item,'open')->textarea(['rows'=>5])
                    .$form->field($item,'close')->textarea(['rows'=>5])
                    
                    
            ],
                
            ];
        };
    }
    
    /**
     *
     * {@inheritDoc}
     * @see \devskyfly\yiiModuleAdminPanel\controllers\AbstractContentPanelController::sectionEditorItems()
     */
    public function sectionEditorViews()
    {
        return null;
    }
    
    /**
     *
     * {@inheritDoc}
     * @see \devskyfly\yiiModuleAdminPanel\controllers\AbstractContentPanelController::itemLabel()
     */
    public function itemLabel()
    {
        return "Агенты";
    }
}