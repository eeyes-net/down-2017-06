<?php

namespace app\index\behavior;

use Eeyes\Common\Api\Eeyes\Notification;

class IssueBehavior
{
    /**
     * 提交反馈建议的Hook
     */
    public function issueSave() {
        $content = request()->post('content');
        $name = request()->post('name');
        $contact = request()->post('contact');
        $content = "e快下意见反馈：\n内容：{$content}\n反馈者：{$name}\n联系方式：{$contact}";
        Notification::dingTalk($content);
    }
}
