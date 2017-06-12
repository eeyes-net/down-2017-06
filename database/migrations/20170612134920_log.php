<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Log extends Migrator
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
        $this->table('log', ['engine' => 'MyISAM'])
            ->addColumn('url', 'text', ['comment' => '网址'])
            ->addColumn('status', 'string', ['limit' => 20, 'comment' => '状态'])
            ->addColumn('file_id', 'integer', ['comment' => '文件id'])
            ->addColumn('file_name', 'string', ['limit' => 190, 'comment' => '文件名称'])
            ->addColumn('ua', 'text', ['comment' => 'User Agent'])
            ->addColumn('ip', 'string', ['limit' => 50, 'comment' => 'IP'])
            ->addColumn('create_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('file_id', 'down_file', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->create();
    }
}
