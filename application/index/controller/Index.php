<?php

namespace app\index\controller;

use app\common\model\DownList;

class Index
{
    public function index()
    {
        $downList = DownList::where('enabled', '1')->order(['rank', 'id'])->with(['winFile', 'macFile'])->select();
        return view('', compact('downList'));
    }
}
