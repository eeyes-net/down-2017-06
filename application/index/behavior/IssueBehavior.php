<?php

namespace app\index\behavior;

class IssueBehavior
{
    public function issueSave() {
        $content = request()->post('content');
        $name = request()->post('name');
        $contact = request()->post('contact');
        config('hook.issue_save')($content, $name, $contact);
    }
}
