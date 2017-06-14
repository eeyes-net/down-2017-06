<?php

/**
 * 遍历指定目录及子目录的文件
 *
 * @param $path string 开始遍历的目录
 * @param $root string 服务器根目录
 *
 * @return array 包含所有文件（不含文件夹）的文件名、路径、绝对路径、相对根目录的路径、文件大小的数组
 */
function &scan_file($path, $root)
{
    // 遍历指定目录并除去.和..
    $files = array_diff(scandir($path), array('..', '.'));
    $ret_arr = [];
    foreach ($files as &$file) {
        $file_path = $path . '/' . $file;
        if (is_dir($file_path)) {
            $ret_arr = array_merge($ret_arr, scan_file($file_path, $root));
        } else {
            // 获取绝对路径
            $abs_path = realpath($file_path);
            // 判断是否以指定根目录开始
            if (strpos($abs_path, $root) === 0) {
                $ret_arr[] = [
                    'name' => $file,
                    'path' => $file_path,
                    // 'abs_path' => $abs_path,
                    'rel_path' => substr($abs_path, strlen($root) + 1), // 除去首部的根目录，转换为相对路径
                    'size' => filesize($abs_path),
                ];
            }
        }
    }
    return $ret_arr;
}

/**
 * 方便人类阅读的文件大小表示法
 *
 * @param $bytes int 字节数
 *
 * @return string
 */
function readable_size($bytes)
{
    if ($bytes < 1024) {
        return $bytes . 'B';
    }
    if ($bytes < 1048576) {
        return round($bytes / 1024, 1) . 'K';
    }
    if ($bytes < 1073741824) {
        return round($bytes / 1048576, 1) . 'M';
    }
    return round($bytes / 1073741824, 1) . 'G';
}

/**
 * IP掩码检查
 *
 * @link https://stackoverflow.com/questions/594112/matching-an-ip-to-a-cidr-mask-in-php-5/594134#594134
 *
 * @param string $ip IP
 * @param string|array $range IP掩码
 *
 * @return bool
 */
function cidr_match($ip, $range)
{
    if (is_array($range)) {
        foreach ($range as $range1) {
            if (cidr_match($ip, $range1)) {
                return true;
            }
        }
        return false;
    }
    list($subnet, $bits) = explode('/', $range);
    $ip = ip2long($ip);
    $subnet = ip2long($subnet);
    $mask = -1 << (32 - $bits);
    return ($ip & $mask) === ($subnet & $mask);
}

/**
 * 初始化phpCAS配置
 */
function init_php_cas()
{
    phpCAS::client(config('cas.server_version'), config('cas.server_hostname'), config('cas.server_port'), config('cas.server_uri'));
    if (config('app_debug')) {
        phpCAS::setVerbose(true);
        phpCAS::setDebug(LOG_PATH . 'phpCAS.log');
        phpCAS::setNoCasServerValidation();
    }
}
