<?php

use Phinx\Db\Adapter\MysqlAdapter;
use think\migration\Migrator;

class DownList extends Migrator
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
        $this->table('down_list', ['engine' => 'MyISAM'])
            ->addColumn('name', 'text', ['comment' => '软件名称'])
            ->addColumn('description', 'text', ['comment' => '软件描述'])
            ->addColumn('icon_path', 'string', ['limit' => 2048, 'default' => '', 'comment' => '图标相对路径'])
            ->addColumn('win_id', 'integer', ['default' => '0', 'comment' => 'Windows版的外键'])
            ->addColumn('mac_id', 'integer', ['default' => '0', 'comment' => 'Mac版的外键'])
            ->addColumn('rank', 'integer', ['default' => '32767', 'comment' => '排序'])
            ->addColumn('enabled', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'default' => '1', 'comment' => '是否上架'])
            ->addForeignKey('win_id', 'down_file', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->addForeignKey('mac_id', 'down_file', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->create();
    }
}
