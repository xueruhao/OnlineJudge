<style>
  .nav-link{
    color: black !important;
  }
</style>
{{-- 移动端按钮 --}}
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
  <span class="navbar-toggler-icon"></span>
</button>

{{-- 导航栏项 --}}
<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
  <ul class="navbar-nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link text-nowrap p-2 @if (Route::currentRouteName() == 'home') active @endif" href="{{ route('home') }}">
        <i class="fa fa-home" aria-hidden="true">&nbsp;{{ trans('main.Home') }}</i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-nowrap p-2 @if (Route::currentRouteName() == 'solutions') active @endif"
        href="{{ route('solutions') }}">
        <i class="fa fa-hourglass-half" aria-hidden="true">&nbsp;{{ trans('main.HomeStatus') }}</i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-nowrap p-2 @if (preg_match('/^problem\S*$/', Route::currentRouteName())) active @endif"
        href="{{ route('problems', ['show_hidden' =>Auth::check() &&request()->user()->can('admin.problem.view')? 'on': null]) }}">
        <i class="fa fa-list" aria-hidden="true">&nbsp;{{ trans('main.Problems') }}</i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-nowrap p-2 @if (preg_match('/^contest\S*$/', Route::currentRouteName())) active @endif"
        href="{{ route('contests') }}">
        <i class="fa fa-trophy" aria-hidden="true">&nbsp;{{ trans('main.Contests') }}</i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-nowrap p-2 @if (preg_match('/^group\S*$/', Route::currentRouteName())) active @endif"
        href="{{ route('groups', ['mygroups' => 'on']) }}">
        <i class="fa fa-users" aria-hidden="true">&nbsp;{{ trans('main.Groups') }}</i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-nowrap p-2 @if (Route::currentRouteName() == 'standings') active @endif"
        href="{{ route('standings') }}">
        <i class="fa fa-sort-amount-desc" aria-hidden="true">&nbsp;{{ trans('main.Standings') }}</i>
      </a>
    </li>

    {{-- 下拉菜单 --}}
    {{-- <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle text-nowrap p-2" href="#" id="contestDropdown" data-toggle="dropdown">
        <i class="fa fa-info-circle" aria-hidden="true">&nbsp;{{ trans('main.About') }}</i>
      </a>
      <div class="dropdown-menu" aria-labelledby="contestDropdown">
        <a class="dropdown-item" href="https://winterant.github.io/OnlineJudge/web/"
          target="_blank">{{ __('main.Introduction') }}</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item text-nowrap" href="https://winterant.github.io/OnlineJudge/web/judge.html"
          target="_blank">
          {{ __('main.Judge Service') }}
        </a>
        <a class="dropdown-item" href="https://winterant.github.io/OnlineJudge/web/result.html"
          target="_blank">{{ __('main.Judge Result') }}</a>

        <a class="dropdown-item" href="https://winterant.github.io/OnlineJudge/web/spj.html"
          target="_blank">{{ __('main.Special Judge') }}</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="https://github.com/winterant/OnlineJudge"
          target="_blank">{{ __('main.Open-Source Code') }}</a>
      </div>
    </li> --}}

  </ul>

  {{-- <form class="form-inline">
      <input class="form-control mr-sm-2" type="text" />
      <button class="btn btn-primary my-2 my-sm-0" type="submit">
          Search
      </button>
  </form> --}}

  {{-- 登陆按钮 --}}
  <ul class="navbar-nav ml-auto float-right">
    {{-- 深色模式 --}}
    <li class="nav-item mr-3">
      <x-dark-mode>{{ __('main.DarkMode') }}</x-dark-mode>
    </li>

    {{-- 语言切换 --}}
    <li class="nav-item dropdown mr-3">
      <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false" v-pre>
        <i class="fa fa-language" aria-hidden="true"></i>
        @php($langs = ['en' => 'English', 'zh-CN' => '简体中文'])
        {{ $langs[request()->cookie('unencrypted_client_language') ?? get_setting('APP_LOCALE', 'en')] }}
        <span class="caret"></span>
      </a>

      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        @foreach ($langs as $k => $item)
          <a class="dropdown-item" href="{{ route('change_language', $k) }}">{{ $item }}</a>
        @endforeach
      </div>
    </li>

    <!-- Authentication Links -->
    @guest
      <li class="nav-item">
        <a class="nav-link" href="{{ route('login') }}">{{ trans('main.Login') }}</a>
      </li>
      @if (Route::has('register'))
        <li class="nav-item">
          <a class="nav-link" href="{{ route('register') }}">{{ trans('main.Register') }}</a>
        </li>
      @endif
    @else
      <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false" v-pre>
          <i class="fa fa-user" aria-hidden="true"></i>
          {{ Auth::user()->username }} <span class="caret"></span>
        </a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

          <a class="dropdown-item" href="{{ route('user', Auth::user()->username) }}">{{ trans('main.Profile') }}</a>
          <a class="dropdown-item"
            href="{{ route('password_reset', Auth::user()->username) }}">{{ trans('sentence.Modify Password') }}</a>

          @can('admin.view')
            <a class="dropdown-item" href="{{ route('admin.home') }}">{{ trans('main.Administration') }}</a>
          @endcan

          <div class="dropdown-divider"></div>

          <a class="dropdown-item" href="{{ route('logout') }}"
            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            {{ __('main.Logout') }}
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </div>
      </li>
    @endguest
  </ul>
  {{-- end of 个人信息按钮 --}}
</div>
