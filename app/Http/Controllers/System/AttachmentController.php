<?php

namespace App\Http\Controllers\System;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Http/Controllers/System/AttachmentController.php
 * Created Time: 2022-09-23 10:57:18
 * Last Edit Time: 2023-05-30 19:14:31
 * Description: 附件控制器
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\System\Attachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; //文件
class AttachmentController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->has('type') && $request->type == 'trash') {
            $this->data['attachments'] = Attachment::onlyTrashed()->orderByDesc('deleted_at')->paginate($this->perpage);
        } else {
            $this->data['attachments'] = Attachment::orderByDesc('created_at')->paginate($this->perpage);
        }
        $this->data['count'] = Attachment::count();
        $this->data['trashcount'] = Attachment::onlyTrashed()->count();


        foreach ($this->data['attachments'] as $item) {
            $item->size =  $this->format_size($item->file_size);
        }

        return $this->view('tool.attachments');
    }

    //文件下载
    public function downloads($id)
    {
        $attach = Attachment::find($id);
        abort_if(empty($attach) || !Storage::exists($attach->file_path) || (Auth::id() != $attach->company->staff_id && !Auth::user()->isAdmin()), 404);
        return Storage::download($attach->file_path, empty($attach->file_name) ? null : $attach->file_name);
    }
    //附件软删除(回收站)
    public function destroy(Request $request)
    {
        try {
            $attach = Attachment::find($request->id);
            throw_unless($attach, new \ErrorException('附件不存在~'));
            //权限验证
            if (Auth::id() === $attach->staff_id || Auth::user()->isAdmin()) {
                //软删除
                if (!$attach->delete()) {
                    throw new \ErrorException('删除失败~');
                }
                $this->result['status'] = 'success';
            } else {
                throw new \ErrorException('没有删除权限~');
            }
        } catch (\Throwable $th) {
            $this->result['msg'] = $th->getMessage();
        }
        return $this->api_response();
    }

    //移出回收站
    public function recycle($id)
    {
        Attachment::withTrashed()->find($id)->restore();
        return back();
    }

    //附件真实删除
    public function delete($id)
    {
        $attachment = Attachment::withTrashed()->find($id);
        if ($attachment->trashed()) {
            if (Storage::delete($attachment->file_path)) {
                $attachment->forceDelete(); //删除附件表关联内容
            }
        }
        return back();
    }
}
