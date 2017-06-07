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
        $issue->title = request()->post('title');
        if (!$issue->title) {
            return json([
                'code' => 400,
                'msg' => '反馈概述不能为空',
            ]);
        }
        $issue->content = request()->post('content');
        $issue->name = request()->post('name');
        $issue->contact = request()->post('contact');
        $issue->save();
        return json([
            'code' => 200,
            'msg' => '提交反馈成功',
        ]);
    }
}
