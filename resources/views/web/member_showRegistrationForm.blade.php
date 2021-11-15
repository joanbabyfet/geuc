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
        <div id="pagePath"><a href="{{ route('web.index.index') }}">首頁</a><span class="side">&nbsp;</span>會員註冊</div>
        <div id="mainContents">
            <h1>會員註冊</h1>
            <div class="layui-card">
                <div class="layui-card-body ">
                    <form id="layer-form" class="layui-form" action="" method="POST">
                        @include('web.common.msg')
                        {{ csrf_field() }}
                        <div class="layui-form-item">
                            <label class="layui-form-label label-required-next">用戶名:</label>
                            <div class="layui-input-block">
                                <input type="text" name="username" value="" placeholder="請輸入用戶名"
                                       class="layui-input" required lay-verify="required">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label label-required-next">用戶密碼:</label>
                            <div class="layui-input-inline">
                                <input type="password" name="password" value="" placeholder="請輸入用戶密碼"
                                       class="layui-input" required lay-verify="required">
                            </div>
                            <div class="layui-form-mid layui-word-aux">必須大於6位，包含大小寫字母和數字</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label label-required-next">確認密碼:</label>
                            <div class="layui-input-inline">
                                <input type="password" name="password_confirmation" value="" placeholder="請輸入確認密碼"
                                       class="layui-input" required lay-verify="required|confirm_pass">
                            </div>
                            <div class="layui-form-mid layui-word-aux"></div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label label-required-next">真實姓名:</label>
                            <div class="layui-input-block">
                                <input type="text" name="realname" value="" placeholder="請輸入真實姓名"
                                       class="layui-input" required lay-verify="required">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label label-required-next">郵箱:</label>
                            <div class="layui-input-block">
                                <input type="text" name="email" value="" placeholder="請輸入郵箱"
                                       class="layui-input" required lay-verify="required">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label label-required-next">手機號:</label>
                            <div class="layui-input-inline" style="width: 100px;">
                                <select name="phone_code" lay-verify="required">
                                    <option value=""></option>
                                    {!! make_options($mobile_prefix_options) !!}
                                </select>
                            </div>
                            <div class="layui-input-inline">
                                <input type="text" name="phone" value="" placeholder="請輸入聯絡電話"
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
        var verify = { //自定義表單驗證規則
            confirm_pass:function(value){
                if($('input[name=password]').val() !== value)
                    return '兩次密碼輸入不一致';
            }
        };

        layui.use('form', function(){
            var form = layui.form;
            var $ = layui.$;

            form.verify(verify);
            form.on('submit(save)', function(data){
            });
        });
    </script>
@endsection
