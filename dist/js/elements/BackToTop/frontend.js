(()=>{var t={};t.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(t){if("object"==typeof window)return window}}(),(()=>{var e;t.g.importScripts&&(e=t.g.location+"");var i=t.g.document;if(!e&&i&&(i.currentScript&&(e=i.currentScript.src),!e)){var r=i.getElementsByTagName("script");r.length&&(e=r[r.length-1].src)}if(!e)throw new Error("Automatic publicPath is not supported in this browser");e=e.replace(/#.*$/,"").replace(/\?.*$/,"").replace(/\/[^\/]+$/,"/"),t.p=e+"../../../"})(),t.p=window.zionBuilderPaths[{}.appName],$(document).ready((function(){var t=function(t){return 1==t.which||13==t.which||32==t.which||null==t.which};$(".zb-el-zuBackToTop").each((function(){var e=JSON.parse($(this).attr("data-zuscroll-config")),i=parseInt(e.speed),r=e.easing;if($(this).hasClass("jump-to-section")){var o=e.offset,n=e.selector;$(this).on("click keypress",(function(e){e.preventDefault(),t(e)&&$("html, body").animate({scrollTop:$(n).offset().top-parseInt(o)},i,r)}))}else{var s=e.visibility;$(this).on("click keypress",(function(e){e.preventDefault(),t(e)&&$("html, body").animate({scrollTop:0},i,r)}));var c=$(this);$(window).scroll((function(){$(this).scrollTop()>=s?c.addClass("backtotop-visible"):c.removeClass("backtotop-visible")}))}}))}))})();