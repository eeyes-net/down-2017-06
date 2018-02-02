<?php

namespace app\common\model;

use think\Model;

/**
 * 评论模型
 *
 * @package app\common\model
 *
 * @property string $id 评论ID
 * @property string $user_id 根评论ID，根评论为0
 * @property string $content 评论内容
 * @property boolean $is_admin
 * @property string $username netID
 * @property string $create_time 创建时间
 */
class Comment extends Model
{
    //
}
