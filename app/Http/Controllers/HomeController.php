<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helpers\CacheHelper;
use App\Http\Helpers\ProblemHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $notices = DB::table('notices')
            ->leftJoin('users', 'users.id', '=', 'user_id')
            ->select(['notices.id', 'title', 'state', 'notices.created_at', 'username'])
            ->where('state', '>', 0)
            ->orderByDesc('state')
            ->orderByDesc('id')->paginate(6);

        // 获取周一时间. 如果今天是周一则为今天，否则为最近一次周一
        $monday_time = (date('w') == 1 ? strtotime('today') : strtotime('last monday'));
        $last_monday_time = $monday_time - 3600 * 24 * 7;
        $next_monday_time = $monday_time + 3600 * 24 * 7;

        $rk = 'home:cache:this_week_top10';
        CacheHelper::has_key_with_autoclear_if_rejudged($rk);
        $this_week = Cache::remember($rk, 3600, function () use ($monday_time) {
            $this_week = DB::table('solutions')
                ->join('users', 'users.id', '=', 'solutions.user_id')
                ->select(['user_id', 'username', 'school', 'class', 'nick', DB::raw('count(distinct problem_id) as solved'),])
                ->where('submit_time', '>', date('Y-m-d H:i:s', $monday_time))
                ->where('result', 4)
                ->groupBy(['user_id'])
                ->orderByDesc('solved')
                ->limit(10)->get();
            return $this_week; // 缓存有效期1小时
        });

        $rk = 'home:cache:last_week_top10';
        CacheHelper::has_key_with_autoclear_if_rejudged($rk);
        $last_week = Cache::remember(
            $rk,
            $next_monday_time - time(),
            function () use ($monday_time, $last_monday_time) {
                $last_week = DB::table('solutions')
                    ->join('users', 'users.id', '=', 'solutions.user_id')
                    ->select(['user_id', 'username', 'school', 'class', 'nick', DB::raw('count(distinct problem_id) as solved')])
                    ->where('submit_time', '>', date('Y-m-d H:i:s', $last_monday_time))
                    ->where('submit_time', '<', date('Y-m-d H:i:s', $monday_time))
                    ->where('result', 4)
                    ->groupBy(['user_id'])
                    ->orderByDesc('solved')
                    ->limit(10)->get();
                return $last_week; // 缓存有效至周日晚24:00
            }
        );

        return view('home', compact('notices', 'this_week', 'last_week'));
    }
}
