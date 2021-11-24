<?php
namespace devskyfly\yiiModuleIitPartners\models\common;

use devskyfly\yiiModuleAdminPanel\models\contentPanel\AbstractBinder as Binder;

abstract class AbstractBinder extends Binder
{
    public static function tableName()
    {
        return 'iit_partners_binder';
    }
}