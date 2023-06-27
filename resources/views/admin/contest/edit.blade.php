@extends('layouts.admin')

@section('title', $pageTitle . ' | 后台')

@section('content')
  <h2>{{ $pageTitle }}</h2>
  <hr>
  <div>
    <form class="p-4 col-12" action="" method="post" enctype="multipart/form-data" onsubmit="presubmit()"
      style="max-width: 80rem">
      @csrf
      <div class="form-inline mb-3">
        <span>竞赛分类：</span>

        <select name="contest[cate_id]" class="form-control px-3">
          <option value="0">--- 不分类 ---</option>
          @foreach ($categories as $cate)
            <option value="{{ $cate->id }}" @if (isset($contest->cate_id) && $contest->cate_id == $cate->id) selected @endif>
              @if ($cate->is_parent)
                --- [{{ $cate->title }}] ---
              @else
                [{{ $cate->parent_title }}]
                {{ $cate->title }}
              @endif
            </option>
          @endforeach
        </select>
      </div>

      <div class="form-inline mb-3">
        <span>是否发布：</span>
        <div class="custom-control custom-radio ml-3">
          <input type="radio" name="contest[hidden]" value="0" class="custom-control-input" id="hidden_on" checked>
          <label class="custom-control-label pt-1" for="hidden_on">发布</label>
        </div>
        <div class="custom-control custom-radio ml-3">
          <input type="radio" name="contest[hidden]" value="1" class="custom-control-input" id="hidden_off"
            @if (!isset($contest->hidden) || $contest->hidden == 1) checked @endif>
          <label class="custom-control-label pt-1" for="hidden_off">隐藏</label>
        </div>
        <a href="javascript:" class="text-gray mx-2" onclick="whatisthis('若设为发布，则在网站前台竞赛列表中展示；若设为隐藏，则仅管理员可在后台中看到。')">
          <i class="fa fa-question-circle-o" aria-hidden="true"></i>
        </a>
      </div>

      <div class="form-inline mb-3">
        <span>标签收集：</span>
        <div class="custom-control custom-radio ml-3">
          <input type="radio" name="contest[enable_tagging]" value="1" class="custom-control-input"
            id="enable_tagging_on" checked>
          <label class="custom-control-label pt-1" for="enable_tagging_on">收集</label>
        </div>
        <div class="custom-control custom-radio ml-3">
          <input type="radio" name="contest[enable_tagging]" value="0" class="custom-control-input"
            id="enable_tagging_off" @if (!isset($contest) || $contest->enable_tagging == 0) checked @endif>
          <label class="custom-control-label pt-1" for="enable_tagging_off">不收集</label>
        </div>
        <a href="javascript:" class="text-gray mx-2"
          onclick="whatisthis('用户正确通过题目后，在题目下方邀请用户为当前题目标记（知识点名称）。<br>若设为不收集，则不会邀请用户进行标记。<br>注：若设为收集，则在比赛进行中、结束后均邀请标记，但仅在结束后显示已收集的标签。')">
          <i class="fa fa-question-circle-o" aria-hidden="true"></i>
        </a>
      </div>

      {{--
      <div class="form-inline mb-3">
        <span>题目讨论：</span>
        <div class="custom-control custom-radio ml-3">
          <input type="radio" name="contest[enable_discussing]" value="1" class="custom-control-input" id="kaifang" checked>
          <label class="custom-control-label pt-1" for="kaifang">允许讨论</label>
        </div>
        <div class="custom-control custom-radio ml-3">
          <input type="radio" name="contest[enable_discussing]" value="0" class="custom-control-input" id="guanbi" @if (!isset($contest) || $contest->enable_discussing == 0) checked @endif>
          <label class="custom-control-label pt-1" for="guanbi">禁用（赛后可用）</label>
        </div>
      </div>
      --}}

      <div class="input-group">
        <span style="margin: auto">竞赛标题：</span>
        <input type="text" name="contest[title]" value="{{ isset($contest->title) ? $contest->title : '' }}" required
          class="form-control" style="color: black">
      </div>

      <div class="form-group mt-4">
        <x-ckeditor5 name="contest[description]" :content="$contest->description ?? ''" title="竞赛描述/考试说明" />
      </div>

      <div class="mt-4 p-2 bg-sky">为竞赛添加一些附件（仅支持如下类型：txt, pdf, doc, docx, xls, xlsx, csv, ppt, pptx）</div>
      <div class="border p-2">
        <div class="form-group">
          <div class="form-inline">选择文件：
            <input type="file" name="files[]" multiple class="form-control"
              accept=".txt, .pdf, .doc, .docx, .xls, .xlsx, .csv, .ppt, .pptx">
          </div>
        </div>

        @if (isset($files) && $files)
          <div class="form-group">
            <div class="form-inline">已有附件：
              @foreach ($files as $i => $file)
                <div class="mr-4">
                  {{ $i + 1 }}.
                  <a href="{{ Storage::url('public/contest/files/' . $contest->id . '/' . $file) }}" class="mr-1"
                    target="_blank">{{ $file }}</a>
                  <a href="javascript:" onclick="delete_file($(this),'{{ $file }}')" title="删除"><i
                      class="fa fa-trash" aria-hidden="true"></i></a>
                </div>
              @endforeach
            </div>
          </div>
        @endif
      </div>

      <div class="mt-4 p-2 bg-sky">设置比赛时间、封榜比例</div>
      <div class="border p-2">

        <div class="form-inline my-2">
          <div class="custom-control custom-checkbox mx-2">
            <input type="checkbox" name="setToProblemList" class="custom-control-input" id="setToProblemList"
              onchange="if($(this).prop('checked')){$('#contestTimeInput').hide()}else{$('#contestTimeInput').show()}">
            <label class="custom-control-label pt-1" for="setToProblemList">忽略时间限制，将此竞赛视为普通的题目清单</label>
          </div>
        </div>
        @if (isset($contest) && $contest->end_time == $contest->start_time)
          <script>
            $(() => {
              $("#setToProblemList").click()
            })
          </script>
        @endif

        <div id="contestTimeInput">
          <div class="form-inline">
            <label>
              比赛时间：
              <input type="datetime-local" name="contest[start_time]"
                value="{{ isset($contest) ? substr(str_replace(' ', 'T', $contest->start_time), 0, 16) : str_replace(' ', 'T', date('Y-m-d H:00', time() + 3600)) }}"
                class="form-control" required>
              <span class="mx-2">—</span>
              <input type="datetime-local" name="contest[end_time]"
                value="{{ isset($contest) ? substr(str_replace(' ', 'T', $contest->end_time), 0, 16) : str_replace(' ', 'T', date('Y-m-d H:00', time() + 3600 * 6)) }}"
                class="form-control" required>
            </label>
          </div>

          <div class="form-group mt-2">
            <label class="form-inline">封榜比例：
              <input type="number" step="0.01" max="1" min="0" name="contest[lock_rate]"
                value="{{ isset($contest) ? $contest->lock_rate : 0 }}" class="form-control">
              <a href="javascript:" class="ml-1" style="color: #838383"
                onclick="whatisthis('封榜时长=比赛时长×封榜比例；<br>数值范围0.0~1.0' +
                             '<br><br>例如：封榜比例0.2，比赛总时长5小时，则比赛达到4小时后榜单停止更新' +
                              '（管理员依旧可以看到实时榜单）' +
                               '<br><br>若封榜比例为1.0，则全程不更新榜单，适合考试。')">
                <i class="fa fa-question-circle-o" aria-hidden="true"></i>
              </a>
            </label>
          </div>
        </div>
      </div>

      <div class="mt-4 p-2 bg-sky">哪些用户可以参加本次竞赛/考试？</div>
      <div class="border p-2">

        <div class="form-inline my-2">
          <span>验证方式：</span>
          <div class="custom-control custom-radio mx-3">
            <input type="radio" name="contest[access]" value="public" class="custom-control-input" id="Public"
              checked onchange="access_has_change('public')">
            <label class="custom-control-label pt-1" for="Public">Public</label>
          </div>
          <div class="custom-control custom-radio mx-3">
            <input type="radio" name="contest[access]" value="password" class="custom-control-input" id="Password"
              oninput="this.value=this.value.replace(/\s+/g,'')" onchange="access_has_change('password')"
              @if (isset($contest) && $contest->access == 'password') checked @endif>
            <label class="custom-control-label pt-1" for="Password">Password</label>
          </div>
          <div class="custom-control custom-radio mx-3">
            <input type="radio" name="contest[access]" value="private" class="custom-control-input" id="Private"
              onchange="access_has_change('private')" @if (isset($contest) && $contest->access == 'private') checked @endif>
            <label class="custom-control-label pt-1" for="Private">Private</label>
          </div>
        </div>

        {{--                <div class="form-group"> --}}
        {{--                    <label class="form-inline">验证方式： --}}
        {{--                        <select name="contest[access]" class="form-control" onchange="type_has_change($(this).val())"> --}}
        {{--                            <option value="public">public：任意用户可以参与</option> --}}
        {{--                            <option value="password" {{isset($contest)&&$contest->access=='password'?'selected':''}}>password：需要输入密码进入</option> --}}
        {{--                            <option value="private" {{isset($contest)&&$contest->access=='private'?'selected':''}}>private：指定用户可参与</option> --}}
        {{--                        </select> --}}
        {{--                    </label> --}}
        {{--                </div> --}}

        <div id="access_type_public">
          <p class=" alert alert-success p-2">
            当竞赛公开时，任意已登陆用户均可直接进入竞赛。
          </p>
        </div>
        <div id="type_password">
          <div class="form-inline my-3">
            <label>
              参赛密码：
              <input type="text" name="contest[password]" value="{{ isset($contest) ? $contest->password : '' }}"
                class="form-control">
            </label>
            <br>
          </div>
          <p class=" alert alert-warning p-2">
            用户必须输入密码才能进入竞赛。注：无论竞赛被加入任何团队，都仍然需要输入密码才能进入。
          </p>
        </div>

        <div id="type_users" class="form-group my-3">
          <div class="float-left">指定用户：</div>
          <label>
            <textarea name="contest_users" class="form-control-plaintext border bg-white" rows="8" cols="26"
              placeholder="user1&#13;&#10;user2&#13;&#10;每行一个用户登录名&#13;&#10;你可以将表格的整列粘贴到这里">
