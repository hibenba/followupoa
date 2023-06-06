<?php

namespace App\Http\Controllers;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Http/Controllers/DataConversionController.php
 * Created Time: 2023-03-16 10:18:34
 * Last Edit Time: 2023-04-02 12:12:17
 * Description: 数据转换控制器
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Company\Company; //客户Model
use App\Models\Company\Feature; //产品特性
use App\Models\Company\Contact; //联系人主体
use App\Models\Company\ContactsMap; //联系人主体
use Illuminate\Support\Str; //字符处理
use Carbon\Carbon; //时间包
class DataConversionController extends Controller
{

    public function __invoke(Request $request)
    {
        $this->data['nextpage'] = $request->page + 1; //下一页
        $customers = DB::table('customer_old')->paginate(1000); //分页显示客户信息
        foreach ($customers as $customer) {
            if (empty(Company::find($customer->id))) {
                $deleted_at = null; //重复的放到回收站
                //检测重复
                $unique = Company::where('company_name', $customer->companyname)->first();
                if (!empty($unique->id)) {
                    $customer->companyname = $customer->companyname . '_repeat_' . $customer->id;
                    $deleted_at = now();
                }
                $unique = Company::where('url', $customer->url)->first();
                if (!empty($unique->id)) {
                    $customer->url = $customer->url . '_repeat_' . $customer->id;
                    $deleted_at = now();
                }
                //写入到数据库
                DB::table('companies')->insert([
                    'id' => $customer->id,
                    'upid' => 0,
                    'company_name' => $customer->companyname,
                    'url' => $customer->url,
                    'ceo' => '',
                    'staff_id' => $customer->uid,
                    'staff_name' => $customer->creator,
                    'username' => $customer->username,
                    'created_at' => Carbon::createFromTimestamp($customer->dateline)->toDateTimeString(),
                    'updated_at' => Carbon::createFromTimestamp($customer->updatetime)->toDateTimeString(),
                    'deleted_at' => $deleted_at,
                    'track_at' => $customer->followuptime ? Carbon::createFromTimestamp($customer->followuptime)->toDateTimeString() : null,
                    'ip' => $customer->ip,
                    'is_vip' => $customer->is_vip,
                    'is_contact_ok' => $customer->is_contact_ok, //是否完全联系人
                    'is_interactive' => $customer->is_interactive, //是否曾经互动过
                    'country' => $customer->country,
                    'state' => $customer->state,
                    'is_online' => $customer->online, //线上/线下
                    'customer_grade' => $customer->customergrade, //客户评级
                    'special_grade' => $customer->othergrade, //特别评级
                    'customer_type' => $customer->customertype, //客户类型
                    'cooperate' => $customer->cooperate, //合作关系
                    'mobile_brand' => $customer->mobilebrand,
                    'data_source' => $customer->datasource,
                    'products' => $this->decode_content($customer->product),
                    'sale_brands' => $this->decode_content($customer->salebrands),
                    'international_brands' => $this->decode_content($customer->internationalbrands),
                    'description' => $this->decode_content($customer->remarks),
                    'introduction' => $this->decode_content($customer->introduction),
                    'telephone' => $customer->telephone,
                    'address' => $customer->address,
                    'contact_url' => $customer->onlinecontact, //在线联系地址
                    'linkedin' => $customer->linkedin,
                    'facebook' => $customer->facebook,
                    'twitter' => $customer->twitter,
                    'instagram' => $customer->instagram,
                    'invalid' => $this->decode_content($customer->invalid),
                    'status' => $customer->folder == 3 ? 1 : 0
                ]);
                //产品特性(多选)
                if ($customer->features > 0) {
                    $features = $this->custom_key('features');
                    Feature::create([
                        'company_id' => $customer->id,
                        'feature' => $customer->features,
                        'description' => $features[$customer->features] ?? '' //文字
                    ]);
                }

                //客户负责人联系方式
                $this->contact_conversion($customer->id, $customer->creator);
            }
        }
        return $this->view('data.conversion');
    }


