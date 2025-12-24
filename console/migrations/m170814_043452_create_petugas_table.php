<?php

use yii\db\Migration;

/**
 * Handles the creation of table `petugas`.
 * Has foreign keys to the tables:
 *
 * - `bmt`
 */
class m170814_043452_create_petugas_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('petugas', [
            'id' => $this->primaryKey(),
            'username' => $this->string(),
            'password' => $this->string(),
            'nama' => $this->string(),
            'imei' => $this->string(),
            'batch' => $this->string(),
            'kodeAo' => $this->string(),
            'bmtId' => $this->integer()->notNull(),
            'tanggalDaftar' => $this->dateTime(),
            'tanggalUbah' => $this->dateTime(),
            'status' => $this->integer(),
        ]);

        // creates index for column `bmtId`
        $this->createIndex(
            'idx-petugas-bmtId',
            'petugas',
            'bmtId'
        );

        // add foreign key for table `bmt`
        $this->addForeignKey(
            'fk-petugas-bmtId',
            'petugas',
            'bmtId',
            'bmt',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `bmt`
        $this->dropForeignKey(
            'fk-petugas-bmtId',
            'petugas'
        );

        // drops index for column `bmtId`
        $this->dropIndex(
            'idx-petugas-bmtId',
            'petugas'
        );

        $this->dropTable('petugas');
    }
}
