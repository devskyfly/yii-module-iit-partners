<?php

use yii\db\Migration;

/**
 * Class m190306_125423_alter_agent_table
 */
class m190306_125423_alter_agent_table extends Migration
{
    public $table="iit_partners_agent";
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn($this->table, '_region__id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190306_125423_alter_agent_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190306_125423_alter_agent_table cannot be reverted.\n";

        return false;
    }
    */
}
