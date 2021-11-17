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
        <div id="adBanner"><img src="{{ WEB_IMG }}/visual/products.jpg" width="768" height="204" /></div>
        <div id="pagePath"><a href="{{ route('web.index.index') }}">首頁</a><span class="side">&nbsp;</span><a href="{{ route('web.products.index') }}">產品資訊</a><span class="side">&nbsp;</span>產品詳細內容</div>
        <div id="productsDetail">
            <h3>{{ $product['sn'] }}</h3>
            <h1>{{ $product['name'] }}</h1>
            <div id="productsPhotos"><img src="../images/products/Products01.jpg" width="374" height="284" />
                <div class="productsAlbumlist">
                    <ul>
                        @foreach($product['img_url_dis'] as $img)
                            <li>
                                <a href="{{ $img }}" rel="lightbox[Factory]" title="{{ $product['name'] }}">
                                    <img src="{{ $img }}" alt="{{ $product['name'] }}" border="0" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="photosClear"></div>
                </div>
            </div>
            <div id="productsDetailInfo">
                <h2>產品特色</h2>
                <p>{{ $product['desc'] }}</p>
                <h2>產品尺寸</h2>
                <p>{{ $product['spec'] }}</p>
                <h2>產品顏色</h2>
                <div class="productsAlbumlist color">
                    <ul>
                        @foreach($colors as $color)
                            @if(in_array($color['id'], $product['color_arr']))
                            <li><img src="{{ $color['img_url_dis'][0] }}" alt="" border="0" />{{ $color['name'] }}</li>
                            @endif
                        @endforeach
                    </ul>
                    <div class="photosClear"></div>
                </div>
                <h2>相關配件</h2>
                <table width="100%" border="0" cellpadding="2" cellspacing="0">
                    @foreach($accessories as $accessory)
                        <tr>
                            <td width="120">{{ $accessory['sn'] ?: '' }}</td>
                            <td>{{ $accessory['name'] }}</td>
                        </tr>
                    @endforeach
                </table>
{{--                <h2>相關文件</h2>--}}
{{--                <div class="files">--}}
{{--                    <ul>--}}
{{--                        <li class="iconJPG"><a href="#">尺寸圖</a></li>--}}
{{--                        <li class="iconJPG"><a href="#">尺寸圖</a></li>--}}
{{--                        <li class="iconPDF"><a href="#">安裝教學</a></li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
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
