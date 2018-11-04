<?php

use yii\db\Migration;

/**
 * Class m181104_034851_crate_loan_table
 */
class m181104_034851_crate_loan_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('loan', [
            'id' => $this->primaryKey(),
            'customer_id'=>$this->integer()->notNull(),
            'banker_id'=>$this->integer()->notNull(),
            'amount'=>$this->double()->notNull(),
            'porcent_interest'=>$this->double()->notNull(),
            'status'=>$this->tinyInteger()->notNull(),
            'refinancing_id'=>$this->integer(),
            'frequency_payment'=>$this->integer()->notNull(),
            'start_date'=>$this->dateTime()->notNull(),
            'end_date'=>$this->dateTime()->notNull(),
            'created_at'=>$this->timestamp()->notNull(),
            'updated_at'=>$this->timestamp()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_loan_customer_id',
            'loan',
            'customer_id',
            'customer',
            'id' ,
            'RESTRICT',
            'CASCADE');

        $this->addForeignKey(
            'fk_loan_banker_id',
            'loan',
            'banker_id',
            'user',
            'id' ,
            'RESTRICT',
            'CASCADE');

        $this->addForeignKey(
            'fk_loan_refinancing_id',
            'loan',
            'refinancing_id',
            'loan',
            'id' ,
            'SET NULL',
            'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181104_034851_crate_loan_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181104_034851_crate_loan_table cannot be reverted.\n";

        return false;
    }
    */
}
