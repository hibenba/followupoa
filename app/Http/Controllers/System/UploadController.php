<?php

namespace App\Http\Controllers\System;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Http/Controllers/System/UploadController.php
 * Created Time: 2022-09-23 10:57:18
 * Last Edit Time: 2023-03-31 11:13:48
 * Description: 文件上传控制器
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Models\System\Attachment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class UploadController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            if ($request->hasFile('file')) {
                $company = Company::find($request->id);
                throw_unless($company, new \ErrorException('客户不存在~'));
                $file = $request->file('file');
                $ext = $file->extension(); //文件真实mime扩展名(可能会和客户端不一样)
                $originalName = $file->getClientOriginalName(); //原始文件名
                $fileName = Str::lower($file->hashName()); //生成一个唯一的随机名称...(小写)
                $path = 'public' . DIRECTORY_SEPARATOR . $company->id; //保存目录
                $filePath = $request->file->storeAs($path, $fileName);
                if ($filePath) {
                    $this->result['data'] = Attachment::create([
                        'company_id' => $company->id,
                        'staff_id' => Auth::id(),
                        'staff_name' => Auth::user()->name,
                        'username' => Auth::user()->username,
                        'file_path' => $filePath,
                        'file_type' => $ext,
                        'file_size' => $file->getSize(),
                        'file_name' => $originalName,
                        'ip' => $request->ip(),
                        'description' => '',
                        'is_image' => substr($file->getMimeType(), 0, 5) == 'image' ? 1 : 0,
                    ]);
                    $this->result['status'] = 'success';
                }
            } else {
                throw new \ErrorException('未检测到上传文件信息~');
            }
        } catch (\Throwable $th) {
            $this->result['msg'] = $th->getMessage();
        }
        return $this->api_response();
    }
}
