<?php

use yii\db\Migration;

/**
 * Handles adding username_column_password to table `bmt`.
 */
class m170829_103658_add_username_column_password_column_to_bmt_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('bmt', 'username', $this->string());
        $this->addColumn('bmt', 'password', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('bmt', 'username');
        $this->dropColumn('bmt', 'password');
    }
}
