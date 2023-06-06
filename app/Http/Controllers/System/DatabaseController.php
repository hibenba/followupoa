<?php

namespace App\Http\Controllers\System;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Http/Controllers/System/DatabaseController.php
 * Created Time: 2022-09-23 10:57:18
 * Last Edit Time: 2023-05-23 13:35:12
 * Description: 数据表维护
 */

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DatabaseController extends Controller
{
    public function __invoke()
    {
        $tables = DB::select('SHOW TABLE STATUS');
        $this->data['tables'] = [];
        foreach ($tables as $table) {
            $table->free_size = $this->format_size($table->Data_free);
            $this->data['tables'][] = $table;
        }
        return $this->view('system.database');
    }
    //数据表备份
    public function backup($table)
    {
        $path = 'database' . DIRECTORY_SEPARATOR . now()->toDateString() . DIRECTORY_SEPARATOR . $table . '.sql'; //保存路径
        Storage::put($path, $this->backmysql($table));
        return back()->with('message', '数据表备份成功:' . Storage::url($path));
    }
    //数据库备份
    public function backups()
    {
        $tables = DB::select('SHOW TABLE STATUS');
        $path = 'database' . DIRECTORY_SEPARATOR . 'all_database_' . now()->toDateString() . '.sql'; //保存路径
        $data = '';
        foreach ($tables as $table) {
            $data .= $this->backmysql($table->Name);
        }
        Storage::put($path, $data);
        return back()->with('message', '数据表备份成功:' . Storage::url($path));
    }

    //数据表优化
    public function datafree($table)
    {
        DB::select('OPTIMIZE TABLE `' . $table . '`;');
        return back();
    }

    //数据表修复
    public function repair($table)
    {
        DB::select('REPAIR TABLE `' . $table . '`;');
        return back();
    }

    //备份单个表操作
    private function backmysql($table)
    {
        $tabledump = '-- KwokCMS SQL Dump
-- version: ' . \Illuminate\Foundation\Application::VERSION . '
-- 生成日期: ' . now() . '
-- MYSQL版本: ' . DB::select('SELECT VERSION() as `version`')[0]->version . '
-- PHP 版本: ' . PHP_VERSION . '
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";' . PHP_EOL;
        $tabledump .= 'DROP TABLE IF EXISTS `' . $table . '`;' . PHP_EOL; //如果表存在就删除，用于恢复时使用
        $createtable = DB::select('SHOW CREATE TABLE `' . $table . '`;');
        $tabledump .= end($createtable[0]) . ';' . PHP_EOL; //表结构
        //表数据
        $query = DB::select('SELECT * FROM `' . $table . '`;');
        foreach ($query as $value) {
            $sqlstr = "INSERT INTO `" . $table . "` VALUES (";
            foreach ($value as $str) {
                $sqlstr .= "'" . $str . "', ";
            }
            $tabledump .= substr($sqlstr, 0, strlen($sqlstr) - 2) . ');' . PHP_EOL; //去掉最后的,和空格并结束
        }
        return $tabledump;
    }
}
