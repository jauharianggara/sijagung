<?php

use yii\db\Migration;

/**
 * Class m170829_230131_alter_id_column_from_bmt_table
 */
class m170829_230131_alter_id_column_from_bmt_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
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
		
		$this->alterColumn('bmt', 'id', $this->string());
		$this->alterColumn('petugas', 'bmtId', $this->string()->notNull());
		$this->alterColumn('setoranMobile', 'bmtId', $this->string()->notNull());
		
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
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
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
		
		$this->alterColumn('bmt', 'id', $this->primaryKey());
		$this->alterColumn('petugas', 'bmtId', $this->integer()->notNull());
		$this->alterColumn('setoranMobile', 'bmtId', $this->integer()->notNull());
		
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
		
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170829_230131_alter_id_column_from_bmt_table cannot be reverted.\n";

        return false;
    }
    */
}
