<?php

use yii\db\Migration;

/**
 * Handles the creation of table `bmt`.
 */
class m170814_041820_create_bmt_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('bmt', [
            'id' => $this->primaryKey(),
            'nama' => $this->string(),
            'alamat' => $this->string(),
            'telp' => $this->string(),
            'logo' => $this->string(),
            'tanggalDaftar' => $this->dateTime(),
            'status' => $this->integer(),
            'dbHost' => $this->string(),
            'dbUsername' => $this->string(),
            'dbPassword' => $this->string(),
            'dbDatabase' => $this->string(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('bmt');
    }
}
