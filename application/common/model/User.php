<?php

namespace app\common\model;

use think\Model;

/**
 * 用户模型
 * @package app\common\model
 *
 * @property string $id 用户ID
 * @property string $username 用户netID
 * @property string $name 姓名
 * @property array $comments
 */
class User extends Model
{
    public function comments()
    {
        return $this->hasMany('Comment', 'user_id')->order('create_time','desc');
    }
}
