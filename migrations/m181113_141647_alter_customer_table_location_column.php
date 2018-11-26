<?php

use yii\db\Migration;

/**
 * Class m181113_141647_alter_customer_table_location_column
 */
class m181113_141647_alter_customer_table_location_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('customer','location', $this->text()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('customer','location', $this->string()->notNull());
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181113_141647_alter_customer_table_location_column cannot be reverted.\n";

        return false;
    }
    */
}
