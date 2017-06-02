<?php

namespace app\common\model;

use think\Model;

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
}
