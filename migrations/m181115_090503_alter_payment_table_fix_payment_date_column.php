<?php

use yii\db\Migration;

/**
 * Class m181115_090503_alter_payment_table_fix_payment_date_column
 */
class m181115_090503_alter_payment_table_fix_payment_date_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('payment','payment_date', $this->date()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181115_090503_alter_payment_table_fix_payment_date_column cannot be reverted.\n";

        return false;
    }
    */
}
