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
        <div id="adBanner"><img src="images/visual/style.jpg" width="767" height="203" /></div>
        <div id="pagePath"><a href="{{ route('web.index.index') }}">首頁</a><span class="side">&nbsp;</span>生活風格</div>
        <div id="mainContentsFill">
            <h1>生活風格</h1>
            <h2>Contemporary</h2>
            <div class="albumlist">
                <ul>
                    <li><a href="images/style/contemporary/001.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/style/contemporary/s/001.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/style/contemporary/002.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/style/contemporary/s/002.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/style/contemporary/003.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/style/contemporary/s/003.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/style/contemporary/004.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/style/contemporary/s/004.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/style/contemporary/005.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/style/contemporary/s/005.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/style/contemporary/006.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/style/contemporary/s/006.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/style/contemporary/007.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/style/contemporary/s/007.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/style/contemporary/008.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/style/contemporary/s/008.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/style/contemporary/009.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/style/contemporary/s/009.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/style/contemporary/010.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/style/contemporary/s/010.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/style/contemporary/011.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/style/contemporary/s/011.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/style/contemporary/012.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/style/contemporary/s/012.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/style/contemporary/013.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/style/contemporary/s/013.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/style/contemporary/014.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/style/contemporary/s/014.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/style/contemporary/015.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/style/contemporary/s/015.jpg" alt="Contemporary" border="0" /></a></li>
                </ul>
                <div class="photosClear"></div>
            </div>
        </div>
@endsection

{{--自定義js--}}
@section('script')
    @parent
    <script>
    </script>
@endsection
