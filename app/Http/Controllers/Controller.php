<?php

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Http/Controllers/Controller.php
 * Created Time: 2023-02-28 02:37:48
 * Last Edit Time: 2023-06-06 09:29:28
 * Description: 主控制器(供所有子控制器)
 */

namespace App\Http\Controllers;

//use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
//use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\Tag\Tag;
use App\Models\Tag\TagMap;
use Illuminate\Support\Facades\Cache; //使用缓存

class Controller extends BaseController
{
    // use AuthorizesRequests, DispatchesJobs, ValidatesRequests;//不是每个控制器都需要这些请求，按需要调用
    protected $data; //返回数据
    protected $app; //网站配置
    protected $id; //模块 ID
    protected $duration = 0; //缓存时间
    protected $page = 1; //页码
    protected $perpage = 30; //页码
    protected $result = ['status' => 'error', 'msg' => '', 'data' => []]; //response 返回统一的json
    protected $appends = []; //分页额外参数
    //初始化类
    public function __construct()
    {
        $this->app = $this->settings(); //获取到网站配置信息
        $this->data = []; //初始化
    }
    //返回视图
    protected function view(string $view)
    {
        $this->data['user'] = Auth::user(); //追加网站配置给模板使用
        $this->data['active'] = $view; //菜单激活CSS样式
        //可用颜色项
        $this->data['colors'] = ['success', 'info', 'primary', 'purple', 'warning', 'gray', 'secondary', 'navy', 'lightblue', 'indigo', 'orange', 'maroon', 'pink', 'fuchsia', 'olive', 'danger'];
        $this->data['settings'] = $this->app;
        return view($view, $this->data); //将数据交给视图处理
    }
    //将字符串里的斜杠、换行、符号、空格、HTML等去掉
    public function format_string(string $string)
    {
        return trim(str_ireplace([PHP_EOL, '&nbsp;', "\r", "\n", "\t", '\'', '"', ' ', '　'], '', stripslashes(strip_tags($string))));
    }

    //去特殊字符
    public function str_filter($str)
    {
        $rex = ['`', '·', '~', '!', '！', '@', '#', '$', '￥', '%', '^', '……', '&', '*', '(', ')', '（', '）', '—', '+', '=', '|', "\\", '[', ']', '【', '】', '{', '}', ';', '；', ':', '：', '“', '”', ',', '，', '<', '>', '《', '》', '.', '。', '/', '、', '?', '？'];
        return str_replace($rex, '', $this->format_string($str));
    }

    // 通过$key 从缓存/数据表settings返回配置值
    public function settings($key = null)
    {
        static $settings; //静态变量 将$settings常驻内存
        if (is_null($settings)) {
            $settings = \Illuminate\Support\Facades\Cache::rememberForever('forever:settings', function () {
                return \Illuminate\Support\Arr::pluck(\App\Models\System\Setting::all('key', 'value'), 'value', 'key');
            });
        }
        return is_null($key) ? $settings : ((is_array($key)) ? \Illuminate\Support\Arr::only($settings, $key) : $settings[$key]);    //$key 传数组则返回多个 key->value
    }

    // 从custom_keys表获取并缓存自定的值
    public function custom_key(string $key = '')
    {
        static $datas;
        if (is_null($datas)) {
            $datas = \Illuminate\Support\Facades\Cache::rememberForever('forever:custom_keys', function () {
                $values = [];
                $custom_key = \App\Models\System\CustomKey::all();
                foreach ($custom_key as $value) {
                    $values[$value->key] = $value->value;
                }
                return $values;
            });
        }
        //不指定$key则返回所有字段
        return empty($key) ? $datas : $datas[$key] ?? [];
    }
    //人性化显示数字
    public function nice_number($n)
    {
        $n = intval($n);
        if ($n >= 100000000) {
            return round(($n / 100000000), 1) . '亿';
        } elseif ($n >= 10000) {
            return round(($n / 10000), 1) . 'w';
        } elseif ($n >= 1000) {
            return round(($n / 1000), 1) . 'k';
        }
        return number_format($n);
    }
    //格式化大小函数,根据字节数自动显示成'KB','MB'等等
    protected function format_size($bytes, $decimals = 2)
    {
        $factor = floor((strlen($bytes) - 1) / 3);
        if ($factor > 0) $sz = 'KMGT';
        return str_replace('.' . str_repeat('0', $decimals), '', sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ($sz[$factor - 1] ?? '') . 'B');
    }

    //顶部菜单选项工厂
    protected function table_factory($count, $name, $font)
    {
        return ['name' => $name, 'font' => '<i class="fas fa-' . $font . '"></i>', 'count' => $count];
    }

    //字段累加
    protected function increment($model, $id, $field)
    {
        $item = $model::find($id);
        if ($item) {
            $item->increment($field);
            return $item->$field;
        }
        return 0;
    }

    //处理Slug
    protected function slug($slug, $model)
    {
        $slug = \Illuminate\Support\Str::slug($slug);
        if (is_numeric($slug)) $slug = 'slug-' . $slug;
        return empty($slug) ? null : (($model::where('slug', $slug)->get('slug'))->isEmpty() //从$model里查询是否已存在
            ? $slug //不存在重复
            : $slug . '-' . mt_rand(1, 9999999)); //附加随机数
    }

    //写入$id关联的$Tags
    protected function tags_insert(string $tags, int $id, string $taggable)
    {
        TagMap::where('taggable_id', $id)->where('taggable_type', $taggable)->delete(); //删除关联
        if (empty($tags)) return; //空Tags/id
        array_map(function ($tag) use ($id, $taggable) {
            $tag = $this->str_filter($tag); //过滤特殊字符
            if ($tag) {
                $tag = Tag::firstOrCreate(['name' => $tag]); //检索或创建模型
                $query_array = ['tag_id' => $tag->id, 'taggable_id' => $id, 'taggable_type' => $taggable]; //查询数组
                if ((TagMap::where($query_array)->get('tag_id'))->isEmpty()) {
                    TagMap::create($query_array); //通过tag_maps表关联起来
                }
            }
        }, explode(',', str_replace(['，', ' ', '　', '   '], ',', $tags))); //格式化$tags字符串为数组
    }
    //国家列表
    public function countries()
    {
        return Cache::remember('sys_countries', $this->duration, function () {
            $countries = [];
            foreach (\App\Models\System\Country::all() as $country) {
                $country->state = json_decode($country->state, true);
                $countries[$country->id] = $country->toArray();
            }
            return $countries; //按ID返回
        });
    }
    //API 返回标准格式
    public function api_response($http_code = 200)
    {
        return response()->json($this->result, $http_code);
    }
}
