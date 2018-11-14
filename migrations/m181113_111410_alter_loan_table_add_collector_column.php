<?php

use yii\db\Migration;

/**
 * Class m181113_111410_alter_loan_table_add_collector_column
 */
class m181113_111410_alter_loan_table_add_collector_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('loan','collector_id', $this->integer());
        $this->addForeignKey(
            'fk_loan_collector_id',
            'loan',
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
        $this->dropForeignKey('fk_loan_collector_id','loan');
        $this->dropColumn('loan', 'collector_id');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181113_111410_alter_loan_table_add_collector_column cannot be reverted.\n";

        return false;
    }
    */
}
