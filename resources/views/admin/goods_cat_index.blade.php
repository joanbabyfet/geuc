@extends('admin.layouts.app')

@section('title', config('global.admin.app_title'))

{{--自定義css--}}
@section('style')

@endsection

{{--導航--}}
@section('breadcrumb')
    <div class="admin-breadcrumb">
        <span class="layui-breadcrumb">
          <a href="{{ route('admin.index.index') }}">首頁</a>
          <a><cite>商品分類</cite></a>
        </span>
    </div>
@endsection

{{--內容--}}
@section('content')
    <div class="layui-card-body ">
        <form class="layui-form layui-col-space5" id="search-form" method="GET">
            <div class="layui-inline layui-show-xs-block">
                <input type="text" name="name" placeholder="請輸入分類名稱" value="{{ request("name") }}" autocomplete="off"
                       class="layui-input">
            </div>
            <div class="layui-inline layui-show-xs-block">
                <select name="store_id" lay-verify="" lay-search>
                    <option value=""></option>
                    {!! make_options($store_options, request("store_id")) !!}
                </select>
            </div>
            <div class="layui-inline layui-show-xs-block">
                <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                <button type="reset" id="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </form>
    </div>
    <div class="layui-card-body ">
        <div class="layui-btn-container">
            <a class="layui-btn layui-btn-sm" onclick="admin.openLayerForm('{{ route("admin.goods_cat.add") }}', '分類添加', 'POST', '550px', '400px')"><i class="layui-icon"></i>新增</a>
{{--            <a class="layui-btn layui-btn-sm layui-btn-primary" lay-event="batch_del"><i class="layui-icon">&#xe640;</i>批量刪除</a>--}}
{{--            <a class="layui-btn layui-btn-sm layui-btn-primary" lay-event="batch_enable"><i class="layui-icon">&#xe605;</i>批量啟用</a>--}}
{{--            <a class="layui-btn layui-btn-sm layui-btn-primary" lay-event="batch_disable"><i class="layui-icon">&#x1006;</i>批量禁用</a>--}}
        </div>
        <table class="layui-table layui-form" id="tree-table" lay-size="sm"></table>
    </div>
    <!--匯出數據进度条-->
    @include('admin.common.pup_progress')
@endsection

{{--自定義js--}}
@section('script')
    <script>
        var verify = { //自定義表單驗證規則
        };

        layui.use(['form', 'table', 'treeTable'], function () {
            var table = layui.table;
            var form = layui.form;
            form.verify(verify);
            //table.init('list'); //初始化,靜態表格轉動態,獲取lay-filter值,獲取lay-filter值

            var treeTable = layui.treeTable;
            treeTable.render({  //執行方法渲染
                elem: '#tree-table',
                data: {!! $list !!},
                //is_checkbox: true, //顯示複選框
                icon_key: 'name', //必須
                primary_key: 'id',
                parent_key: "pid", //父級id
                end: function(e){
                    form.render();
                },
                cols: [
                    { key: 'id', title: 'ID' },
                    { key: 'name', title: '分類名稱',
                        template: function(item){
                            if(item.level == 0){
                                return '<span style="color:red;">'+item.name+'</span>';
                            }else if(item.level == 1){
                                return '<span style="color:green;">'+item.name+'</span>';
                            }else if(item.level == 2){
                                return '<span style="color:blue;">'+item.name+'</span>';
                            }else if(item.level == 3){
                                return '<span style="color:grey;">'+item.name+'</span>';
                            }
                        }
                    },
                    { key: 'pid', title: '上級ID' },
                    { key: 'store_name_dis', title: '所屬店舖',
                        template: function (item) {
                            return item.store_name_dis ? item.store_name_dis : '-';
                        }
                    },
                    { key: 'status_dis', title: '狀態', align: 'center' },
                    { title: '操作', align: 'center',
                        template: function(item){
                            return '<a class="layui-btn layui-btn-xs" lay-filter="edit">編輯</a>' +
                                '<a class="layui-btn layui-btn-xs layui-btn-danger" lay-filter="delete">刪除</a>';
                        }
                    }
                ]
            });

            //监听樹事件
            treeTable.on('tree(delete)', function (data) {
                admin.tableDataDelete('{{ route("admin.goods_cat.delete").'?ids[]=' }}' + data.item.id, this, true);
            });

            treeTable.on('tree(edit)', function (data) {
                admin.openLayerForm('{{ route("admin.goods_cat.edit").'?id=' }}' + data.item.id, "菜單編輯", 'POST', '500px', '350px');
            });

            //重置
            $('#reset').on('click', function (e) {
                $('input[name="name"]').val("");
                $('select[name="store_id"]').val("");
                $('#search-form').submit();
            });
        });
    </script>
@endsection
