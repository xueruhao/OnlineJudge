@extends('layouts.admin')

@section('title', '竞赛管理 | 后台')

@section('content')

  <h2>竞赛管理</h2>
  <hr>
  <form action="" method="get" class="float-right form-inline">
    <div class="form-inline mx-3">
      <select name="perPage" class="form-control px-2" onchange="this.form.submit();">
        <option value="10">10</option>
        <option value="20" @if (request()->has('perPage') && request('perPage') == 20) selected @endif>20</option>
        <option value="30" @if (request()->has('perPage') && request('perPage') == 30) selected @endif>30</option>
        <option value="50" @if (request()->has('perPage') && request('perPage') == 50) selected @endif>50</option>
        <option value="100" @if (request()->has('perPage') && request('perPage') == 100) selected @endif>100</option>
      </select>
      项每页
    </div>
    <div class="form-inline mx-3">

      <select name="cate_id" class="form-control px-3" onchange="this.form.submit();"
        style="width:auto;padding:0 1%;text-align:center;text-align-last:center;">
        <option value="">所有类别</option>
        <option value="0" @if (request()->has('cate_id') && request('cate_id') === '0') selected @endif>--- 未分类 ---</option>
        @foreach ($categories as $cate)
          <option value="{{ $cate->id }}" @if (request()->has('cate_id') && request('cate_id') == $cate->id) selected @endif>
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
    <div class="form-inline mx-3">
      <select name="state" class="form-control px-3" onchange="this.form.submit();">
        <option value="all">所有进行阶段</option>
        <option value="waiting" @if (request()->has('state') && request('state') == 'waiting') selected @endif>尚未开始</option>
        <option value="running" @if (request()->has('state') && request('state') == 'running') selected @endif>正在进行中</option>
        <option value="ended" @if (request()->has('state') && request('state') == 'ended') selected @endif>已结束</option>
      </select>
    </div>
    <div class="form-inline mx-3">
      <select name="judge_type" class="form-control px-3" onchange="this.form.submit();">
        <option value="">所有规则</option>
        <option value="acm" @if (request()->has('judge_type') && request('judge_type') == 'acm') selected @endif>ACM</option>
        <option value="oi" @if (request()->has('judge_type') && request('judge_type') == 'oi') selected @endif>OI</option>
      </select>
    </div>
    <div class="form-inline mx-3">
      <input type="text" class="form-control text-center" placeholder="标题" onchange="this.form.submit();"
        name="title" value="{{ request('title') ?? '' }}">
    </div>
    <button class="btn btn-secondary border">查找</button>
  </form>
  <div class="float-left">
    {{ $contests->appends($_GET)->links() }}
    <a href="javascript:$('.cb input[type=checkbox]').prop('checked',true)" class="btn btn-secondary border">全选</a>
    <a href="javascript:$('.cb input[type=checkbox]').prop('checked',false)" class="btn btn-secondary border">取消</a>

    &nbsp;公开榜单:[
    <a href="javascript:" onclick="update_public_rank(1)">公开</a>
    |
    <a href="javascript:" onclick="update_public_rank(0)">隐藏</a>
    ]

    &nbsp;前台可见性:[
    <a href="javascript:" onclick="update_hidden(0)">公开</a>
    |
    <a href="javascript:" onclick="update_hidden(1)">隐藏</a>
    ]
    <a href="javascript:" class="text-gray" onclick="whatisthis('普通用户是否可以在前台竞赛页面看到该竞赛.')">
      <i class="fa fa-question-circle-o" aria-hidden="true"></i>
    </a>
  </div>
  <div class="table-responsive">
    <table class="table table-striped table-hover table-sm">
      <thead>
        <tr>
          <th></th>
          <th>编号</th>
          @if (request()->has('cate_id') && request('cate_id') !== '')
            <th>顺序
              <a href="javascript:" style="color: #838383"
                onclick="whatisthis('当您浏览某具体类别的竞赛时，您可以移动竞赛的位置以改变顺序。<br>后台与前台将保持同步顺序，唯一的区别是前台不向普通用户展示隐藏的竞赛。')">
                <i class="fa fa-question-circle-o" aria-hidden="true"></i>
              </a>
            </th>
          @endif
          <th>类别</th>
          <th>标题</th>
          <th>赛制</th>
          <th>开始时间</th>
          <th>结束时间</th>
          <th>参赛权限
            <a href="javascript:" style="color: #838383"
              onclick="whatisthis('public：任意用户可参加。<br>password：输入密码正确者可参加。<br>private：后台规定的用户可参加')">
              <i class="fa fa-question-circle-o" aria-hidden="true"></i>
            </a>
          </th>
          <th>参与人数</th>
          <th>封榜比例
            <a href="javascript:" style="color: #838383"
              onclick="whatisthis('数值范围0~1<br>比赛时长*封榜比例=比赛封榜时间。<br>如：时长5小时，比例0.2，则第4小时开始榜单不更新。<br><br>值为0表示不封榜。<br>管理员不受影响')">
              <i class="fa fa-question-circle-o" aria-hidden="true"></i>
            </a>
          </th>
          <th>公开榜单
            <a href="javascript:" style="color: #838383" onclick="whatisthis('是否允许任意访客查看榜单。如果关闭此项，则只有参赛选手和管理员可以查看榜单')">
              <i class="fa fa-question-circle-o" aria-hidden="true"></i>
            </a>
          </th>
          <th>前台可见</th>
          <th>创建人</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($contests as $item)
          <tr>
            <td class="cb"
              onclick="var cb=$(this).find('input[type=checkbox]');cb.prop('checked',!cb.prop('checked'))">
              <input type="checkbox" value="{{ $item->id }}" onclick="window.event.stopPropagation();"
                style="vertical-align:middle;zoom: 140%">
            </td>
            <td>{{ $item->id }}</td>
            @if (request()->has('cate_id') && request('cate_id') !== '')
              <td nowrap>
                <select onchange="update_contest_order($(this).val())"
                  style="width:auto;padding:0 1%;text-align:center;text-align-last:center;border-radius: 2px;">
                  <option value="{{ route('api.admin.contest.update_order', [$item->id, 1000000000]) }}">置顶</option>
                  @for ($shift = 256; $shift > 0; $shift >>= 1)
                    <option value="{{ route('api.admin.contest.update_order', [$item->id, $shift]) }}">
                      <i class="fa fa-arrow-up" aria-hidden="true"></i>上移{{ $shift }}项
                    </option>
                  @endfor
                  <option value="" selected>{{ $item->order }}</option>
                  @for ($shift = 1; $shift <= 128 && $item->order - $shift > 0; $shift <<= 1)
                    <option value="{{ route('api.admin.contest.update_order', [$item->id, -$shift]) }}">
                      <i class="fa fa-arrow-down" aria-hidden="true"></i>下移{{ $shift }}项
                    </option>
                  @endfor
                  <option value="{{ route('api.admin.contest.update_order', [$item->id, -1000000000]) }}">置底</option>
                </select>
              </td>
            @endif
            <td>
              <div class="form-inline">
                <select class="" onchange="update_contest_cate_id($(this).val())"
                  style="width:auto;padding:0 1%;text-align:center;text-align-last:center;border-radius: 2px;">
                  <option value="{{ route('api.admin.contest.update_cate_id', [$item->id, 0]) }}">--- 未分类 ---</option>
                  @foreach ($categories as $cate)
                    <option value="{{ route('api.admin.contest.update_cate_id', [$item->id, $cate->id]) }}"
                      @if ($item->cate_id == $cate->id) selected @endif>
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
            </td>
            <td nowrap><a href="{{ route('contest.home', $item->id) }}" target="_blank">{{ $item->title }}</a></td>
            <td nowrap>{{ $item->judge_type }}</td>
            <td nowrap>{{ substr($item->start_time, 0, 16) }}</td>
            <td nowrap>{{ substr($item->end_time, 0, 16) }}</td>
            <td nowrap>{{ $item->access }}</td>
            <td nowrap><i class="fa fa-user-o text-sky" aria-hidden="true"></i> {{ $item->num_members }}</td>
            <td nowrap>{{ sprintf('%.2f', $item->lock_rate) }}</td>
            <td nowrap>
              {{-- {{$item->public_rank}} --}}
              <input id="switch_prank{{ $item->id }}" type="checkbox"
                @if ($item->public_rank) checked @endif>
            </td>
            <td nowrap>
              {{-- {{$item->hidden}} --}}
              <input id="switch_hidden{{ $item->id }}" type="checkbox"
                @if (!$item->hidden) checked @endif>
            </td>
            <td nowrap>{{ $item->username }}</td>
            <td nowrap>
              <a href="{{ route('admin.contest.update', $item->id) }}" class="mx-1" target="_blank" title="修改">
                <i class="fa fa-edit" aria-hidden="true"></i> 编辑
              </a>
              <a href="javascript:" onclick="delete_contest({{ $item->id }}, this.parentNode.parentNode)" class="mx-1" title="删除">
                <i class="fa fa-trash" aria-hidden="true"></i> 删除
              </a>
              <a href="javascript:" onclick="clone_contest({{ $item->id }})" class="mx-1" title="克隆该竞赛">
                <i class="fa fa-clone" aria-hidden="true"></i> 克隆
              </a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    {{ $contests->appends($_GET)->links() }}
  </div>

  <script type="text/javascript">
    // 由于修改hidden、public_rank等字段时会修改开关，出发开关递归调用onchange
    // 所以在js函数内操作开关前，先加锁，防止递归调用。
    var lock_switch_onchange = false
    // 收集switch开关对象
    var switchs_hidden = {}
    var switchs_prank = {}
    $(function() {
      @foreach ($contests as $item)
        // 初始化public_rank开关
        var s = new Switch($("#switch_prank{{ $item->id }}")[0], {
          size: 'small',
          onChange: function() {
            update_public_rank(this.getChecked() ? 1 : 0, "{{ $item->id }}")
          }
        });
        switchs_prank[{{ $item->id }}] = s
        // 初始化hidden开关
        var s = new Switch($("#switch_hidden{{ $item->id }}")[0], {
          size: 'small',
          // checked: "{{ $item->hidden }}" == "0",
          onChange: function() {
            update_hidden(this.getChecked() ? 0 : 1, "{{ $item->id }}")
          }
        });
        switchs_hidden[{{ $item->id }}] = s
      @endforeach
    })

    // 切换hidden开关
    function update_hidden(hidden, id = -1) {
      if (lock_switch_onchange) // 已加锁，禁止执行，否则会发生递归
        return;
      var cids = [];
      if (id != -1) { ///单独一个
        cids = [id]
      } else {
        lock_switch_onchange = true
        $('.cb input[type=checkbox]:checked').each(function() {
          cids.push($(this).val());
          if (hidden)
            switchs_hidden[$(this).val()].off()
          else
            switchs_hidden[$(this).val()].on()
        });
        lock_switch_onchange = false
      }
      $.post(
        '{{ route('admin.contest.update_hidden') }}', {
          '_token': '{{ csrf_token() }}',
          'cids': cids,
          'hidden': hidden,
        },
        function(ret) {
          if (id == -1) {
            Notiflix.Notify.Success('修改成功');
          } else {
            if (ret > 0) {
              Notiflix.Notify.Success('修改成功');
            } else Notiflix.Report.Failure('修改失败', '没有可以更新的数据或权限不足', 'confirm')
          }
        }
      );
    }
  </script>

  <script type="text/javascript">
    // 修改竞赛公开榜单字段 api
    function update_public_rank(public_rank, id = -1) {
      if (lock_switch_onchange) // 已加锁，禁止执行，否则会发生递归
        return;
      var cids = [];
      if (id != -1) {
        cids = [id]
      } else {
        lock_switch_onchange = true
        $('.cb input[type=checkbox]:checked').each(function() {
          cids.push($(this).val());
          if (public_rank)
            switchs_prank[$(this).val()].on()
          else
            switchs_prank[$(this).val()].off()
        });
        lock_switch_onchange = false
      }
      $.post(
        '{{ route('admin.contest.update_public_rank') }}', {
          '_token': '{{ csrf_token() }}',
          'cids': cids,
          'public_rank': public_rank,
        },
        function(ret) {
          if (id == -1) {
            Notiflix.Notify.Success('修改成功');
          } else {
            if (ret > 0) {
              Notiflix.Notify.Success('修改成功');
            } else Notiflix.Report.Failure('修改失败', '没有可以更新的数据或权限不足', 'confirm')
          }
        }
      );
    }

    // 修改竞赛的位置顺序 api
    function update_contest_order(url) {
      $.ajax({
        method: 'patch',
        url: url,
        success: function(ret) {
          if (ret.ok)
            location.reload()
          else
            Notiflix.Notify.Failure(ret.msg);
        }
      });
    }

    //修改竞赛的类别 api
    function update_contest_cate_id(url) {
      $.ajax({
        method: 'patch',
        url: url,
        success: function(ret) {
          if (ret.ok)
            Notiflix.Notify.Success(ret.msg);
          else
            Notiflix.Notify.Failure(ret.msg);
        }
      });
    }

    // 复制竞赛
    function clone_contest(cid) {
      Notiflix.Confirm.Show('克隆竞赛', '您即将克隆这场比赛，是否继续？', '继续', '取消', function() {
        $.post(
          '{{ route('admin.contest.clone') }}', {
            'cid': cid,
          },
          function(ret) {
            ret = JSON.parse(ret);
            setTimeout(function() {
              if (ret.cloned) {
                Notiflix.Confirm.Init({
                  plainText: false, //使<br>可以换行
                });
                Notiflix.Confirm.Show('克隆成功', '新克隆竞赛：' + ret.cloned_cid + '，是否编辑？' +
                  '<br>注意：若参赛权限为private，您需要重新录入参赛账号', '编辑', '取消',
                  function() {
                    location.href = ret.url;
                  });
              } else {
                Notiflix.Report.Failure("克隆失败", "要克隆的竞赛不存在！", "好的");
              }
            }, 450);
          }
        );
      });
    }

    // 删除竞赛
    function delete_contest(cid, tr) {
      Notiflix.Confirm.Show('删除竞赛', '删除后这场竞赛记录将永久丢失，确定删除？', '确定', '返回', function() {
        $.ajax({
          type: 'delete',
          url: '{{ route('api.admin.contest.delete', '??') }}'.replace('??', cid),
          success: function(ret) {
            if (ret.ok) {
              Notiflix.Notify.Success(ret.msg);
              $(tr).hide()
            } else {
              Notiflix.Report.Init({
                plainText: false, // 使<br>可以换行
              })
              Notiflix.Report.Failure('删除失败', ret.msg, '返回')
            }
          }
        })
      });
    }
  </script>
@endsection
