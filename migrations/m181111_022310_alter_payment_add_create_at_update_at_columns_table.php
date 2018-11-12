<?php

use yii\db\Migration;

/**
 * Class m181111_022310_alter_payment_add_create_at_update_at_columns_table
 */
class m181111_022310_alter_payment_add_create_at_update_at_columns_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('payment', 'created_at', $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'));
        $this->addColumn('payment', 'updated_at', $this->timestamp()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropColumn('payment', 'created_at');
        $this->dropColumn('payment', 'updated_at');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181111_022310_alter_payment_add_create_at_update_at_columns_table cannot be reverted.\n";

        return false;
    }
    */
}
