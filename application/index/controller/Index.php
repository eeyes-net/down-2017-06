<?php

namespace app\index\controller;

use app\common\model\DownList;
use app\common\model\Issue;

class Index
{
    public function index()
    {
        $downList = DownList::where('enabled', '1')->order(['rank', 'id'])->with(['winFile', 'macFile'])->select();
        return view('', compact('downList'));
    }

    public function saveIssue()
    {
        $issue = new Issue();
        $issue->name = request()->post('name');
        $issue->title = request()->post('title');
        $issue->content = request()->post('content');
        $issue->contact = request()->post('contact');
        $issue->save();
        return json(true);
    }
}
