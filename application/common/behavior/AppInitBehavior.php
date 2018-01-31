<?php

namespace app\common\behavior;

use Eeyes\Common\EeyesCommon;
use think\Env;

class AppInitBehavior
{
    /**
     * 提交反馈建议的Hook
     */
    public function appInit() {
        EeyesCommon::config([
            'API_URL' => Env::get('eeyes.api_url', ''),
            'API_TOKEN' => Env::get('eeyes.api_token', ''),
            'IMG_URL' => Env::get('eeyes.img_url', ''),
        ]);
    }
}