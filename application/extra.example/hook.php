<?php

return [
    'issue_save' => function ($content, $name, $contact) {
        think\Log::info("Content: $content. Name: $name. Contact: $contact.");
    },
];
