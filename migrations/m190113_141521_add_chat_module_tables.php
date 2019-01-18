<?php

use yii\db\Migration;

/**
 * Class m190113_141521_add_chat_module_tables
 */
class m190113_141521_add_chat_module_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%chat_users}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'avatar' => $this->string()->null(),
            'status' => $this->smallInteger()->notNull()
        ], $tableOptions);

        $this->createTable('{{%chat_dialogs}}', [
            'id' => $this->primaryKey(),
            'user_id_one' => $this->integer()->notNull(),
            'user_id_two' => $this->integer()->notNull(),
            'ctime' => $this->integer()->notNull(),
            'utime' => $this->integer()->notNull()
        ], $tableOptions);

        $this->addForeignKey('chat_dialog_user_id_two_fk', '{{%chat_dialogs}}', 'user_id_one', '{{%chat_users}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('chat_dialog_user_id_one_fk', '{{%chat_dialogs}}', 'user_id_two', '{{%chat_users}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%chat_dialog_messages}}', [
            'id' => $this->primaryKey(),
            'author_user_id' => $this->integer()->notNull(),
            'dialog_id' => $this->integer()->notNull(),
            'message' => $this->text(),
            'ctime' => $this->integer()->notNull(),
            'utime' => $this->integer()->notNull()
        ], $tableOptions);

        $this->addForeignKey('chat_dialog_id_fk', '{{%chat_dialog_messages}}', 'dialog_id', '{{%chat_dialogs}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%chat_dialog_files}}', [
            'id' => $this->primaryKey(),
            'dialog_message_id' => $this->integer()->notNull(),
            'file' => $this->string()->notNull(),
            'ctime' => $this->integer()->notNull(),
            'utime' => $this->integer()->notNull()
        ], $tableOptions);

        $this->addForeignKey('chat_dialog_message_id_fk', '{{%chat_dialog_files}}', 'dialog_message_id', '{{%chat_dialog_messages}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190113_141521_add_chat_module_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190113_141521_add_chat_module_tables cannot be reverted.\n";

        return false;
    }
    */
}
