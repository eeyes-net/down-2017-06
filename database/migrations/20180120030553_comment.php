<?php

use think\migration\Migrator;

class Comment extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->table('comment', ['engine' => 'MyISAM'])
            ->addColumn('user_id', 'integer')
            ->addColumn('content', 'text', ['comment' => '评论内容'])
            ->addColumn('is_admin', 'boolean', ['comment' => '是否为管理员'])
            ->addColumn('create_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
