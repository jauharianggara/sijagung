<?php

use yii\db\Migration;

/**
 * Handles the creation of table `userLog`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m170818_072919_create_userLog_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('userLog', [
            'id' => $this->primaryKey(),
            'tanggal' => $this->dateTime(),
            'userId' => $this->integer(),
            'url' => $this->string(),
            'parameter' => $this->string(),
            'keterangan' => $this->string(),
        ]);

        // creates index for column `userId`
        $this->createIndex(
            'idx-userLog-userId',
            'userLog',
            'userId'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-userLog-userId',
            'userLog',
            'userId',
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
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-userLog-userId',
            'userLog'
        );

        // drops index for column `userId`
        $this->dropIndex(
            'idx-userLog-userId',
            'userLog'
        );

        $this->dropTable('userLog');
    }
}
