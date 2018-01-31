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
    /**
     * @param $username netID 
     * @return array 某个用户的所有评论树
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getTree($username)
    {
        $roots = self::where('root_id',0)->where('username',$username)->order('create_time','desc')->select();
        foreach ($roots as $index => $root) {
            $roots[$index] = $root->visible(['root_id'])->append(['children'])->toArray();
        }
        return $roots;
    }

    /**
     * @return array 所有评论树
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getAllTree()
    {
        $roots = self::where('root_id',0)->order('create_time','desc')->select();
        foreach ($roots as $index => $root) {
            $roots[$index] = $root->visible(['root_id'])->append(['children'])->toArray();
        }
        return $roots;
    }

    /**
     * 获取某条根评论的所有子评论(包括本身)
     * 若不是根评论则返回空数组
     * @param null $value 空字段没有$value参数
     * @param array $data 该Comment对象的其他数据
     * @return array 所有子评论(包括本身)
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getChildrenAttr($value, $data)
    {
        if ($data['root_id'] == 0) {
            $children = self::where('id|root_id', $data['id'])
                ->order('create_time', 'desc')
                ->select();
            foreach ($children as $index => $comment) {
                $children[$index] = $comment->visible(['id','username','content'])->toArray();
            }
            return $children;
        } else {
            return [];
        }
    }
}
