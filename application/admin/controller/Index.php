<?php

namespace app\Admin\controller;

use app\common\model\DownFile;
use app\common\model\DownList;
use think\Controller;

class Index extends Controller
{
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
            $name_md5 = md5($file['name']);
            $downFile = DownFile::getByNameMd5($name_md5);
            if (!$downFile) {
                $downFile = new DownFile([
                    'name_md5' => $name_md5,
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
        return json(DownFile::all());
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
        $downFile->enabled = (request()->put('enabled') == true);
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
        return json(DownList::order(['rank', 'id'])->select());
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
        return json(true);
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
        $downList->enabled = request()->put('enabled');
        $downList->save();
        return json(true);
    }

    /**
     * 排序列表
     *
     * @return \think\response\Json
     */
    public function sortList()
    {
        $list = request()->put('list/a');
        foreach ($list as $key => $item_id) {
            $downList = DownList::get($item_id);
            $downList->rank = $key;
            $downList->save();
        }
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
