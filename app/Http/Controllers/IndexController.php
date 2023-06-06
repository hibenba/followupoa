<?php

namespace App\Http\Controllers;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Http/Controllers/IndexController.php
 * Created Time: 2023-03-16 10:18:34
 * Last Edit Time: 2023-05-30 21:50:13
 * Description: 首页
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache; //使用缓存
use App\Models\System\Session; //session
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        //强制下线
        if ($request->has('kill')) {
            Session::where('token', $request->kill)->delete();
            Cache::forget('admin:index');
        }
        $this->data = Cache::remember('admin:index', $this->duration, function () {
            $dbinfo = $this->dbinfo();
            return [
                'fileupload' => @ini_get('file_uploads') ? ini_get('upload_max_filesize') : '<span style="color:red">禁止上传</span>',
                'limit_upload_size' => $this->format_size($this->app['limit_upload_size']), //系统设置上传大小
                'dbsize' => $dbinfo['dbsize'],
                'dbnum' => $dbinfo['dbnum'],
                'dbdriver' => DB::connection()->getDriverName(),
                'sql_version' => $this->sql_version(),
                'df' => $this->format_size(@disk_free_space(".")),
                'app_make_date' => $this->app['app_make_date'],
                'app_make_time' => (\Illuminate\Support\Carbon::createFromDate($this->app['app_make_date']))->locale('zh_CN')->diffForHumans(), //人性化时间
                'attachsize' => $this->attachsize(),
                'todolist' => $this->todolist()->where('staff_id', Auth::id())->get(), //待办事项
                'online' => $this->online(), //在线的管理用户
            ];
        });
        return $this->view('index');
    }

    //获取当前数据库使用情况
    private function dbinfo()
    {
        $num = $dbsize = 0;
        $tables = DB::select('SHOW TABLE STATUS');
        foreach ($tables as $table) {
            $dbsize += $table->Data_length + $table->Index_length;
            $num++;
        }
        return [
            'dbnum' => $num,
            'dbsize' => $this->format_size($dbsize)
        ];
    }
    //获取当前数据库版本
    private function sql_version()
    {
        return DB::select('SELECT VERSION() as `version`')[0]->version;
    }
    //获取附件大小
    private function attachsize()
    {
        $files_size = 0;
        $files = \Illuminate\Support\Facades\Storage::disk('public');
        foreach ($files->allFiles() as $file) {
            $files_size += $files->size($file);
        }
        return $this->format_size($files_size);
    }
    //待办事项
    private function todolist()
    {
        return \App\Models\System\TodoList::where('status', 0)->orderByDesc('created_at')->take(8);
    }
    //在线用户
    private function online()
    {
        return Session::orderByDesc('updated_at')->get();
    }
}
