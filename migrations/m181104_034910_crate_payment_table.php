<?php

use yii\db\Migration;

/**
 * Class m181104_034910_crate_payment_table
 */
class m181104_034910_crate_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('payment', [
            'id' => $this->primaryKey(),
            'loan_id'=>$this->integer()->notNull(),
            'collector_id'=>$this->integer()->notNull(),
            'payment_date'=>$this->string()->notNull(),
            'amount'=>$this->double()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_payment_loan_id',
            'payment',
            'loan_id',
            'loan',
            'id' ,
            'RESTRICT',
            'CASCADE');

        $this->addForeignKey(
            'fk_payment_collector_id',
            'payment',
            'collector_id',
            'user',
            'id' ,
            'RESTRICT',
            'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181104_034910_crate_payment_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181104_034910_crate_payment_table cannot be reverted.\n";

        return false;
    }
    */
}
