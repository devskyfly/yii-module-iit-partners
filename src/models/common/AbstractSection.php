<?php
namespace devskyfly\yiiModuleIitPartners\models\common;


use devskyfly\yiiModuleAdminPanel\models\contentPanel\AbstractSection as Section;

abstract class AbstractSection extends Section
{
    public static function tableName()
    {
        return 'iit_partners_section';
    }
}