<?php

namespace app\common\model;

use think\Model;

/**
 * 用户反馈模型
 *
 * @package app\common\model
 *
 * @property string $name 称呼
 * @property string $title 标题
 * @property string $content 内容
 * @property string $contact 联系方式
 */
class Issue extends Model
{
    // protected $autoWriteTimestamp = 'datetime';
}
