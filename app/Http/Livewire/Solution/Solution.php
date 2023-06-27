<?php

namespace App\Http\Livewire\Solution;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Solution extends Component
{
    public $sid;
    public $solution;
    public $detail;   // 正在展示的评测点
    public int $numAccepted, $numDetails; // 测试点计数
    public bool $only_details = false;
    public  $msg; // 可能的报错信息，例如权限不足,默认null

    protected $listeners = ['set_id'];

    public function mount($id = null, $only_details = false)
    {
        $this->only_details = $only_details;
        $this->set_id($id);
    }

    // 重设solution id
    public function set_id($id)
    {
        $this->sid = $id;
        $this->solution = null;
        $this->detail = null;
        $this->msg = null;
        $this->refresh();   // 刷新结果
    }

    // 根据solution id刷新
    public function refresh()
    {
        if ($this->sid == null)
            return;

        // 判断权限
        /** @var App/Model/User */
        $user = Auth::user();
        if ($user == null || !$user->can_view_solution($this->sid)) {
            $this->msg = __('sentence.Permission denied');
            return;
        }

        // 读取数据库中 所有测试数据的详细结果 {'testname':{'result':int, ...}, ...}
        $this->solution = DB::table('solutions')
            ->select($this->only_details ? ['id', 'result', 'error_info', 'wrong_data', 'judge_result', 'user_id', 'pass_rate'] : ['*'])
            ->find($this->sid);
        if ($this->solution ?? false) {
            // ========================= 先查询所在竞赛的必要信息 ==========================
            if ($this->solution->contest_id ?? false) {
                $contest = DB::table('contests as c')
                    ->join('contest_problems as cp', 'c.id', 'cp.contest_id')
                    ->select(['c.end_time', 'cp.index'])
                    ->where('c.id', $this->solution->contest_id)
                    ->where('cp.problem_id', $this->solution->problem_id)
                    ->first();
                if ($contest) {
                    $this->solution->index = $contest->index; // 记下该代码在竞赛中的题号
                    $this->solution->end_time = $contest->end_time; // 记下所在竞赛的结束时间
                } else
                    $this->solution->contest_id = -1; // 这条solution以前是竞赛中的，但题目现在被从竞赛中删除了
            }
            // 转为数组，前端读取
            $this->solution = json_decode(json_encode($this->solution), true);

            $this->solution['username'] = DB::table('users')->find($this->solution['user_id'])->username ?? null;

            $this->solution['judge_result'] = $this->process_details($this->solution['judge_result']);
            // 刷新测试点通过数量
            $this->numDetails = count($this->solution['judge_result']);
            $this->numAccepted = 0;
            foreach ($this->solution['judge_result'] ?? [] as $d) {
                if ($d['result'] == 4) $this->numAccepted++;
            }
            // 展示详情点
            $this->display_detail();
        }
    }

    // json数据进行预处理，返回数组
    private function process_details(string $judge_result = null)
    {
        $judge_result = json_decode($judge_result ?? '[]', true);
        foreach ($judge_result as $k => &$test) {
            $judge_result[$k]['result_desc'] = trans('result.' . config("judge.result." . $test['result'] ?? 0));
            if (!isset($judge_result[$k]['testname']))
                $judge_result[$k]['testname'] = $k; // 记下测试名，用于排序
        }
        uasort($judge_result, function ($a, $b) {
            return $a['testname'] < $b['testname'] ? -1 : 1; // 按测试名升序
        });
        return array_values($judge_result); // 转为数组
    }

    // 点击某一个detail时触发，将显示当前detail的错误细节
    public function display_detail(int $index = null)
    {
        if ($index === null)
            $index = $this->detail['index'] ?? null; // 默认为上次的detail

        // 如果上次detail存在 且 有更新值，则重新获取；否则，置空
        if ($index !== null && ($this->solution['judge_result'][$index] ?? false)) {
            $this->detail = $this->solution['judge_result'][$index];
            $this->detail['index'] = $index;
        } else {
            $this->detail = null;
        }
    }

    public function render()
    {
        if ($this->msg != null)
            return view('message', ['msg' => $this->msg]);
        if ($this->only_details)
            return view('livewire.solution.solution');
        return view('livewire.solution.solution')->extends('layouts.client')->section('content');
    }
}
