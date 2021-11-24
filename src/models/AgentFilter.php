<?php
namespace devskyfly\yiiModuleIitPartners\models;

use devskyfly\yiiModuleAdminPanel\models\contentPanel\FilterInterface;
use devskyfly\yiiModuleAdminPanel\models\contentPanel\FilterTrait;

class AgentFilter extends Agent implements FilterInterface
{
    use FilterTrait;
    
    public function rules()
    {
        return [[["active", "name", "create_date_time", "change_date_time"],"string"]];
    }
}