@extends('web.layouts.app')

@section('title', config('global.web.app_title'))

{{--自定義css--}}
@section('style')
    @parent
    <link rel="stylesheet" href="{{ PLUGINS }}/lightbox/css/lightbox.css" type="text/css" media="screen" />
    <style>
    </style>
@endsection

{{--內容--}}
@section('content')
    <div id="innerBodyMain">
        <div id="adBanner"><img src="images/visual/show.jpg" width="767" height="203"/></div>
        <div id="pagePath"><a href="{{ route('web.index.index') }}">首頁</a><span class="side">&nbsp;</span>展示中心</div>
        <div id="mainContentsFill">
            <h1>展示中心</h1>
            <h2>Contemporary</h2>
            <div class="albumlist">
                <ul>
                    <li><a href="images/show/001.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/show/s/001.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/show/002.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/show/s/002.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/show/003.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/show/s/003.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/show/004.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/show/s/004.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/show/005.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/show/s/005.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/show/006.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/show/s/006.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/show/007.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/show/s/007.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/show/008.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/show/s/008.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/show/009.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/show/s/009.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/show/010.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/show/s/010.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/show/011.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/show/s/011.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/show/012.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/show/s/012.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/show/013.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/show/s/013.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/show/014.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/show/s/014.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/show/015.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/show/s/015.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/show/016.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/show/s/016.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/show/017.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/show/s/017.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/show/018.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/show/s/018.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/show/019.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/show/s/019.jpg" alt="Contemporary" border="0" /></a></li>
                    <li><a href="images/show/020.jpg" rel="lightbox[Factory]" title="Contemporary"><img src="images/show/s/010.jpg" alt="Contemporary" border="0" /></a></li>
                </ul>
                <div class="photosClear"></div>
            </div>
        </div>
    </div>
@endsection

{{--自定義js--}}
@section('script')
    @parent
    <script src="{{ PLUGINS }}/lightbox/js/prototype.js" type="text/javascript"></script>
    <script src="{{ PLUGINS }}/lightbox/js/scriptaculous.js?load=effects,builder" type="text/javascript"></script>
    <script src="{{ PLUGINS }}/lightbox/js/lightbox.js" type="text/javascript"></script>
    <script>
    </script>
@endsection
