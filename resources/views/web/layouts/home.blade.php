<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
    <link href="{{ WEB_CSS }}/geuc/style.css" rel="stylesheet" type="text/css"/>
    @section('style')
        {{-- 自定义css --}}
    @show
</head>
<body id="home"
      onload="MM_preloadImages('styles/geuc/images/homeVisual01.jpg','styles/geuc/images/homeNav01_f2.jpg','styles/geuc/images/homeNav02.jpg','styles/geuc/images/homeNav03.jpg','styles/geuc/images/homeVisual02.jpg','styles/geuc/images/homeNav01.jpg','styles/geuc/images/homeNav02_f2.jpg','styles/geuc/images/homeVisual03.jpg','styles/geuc/images/homeNav03_f2.jpg','styles/geuc/images/homeNav04_f2.jpg')">
<div id="containerIdx">
    <div id="HeaderIdx">
        <div id="innerHeaderIdx">
            <div id="headerLogoIdx">
                <img src="styles/geuc/images/homeLogo.jpg" alt="logo" name="Logo" width="184" height="79"
                     border="0" id="Logo"/></div>
            <div id="top">
            </div>
        </div>
    </div>
    <div id="homeNav">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td width="390">
                    <img src="styles/geuc/images/homeVisual01.jpg" name="homeVisual" width="390" height="205"
                         id="homeVisual"/></td>
                <td width="185">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td>
                                <img src="styles/geuc/images/homeNav01.jpg" name="homeNav01" width="185" height="62"
                                     border="0" id="homeNav01"
                                     onmouseover="MM_swapImage('homeVisual','','styles/geuc/images/homeVisual01.jpg','homeNav01','','styles/geuc/images/homeNav01_f2.jpg','homeNav02','','styles/geuc/images/homeNav02.jpg','homeNav03','','styles/geuc/images/homeNav03.jpg',1);MM_changeProp('idxLogoBoxA','','display','block','DIV');MM_changeProp('idxLogoBoxB','','display','none','DIV');MM_changeProp('idxLogoBoxC','','display','none','DIV')"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img src="styles/geuc/images/homeNav02.jpg" name="homeNav02" width="185" height="62"
                                     border="0" id="homeNav02"
                                     onmouseover="MM_swapImage('homeVisual','','styles/geuc/images/homeVisual02.jpg','homeNav01','','styles/geuc/images/homeNav01.jpg','homeNav02','','styles/geuc/images/homeNav02_f2.jpg','homeNav03','','styles/geuc/images/homeNav03.jpg',1);MM_changeProp('idxLogoBoxA','','display','none','DIV');MM_changeProp('idxLogoBoxB','','display','block','DIV');MM_changeProp('idxLogoBoxC','','display','none','DIV')"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img src="styles/geuc/images/homeNav03.jpg" name="homeNav03" width="185" height="62"
                                     border="0" id="homeNav03"
                                     onmouseover="MM_swapImage('homeVisual','','styles/geuc/images/homeVisual03.jpg','homeNav01','','styles/geuc/images/homeNav01.jpg','homeNav02','','styles/geuc/images/homeNav02.jpg','homeNav03','','styles/geuc/images/homeNav03_f2.jpg',1);MM_changeProp('idxLogoBoxA','','display','none','DIV');MM_changeProp('idxLogoBoxB','','display','none','DIV');MM_changeProp('idxLogoBoxC','','display','block','DIV')"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img src="styles/geuc/images/homeNavSide.jpg" name="homeNavSide" width="185" height="19"
                                     border="0" id="homeNavSide"/></td>
                        </tr>
                    </table>
                </td>
                <td>
                    <div id="idxLogoBoxA" class="idxLogoBox">
                        <a href="http://kohler.geuc.tw" target="_blank" hidefocus="true">
                            <img src="styles/geuc/images/homeNavLogo01.jpg" name="homeNavLogo" width="139" height="30"
                                 border="0" align="absmiddle" id="homeNavLogo"/></a></div>
                    <div id="idxLogoBoxB" class="idxLogoBox" style="display: none;">
                        <a href="http://www.stosa.it/" target="_blank">
                            <img src="styles/geuc/images/homeNavLogo02.jpg" name="homeNavLogo"
                                 border="0" align="absmiddle" id="homeNavLogo" style="margin-right: 10px;"/></a><a
                            href="http://www.comprex.it/" target="_blank"></a></div>
                    <div id="idxLogoBoxC" class="idxLogoBox" style="display: none;">
                        <a href="http://www.poltronafrau.com.tw/" target="_blank" hidefocus="true">
                            <img src="styles/geuc/images/homeNavLogo03.jpg" name="homeNavLogo" border="0"
                                 align="absmiddle"
                                 id="homeNavLogo"/></a></div>
                </td>
            </tr>
        </table>
    </div>
    <div id="mainIdx">
        <div id="idxMain">
            @yield('content')
        </div>
        <div id="mainFooterSideIdx">
        </div>
    </div>
    <div id="footerIdx">
        <div id="innerFooter">
            <div class="copyright">
                Copyright © 2010 GEUC.COM All Rights Reserved.
            </div>
        </div>
    </div>
</div>
@section('script')
    {{-- 自定义js --}}
@show
@include('web.common.google_analytics')
<script type="text/javascript">
    <!--
    function MM_preloadImages() { //v3.0
        var d = document;
        if (d.images) {
            if (!d.MM_p) d.MM_p = new Array();
            var i, j = d.MM_p.length, a = MM_preloadImages.arguments;
            for (i = 0; i < a.length; i++)
                if (a[i].indexOf("#") != 0) {
                    d.MM_p[j] = new Image;
                    d.MM_p[j++].src = a[i];
                }
        }
    }

    function MM_findObj(n, d) { //v4.01
        var p, i, x;
        if (!d) d = document;
        if ((p = n.indexOf("?")) > 0 && parent.frames.length) {
            d = parent.frames[n.substring(p + 1)].document;
            n = n.substring(0, p);
        }
        if (!(x = d[n]) && d.all) x = d.all[n];
        for (i = 0; !x && i < d.forms.length; i++) x = d.forms[i][n];
        for (i = 0; !x && d.layers && i < d.layers.length; i++) x = MM_findObj(n, d.layers[i].document);
        if (!x && d.getElementById) x = d.getElementById(n);
        return x;
    }

    function MM_swapImage() { //v3.0
        var i, j = 0, x, a = MM_swapImage.arguments;
        document.MM_sr = new Array;
        for (i = 0; i < (a.length - 2); i += 3)
            if ((x = MM_findObj(a[i])) != null) {
                document.MM_sr[j++] = x;
                if (!x.oSrc) x.oSrc = x.src;
                x.src = a[i + 2];
            }
    }

    function MM_swapImgRestore() { //v3.0
        var i, x, a = document.MM_sr;
        for (i = 0; a && i < a.length && (x = a[i]) && x.oSrc; i++) x.src = x.oSrc;
    }

    function MM_changeProp(objId, x, theProp, theValue) { //v9.0
        var obj = null;
        with (document) {
            if (getElementById)
                obj = getElementById(objId);
        }
        if (obj) {
            if (theValue == true || theValue == false)
                eval("obj.style." + theProp + "=" + theValue);
            else eval("obj.style." + theProp + "='" + theValue + "'");
        }
    }
    //-->
</script>
</body>
</html>
