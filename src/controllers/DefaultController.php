<?php
namespace devskyfly\yiiModuleIitPartners\controllers;

use devskyfly\yiiModuleIitPartners\Module;
use devskyfly\yiiModuleIitPartners\models\common\ModuleNavigation;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $title=Module::TITLE;
        $module_navigation=new ModuleNavigation();
        $list=[$module_navigation->getData()];
        return $this->render('index',compact("list","title"));
    }
}