    //客户联系方式处理
    private function contact_conversion($id, $staff_name)
    {
        $contacts = DB::table('contact_old')->where('id', $id)->get(); //联系人信息
        foreach ($contacts as $ct) {
            //联系人主体
            $contact = $this->contact(
                $id,
                $ct->job,
                $staff_name,
                $ct->ismain,
                '',
                $ct->fullname,
                $ct->note,
                $ct->ip,
                $ct->status - 1 //联系人状态，1正常、2离职、3联系不上。	新 状态(0在职，1离职，2联系不上)	
            );
            //*********** 联系方式处理 ******************//

            //邮箱处理
            if (Str::length($ct->email) > 2) {
                $this->contacts_map_arr(
                    $contact->id,
                    'email',
                    $ct->email,
                    $staff_name,
                    $ct->mailower ? 1 : 0,
                    $ct->localemail, //本地联系邮箱
                    '',
                    $ct->folder ? 1 : 0,
                    $ct->mailreason
                );
            }

            //whatsapp
            if (Str::length($ct->whatsapp) > 2) {
                $this->contacts_map_arr(
                    $contact->id,
                    'whatsapp',
                    $ct->whatsapp,
                    $staff_name,
                    2, //未知
                    $ct->whatsphone, //本地联系手机
                    '',
                    $ct->folder ? 1 : 0,
                    '' //无效原因
                );
            }

            //wechat
            if (Str::length($ct->wechat) > 2) {
                $this->contacts_map_arr(
                    $contact->id,
                    'wechat',
                    $ct->wechat,
                    $staff_name,
                    2, //未知
                    $ct->wechatphone, //本地联系手机
                    '',
                    $ct->folder ? 1 : 0,
                    '' //无效原因
                );
            }

            //skype
            if (Str::length($ct->skype) > 2) {
                $this->contacts_map_arr(
                    $contact->id,
                    'skype',
                    $ct->skype,
                    $staff_name,
                    2, //未知
                    $ct->skypephone, //本地联系手机
                    '',
                    $ct->folder ? 1 : 0,
                    '' //无效原因
                );
            }

            //messaging
            if (Str::length($ct->messaging) > 2) {
                $this->contacts_map_arr(
                    $contact->id,
                    'messaging',
                    $ct->messaging,
                    $staff_name,
                    2, //未知
                    $ct->messagephone, //本地联系手机
                    '',
                    $ct->folder ? 1 : 0,
                    '' //无效原因
                );
            }

            //instagram
            if (Str::length($ct->instagram) > 2) {
                $this->contacts_map_arr(
                    $contact->id,
                    'instagram',
                    $ct->instagram,
                    $staff_name,
                    2, //未知
                    $ct->instagramphone, //本地联系手机
                    '',
                    $ct->folder ? 1 : 0,
                    '' //无效原因
                );
            }

            //facebook
            if (Str::length($ct->facebook) > 2) {
                $this->contacts_map_arr(
                    $contact->id,
                    'facebook',
                    $ct->facebook,
                    $staff_name,
                    2, //未知
                    $ct->facebookphone, //本地联系手机
                    '',
                    $ct->folder ? 1 : 0,
                    '' //无效原因
                );
            }
            //twitter
            if (Str::length($ct->twitter) > 2) {
                $this->contacts_map_arr(
                    $contact->id,
                    'twitter',
                    $ct->twitter,
                    $staff_name,
                    2, //未知
                    $ct->twitterphone, //本地联系手机
                    '',
                    $ct->folder ? 1 : 0,
                    '' //无效原因
                );
            }

            //phone
            if (Str::length($ct->phone) > 2) {
                $this->contacts_map_arr(
                    $contact->id,
                    'phone',
                    $ct->phone,
                    $staff_name,
                    2, //未知
                    '', //本地联系手机
                    '',
                    $ct->folder ? 1 : 0,
                    '' //无效原因
                );
            }

            //mobilephone
            if (Str::length($ct->mobilephone) > 2) {
                $this->contacts_map_arr(
                    $contact->id,
                    'mobilephone',
                    $ct->mobilephone,
                    $staff_name,
                    2, //未知
                    '', //本地联系手机
                    '',
                    $ct->folder ? 1 : 0,
                    '' //无效原因
                );
            }
        }
    }

    //写入主体数组
    private function contact($id, $job, $staff_name, $is_main, $last_name, $name, $description, $ip, $status)
    {
        return Contact::create([
            'company_id' => $id,
            'job' => $this->decode_content($job),
            'staff_name' => $staff_name,
            'is_main' => $is_main ? 1 : 0, //是否重要联系人(0否，1是)
            'last_name' => $this->decode_content($last_name),
            'name' => $this->decode_content($name),
            'description' => $this->decode_content($description),
            'ip' => $ip,
            'status' => $status  //状态(0在职，1离职，2联系不上)	
        ]);
    }

    //写入联系方式
    private function contacts_map_arr($id, $type, $contact, $staff_name, $owner, $loacl_contact, $description, $status, $invalid_reasons)
    {
        return ContactsMap::create([
            'contact_id' => $id, //联系人主体ID
            'contact_type' => $type,
            'contact' => $this->decode_content($contact),
            'staff_name' => $staff_name,
            'owner' => $owner, //联系方式拥有者，0否，1是，2未知
            'loacl_contact' => $this->decode_content($loacl_contact), //本地使用哪个邮箱、手机等
            'description' => $this->decode_content($description),
            'status' => $status, //状态(0有效,1无效)
            'invalid_reasons' => $this->decode_content($invalid_reasons),
        ]);
    }
    //转换内容处理
    private function decode_content($str)
    {
        return html_entity_decode(stripslashes(trim(str_replace([PHP_EOL, '\n', '\t', '\r'], '', $str))));
    }
}
