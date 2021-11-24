<?php

use yii\db\Migration;
use yii\db\mssql\Schema;
use devskyfly\yiiModuleIitPartners\models\Agent;

/**
 * Class m190530_105250_alter_agent_table
 */
class m190530_105250_alter_agent_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE ".Agent::tableName()." ADD COLUMN flag_is_fast_release ENUM('Y','N') DEFAULT 'N';";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190530_105250_alter_agent_table cannot be reverted.\n";
        $this->dropColumn(Agent::tableName(), 'flag_is_fast_release');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190530_105250_alter_agent_table cannot be reverted.\n";

        return false;
    }
    */
}
