@extends('layouts.client')

@section('title', trans('main.Contests'))

@section('content')

  <style>
    select {
      text-align: center;
      text-align-last: center;
      color: black;
    }

    @media screen and (max-width: 992px) {
      .p-xs-0 {
        padding: 0
      }
    }
  </style>

  <div class="container">

    <div class="tabbable">
      <ul class="nav nav-tabs border-bottom">
        @foreach ($categories as $cate)
          @if ($cate->is_parent)
            <li class="nav-item dropdown">

              {{-- 分裂式下拉菜单 --}}
              <div class="btn-group" style="margin:0px">
                <a class="nav-link text-nowrap py-3 @if ($current_cate->parent_id == $cate->id || $current_cate->id == $cate->id) active @endif"
                  href="{{ route('contests', ['cate' => $cate->id]) }}"
                  style="min-width:0px !important; @if ($cate->num_sons > 0) padding-right:1.9rem @endif">{{ ucfirst($cate->title) }}</a>
                {{-- 有子类别的，显示下拉菜单 --}}
                @if ($cate->num_sons > 0)
                  <a href="javascript:" class="py-3 dropdown-toggle dropdown-toggle-split" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false" style="margin-left:-2.1rem">
                    <span class="sr-only">Toggle Dropdown</span>
                  </a>
                  <div class="dropdown-menu">
                    <div class="btn-group d-flex flex-wrap">
                      <a class="dropdown-item flex-nowrap border-bottom"
                        href="{{ route('contests', ['cate' => $cate->id]) }}">
                        <i class="fa fa-trophy px-1" aria-hidden="true"></i>
                        {{ ucfirst($cate->title) }}
                      </a>
                      <div class="dropdown-divider"></div>
                      @foreach ($categories as $c)
                        @if ($c->parent_id == $cate->id)
                          <a class="btn btn-secondary dropdown-item flex-nowrap @if ($current_cate->id == $c->id) active @endif"
                            href="{{ route('contests', ['cate' => $c->id]) }}">
                            <i class="fa fa-book px-1" aria-hidden="true"></i>
                            {{ ucfirst($c->title) }}
                          </a>
                        @endif
                      @endforeach
                    </div>
                  </div>
                @endif
              </div>

              {{-- 普通横向菜单 --}}
              {{-- <a class="nav-link text-center py-3 @if ($current_cate->parent_id == $cate->id || $current_cate->id == $cate->id) active @endif"
                href="{{ route('contests', ['cate' => $cate->id]) }}">
                <i class="fa fa-trophy" aria-hidden="true"></i>
                {{ ucfirst($cate->title) }}
              </a> --}}

            </li>
          @endif
        @endforeach
      </ul>

      {{-- 当前一级类别下的二级类别 显示横向菜单 --}}
      <div class="btn-group d-flex flex-wrap">
        {{-- <i class="fa fa-angle-double-right fa-lg p-2" aria-hidden="true"></i> --}}
        @foreach ($categories as $cate)
          @if (!$cate->is_parent && ($cate->parent_id == $current_cate->id || $cate->parent_id == $current_cate->parent_id))
            <a class="btn btn-secondary border @if ($current_cate->id == $cate->id) active @endif" style="flex: none;"
              href="{{ route('contests', ['cate' => $cate->id]) }}">
              {{ ucfirst($cate->title) }}
            </a>
          @endif
        @endforeach
      </div>

    </div>

    <div class="my-container bg-white">
      <div class="overflow-hidden">

        <div class="d-flex flex-wrap float-left mr-3">
          <ul class="breadcrumb text-nowrap">
            @if ($current_cate->parent)
              <li><i class="fa fa-trophy mr-1" aria-hidden="true"></i>{{ $current_cate->parent->title }}</li>
              <li class="mx-1">/</li>
            @endif
            <li>{{ $current_cate->title }}</li>
          </ul>
        </div>

        <p class="float-left">{{ $current_cate->description }}</p>

        <form action="" method="get" class="float-right form-inline">
          <div class="custom-control custom-checkbox m-2">
            <input type="checkbox" name="simple_style" class="custom-control-input" id="customCheck"
              @if (request()->has('simple_style')) checked @endif onchange="this.form.submit()">
            <label class="custom-control-label pt-1" for="customCheck">{{ __('main.Simple Style') }}</label>
          </div>
          <div class="m-2">
            <select name="perPage" class="form-control px-2" onchange="this.form.submit();">
              <option value="5" @if (request()->has('perPage') && request('perPage') == 5) selected @endif>5</option>
              <option value="10" @if (!request()->has('perPage') || request('perPage') == 10) selected @endif>10</option>
              <option value="30" @if (request()->has('perPage') && request('perPage') == 30) selected @endif>30</option>
              <option value="50" @if (request()->has('perPage') && request('perPage') == 50) selected @endif>50</option>
              <option value="100" @if (request()->has('perPage') && request('perPage') == 100) selected @endif>100</option>
            </select>
            {{ __('sentence.items per page') }}
          </div>
          <div class="m-2">
            <select name="state" class="form-control px-3" onchange="this.form.submit();">
              <option value="">{{ __('main.All') }}@lang('main.Status')</option>
              <option value="waiting" @if (request()->has('state') && request('state') == 'waiting') selected @endif>{{ __('main.Waiting') }}
              </option>
              <option value="running" @if (request()->has('state') && request('state') == 'running') selected @endif> {{ __('main.Running') }}
              </option>
              <option value="ended" @if (request()->has('state') && request('state') == 'ended') selected @endif>{{ __('main.Ended') }}</option>
            </select>
          </div>
          <div class="m-2">
            <input type="text" class="form-control text-center" placeholder="{{ __('main.Title') }}" name="title"
              value="{{ request('title') ?? '' }}">
          </div>
          <button class="btn text-white bg-success m-2">
            <i class="fa fa-filter" aria-hidden="true"></i>
            {{ __('main.Find') }}</button>
        </form>
      </div>

      {{ $contests->appends($_GET)->links() }}

      @if (request('simple_style'))
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th width="1">{{ __('main.ID') }}</th>
                <th>{{ __('main.Title') }}</th>
                <th>{{ __('main.Time') }}</th>
                <th>{{ __('main.Access') }}</th>
                <th>{{ __('main.Members') }}</th>
                <th>{{ __('main.Status') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($contests as $item)
                <tr>
                  <td>{{ $item->id }}</td>
                  <td>
                    <a href="{{ route('contest.home', $item->id) }}">{{ $item->title }}</a>

                    @if ($item->hidden)
                      <i class="fa fa-eye-slash ml-2" aria-hidden="true"></i>
                      <span class="text-gray">{{ trans('main.Hidden') }}</span>
                    @endif

                    @if ($item->end_time == $item->start_time)
                      <span class="border bg-light px-1 text-sky" style="border-radius: 12px;">@lang('main.ProblemList')</span>
                    @endif
                  </td>
                  <td nowrap>
                    @if ($item->end_time != $item->start_time)
                      <i class="fa fa-calendar pr-1 text-sky"aria-hidden="true"></i>{{ $item->start_time }}
                      <span class="px-1">
                        <i class="fa fa-clock-o text-sky" aria-hidden="true"></i>
                        @php($time_len = strtotime($item->end_time) - strtotime($item->start_time))
                        @if ($time_len > 3600 * 24 * 30)
                          {{ round($time_len / (3600 * 24 * 30), 1) }}
                          {{ trans_choice('main.months', round($time_len / (3600 * 24 * 30), 1)) }}
                        @elseif($time_len > 3600 * 24)
                          {{ round($time_len / (3600 * 24), 1) }}
                          {{ trans_choice('main.days', round($time_len / (3600 * 24), 1)) }}
                        @else
                          {{ round($time_len / 3600, 1) }} {{ trans_choice('main.hours', round($time_len / 3600, 1)) }}
                        @endif
                      </span>
                    @endif
                  </td>
                  <td>
                    <span class="text-{{ $item->access == 'public' ? 'green' : 'red' }}">
                      @if ($item->access != 'public')
                        <i class="fa fa-lock" aria-hidden="true"></i>
                      @endif
                      {{ trans('main.access_' . $item->access) }}
                      @if (Auth::check() && Auth::user()->can('admin.contest.view') && $item->access == 'password')
                        [{{ __('main.Password') }}:{{ $item->password }}]
                      @endif
                    </span>
                  </td>
                  <td>
                    <i class="fa fa-users text-sky" aria-hidden="true"></i>
                    {{ $item->num_members }}
                  </td>
                  <td>
                    <a href="{{ route('contest.rank', $item->id) }}" title="{{ __('main.Rank') }}">
                      @if ($item->end_time == $item->start_time)
                        <i class="fa fa-thumbs-up text-red pr-1" aria-hidden="true"></i>{{ __('main.Rank') }}
                      @elseif (strtotime(date('Y-m-d H:i:s')) < strtotime($item->start_time))
                        <i class="fa fa-circle text-orange pr-1" aria-hidden="true"></i>{{ __('main.Waiting') }}
                      @elseif(strtotime(date('Y-m-d H:i:s')) > strtotime($item->end_time))
                        <i class="fa fa-thumbs-up text-red pr-1" aria-hidden="true"></i>{{ __('main.Ended') }}
                      @else
                        <i class="fa fa-hourglass text-green pr-1" aria-hidden="true"></i>{{ __('main.Running') }}
                      @endif
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <ul class="list-unstyled border-top">
          @foreach ($contests as $item)
            <li class="d-flex flex-wrap border-bottom py-3">
              <div class="p-xs-0 px-3 text-center align-self-center">
                <img height="45px"
                  @if ($item->end_time == $item->start_time) src="{{ asset_ts('images/trophy/problem-list.png') }}"
                @elseif (strtotime($item->start_time) < time() && time() < strtotime($item->end_time)) src="{{ asset_ts('images/trophy/running.png') }}"
                @else src="{{ asset_ts('images/trophy/gold.png') }}" @endif
                  alt="pic">
              </div>
              <div class="col-9 col-sm-8">
                <h5 style="font-size: 1.15rem">
                  <a href="{{ route('contest.home', $item->id) }}" class="text-black">{{ $item->title }}</a>
                  <span style="font-size: 0.9rem; vertical-align: top;">
                    @if ($item->end_time == $item->start_time)
                      <span class="border bg-light px-1 text-sky" style="border-radius: 12px;">@lang('main.ProblemList')</span>
                    @endif
                    <span class="border bg-light px-1 text-{{ $item->access == 'public' ? 'green' : 'red' }}"
                      style="border-radius: 12px;">
                      @if ($item->access != 'public')
                        <i class="fa fa-lock" aria-hidden="true"></i>
                      @endif
                      {{ trans('main.access_' . $item->access) }}
                      @if (Auth::check() && Auth::user()->can('admin.contest.view') && $item->access == 'password')
                        [{{ __('main.Password') }}:{{ $item->password }}]
                      @endif
                    </span>
                    @if ($item->hidden)
                      <i class="fa fa-eye-slash ml-2" aria-hidden="true"></i>
                      <span class="text-gray">{{ __('main.Hidden') }}</span>
                    @endif
                  </span>
                </h5>
                <ul class="d-flex flex-wrap list-unstyled" style="font-size: 0.9rem;color: #484f56">
                  <li>@lang('main.ID') {{ $item->id }}</li>
                  @if ($item->end_time != $item->start_time)
                    <li class="px-2"><i class="fa fa-calendar pr-1 text-sky"
                        aria-hidden="true"></i>{{ $item->start_time }}
                    </li>
                    <li class="px-2">
                      <i class="fa fa-clock-o text-sky" aria-hidden="true"></i>
                      {{ null, $time_len = strtotime($item->end_time) - strtotime($item->start_time) }}
                      @if ($time_len > 3600 * 24 * 30)
                        {{ round($time_len / (3600 * 24 * 30), 1) }}
                        {{ trans_choice('main.months', round($time_len / (3600 * 24 * 30), 1)) }}
                      @elseif($time_len > 3600 * 24)
                        {{ round($time_len / (3600 * 24), 1) }}
                        {{ trans_choice('main.days', round($time_len / (3600 * 24), 1)) }}
                      @else
                        {{ round($time_len / 3600, 1) }} {{ trans_choice('main.hours', round($time_len / 3600, 1)) }}
                      @endif
                    </li>
                  @endif
                  <li class="px-2">
                    <i class="fa fa-user-o text-sky" aria-hidden="true"></i>
                    {{ $item->num_members }}
                  </li>
                </ul>
              </div>
              <div class="col-12 col-sm-3 m-auto">
                <a href="{{ route('contest.rank', $item->id) }}" class="btn btn-secondary border" style="width: 120px"
                  title="{{ __('main.Rank') }}">
                  @if ($item->end_time == $item->start_time)
                    <i class="fa fa-thumbs-up text-red pr-1" aria-hidden="true"></i>{{ __('main.Rank') }}
                  @elseif (strtotime(date('Y-m-d H:i:s')) < strtotime($item->start_time))
                    <i class="fa fa-circle text-orange pr-1" aria-hidden="true"></i>{{ __('main.Waiting') }}
                  @elseif(strtotime(date('Y-m-d H:i:s')) > strtotime($item->end_time))
                    <i class="fa fa-thumbs-up text-red pr-1" aria-hidden="true"></i>{{ __('main.Ended') }}
                  @else
                    <i class="fa fa-hourglass text-green pr-1" aria-hidden="true"></i>{{ __('main.Running') }}
                  @endif
                </a>
              </div>
            </li>
          @endforeach
        </ul>
      @endif


      {{ $contests->appends($_GET)->links() }}

      @if (count($contests) == 0)
        <p class="text-center">{{ __('sentence.No data') }}</p>
      @endif
    </div>
  </div>

@endsection
