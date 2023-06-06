<?php

use Illuminate\Support\Facades\Route;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /routes/admin.php
 * Created Time: 2023-02-28 02:37:48
 * Last Edit Time: 2023-06-06 09:56:16
 * Description: 管理员权限路由
 */
Route::get('flush', [App\Http\Controllers\System\SettingsController::class, 'flush'])->name('flush'); //清除所有缓存
Route::get('cached', [App\Http\Controllers\System\SettingsController::class, 'cached'])->name('cached'); //优化加载
Route::get('clearcache', [App\Http\Controllers\System\SettingsController::class, 'clearcache'])->name('clearcache'); //清理优化加载
//Route::get('conversion', App\Http\Controllers\DataConversionController::class)->name('conversion'); //数据转换
//附件管理
Route::get('attachment', App\Http\Controllers\System\AttachmentController::class)->name('attachment'); //附件列表
Route::put('attachment/{id}', [App\Http\Controllers\System\AttachmentController::class, 'recycle'])->name('attachment.recycle'); //附件列表
Route::delete('attachment/{id}', [App\Http\Controllers\System\AttachmentController::class, 'delete'])->name('attachment.delete'); //附件真实删除


//客户管理 
Route::get('company/{company}/recover', [App\Http\Controllers\Company\CompanyController::class, 'recover'])->name('company.recover'); //移出回收站
Route::delete('company/{company}/delete', [App\Http\Controllers\Company\CompanyController::class, 'forcedelete'])->name('company.forcedelete'); //移出回收站

//系统设置
Route::get('settings', App\Http\Controllers\System\SettingsController::class)->name('settings'); //系统设置
Route::put('settings', [App\Http\Controllers\System\SettingsController::class, 'update'])->name('settings.update'); //系统设置修改
//自定义字段
Route::get('customkeys', App\Http\Controllers\System\CustomkeyController::class)->name('customkeys'); //自定义字段
Route::get('customkey/{key}/edit', [App\Http\Controllers\System\CustomkeyController::class, 'edit'])->name('customkey.edit'); //修改字段表单
Route::get('customkey/create', [App\Http\Controllers\System\CustomkeyController::class, 'create'])->name('customkey.create'); //新增字段
Route::put('customkey/{key}', [App\Http\Controllers\System\CustomkeyController::class, 'update'])->name('customkey.update'); //自定义字段修改
Route::post('customkey', [App\Http\Controllers\System\CustomkeyController::class, 'store'])->name('customkey.store'); //新增存储
Route::delete('customkey/{key}', [App\Http\Controllers\System\CustomkeyController::class, 'delete'])->name('customkey.delete'); //自定义字段删除
//数据库
Route::get('database', App\Http\Controllers\System\DatabaseController::class)->name('database'); //数据库维护
Route::post('database/{table}/backup', [App\Http\Controllers\System\DatabaseController::class, 'backup'])->name('database.backup'); //数据表备份
Route::post('database/{table}/repair', [App\Http\Controllers\System\DatabaseController::class, 'repair'])->name('database.repair'); //数据表修复
Route::post('database/{table}/datafree', [App\Http\Controllers\System\DatabaseController::class, 'datafree'])->name('database.datafree'); //数据库优化
Route::get('database/backups', [App\Http\Controllers\System\DatabaseController::class, 'backups'])->name('database.backups'); //数据表备份
//公司架构
Route::resource('team/job', App\Http\Controllers\Team\JobController::class)->except(['show']); //职位管理
Route::resource('team/staff', App\Http\Controllers\Team\StaffController::class); //职员管理
//数据导出
Route::get('export', App\Http\Controllers\System\ExportController::class)->name('export'); //导出条件
Route::post('export', [App\Http\Controllers\System\ExportController::class, 'export'])->name('export.post'); //数据导出

//国家地区
Route::resource('country', App\Http\Controllers\System\CountryController::class)->only(['index', 'store', 'create', 'edit', 'update', 'destroy']);


//日志报告
Route::get('logs', App\Http\Controllers\System\LogController::class)->name('logs'); //后台访问日志
Route::get('login_logs', App\Http\Controllers\System\LoginlogController::class)->name('login.logs'); //登陆日志