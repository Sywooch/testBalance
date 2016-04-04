<?php

use yii\db\Schema;
use yii\db\Migration;

class m160402_134807_db extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => Schema::TYPE_PK,
            'nickname' => Schema::TYPE_STRING . ' NOT NULL DEFAULT ""',
            'balance' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'auth_key' => 'VARCHAR(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT ""'
        ], $tableOptions);

        $this->createTable('{{%balance}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'amount' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'type' => 'ENUM("income", "outlay") NOT NULL',
            'operation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ], $tableOptions);

        $this->addForeignKey('fk_balance_user', '{{%balance}}', 'user_id', '{{%user}}', 'id');

        $this->createTable('{{%bill}}', [
            'id' => Schema::TYPE_PK,
            'user_id_from' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'user_id_to' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'amount' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'status' => 'ENUM("new", "paid", "canceled") NOT NULL DEFAULT "new"',
            'operation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ], $tableOptions);

        $this->addForeignKey('fk_bill_user_from', '{{%bill}}', 'user_id_from', '{{%user}}', 'id');
        $this->addForeignKey('fk_bill_user_to', '{{%bill}}', 'user_id_to', '{{%user}}', 'id');
    }

    public function down()
    {
        $this->dropForeignKey('fk_balance_user', '{{%balance}}');
        $this->dropForeignKey('fk_bill_user_from', '{{%bill}}');
        $this->dropForeignKey('fk_bill_user_to', '{{%bill}}');

        $this->dropTable('{{%user}}');
        $this->dropTable('{{%balance}}');
        $this->dropTable('{{%bill}}');
    }
}
