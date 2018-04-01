<?php

use think\migration\Migrator;

class IssueMigrateToComment extends Migrator
{
    const ANONYMOUS_USERNAME = '__anonymous';
    const ANONYMOUS_NAME = '';

    public function up()
    {
        $anonymous_user = \think\Db::name('user')->where('username', self::ANONYMOUS_USERNAME)->find();
        if ($anonymous_user) {
            $anonymous_user_id = $anonymous_user['id'];
        } else {
            $anonymous_user_id = \think\Db::name('user')->insert([
                'username' => self::ANONYMOUS_USERNAME,
                'name' => self::ANONYMOUS_NAME,
            ]);
        }
        \think\Db::name('issue')->chunk(100, function ($issues) use (&$anonymous_user_id) {
            $data = [];
            foreach ($issues as $issue) {
                if ($issue['username']) {
                    $user = \think\Db::name('user')->where('username', $issue['username'])->find();
                    if ($user) {
                        $user_id = $user['id'];
                    } else {
                        $user_id = \think\Db::name('user')->insert([
                            'username' => $issue['username'],
                            'name' => $issue['name'],
                        ]);
                    }
                } else {
                    $user_id = $anonymous_user_id;
                }
                $data[] = [
                    'user_id' => $user_id,
                    'content' => $issue['content'],
                    'is_admin' => 0,
                    'create_time' => $issue['create_time'],
                ];
            }
            \think\Db::name('comment')->insertAll($data);
        }, 'id');
    }

    public function down()
    {
        \think\Db::name('comment')->join('user', 'user.id = comment.user_id')->where('is_admin', '0')->chunk(100, function ($comments) use (&$anonymous_user_id) {
            $data = [];
            foreach ($comments as $comment) {
                $data[] = [
                    'content' => $comment['content'],
                    'name' => $comment['name'],
                    'contact' => '',
                    'username' => $comment['username'] === self::ANONYMOUS_USERNAME ? '' : $comment['username'],
                    'create_time' => $comment['create_time'],
                ];
            }
            \think\Db::name('issue')->insertAll($data);
        }, 'comment.id');
    }
}
