<?php

use devskyfly\yiiModuleAdminPanel\migrations\helpers\contentPanel\EntityMigrationHelper;

class m181102_085516_create_agent_table extends EntityMigrationHelper
{
    public $table="iit_partners_agent";
    
    public function up()
    {
        $fields=$this->getFieldsDefinition();
        
        $fields['info']=$this->text();
        
        $fields['lk_guid']=$this->char(36);
        
        $fields['lng']=$this->char(40);
        $fields['lat']=$this->char(40);
        
        $fields['lk_address']=$this->text();
        $fields['custom_address']=$this->text();
        
        $fields['manager_in_charge']=$this->text();
        
        $fields['_region__id']=$this->integer();
        $fields['_settlement__id']=$this->integer();
        
        $fields['phone']=$this->text();
        $fields['email']=$this->text();
        
        $fields['flag_is_license']="ENUM('Y','N') NOT NULL";
        $fields['flag_is_own']="ENUM('Y','N') NOT NULL";
        $fields['flag_is_public']="ENUM('Y','N') NOT NULL";
        $fields['flag_is_need_to_custom']="ENUM('Y','N') NOT NULL";
        
        $fields['partner_code']=$this->char(200);
        
        $this->createTable($this->table, $fields);
    }

    public function down()
    {
        echo "m181102_085516_create_agents_table cannot be reverted.\n";
        $this->dropTable($this->table);
        //return false;
    }
}
