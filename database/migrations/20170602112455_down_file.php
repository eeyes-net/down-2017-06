<?php

use Phinx\Db\Adapter\MysqlAdapter;
use think\migration\Migrator;

class DownFile extends Migrator
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
        $this->table('down_file', ['engine' => 'MyISAM'])
            ->addColumn('name', 'string', ['limit' => 190, 'comment' => '文件名称'])
            ->addColumn('path', 'string', ['limit' => 2048, 'comment' => '文件相对路径'])
            ->addColumn('size', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'comment' => '文件大小'])
            ->addColumn('version', 'string', ['limit' => 255, 'default' => '', 'comment' => '文件版本'])
            ->addColumn('enabled', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'default' => '1', 'comment' => '是否上架'])
            ->addIndex(['name'], ['unique' => true, 'name' => 'name_unique'])
            ->create();
    }
}
