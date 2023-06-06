<?php

namespace App\Http\Controllers\System;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Http/Controllers/System/CountryController.php
 * Created Time: 2022-09-23 10:57:18
 * Last Edit Time: 2023-05-23 18:24:53
 * Description: 国家地区管理
 */

use App\Http\Controllers\Controller;
use App\Models\System\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CountryController extends Controller
{
    //列表
    public function index()
    {
        $this->data['countries'] = Country::all();
        return $this->view('system.country');
    }

    /**
     * 新增行业表单
     */
    public function create(Request $request)
    {
        $this->data['title'] = '新增国家/地区';
        $this->data['note'] = '请输入正确的国家/地区信息。';
        $this->data['timeoffset'] = $this->timeoffset();
        $this->data['daylight'] = 0;
        $this->data['state'] = json_encode(['']);
        $this->data['action'] = route('admin.country.store'); //请求路由
        return $this->view('system.country_create');
    }
    /**
     * 接收新增
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2|max:255',
            'abbr' => 'required|alpha_dash',
        ], [], ['name' => '名称',  'abbr' => '英文缩写']);
        $country = new Country;
        $country->name = $request->name;
        $country->abbr = Str::of($request->abbr)->upper();
        $country->pinyin = Str::of($request->pinyin)->upper()->limit(1, '');
        $country->area_code = $request->area_code ?? 0;
        $country->timezone = $request->timezone ?? 0;
        $country->daylight = $request->daylight ?? 0;
        $cities = [];
        foreach ($request->cities as $item => $value) {
            if (!empty($value)) {
                $cities[] = $value;
            }
        }
        $country->state = json_encode($cities, JSON_UNESCAPED_UNICODE);
        $country->save();
        return redirect()->route('admin.country.index');
    }


    /**
     * 显示行业编辑表单
     */
    public function edit(string $id)
    {
        $this->data = Country::find($id);
        $this->data['title'] = '编辑《' . $this->data->name . '》';
        $this->data['note'] = '请输入正确的国家/地区信息。';
        $this->data['timeoffset'] = $this->timeoffset();
        $this->data['action'] = route('admin.country.update', ['country' => $this->data->id]); //请求路由
        return $this->view('system.country_create');
    }

    /**
     * 接收行业修改信息
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|min:2|max:255',
            'abbr' => 'required|alpha_dash',
        ], [], ['name' => '名称',  'abbr' => '英文缩写']);
        $country = Country::find($id);
        abort_if(empty($country), 404);
        $country->name = $request->name;
        $country->abbr = Str::of($request->abbr)->upper();
        $country->pinyin = Str::of($request->pinyin)->upper()->limit(1, '');
        $country->area_code = $request->area_code ?? 0;
        $country->timezone = $request->timezone ?? 0;
        $country->daylight = $request->daylight ?? 0;
        $cities = [];
        foreach ($request->cities as $item => $value) {
            if (!empty($value)) {
                $cities[] = $value;
            }
        }
        $country->state = json_encode($cities, JSON_UNESCAPED_UNICODE);
        $country->save();
        return redirect()->route('admin.country.index');
    }

    /**
     * 删除行业
     */
    public function destroy($id)
    {
        $country = Country::find($id);
        abort_if(empty($country), 404);
        $country->delete(); //删除字段
        return redirect()->route('admin.country.index');
    }
    //时区列表
    private function timeoffset()
    {
        return [
            '-12' => '(GMT -12:00) Eniwetok, Kwajalein',
            '-11' => '(GMT -11:00) Midway Island, Samoa',
            '-10' => '(GMT -10:00) Hawaii',
            '-9' => '(GMT -09:00) Alaska',
            '-8' => '(GMT -08:00) Pacific Time',
            '-7' => '(GMT -07:00) Mountain Time',
            '-6' => '(GMT -06:00) Central Time',
            '-5' => '(GMT -05:00) Eastern Time',
            '-4' => '(GMT -04:00) Atlantic Time',
            '-3.5' => '(GMT -03:30) Newfoundland',
            '-3' => '(GMT -03:00) Brassila, Buenos Aires',
            '-2' => '(GMT -02:00) Mid-Atlantic,Ascension',
            '-1' => '(GMT -01:00) Azores,Cape Verde',
            '0' => '(GMT) Casablanca,Dublin',
            '1' => '(GMT +01:00) Amsterdam,Berlin',
            '2' => '(GMT +02:00) Cairo,Helsinki',
            '3' => '(GMT +03:00) Baghdad,Riyadh',
            '3.5' => '(GMT +03:30) Tehran',
            '4' => '(GMT +04:00) Abu Dhabi, Baku',
            '4.5' => '(GMT +04:30) Kabul',
            '5' => '(GMT +05:00) Ekaterinburg,Islamabad',
            '5.5' => '(GMT +05:30) Bombay,Calcutta',
            '5.75' => '(GMT +05:45) Katmandu',
            '6' => '(GMT +06:00) Almaty,Colombo',
            '6.5' => '(GMT +06:30) Rangoon',
            '7' => '(GMT +07:00) Bangkok,Hanoi,Jakarta',
            '8' => '(GMT +08:00) 中国、北京、上海',
            '9' => '(GMT +09:00) Osaka,Sapporo',
            '9.5' => '(GMT +09:30) Adelaide,Darwin',
            '10' => '(GMT +10:00) Canberra,Guam',
            '11' => '(GMT +11:00) Magadan,Solomon',
            '12' => '(GMT +12:00) Auckland,Wellington'
        ];
    }
}
