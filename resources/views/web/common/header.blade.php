<div id="innerHeaderNav">
    <ul>
        <li><a href="{{ route('web.index.index') }}"{!! Request::is('/') ? ' class="current"':'' !!}>首頁</a></li>
        <li><a href="{{ route('web.about.index') }}"{!! Request::is('about') ? ' class="current"':'' !!}>關於我們</a></li>
        <li><a href="{{ route('web.news.index') }}"{!! Request::is('news') ? ' class="current"':'' !!}>新聞訊息</a></li>
        <li><a href="{{ route('web.products.index') }}"{!! Request::is('products') ? ' class="current"':'' !!}>產品資訊</a></li>
        <li><a href="{{ route('web.style.index') }}"{!! Request::is('style') ? ' class="current"':'' !!}>生活風格</a></li>
        <li><a href="{{ route('web.display.index') }}"{!! Request::is('display') ? ' class="current"':'' !!}>展示中心</a></li>
        <li><a href="{{ route('web.contact.index') }}"{!! Request::is('contact') ? ' class="current"':'' !!}>聯絡我們</a></li>
    </ul>
</div>
