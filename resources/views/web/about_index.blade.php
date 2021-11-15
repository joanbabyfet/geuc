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
        <div id="adBanner"><img src="{{ WEB_IMG }}/visual/aboutUs.jpg" width="768" height="204"/></div>
        <div id="pagePath"><a href="{{ route('web.index.index') }}">首頁</a><span class="side">&nbsp;</span>關於我們</div>
        <div id="mainContents">
            <h1>關於我們</h1>
            <p>
                禹臣位於新竹地區並深耕二十多年，向來以成就設計”家”的藝術，造就時尚創意生活為目標；公司隨著專業服務團隊與市場形象不斷的成長，目前公司事業版圖已包含衛浴、廚具、傢具等三大事業群，除了台北、新竹合計四家直營旗艦店外，全省經銷夥伴亦陸續增加台北、台中、台南、宜蘭等各點，為全省各地區的客戶服務；對顧客而言，禹臣開啟了國際視窗與精緻居家風潮；對市場而言，禹臣引導時尚潮流並建立高規格的經營風格。 </p>
            <p>禹臣集團在不斷的求新求變下，首次結合傢俱、衛浴及廚具為”家”描繪出與歐洲零時差的居家提案，邀您一窺當代居家全領域資訊，結合義大利Poltrona Frau
                百年傢俱與義大利CATALANO前衛衛浴、POZZI-GINORI義大利國寶衛浴、美國KOHLER精品衛浴，搭配HANSGROHE德國專業龍頭以及義大利COMPREX時尚廚具、義式風格STOSA
                美學廚具和獨步全球的德國百年頂級Poggenpohl訂製廚具讓整個家的舒適與滿足感再昇華。成熟內斂的系列風格與原地生產的品質堅持，讓禹臣在豪宅市場備受肯定。 </p>
        </div>
        <div id="mainContentsInfo"><img src="{{ WEB_IMG }}/contentsInfo01.jpg" width="192" height="204"/></div>
    </div>
@endsection

{{--自定義js--}}
@section('script')
    @parent
    <script>
    </script>
@endsection
