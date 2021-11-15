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
        <div id="adBanner"><img src="{{ WEB_IMG }}/visual/contactUs.jpg" width="767" height="203"/></div>
        <div id="pagePath"><a href="{{ route('web.index.index') }}">首頁</a><span class="side">&nbsp;</span>聯絡我們</div>
        <div id="mainContents">
            <h1>聯絡我們</h1>
            <h2>禹臣國際集團 </h2>
            <div class="maps">
                <h3>傢俱事業群 亞洲旗鑑店</h3>
                <p>台北市松德路200巷1號B1<br/>
                    Tel：+886-2-8789-0299
                    Fax：+886-2-8789-0399<br/>
                    E-mail：<a href="mailto:pf@geuc.tw">pf@geuc.tw</a></p>
            </div>
            <div class="maps" style="background:url(images/mapsA.gif) no-repeat right top;">
                <h3>衛浴事業群 新竹門市</h3>
                <p>新竹市林森路277號<br/>
                    Tel：+886-3-525-8717
                    Fax：+886-3-525-8704<br/>
                    E-mail：<a href="mailto:bathrooms@geuc.tw">bathrooms@geuc.tw</a></p>
            </div>
            <div class="maps" style="background:url(images/mapsB.gif) no-repeat right top;">
                <h3>廚具事業群 新竹門市</h3>
                <p>新竹市林森路272號<br/>
                    Tel：+886-3-526-6096
                    Fax：+886-3-526-7371<br/>
                    E-mail：<a href="mailto:kitchens@geuc.tw">kitchens@geuc.tw</a></p>
            </div>
            <div class="maps" style="background:url(images/mapsC.gif) no-repeat right top;">
                <h3>傢俱事業群 新竹門市</h3>
                <p>新竹市東大路201號<br/>
                    Tel：+886-3-532-5385
                    Fax：+886-3-532-5503<br/>
                    E-mail：<a href="mailto:furnitures@geuc.tw">furnitures@geuc.tw</a></p>
            </div>
        </div>
    </div>
@endsection

{{--自定義js--}}
@section('script')
    @parent
    <script>
    </script>
@endsection
