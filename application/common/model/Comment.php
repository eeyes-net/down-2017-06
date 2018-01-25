<?php

namespace app\common\model;

use think\Model;

/**
 * 评论模型
 *
 * @package app\common\model
 *
 * @property string $id 评论ID
 * @property string $root_id 根评论ID，根评论为0
 * @property string $content 评论内容
 * @property string $username netID
 * @property string $create_time 创建时间
 */
class Comment extends Model
{
    public function getTree($username)
    {
        $roots = $this->where('root_id',0)->where('username',$username)->select();

        $data = [];
        foreach ($roots as $root)
        {
            $tmp = [
                'root_id' => $root->id,
                'children' => $this->getChildren($root->id),
            ];
            $data[] = $tmp;
        }
        return $data;
    }

    public function getAllTree()
    {
        $roots = $this->where('root_id',0)->order('create_time','desc')->select();

        $data = [];
        foreach ($roots as $root)
        {
            $tmp = [
                'root_id' => $root->id,
                'children' => $this->getChildren($root->id),
            ];
            $data[] = $tmp;
        }
        return $data;
    }

    private function getChildren($root_id)
    {
        $commentaries = $this->where('id|root_id',$root_id)->order('create_time','desc')->select();

        $data = [];
        foreach ($commentaries as $commentary)
        {
            $tmp = [
                'id' => $commentary->id,
                'username' => $commentary->username,
                'content' => $commentary->content,
            ];
            $data[] = $tmp;
        }
        return $data;
    }

}
