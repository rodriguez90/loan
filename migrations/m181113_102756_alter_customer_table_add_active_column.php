<?php

use yii\db\Migration;

/**
 * Class m181113_102756_alter_customer_table_add_active_column
 */
class m181113_102756_alter_customer_table_add_active_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('customer','active', $this->boolean()->defaultValue(true));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('customer', 'active');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181113_102756_alter_customer_table_add_active_column cannot be reverted.\n";

        return false;
    }
    */
}
