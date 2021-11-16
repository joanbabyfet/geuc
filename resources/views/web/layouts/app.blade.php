<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link href="{{ WEB_CSS }}/geuc/style.css" rel="stylesheet" type="text/css"/>
    <link href="{{ WEB_CSS }}/geuc/form.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ PLUGINS }}/laravel-layui-admin/lib/layui/css/layui.css">
    <link rel="stylesheet" href="{{ PLUGINS }}/lightbox/css/lightbox.css" type="text/css" media="screen" />
    @section('style')
        {{-- 自定义css --}}
    @show
</head>

<body>
<div id="container">
    <div id="Header">
        <div id="innerHeader">
            <div id="headerLogo"><a href="{{ route('web.index.index') }}"><img src="{{ WEB_CSS }}/geuc/images/pageLogo.jpg" alt="logo" name="Logo"
                                                          width="85" height="73" border="0" id="Logo"/></a></div>
            <div id="top" class="brandKohler">
                <div class="serchBox">
                    <form action="{{ route('web.products.search') }}" method="GET">
                    <input name="keyword" type="text" id="keyword" placeholder="請輸入關鍵字" value="{{ request("keyword") }}" />
                        <button type="submit" style="border-width: 0px; cursor:pointer">
                            <img src="{{ WEB_CSS }}/geuc/images/btnSerach.jpg"/>
                        </button>
                    </form>
                </div>
            </div>
            <div id="HeaderNav">
                @include('web.common.header')
            </div>
        </div>
    </div>
    <div id="main">
        <div id="sidebar">
            @include('web.common.sidebar')
        </div>
        <div id="bodyMain">
            @yield('content')
        </div>
        <div id="mainFooterSide"></div>
    </div>
    <div id="footer">
        @include('web.common.footer')
    </div>
</div>
<script src="{{ PLUGINS }}/laravel-layui-admin/lib/layui/layui.js"></script>
<script src="{{ WEB_JS }}/main.js"></script>
<script src="{{ PLUGINS }}/lightbox/js/prototype.js" type="text/javascript"></script>
<script src="{{ PLUGINS }}/lightbox/js/scriptaculous.js?load=effects,builder" type="text/javascript"></script>
<script src="{{ PLUGINS }}/lightbox/js/lightbox.js" type="text/javascript"></script>
@section('script')
    {{-- 自定义js --}}
@show
@include('web.common.google_analytics')
</body>
</html>
