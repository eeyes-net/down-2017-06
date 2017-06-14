<?php

namespace app\common\model;

use think\Model;

/**
 * Class DownList
 *
 * @package app\common\model
 *
 * @property string $name 软件名称
 * @property string $description 软件描述
 * @property string $icon_path 图标相对路径
 * @property int $win_id Windows版的外键
 * @property int $mac_id Mac版的外键
 * @property int $rank 排序
 * @property int $enabled 是否上架（0：不上架，1：上架）
 * @property DownFile $winFile
 * @property DownFile $macFile
 */
class DownList extends Model
{
    public function winFile()
    {
        return $this->belongsTo('DownFile', 'win_id', 'id');
    }

    public function macFile()
    {
        return $this->belongsTo('DownFile', 'mac_id', 'id');
    }

    public function hasWinFile()
    {
        return (!empty($this->winFile) && $this->winFile->enabled);
    }

    public function hasMacFile()
    {
        return (!empty($this->macFile) && $this->macFile->enabled);
    }
}
