<?php

use yii\db\Migration;

/**
 * Class m181114_132232_alter_payment_table_status_column
 */
class m181114_132232_alter_payment_table_status_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('payment','status', $this->boolean()->notNull()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('payment', 'status');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181114_132232_alter_payment_table_status_column cannot be reverted.\n";

        return false;
    }
    */
}
