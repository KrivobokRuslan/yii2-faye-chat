<?php

use yii\db\Migration;

/**
 * Class m190720_105628_add_chat_rooms
 */
class m190720_105628_add_chat_rooms extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%chat_rooms}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'owner_user_id' => $this->integer()->notNull(),
            'ctime' => $this->integer()->notNull()
        ]);

        $this->createTable('{{%chat_room_messages}}', [
            'id' => $this->primaryKey(),
            'message' => $this->text(),
            'room_id' => $this->integer()->notNull(),
            'author_user_id' => $this->integer()->notNull(),
            'status' => $this->smallInteger(1)->null(),
            'ctime' => $this->integer()->notNull()
        ]);

        $this->addForeignKey('fk_room_message_to_room', '{{%chat_room_messages}}', 'room_id', '{{%chat_rooms}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%chat_room_message_files}}', [
            'id' => $this->primaryKey(),
            'file' => $this->string()->notNull(),
            'room_message_id' => $this->integer()->notNull(),
            'ctime' => $this->integer()->notNull()
        ]);

        $this->addForeignKey('fk_room_message_file_to_message', '{{%chat_room_message_files}}', 'room_message_id', '{{%chat_room_messages}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%chat_room_roles}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'message_permissions' => $this->integer(),
            'member_permissions' => $this->integer(),
            'status' => $this->integer(1),
            'slug' => $this->string(32)->notNull()->unique(),
            'ctime' => $this->integer()->notNull()
        ]);

        $this->createTable('{{%chat_room_member_roles}}', [
            'room_id' => $this->integer()->notNull(),
            'member_id' => $this->integer()->notNull(),
            'role_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('fk_room_member_role', '{{%chat_room_member_roles}}', ['room_id', 'member_id', 'role_id']);

        $this->addForeignKey('fk_room_member_role_to_room', '{{%chat_room_member_roles}}', 'room_id', '{{%chat_rooms}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_room_member_role_to_role', '{{%chat_room_member_roles}}', 'role_id', '{{%chat_room_roles}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_room_member_role_to_member', '{{%chat_room_member_roles}}', 'member_id', '{{%chat_users}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%chat_room_message_deleted}}', [
            'room_message_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey('chat_room_message_deleted_key', '{{%chat_room_message_deleted}}', ['room_message_id', 'user_id']);

        $this->addForeignKey('room_message_id_fk',
            '{{%chat_room_message_deleted}}',
            'room_message_id',
            '{{%chat_room_messages}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190720_105628_add_chat_rooms cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190720_105628_add_chat_rooms cannot be reverted.\n";

        return false;
    }
    */
}