@foreach (isset($unames) ? $unames : [] as $item)
{{ $item }}
@endforeach
</textarea>
          </label>
          <p class=" alert alert-warning p-2">
            竞赛发布（非隐藏）后，仅管理员和以上被邀请用户可以进入竞赛。
          </p>
        </div>
      </div>

      <div class="mt-4 p-2 bg-sky">为竞赛添加题目</div>
      <div class="border p-2">

        <div class="form-group">
          <div class="pull-left">题目列表：</div>
          <label>
            @if (request()->has('pids'))
              {{ null, $pids[] = request('pids') }}
            @endif
            <textarea name="problems" class="form-control-plaintext border bg-white" rows="10" cols="40"
              placeholder="{Section Name}&#13;&#10;1000&#13;&#10;1024-1030&#13;&#10;每行可以填写以下三者之一：&#13;&#10;1. 一个题号,如1024&#13;&#10;2. 一个题号区间,如1024-1036&#13;&#10;3. 一个花括号括起来的小节名称,如{例题部分}">
@foreach (isset($pids) ? $pids : [] as $item)
{{ $item }}
@endforeach
</textarea>
          </label>
          <a href="javascript:" class="text-gray" style="vertical-align: top"
            onclick="whatisthis('每行可以填写以下三者之一：<br>1. 一个题号,如1024<br>2. 一个题号区间,如1024-1036<br>3. 一个花括号括起来的小节名称,如{例题部分}')">
            <i class="fa fa-question-circle-o" style="vertical-align: top" aria-hidden="true"></i>
          </a>
        </div>

        <div class="form-inline mb-3">
          <div class="pull-left">编程语言：</div>
          <input id="input_allow_lang" type="number" name="contest[allow_lang]" hidden>
          @foreach (config('judge.lang') as $lang => $name)
            <div class="custom-control custom-checkbox mx-2">
              <input type="checkbox" name="allow_lang" value="{{ $lang }}"
                class="lang_checkbox custom-control-input" id="allow_lang{{ $lang }}"
                @if ((!isset($contest) && in_array($lang, [7, 13])) || (isset($contest) && ($contest->allow_lang >> $lang) & 1)) checked @endif>
              <label class="custom-control-label pt-1" for="allow_lang{{ $lang }}">{{ $name }}</label>
            </div>
          @endforeach
          <a href="javascript:" class="text-gray" onclick="whatisthis('允许考生提交的代码语言，请选择至少一个！')">
            <i class="fa fa-question-circle-o" aria-hidden="true"></i>
          </a>
        </div>


        <div class="form-inline mb-3">
          <span>判题策略：</span>
          <div class="custom-control custom-radio ml-2">
            <input type="radio" name="contest[judge_type]" value="acm" class="custom-control-input"
              id="acmicpc" checked>
            <label class="custom-control-label pt-1" for="acmicpc">遇错止评(ACM-ICPC)</label>
          </div>
          <div class="custom-control custom-radio mx-4">
            <input type="radio" name="contest[judge_type]" value="oi" class="custom-control-input"
              id="oixinxi" @if (isset($contest) && $contest->judge_type == 'oi') checked @endif>
            <label class="custom-control-label pt-1" for="oixinxi">全部评测(OI)</label>
          </div>
          <a href="javascript:" style="color: #838383"
            onclick="whatisthis('遇错止评：<br>用户每次提交代码后，测试数据按顺序评测，首次遇到无法通过的测试数据后，则不再评测后续测试数据，适合于ACM赛制的竞赛。<br><br>' +
                            '全部评测：<br>用户每次提交代码后，所有测试数据都将参与评测，适合于OI赛制的竞赛。')">
            <i class="fa fa-question-circle-o" aria-hidden="true"></i>
          </a>
        </div>

        <div class="form-inline mb-3">
          <div class="pull-left">公开榜单：</div>

          <div class="custom-control custom-checkbox mx-2">
            <input type="checkbox" name="contest[public_rank]" class="custom-control-input" id="public_rank"
              @if (isset($contest->public_rank) && $contest->public_rank) checked @endif>
            <label class="custom-control-label pt-1" for="public_rank">允许任意访客查看榜单</label>
          </div>

          <a href="javascript:" class="text-gray" onclick="whatisthis('若勾选此项，任意访客（含未登录用户）都可以查看该榜单；否则仅参赛选手和管理员可查看！')">
            <i class="fa fa-question-circle-o" aria-hidden="true"></i>
          </a>
        </div>

      </div>

      <div class="form-group m-4 text-center">
        <button type="submit" class="btn-lg btn-success">提交</button>
      </div>
    </form>
  </div>

  <script type="text/javascript">
    function presubmit() {
      //将允许语言的标记以二进制形式状态压缩为一个整数
      var ret = 0;
      $(".lang_checkbox:checked").each(function() {
        ret |= 1 << this.value;
      });
      $("#input_allow_lang").val(ret);
    }


    //监听竞赛权限改变
    function access_has_change(type) {
      if (type === 'public') {
        $("#access_type_public").show();
        $("#type_password").hide();
        $("#type_users").hide();
      } else if (type === 'password') {
        $("#access_type_public").hide();
        $("#type_password").show();
        $("#type_users").hide();
      } else {
        $("#access_type_public").hide();
        $("#type_password").hide();
        $("#type_users").show();
      }
    }

    access_has_change('{{ isset($contest) ? $contest->access : 'public' }}'); //初始执行一次


    //删除附件
    function delete_file(that, filename) {
      Notiflix.Confirm.Show('删除前确认', '确定删除这个附件？' + filename, '确认', '取消', function() {
        $.post(
          '{{ route('admin.contest.delete_file', isset($contest) ? $contest->id : 0) }}', {
            '_token': '{{ csrf_token() }}',
            'filename': filename,
          },
          function(ret) {
            if (ret > 0) {
              that.parent().remove()
              Notiflix.Notify.Success('删除成功！')
            } else Notiflix.Notify.Failure('删除失败,系统错误或权限不足！');
          }
        );
      });
    }
  </script>

  <script type="text/javascript">
    window.onbeforeunload = function() {
      return "确认离开当前页面吗？未保存的数据将会丢失！";
    }
    $("form").submit(function(e) {
      window.onbeforeunload = null
    });
  </script>
@endsection
