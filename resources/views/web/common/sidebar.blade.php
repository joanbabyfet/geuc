<div id="innerSidebar">
    <div class="blockA">
        <div class="main menu">
            <ul>
                <li class="iconHeader">
                    <a href="javascript:;">
                        @if(Request::is('about'))
                            關於我們
                        @elseif(Request::is('news') || Request::is('news/*'))
                            新聞訊息
                        @elseif(Request::is('products') || Request::is('products/*'))
                            產品資訊
                        @elseif(Request::is('style'))
                            生活風格
                        @elseif(Request::is('display'))
                            展示中心
                        @elseif(Request::is('contact'))
                            聯絡我們
                        @else
                            會員中心
                        @endif
                    </a>
                </li>
                @if(Request::is('products') || Request::is('products/*'))
                <li><a href="{{ route('web.products.index')."?type=new" }}">新品</a></li>
                @endif
                {!! $cats ?? '' !!}
            </ul>
        </div>
        <div class="footer"></div>
    </div>
</div>
