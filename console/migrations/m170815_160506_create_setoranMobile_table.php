<?php

use yii\db\Migration;

/**
 * Handles the creation of table `setoranMobile`.
 * Has foreign keys to the tables:
 *
 * - `bmt`
 * - `user`
 * - `user`
 */
class m170815_160506_create_setoranMobile_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('setoranMobile', [
            'id' => $this->string()->unique(),
            'tanggal' => $this->dateTime(),
            'namaNasabah' => $this->string(),
            'nomorRekening' => $this->string(),
            'nominal' => $this->integer(),
            'bmtId' => $this->integer()->notNull(),
            'userId' => $this->integer()->notNull(),
            'batch' => $this->string(),
            'imei' => $this->string(),
			'version' => $this->string(),
            'dateAdd' => $this->dateTime(),
            'statusAdd' => $this->integer(),
			'dateUpload' => $this->dateTime(),
            'statusUpload' => $this->integer(),
            'dateEdit' => $this->dateTime(),
            'statusEdit' => $this->integer(),
            'dateDeleteRequest' => $this->dateTime(),
            'statusRequestDelete' => $this->integer(),
            'userAuthDelete' => $this->integer(),
            'dateAccDelete' => $this->dateTime(),
            'deleteDate' => $this->dateTime(),
            'deleteStatus' => $this->integer(),
            'deletePassword' => $this->string(),
			'PRIMARY KEY(id)',
        ]);

        // creates index for column `bmtId`
        $this->createIndex(
            'idx-setoranMobile-bmtId',
            'setoranMobile',
            'bmtId'
        );

        // add foreign key for table `bmt`
        $this->addForeignKey(
            'fk-setoranMobile-bmtId',
            'setoranMobile',
            'bmtId',
            'bmt',
            'id',
            'CASCADE'
        );

        // creates index for column `userId`
        $this->createIndex(
            'idx-setoranMobile-userId',
            'setoranMobile',
            'userId'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-setoranMobile-userId',
            'setoranMobile',
            'userId',
            'user',
            'id',
            'CASCADE'
        );

        // creates index for column `userAuthDelete`
        $this->createIndex(
            'idx-setoranMobile-userAuthDelete',
            'setoranMobile',
            'userAuthDelete'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-setoranMobile-userAuthDelete',
            'setoranMobile',
            'userAuthDelete',
            'user',
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
            'fk-setoranMobile-bmtId',
            'setoranMobile'
        );

        // drops index for column `bmtId`
        $this->dropIndex(
            'idx-setoranMobile-bmtId',
            'setoranMobile'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-setoranMobile-userId',
            'setoranMobile'
        );

        // drops index for column `userId`
        $this->dropIndex(
            'idx-setoranMobile-userId',
            'setoranMobile'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-setoranMobile-userAuthDelete',
            'setoranMobile'
        );

        // drops index for column `userAuthDelete`
        $this->dropIndex(
            'idx-setoranMobile-userAuthDelete',
            'setoranMobile'
        );

        $this->dropTable('setoranMobile');
    }
}
