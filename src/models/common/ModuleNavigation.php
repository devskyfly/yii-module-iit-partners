<?php
namespace devskyfly\yiiModuleIitPartners\models\common;

use devskyfly\yiiModuleAdminPanel\models\common\AbstractModuleNavigation;

class ModuleNavigation extends AbstractModuleNavigation
{
    protected function moduleRoute()
    {
        return "/iit-partners/";
    }

    protected function moduleList()
    {
        return
        [
            ['name'=>'Агенты','route'=>'/iit-partners/agents'],
            ['name'=>'Регионы','route'=>'/iit-partners/regions'],
            ['name'=>'Населенные пункты','route'=>'/iit-partners/settlements']
        ];
    }

    protected function moduleName()
    {
        return 'iit-partners';
    }

}