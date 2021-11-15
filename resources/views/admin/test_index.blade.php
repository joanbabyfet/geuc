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
          <a><cite>测试</cite></a>
        </span>
    </div>
@endsection

{{--內容--}}
@section('content')
    <div class="layui-card-body ">
        <div class="video" id="video" style="width: 833px;height: 500px"></div>
    </div>
    <!--匯出數據进度条-->
    @include('admin.common.pup_progress')
@endsection

{{--自定義js--}}
@section('script')
    <script>
        layui.config({
            //存放拓展模块的根目录
            base: "/vendor/"
        }).extend({
            //设定模块别名
            ckplayer: 'ckplayer/ckplayer'
        }).use(['ckplayer'], function () {
            var ckplayer = layui.ckplayer;
            //定义视频资源地址
            var videoUrl = "https://media.w3.org/2010/05/sintel/trailer.mp4";
            //由于X2的ckplayer需要用url请求方式获取配置json
            //因此定义请求的根目录
            var baseUrl = "/vendor/ckplayer/";

            //定义一个变量：videoObject，用来做为视频初始化配置
            var videoObject = {
                container: '#video',
                variable: 'player',
                video: videoUrl, //视频地址
                baseUrl: baseUrl //配置json所在目录
            };
            var player = new ckplayer(videoObject);//初始化播放器
        });
    </script>
@endsection
