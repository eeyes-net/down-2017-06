<?php

namespace app\common\model;

use think\Model;

/**
 * Class DownFile
 *
 * @package app\common\model
 *
 * @property string $name 文件名
 * @property string $path 文件相对路径
 * @property int $size 文件大小（字节）
 * @property string $version 文件版本
 * @property int $enabled 文件是否存在（0：不存在，1：存在）
 */
class DownFile extends Model
{
}
