<?php

namespace app\index\controller;

use app\common\model\DownList;
use app\common\model\Issue;
use app\common\model\Log;
use think\Hook;
use think\Session;

class Index
{
    public function index()
    {
        $downList = DownList::where('enabled', '1')->order(['rank', 'id'])->with(['winFile', 'macFile'])->select();
        return view('', compact('downList'));
    }

    private function down($id, $type)
    {
        $item = DownList::get($id);
        $log = new Log();
        $log->url = request()->url();
        $log->file_id = 0;
        $log->file_name = '';
        $log->ua = request()->header('User-Agent');
        $log->ip = request()->ip();
        if (!$item) {
            $log->status = '404';
            $log->save();
            return response(null, 404);
        }
        if (!($item->enabled || Session::get('is_login'))) {
            $log->status = '403';
            $log->save();
            return response(null, 403);
        }
        switch ($type) {
            case 'win':
                if (!$item->hasWinFile()) {
                    return response(null, 404);
                }
                $downFile = $item->winFile;
                break;
            case 'mac':
                if (!$item->hasMacFile()) {
                    return response(null, 404);
                }
                $downFile = $item->macFile;
                break;
        }
        $log->status = '200';
        $log->file_id = $downFile->id;
        $log->file_name = $downFile->name;
        $log->save();
        return response(null, 200, [
            'Content-Disposition' => 'attachment; filename=' . $downFile->name,
            'X-Accel-Redirect' => '/' . $downFile->path, // Only for Nginx. Please add 'internal;' in 'location /upload/down/' block.
        ])->contentType('application/octet-stream');
    }

    public function downWin($id)
    {
        return $this->down($id, 'win');
    }

    public function downMac($id)
    {
        return $this->down($id, 'mac');
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
