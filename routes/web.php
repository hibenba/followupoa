<?php

use Illuminate\Support\Facades\Route;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /routes/web.php
 * Created Time: 2023-02-28 02:37:48
 * Last Edit Time: 2023-05-23 16:47:45
 * Description: web 路由配置文件(应用auth中间件)
 */
Route::get('/', App\Http\Controllers\IndexController::class); //首页
Route::get('home', App\Http\Controllers\IndexController::class)->name('home'); //个人中心
Route::post('logout', [App\Http\Controllers\User\LoginController::class, 'destroy'])->name('logout'); //POST 用户退出

Route::get('search', App\Http\Controllers\IndexController::class)->name('search'); //搜索
//待办事项
Route::resource('todolist', App\Http\Controllers\System\TodolistController::class)->only(['index', 'store', 'update', 'destroy']);
//客户信息
Route::resource('companies', App\Http\Controllers\Company\CompanyController::class); //客户资源控制器
Route::post('company/checkdata', [App\Http\Controllers\Company\CompanyUpdateController::class, 'check_company'])->name('company.checkdata'); //检测客户信息是否存在
//客户所属行业
Route::resource('company/business', App\Http\Controllers\Company\BusinessController::class)->only(['index', 'store', 'create', 'edit', 'update', 'destroy']);
Route::put('business/api/update', [App\Http\Controllers\Company\BusinessController::class, 'api_update'])->name('business.api.update'); //更新行业信息API

//客户信息更新
Route::delete('company/tags', [App\Http\Controllers\Company\CompanyUpdateController::class, 'tags_remove'])->name('company.tags.remove'); //移除tag关联
Route::post('company/tags', [App\Http\Controllers\Company\CompanyUpdateController::class, 'tags_add'])->name('company.tags.add'); //增加tag关联
Route::post('company/invalid', [App\Http\Controllers\Company\CompanyUpdateController::class, 'set_invalid'])->name('company.invalid'); //更新客户状态为无效（带说明）
Route::put('company/update/{type}', App\Http\Controllers\Company\CompanyUpdateController::class)->whereAlpha('type')->name('company.update'); //更新客户信息

//跟进记录
Route::post('followup', [App\Http\Controllers\Company\FollowupController::class, 'show'])->name('followup.show'); //显示{id}下的跟进记录
Route::post('followup/store', [App\Http\Controllers\Company\FollowupController::class, 'store'])->name('followup.store'); //增加一条跟进记录
Route::delete('followup', [App\Http\Controllers\Company\FollowupController::class, 'destroy'])->name('followup.destroy'); //软删除跟进记录
Route::put('followup/recycle', [App\Http\Controllers\Company\FollowupController::class, 'recycle'])->name('followup.recycle'); //恢复跟进记录

//预约跟进
Route::post('appointment', [App\Http\Controllers\Company\AppointmentController::class, 'show'])->name('appointment.show'); //显示{id}下的预约记录
Route::post('appointment/store', [App\Http\Controllers\Company\AppointmentController::class, 'store'])->name('appointment.store'); //增加一条预约记录
Route::delete('appointment', [App\Http\Controllers\Company\AppointmentController::class, 'destroy'])->name('appointment.destroy'); //软删除跟进记录
Route::put('appointment/recycle', [App\Http\Controllers\Company\AppointmentController::class, 'recycle'])->name('appointment.recycle'); //恢复预约记录
Route::put('appointment/status', [App\Http\Controllers\Company\AppointmentController::class, 'status'])->name('appointment.status'); //跟进记录状态
Route::get('appointment/tips', [App\Http\Controllers\Company\AppointmentController::class, 'tips'])->name('appointment.tips'); //员工预约记录提醒

//文件处理
Route::post('uploads', App\Http\Controllers\System\UploadController::class)->name('uploads'); //文件上传
//附件处理
Route::get('attachment/downloads/{id}', [App\Http\Controllers\System\AttachmentController::class, 'downloads'])->whereNumber('id')->name('attachment.downloads'); //文件下载
Route::delete('attachment', [App\Http\Controllers\System\AttachmentController::class, 'destroy'])->name('attachment.destroy'); //附件软删除

//联系人处理
Route::resource('contact', App\Http\Controllers\Company\ContactController::class)->only(['edit', 'destroy', 'update', 'create', 'store']); //联系人资源控制器
//标签管理 
Route::resource('tags', App\Http\Controllers\System\TagController::class)->only(['edit', 'destroy', 'update', 'index']); //标签资源控制器
