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
    <div id="innerBodyMain" style="margin-right: -14px;">
        <div id="adBanner">
            <img src="{{ WEB_IMG }}/visual/products.jpg" width="767" height="203" /></div>
        <div id="pagePath">
            <a href="{{ route('web.index.index') }}">首頁</a><span class="side">&nbsp;</span>
            @if(Request::is('search'))
                搜尋
            @else
                產品資訊
            @endif
        </div>
        <div id="mainContentsFill">
            <h1>
                @if(Request::is('search'))
                    產品資訊：{{ request("keyword") }}
                @else
                    產品資訊
                @endif
            </h1>
        </div>
        <div class="blockMain">
            <div class="mainProducts">
                @if(!empty($list))
                    <ul>
                        @foreach($list as $v)
                            <li>
                                @if(empty($v['thumb_url_dis']))
                                    <img src="{{ WEB_IMG }}/products/Products01s.jpg" alt="Products" width="174" height="130"
                                         class="products" />
                                @else
                                    <img src="{{ $v['thumb_url_dis'][0] }}" alt="Products" width="174" height="130"
                                         class="products" />
                                @endif
                                <div class="productsNameBox">
                                    <a href="{{ route('web.products.detail').'?id='.$v['id'] }}">{{ str_limit($v['name'], 60) }}</a>
                                </div>
                                <a href="{{ route('web.products.detail').'?id='.$v['id'] }}" class="no">
                                    <img src="{{ WEB_CSS }}/geuc/images/iconProductMore.gif" alt="more" width="64" height="8" border="0" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>暂无数据...</p>
                @endif
            </div>
            <div id="page" style="text-align: center; margin-top: 20px"></div>
            <div class="footer">
            </div>
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
