<?php

namespace app\common\model;

use think\Model;

/**
 * 用户模型
 * @package app\common\model
 *
 * @property int $id 用户ID
 * @property string $netid 用户netID
 * @property string $name 姓名
 * @property array $comments 该用户所有的评论
 * @property string $last_comment_time 该用户最后一次评论的时间
 */
class User extends Model
{
    public function comments()
    {
        // 这里需要按照升序
        // 这样序号较小的时间较早，序号较大的时间较晚
        // 前端从上往下渲染时，时间就是从过去到未来的
        return $this->hasMany('Comment', 'user_id')->order('create_time','asc');
    }
}
