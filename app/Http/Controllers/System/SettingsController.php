<?php

namespace App\Http\Controllers\System;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Http/Controllers/System/SettingsController.php
 * Created Time: 2022-09-23 10:57:18
 * Last Edit Time: 2023-04-20 11:05:51
 * Description: 后台设置控制器
 */

use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use App\Models\System\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __invoke()
    {
        $this->data = Arr::pluck(Setting::all('key', 'value'), 'value', 'key');
        $this->data['limit_upload_size'] = intval($this->data['limit_upload_size'] / 1024);
        return $this->view('system.settings');
    }

    //更新设置
    public function update(Request $request)
    {
        $this->data = Arr::pluck(Setting::all('key', 'value'), 'value', 'key'); //获取数据库值
        //处理用户提交数据
        $request->limit_upload_size = $this->abs_number($request->limit_upload_size * 1024); //将上传限制Kb转为字节
        if (@ini_get('file_uploads') && $this->return_bytes(ini_get('upload_max_filesize')) < $request->limit_upload_size) {
            $request->limit_upload_size = $this->return_bytes(ini_get('upload_max_filesize')); //设置不能超过PHP限制
        }
        //批量更新
        foreach ($this->data as $item => $setting) {
            if ($request->has($item) && $request->$item != $setting) {
                Setting::where('key', $item)->update(['value' => $request->$item ?? '']); //更新数据库
            }
        }
        return $this->flush(); //刷新缓存
    }

    //正数数字
    private function abs_number(int $number)
    {
        return abs(intval($number));
    }

    //清理系统缓存
    public function flush()
    {
        Cache::flush(); //清空缓存
        return redirect()->back();
    }
    //优化加载
    public function cached()
    {
        Artisan::call('config:cache'); //配置缓存
        Artisan::call('route:cache'); //路由缓存
        Artisan::call('view:cache'); //模板编译
        return redirect()->back();
    }
    //取消优化加载
    public function clearcache()
    {
        Artisan::call('config:clear'); //配置缓存清理
        Artisan::call('route:clear'); //路由缓存清理
        Artisan::call('view:clear'); //模板编译清理
        return redirect()->back();
    }
    //将大小转为byte return_bytes('50G')
    private function return_bytes($val)
    {
        $val  = trim($val);
        if (is_numeric($val)) return $val;
        $last = strtolower($val[strlen($val) - 1]);
        $val  = substr($val, 0, -1);
        //Note: No break
        switch ($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }
}
