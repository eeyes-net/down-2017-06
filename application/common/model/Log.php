<?php

namespace app\common\model;

use think\Model;

/**
 * 下载记录模型
 *
 * @package app\common\model
 *
 * @property string $url 网址
 * @property string $status 状态
 * @property string $file_id 文件id
 * @property string $file_name 文件名
 * @property string $username 用户名
 * @property string $ua User Agent
 * @property string $ip IP
 * @property string $create_time
 */
class Log extends Model
{
    public function file()
    {
        return $this->belongsTo('DownFile', 'file_id', 'id');
    }
}
