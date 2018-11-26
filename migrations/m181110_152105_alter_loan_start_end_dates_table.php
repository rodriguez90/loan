<?php

use yii\db\Migration;

/**
 * Class m181110_152105_alter_loan_start_end_dates_table
 */
class m181110_152105_alter_loan_start_end_dates_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn('loan', 'fee_payment', 'decimal(14,2) not null');
        $this->alterColumn('loan', 'start_date', 'date not null');
        $this->alterColumn('loan', 'end_date', 'date not null');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
//        echo "m181110_152105_alter_loan_start_end_dates_table cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181110_152105_alter_loan_start_end_dates_table cannot be reverted.\n";

        return false;
    }
    */
}
