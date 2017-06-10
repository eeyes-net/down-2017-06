<?php

namespace app\index\controller;

use app\common\model\DownList;
use app\common\model\Issue;
use think\Hook;

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
        $issue->content = request()->post('content');
        if (!$issue->content) {
            return json([
                'code' => 400,
                'msg' => '内容不能为空',
            ]);
        }
        $issue->name = request()->post('name');
        $issue->contact = request()->post('contact');
        $issue->save();
        Hook::listen('issue_save');
        return json([
            'code' => 200,
            'msg' => '提交反馈成功',
        ]);
    }
}
