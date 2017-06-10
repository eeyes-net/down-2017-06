<?php

use think\migration\Migrator;

class Issue extends Migrator
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
        $this->table('issue', ['engine' => 'MyISAM'])
            ->addColumn('content', 'text', ['comment' => '内容'])
            ->addColumn('name', 'text', ['comment' => '称呼'])
            ->addColumn('contact', 'text', ['comment' => '联系方式'])
            ->addColumn('create_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
