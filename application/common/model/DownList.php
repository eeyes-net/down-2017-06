<?php

namespace app\common\model;

use think\Model;

class DownList extends Model
{
    public function winFile()
    {
        return $this->belongsTo('DownFile', 'id', 'win_id');
    }

    public function macFile()
    {
        return $this->belongsTo('DownFile', 'id', 'mac_id');
    }
}
