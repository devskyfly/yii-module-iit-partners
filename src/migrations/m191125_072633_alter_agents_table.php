<?php

use devskyfly\yiiModuleIitPartners\models\Agent;
use yii\db\Migration;

/**
 * Class m191125_072633_alter_agents_table
 */
class m191125_072633_alter_agents_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE ".Agent::tableName()." ADD COLUMN flag_exclude_bundle ENUM('Y','N') DEFAULT 'N';";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191125_072633_alter_agents_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191125_072633_alter_agents_table cannot be reverted.\n";

        return false;
    }
    */
}
