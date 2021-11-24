<?php

use yii\db\Migration;
use devskyfly\yiiModuleIitPartners\models\Agent;
use yii\db\Schema;

/**
 * Class m190425_062049_alter_table_agents_add_commetns_etc
 */
class m190425_062049_alter_table_agents_add_commetns_etc extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Agent::tableName(), "comment", Schema::TYPE_TEXT);
        $this->addColumn(Agent::tableName(), "open", Schema::TYPE_TEXT);
        $this->addColumn(Agent::tableName(), "close", Schema::TYPE_TEXT);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190425_062049_alter_table_agents_add_commetns_etc cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190425_062049_alter_table_agents_add_commetns_etc cannot be reverted.\n";

        return false;
    }
    */
}
