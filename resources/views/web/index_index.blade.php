@extends('web.layouts.home')

@section('title', config('global.web.app_title'))

{{--自定義css--}}
@section('style')
    @parent
    <style>
    </style>
@endsection

{{--內容--}}
@section('content')
    <div id="innerIndMain">
        <div class="newsA">
            <img src="styles/geuc/images/homeNewsTitle.jpg" width="372" height="18"/>
            <ul>
                @foreach($news as $v)
                    <li><a href="{{ route('web.news.detail').'?id='.$v['id'] }}">{{ str_limit($v['title'], 60) }}</a>
                        @if($v['is_hot'])
                            <img src="/styles/sienergy/images/iconNewsHot.gif" width="17" height="5" align="texttop"/>
                        @endif
                    </li>
                @endforeach
            </ul>
            <div class="more">
                <a href="{{ route('web.news.index') }}">
                    <img src="/styles/geuc/images/iconNewsMore.gif" alt="more" width="34" height="10" border="0"/>
                </a>
            </div>
        </div>
        <div id="mainNav">
            <ul>
                <li><a href="{{ route('web.news.index') }}">
                        <img src="styles/geuc/images/homeNav04.jpg" name="homeNav04" width="203" height="18"
                             id="homeNav04"
                             onmouseover="MM_swapImage('homeNav04','','styles/geuc/images/homeNav04_f2.jpg',1)"
                             onmouseout="MM_swapImgRestore()"/></a></li>
                <li><a href="{{ route('web.about.index') }}">
                        <img src="styles/geuc/images/homeNav05.jpg" name="homeNav05" width="203" height="18"
                             id="homeNav05"
                             onmouseover="MM_swapImage('homeNav05','','styles/geuc/images/homeNav05_f2.jpg',1)"
                             onmouseout="MM_swapImgRestore()"/></a></li>
                <li><a href="{{ route('web.contact.index') }}">
                        <img src="styles/geuc/images/homeNav06.jpg" name="homeNav06" width="203" height="18"
                             id="homeNav06"
                             onmouseover="MM_swapImage('homeNav06','','styles/geuc/images/homeNav06_f2.jpg',1)"
                             onmouseout="MM_swapImgRestore()"/></a></li>
            </ul>
        </div>
        <div id="idxMainSide">
        </div>
    </div>
@endsection

{{--自定義js--}}
@section('script')
    @parent
    <script>
    </script>
@endsection
