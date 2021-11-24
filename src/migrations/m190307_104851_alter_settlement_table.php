<?php

use yii\db\Migration;

/**
 * Class m190307_104851_alter_settlement_table
 */
class m190307_104851_alter_settlement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql="ALTER TABLE iit_partners_settlement MODIFY COLUMN type ENUM('NOT_DEFINED','GOROD','STANICA','SELO','XUTOR','POS','PGT','RBP') NOT NULL;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190307_104851_alter_settlement_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190307_104851_alter_settlement_table cannot be reverted.\n";

        return false;
    }
    */
}
