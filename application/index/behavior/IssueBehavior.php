<?php

namespace app\index\behavior;

class IssueBehavior
{
    /**
     * 提交反馈建议的Hook
     */
    public function issueSave() {
        $content = request()->post('content');
        $name = request()->post('name');
        $contact = request()->post('contact');
        config('hook.issue_save')($content, $name, $contact);
    }
}
