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
        <div id="adBanner"><img src="{{ WEB_IMG }}/visual/news.jpg" width="767" height="203" /></div>
        <div id="pagePath"><a href="{{ route('web.index.index') }}">首頁</a><span class="side">&nbsp;</span><a href="{{ route('web.news.index') }}">新聞訊息</a><span class="side">&nbsp;</span>新聞內容</div>
        <div id="mainContents">
            <h1>{{ $news['title'] }}</h1>
            {!! $news['content'] !!}
        </div>
        <div id="mainContentsInfo"></div>
    </div>
@endsection

{{--自定義js--}}
@section('script')
    @parent
    <script>
    </script>
@endsection
