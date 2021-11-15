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
        <div id="adBanner">
            <img src="{{ WEB_IMG }}/visual/news.jpg" width="767" height="203"/></div>
        <div id="pagePath">
            <a href="{{ route('web.index.index') }}">首頁</a><span class="side">&nbsp;</span>新聞訊息
        </div>
        <div id="mainContents">
            <h1>
                新聞訊息</h1>
            <div class="newsB">
                @if(!empty($list))
                    <ul>
                        @foreach($list as $v)
                            <li>
                                <a href="{{ route('web.news.detail').'?id='.$v['id'] }}">{{ str_limit($v['title'], 60) }}</a>
                                @if($v['is_hot'])<img src="styles/sienergy/images/iconNewsHot.gif" width="17" height="5"
                                                      align="texttop"/>@endif</li>
                        @endforeach
                    </ul>
                @else
                    <p>目前無最新訊息...</p>
                @endif
            </div>
            <div id="page" style="text-align: center; margin-top: 20px"></div>
        </div>
        <div id="mainContentsInfo">
        </div>
    </div>
@endsection

{{--自定義js--}}
@section('script')
    @parent
    <script>
        layui.use('laypage', function () {
            //分頁器
            web.paginate("{{ $pages->total() }}", "{{ $pages->currentPage() }}", "{{ $pages->perPage() }}");
        });
    </script>
@endsection
