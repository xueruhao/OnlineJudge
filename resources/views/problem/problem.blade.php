@extends('layouts.client')

@if (isset($contest))
  @section('title', sprintf('%s %s | %s %s', __('main.Problem'), index2ch($problem->index), __('main.Contest'),
    $contest->id))
  @else
  @section('title', trans('main.Problem') . ' ' . $problem->id)
@endif

@section('content')
  <style>
    /* 大屏幕分栏 */
    @media screen and (min-width: 768px) {
      body {
        overflow-y: hidden;
      }

      #container {
        width: 100%;
        height: 93vh;
        margin-top: -1rem;
        display: flex;
        flex-wrap: nowrap;
        align-items: stretch;
        background-color: white;
        position: relative;
      }

      #left {
        width: calc(100% - 4px);
        overflow: auto;
        /* background-color: blue; */
      }

      #resize {
        width: 4px;
        height: 100vh;
        cursor: ew-resize;
      }

      #resize:hover {
        background-color: rgb(255, 238, 0);
      }

      #right {
        width: 100%;
        overflow: auto;
        /* height: 100vh;  */
        /* background-color:green; */
      }
    }
  </style>

  <div id="container">
    <div id="left">
      {{-- 竞赛下，显示菜单 --}}
      @if (isset($contest))
        <div class="mt-3">
          <x-contest.navbar :contest="$contest" :group-id="request('group') ?? null" />
        </div>

        {{-- 题号链接 --}}
        <x-contest.problems-link :contest-id="$contest->id" :problem-index="$problem->index" :group-id="request('group') ?? null" />
      @endif

      {{-- 题目内容 --}}
      <div class="p-3 border-bottom">
        <h4 class="text-center">
          {{ isset($contest) ? index2ch($problem->index) : $problem->id }}. {{ $problem->title }}

          {{-- 非竞赛&&题目未公开，则提示 --}}
          @if (!isset($contest) && $problem->hidden == 1)
            <span class="m-2" style="font-size: 0.9rem; vertical-align: top;">
              <i class="fa fa-eye-slash mr-1" aria-hidden="true"></i>
              <span class="text-gray">{{ trans('main.Hidden') }}</span>
            </span>
          @endif

          {{-- 该题提交记录连接 --}}
          @if (isset($contest))
            <span style="font-size: 0.85rem">
              [ <a
                href="{{ route('contest.solutions', [$contest->id, 'group' => request('group') ?? null, 'index' => $problem->index]) }}">{{ __('main.Solutions') }}</a>
              ]
            </span>
          @else
            <span style="font-size: 0.85rem">
              [ <a href="{{ route('solutions', ['pid' => $problem->id]) }}">{{ __('main.Solutions') }}</a> ]
            </span>
          @endif

          {{-- 原题连接 --}}
          @if (isset($contest) &&
                  ((Auth::check() && Auth::user()->can('admin.problem.view')) || $contest->end_time < date('Y-m-d H:i:s')))
            <span style="font-size: 0.85rem">
              [
              <a href="{{ route('problem', $problem->id) }}" target="_blank">{{ __('main.Problem') }}
                {{ $problem->id }}</a>
              <i class="fa fa-external-link text-sky" aria-hidden="true"></i>
              ]
            </span>
          @endif

          {{-- 编辑链接 --}}
          @if (Auth::check() && Auth::user()->can('admin.problem.update'))
            <span style="font-size: 0.85rem">
              [ <a href="{{ route('admin.problem.update', $problem->id) }}" target="_blank">{{ __('main.Edit') }}</a> ]
              [ <a href="{{ route('admin.problem.test_data', ['pid' => $problem->id]) }}"
                target="_blank">{{ __('main.Test Data') }}</a> ]
            </span>
          @endif
        </h4>
        <hr>

        {{-- 题目基本信息 --}}
        <div class=" alert alert-info p-2 mb-2 d-flex flex-wrap" style="font-size: 0.9rem">
          <div style="min-width: 300px">{{ __('main.Time Limit') }}: {{ $problem->time_limit }}MS</div>
          <div style="min-width: 300px">{{ __('main.Memory Limit') }}: {{ $problem->memory_limit }}MB</div>
          <div style="min-width: 300px">{{ __('main.Result Judgement') }}:
            @if ($problem->spj == 1)
              <span class="text-red">
                {{ __('main.Special Judge') }}
              </span>
            @else
              {{ __('main.Text Comparison') }}
            @endif
          </div>
          <div style="min-width: 300px">
            {{ __('main.Accepted') }}/{{ __('main.Submitted') }}:
            {{ $problem->accepted }}
            (<i class="fa fa-user-o text-sky" aria-hidden="true" style="padding:0 1px"></i>{{ $problem->solved }})
            /
            {{ $problem->submitted }}
          </div>
          @if (!isset($contest) || time() > strtotime($contest->end_time))
            <div style="min-width: 300px">{{ __('main.Official Tags') }}:
              @foreach ($problem->tags as $item)
                <span class="mx-2">
                  <i class="fa fa-tag" aria-hidden="true"></i>
                  <span class="text-nowrap">{{ $item }}</span>
                </span>
              @endforeach
            </div>
            <div style="min-width: 300px">{{ __('main.Users Marks') }}:
              @foreach ($tags as $item)
                <span class="mx-2">
                  <i class="fa fa-tag" aria-hidden="true"></i>
                  <span class="text-nowrap">{{ $item['name'] }}
                    (<i class="fa fa-user-o" aria-hidden="true"></i>{{ $item['count'] }})
                  </span>
                </span>
              @endforeach
            </div>
          @endif
        </div>

        {{-- 题目内容 --}}
        <div class="math_formula">
          <h4 class="text-sky">{{ __('main.PDescription') }}</h4>
          <div class="ck-content">{!! $problem->description !!}</div>

          @if ($problem->input != null)
            <h4 class="mt-2 text-sky">{{ __('main.IDescription') }}</h4>
            <div class="ck-content">{!! $problem->input !!}</div>
          @endif

          @if ($problem->output != null)
            <h4 class="mt-2 text-sky">{{ __('main.ODescription') }}</h4>
            <div class="ck-content">{!! $problem->output !!}</div>
          @endif

          @if (!empty($samples))
            <h4 class="my-2 text-sky">{{ __('main.Samples') }}</h4>
            {{-- <div class="alert alert-info p-2 mb-0">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <span>{{ trans('sentence.explain_sample') }}</span>
            </div> --}}
          @endif
          @foreach ($samples as $i => $sam)
            <div class="border my-2 not_math">
              {{-- 样例输入 --}}
              <div class="border-bottom pl-2 bg-light">
                {{ __('main.Input') }}
                <a href="javascript:" onclick="copy_text($('#sam_in{{ $i }}'))">{{ __('main.Copy') }}</a>
              </div>
              <pre class="m-1" id="sam_in{{ $i }}">{{ $sam['in'] }}</pre>
              {{-- 样例输出 --}}
              <div class="border-top border-bottom pl-2 bg-light">
                {{ __('main.Output') }}
                <a href="javascript:" onclick="copy_text($('#sam_out{{ $i }}'))">{{ __('main.Copy') }}</a>
              </div>
              <pre class="m-1" id="sam_out{{ $i }}">{{ $sam['out'] }}</pre>
            </div>
          @endforeach

          @if ($problem->hint != null)
            <h4 class="mt-2 text-sky">{{ __('main.Hint') }}</h4>
            <div class="ck-content">{!! $problem->hint !!}</div>
          @endif

          @if ($problem->source != null && (!isset($contest) || $contest->end_time < date('Y-m-d H:i:s')))
            <h4 class="mt-2 text-sky">{{ __('main.Source') }}</h4>
            {{ $problem->source }}
          @endif
        </div>
      </div>

      {{-- 讨论版（题库、开启讨论的竞赛、已结束的竞赛） --}}
      {{-- @if (!isset($contest) || $contest->enable_discussing || time() > strtotime($contest->end_time))
        <div class="mt-3">
          <x-problem.disscussions :problem-id="$problem->id" />
        </div>
      @endif --}}

      {{-- 已经AC的用户进行标签标记 --}}
      @if ((!isset($contest) && get_setting('problem_show_tag_collection')) || (isset($contest) && $contest->enable_tagging))
        <x-problem.tag-collection :problem-id="$problem->id" :tags="$tags" />
      @endif

      {{-- 题库中查看题目时，显示涉及到的竞赛 --}}
      @if (!isset($contest) && get_setting('problem_show_involved_contests'))
        <x-problem.involved-contests :problem-id="$problem->id" />
      @endif

      {{-- 空白部分，使底部可以拉上来 --}}
      <div style="width: 100%; height: 10rem;"></div>

    </div>

    {{-- 中轴线 分割线 --}}
    <div id="resize"></div>

    <div id="right">
      {{-- 代码编辑框 --}}
      @livewire('problem.submitter', ['problem' => (array) $problem, 'contest_id' => $contest->id ?? null, 'allow_lang' => $contest->allow_lang ?? null])
    </div>
  </div>

  <script type="text/javascript">
    $(function() {
      //========================================= {{-- 左右分栏js调整 --}} ===============================================
      window.onload = function() {
        var resize = document.getElementById('resize');
        var left = document.getElementById('left');
        var right = document.getElementById('right');
        var container = document.getElementById('container');
        resize.onmousedown = function(e) {
          // 记录鼠标按下时的x轴坐标
          var preX = e.clientX;
          resize.left = resize.offsetLeft;
          document.onmousemove = function(e) {
            var curX = e.clientX;
            var deltaX = curX - preX;
            var leftWidth = resize.left + deltaX;
            // 左边区域的最小宽度限制
            if (leftWidth < 300) leftWidth = 300;
            // 右边区域最小宽度限制
            if (leftWidth > container.clientWidth - 300) leftWidth = container.clientWidth - 300;
            // 设置左边区域的宽度
            left.style.width = leftWidth + 'px';
            // 设备分栏竖条的left位置
            resize.style.left = leftWidth;
            // 设置右边区域的宽度
            right.style.width = (container.clientWidth - leftWidth - 4) + 'px';
          }
          document.onmouseup = function(e) {
            document.onmousemove = null;
            document.onmouseup = null;
          }
        }
      };
    })
  </script>
@endsection
