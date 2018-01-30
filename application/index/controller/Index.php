<?php

namespace app\index\controller;

use app\common\model\DownList;
use app\common\model\Issue;
use app\common\model\Log;
use app\common\model\Comment;
use app\traits\controller\CheckPermission;
use phpCAS;
use think\Controller;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\Hook;
use think\Session;

/**
 * Class Index
 * @package app\index\controller
 */
class Index extends Controller
{
    use CheckPermission;

    protected $beforeActionList = [
        'checkPermission' => ['except' => ['index']],
    ];

    /**
     * 直接返回主页HTML
     *
     * @return \think\response\View
     */
    public function index()
    {
        return view();
    }

    /**
     * 获取文件列表
     *
     * @return \think\response\Json
     */
    public function getDownList()
    {
        $downList = DownList::where('enabled', '1')->order(['rank', 'id'])->with(['winFile', 'macFile'])->select();
        $data = [];
        /** @var DownList $item */
        foreach ($downList as $item) {
            $tmp = [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'icon_path' => $item->icon_path,
            ];
            if ($item->hasWinFile()) {
                $tmp['winFile'] = [
                    'version' => $item->winFile->version,
                    'size' => $item->winFile->size,
                ];
            }
            if ($item->hasMacFile()) {
                $tmp['macFile'] = [
                    'version' => $item->macFile->version,
                    'size' => $item->macFile->size,
                ];
            }
            $data[] = $tmp;
        }
        return json([
            'code' => 200,
            'data' => $data,
            'msg' => 'OK',
        ]);
    }

    /**
     * 利用Nginx的X-Sendfile协议下载文件
     *
     * @param int $id
     * @param string $type 'win'或'mac'
     *
     * @return $this|\think\Response
     */
    public function down($id, $type)
    {
        $item = DownList::get((int)$id);
        $log = new Log();
        $log->url = request()->url();
        $log->file_id = 0;
        $log->file_name = '';
        $log->bytes_range = isset($_SERVER['HTTP_RANGE']) ? (string)$_SERVER['HTTP_RANGE'] : '';
        $log->username = '';
        $log->status = '404';
        init_php_cas();
        if (phpCAS::isAuthenticated()) {
            $log->username = phpCAS::getUser();
        }
        $log->ua = request()->header('User-Agent');
        $log->ip = request()->ip();
        if (!($item && ($item->enabled || Session::get('is_login')))) {
            $log->save();
            $this->error('对不起，这个文件不存在');
            return;
        }
        if (!request()->has('t')) {
            $log->save();
            $this->error('缺少参数t');
            return;
        }
        switch ($type) {
            case 'win':
                if (!$item->hasWinFile()) {
                    $log->save();
                    $this->error('对不起，这个文件没有WIN版本');
                    return;
                }
                $downFile = $item->winFile;
                break;
            case 'mac':
                if (!$item->hasMacFile()) {
                    $log->save();
                    $this->error('对不起，这个文件没有MAC版本');
                    return;
                }
                $downFile = $item->macFile;
                break;
            default:
                $log->save();
                $this->error('对不起，文件版本错误');
                return;
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

    /**
     * 保存反馈建议
     *
     * @return \think\response\Json
     */
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
        $issue->username = '';
        init_php_cas();
        if (phpCAS::isAuthenticated()) {
            $issue->username = phpCAS::getUser();
        }
        $issue->save();
        Hook::listen('issue_save');
        return json([
            'code' => 200,
            'msg' => '提交反馈成功',
        ]);
    }

    /**
     * 获得用户评论
     *
     * @return \think\response\Json
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public function getComment()
    {
        if(isset($_SESSION['authorization'])){
            $username = Session::get('username');

            $comment = new Comment();
            $commentary = $comment->getTree($username);

            return json([
                'code' => '200',
                'data' => $commentary,
                'msg' => 'OK',
            ]);
        } else {
            return redirect('/oauth/login');
        }
    }

    /**
     * 保存用户评论
     *
     * @return \think\response\Json
     */
    public function saveComment()
    {
        if(isset($_SESSION['authorization']))
        {
            $username = Session::get('username');

            $comment = new Comment();

            $comment -> content = request()->post('content');
            if(!$comment->content)
            {
                return json([
                    'code' => '400',
                    'msg' => '内容不能为空',
                ]);
            }
            $comment -> root_id = request()->post('root_id');
            $comment -> username = $username;
            $comment -> save();
            Hook::listen('comment_save');
            return json([
                'code' => '200',
                'msg' => '提交评论成功',
            ]);
        } else {
            return redirect('/oauth/login');
        }
    }
}
