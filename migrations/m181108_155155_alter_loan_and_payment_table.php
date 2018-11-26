<?php

use yii\db\Migration;

/**
 * Class m181108_155155_alter_loan_and_payment_table
 */
class m181108_155155_alter_loan_and_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('loan','amount', 'decimal(14,2) not null');
        $this->addColumn('loan', 'fee_payment', 'decimal(14,2) not null');
        $this->alterColumn('payment', 'amount', 'decimal(14,2) not null');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('payment', 'amount', 'double');
        $this->alterColumn('loan', 'amount', 'double');
        $this->dropColumn('loan','fee_payment');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181108_155155_alter_loan_and_payment_table cannot be reverted.\n";

        return false;
    }
    */
}
