<?php

use yii\db\Migration;

/**
 * Class m181104_034825_crate_customer_table
 */
class m181104_034825_crate_customer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('customer', [
            'id' => $this->primaryKey(),
            'first_name'=>$this->string()->notNull(),
            'last_name'=>$this->string()->notNull(),
            'dni'=>$this->string()->notNull(),
            'email'=>$this->string()->notNull(),
            'phone_number'=>$this->string()->notNull(),
            'location'=>$this->string()->notNull(),
            'address'=>$this->string()->notNull(),
            'created_at'=>$this->timestamp()->notNull(),
            'updated_at'=>$this->timestamp()->notNull(),
            'created_by'=>$this->integer(),
        ]);

        $this->addForeignKey(
            'fk_customer_created_by',
            'customer',
            'created_by',
            'user',
            'id' ,
            'SET NULL',
            'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181104_034825_crate_customer_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181104_034825_crate_customer_table cannot be reverted.\n";

        return false;
    }
    */
}
