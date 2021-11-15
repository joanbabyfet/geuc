@extends('web.layouts.app')

@section('title', config('global.web.app_title'))

{{--自定義css--}}
@section('style')
    @parent
    <style>
    </style>
@endsection

{{--內容--}}
@section('content')
    <div id="innerBodyMain">
        <div id="adBanner"><img src="{{ WEB_CSS }}/geuc/images/VisualA.jpg" width="768" height="204"/></div>
        <div id="pagePath"><a href="{{ route('web.index.index') }}">首頁</a><span class="side">&nbsp;</span>忘記密碼</div>
        <div id="mainContents">
            <h1>忘記密碼</h1>
            <div class="layui-card">
                <div class="layui-card-body ">
                    <form id="layer-form" class="layui-form" action="" method="POST">
                        @include('web.common.msg')
                        {{ csrf_field() }}
                        <div class="layui-form-item">
                            <label class="layui-form-label label-required-next">郵箱:</label>
                            <div class="layui-input-block">
                                <input type="text" name="email" value="" placeholder="請輸入郵箱"
                                       class="layui-input" required lay-verify="required">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn layui-btn-sm" lay-filter="save" lay-submit>提交</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="mainContentsInfo"><img src="{{ WEB_IMG }}/contentsInfo01.jpg" width="192" height="204"/></div>
    </div>
@endsection

{{--自定義js--}}
@section('script')
    @parent
    <script>
        layui.use('form', function(){
            var form = layui.form;
            var $ = layui.$;

            form.on('submit(save)', function(data){
                $.post("{{ route("web.password.email") }}", data.field, function(response) {
                    if (response.code === 0) {
                        layui.layer.msg(response.msg, {time: 3000, icon: 6});
                        window.location = "{{ route("web.password.request") }}";
                    } else {
                        layui.layer.msg(response.msg, {time: 3000, icon: 5});
                    }
                });
                return false;
            });
        });
    </script>
@endsection
