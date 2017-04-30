<?php

namespace App\Models;

class AdSets
{
    public static function belowArticle() {
        return [
                'below-article-revcontent' => '<div id="rcjsload_0e4cca"></div>
                    <script type="text/javascript">
                        (function() {
                            var referer="";try{if(referer=document.referrer,"undefined"==typeof referer)throw"undefined"}catch(exception){referer=document.location.href,(""==referer||"undefined"==typeof referer)&&(referer=document.URL)}referer=referer.substr(0,700);
                            var rcel = document.createElement("script");
                            rcel.id = \'rc_\' + Math.floor(Math.random() * 1000);
                            rcel.type = \'text/javascript\';
                            rcel.src = "http://trends.revcontent.com/serve.js.php?w=17087&t="+rcel.id+"&c="+(new Date()).getTime()+"&width="+(window.outerWidth || document.documentElement.clientWidth)+"&referer="+referer;
                            rcel.async = true;
                            var rcds = document.getElementById("rcjsload_0e4cca"); rcds.appendChild(rcel);
                        })();
                    </script>',
                'below-article-contentad' => '<div id="contentad270620"></div>
<script type="text/javascript">
    (function(d) {
        var params =
        {
            id: "a3041380-a373-4574-ae6b-ecb658101607",
            d:  "cG9zdGl6ZS5jb20=",
            wid: "270620",
            cb: (new Date()).getTime()
        };

        var qs=[];
        for(var key in params) qs.push(key+\'=\'+encodeURIComponent(params[key]));
        var s = d.createElement(\'script\');s.type=\'text/javascript\';s.async=true;
        var p = \'https:\' == document.location.protocol ? \'https\' : \'http\';
        s.src = p + "://api.content-ad.net/Scripts/widget2.aspx?" + qs.join(\'&\');
        d.getElementById("contentad270620").appendChild(s);
    })(document);
</script>',
                'below-article-adnow' => '<div id="SC_TBlock_216610" class="SC_TBlock">Loading...</div>',
                'below-article-adsense-matched' => '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<ins class="adsbygoogle"
     style="display:block"
     data-ad-format="autorelaxed"
     data-ad-client="ca-pub-1766805469808808"
     data-ad-slot="4431216572"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>'
            ];
    }
}