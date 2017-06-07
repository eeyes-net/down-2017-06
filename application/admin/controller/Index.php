<?php

namespace app\Admin\controller;

use app\common\model\DownFile;
use app\common\model\DownList;
use think\Controller;
use think\exception\HttpResponseException;
use think\Response;
use think\Session;

class Index extends Controller
{
    protected $beforeActionList = [
        'mustLogin' => ['except' => ['index', 'login', 'logout']],
    ];

    public function mustLogin()
    {
        if (!Session::get('is_login')) {
            $response = Response::create(['err_msg' => '请先登录'], 'json', 403);
            throw new HttpResponseException($response);
        }
    }

    /**
     * 返回是否登录
     *
     * @return \think\response\Json
     */
    public function isLogin()
    {
        return json(true == Session::get('is_login'));
    }

    /**
     * 登录
     *
     * @return \think\response\Json
     */
    public function login()
    {
        if (request()->post('password') === config('password.admin')) {
            Session::set('is_login', true);
            return json(true);
        }
        return json(false);
    }

    /**
     * 退出登录
     *
     * @return \think\response\Json
     */
    public function logout()
    {
        Session::delete('is_login');
        return json(true);
    }

    /**
     * 重新遍历下载目录
     *
     * @return \think\response\Json
     */
    public function refreshFiles()
    {
        // 禁用不存在的文件
        $downFiles = DownFile::all();
        foreach ($downFiles as $downFile) {
            if (!file_exists(config('filesystem.root') . '/' . $downFile['path'])) {
                $downFile->enabled = 0;
                $downFile->save();
            }
        }
        // 扫描所有文件
        $files = scan_file(config('filesystem.down'), config('filesystem.root'));
        foreach ($files as &$file) {
            $downFile = DownFile::getByName($file['name']);
            if (!$downFile) {
                $downFile = new DownFile([
                    'name' => $file['name'],
                ]);
            }
            $downFile->path = $file['rel_path'];
            $downFile->size = $file['size'];
            $downFile->enabled = 1;
            $downFile->save();
        }
        return json(true);
    }

    /**
     * 获取所有文件
     *
     * @return \think\response\Json
     */
    public function getFiles()
    {
        $downFiles = DownFile::all();
        foreach ($downFiles as &$downFile) {
            $downFile['enabled'] = ($downFile['enabled'] == true);
        }
        return json($downFiles);
    }

    /**
     * 更新文件版本和是否启用
     *
     * @param int $id
     *
     * @return bool|\think\response\Json
     */
    public function updateFile($id)
    {
        $downFile = DownFile::get($id);
        if (!$downFile) {
            return json(false);
        }
        $downFile->version = request()->put('version');
        $downFile->save();
        return json(true);
    }

    /**
     * 获取文件列表
     *
     * @return \think\response\Json
     */
    public function getList()
    {
        $downList = DownList::order(['rank', 'id'])->select();
        foreach ($downList as &$item) {
            $item['enabled'] = ($item['enabled'] == true);
        }
        return json($downList);
    }

    /**
     * 排序列表
     *
     * @return \think\response\Json
     */
    public function updateList()
    {
        $list = request()->put('list/a');
        $rank = 1;
        foreach ($list as $item_id) {
            $downList = DownList::get((int)$item_id);
            $downList->rank = $rank;
            $downList->save();
            ++$rank;
        }
        return json(true);
    }

    /**
     * 创建列表项
     *
     * @return \think\response\Json
     */
    public function createItem()
    {
        $downList = new DownList();
        $downList->name = request()->post('name');
        $downList->icon_path = request()->post('icon_path');
        $downList->win_id = request()->post('win_id');
        $downList->mac_id = request()->post('mac_id');
        $downList->description = request()->post('description');
        $downList->enabled = request()->post('enabled');
        $downList->save();
        return json(DownList::get($downList->id));
        // return json($downList);
    }

    /**
     * 更新列表项
     *
     * @param int $id
     *
     * @return \think\response\Json
     */
    public function updateItem($id)
    {
        $downList = DownList::get($id);
        $downList->name = request()->put('name');
        $downList->icon_path = request()->put('icon_path');
        $downList->win_id = request()->put('win_id');
        $downList->mac_id = request()->put('mac_id');
        $downList->description = request()->put('description');
        $downList->enabled = (request()->put('enabled') == true);
        $downList->save();
        return json(true);
    }

    /**
     * 删除列表项
     *
     * @param int $id
     *
     * @return \think\response\Json
     */
    public function deleteItem($id)
    {
        DownList::destroy($id);
        return json(true);
    }

    /**
     * 后台首页
     *
     * @return \think\response\View
     */
    public function index()
    {
        return view();
    }
}
