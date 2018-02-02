<?php

namespace app\common\model;

use think\Model;

/**
 * 评论模型
 *
 * @package app\common\model
 * 
 * @property int $user_id 评论者对应User模型的id(此字段无须访问)
 *
 * @property int $id 评论ID
 * @property string $content 评论内容
 * @property boolean $is_admin 是否是管理员发出
 * @property string $create_time 创建时间
 * @property User $author 最初发起此评论的用户
 */
class Comment extends Model
{
    protected static function init()
    {
        Comment::afterInsert(function ($comment) {
            if (!$comment->is_admin) {
                $author = $comment->author;
                // 这里必须要转成字符串才能存进去，直接存时间戳不行
                $author->last_comment_time = date("Y-m-d H:i:s", time());;
                $author->save();
            }
        });
    }
    public function author()
    {
        return $this->belongsTo('User', 'user_id', 'id');
    }
}
