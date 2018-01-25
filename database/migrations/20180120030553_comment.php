<?php

use think\migration\Migrator;
use think\migration\db\Column;

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
          ->addColumn('root_id','integer',['default' => 0])
          ->addColumn('content','text',['comment' => '评论内容'])
          ->addColumn('username','char',['comment' => 'netID'])
          ->addColumn('create_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
          ->create();
    }
}
