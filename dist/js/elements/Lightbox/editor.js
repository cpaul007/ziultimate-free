(()=>{var e={};e.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(e){if("object"==typeof window)return window}}(),(()=>{var o;e.g.importScripts&&(o=e.g.location+"");var t=e.g.document;if(!o&&t&&(t.currentScript&&(o=t.currentScript.src),!o)){var n=t.getElementsByTagName("script");n.length&&(o=n[n.length-1].src)}if(!o)throw new Error("Automatic publicPath is not supported in this browser");o=o.replace(/#.*$/,"").replace(/\?.*$/,"").replace(/\/[^\/]+$/,"/"),e.p=o+"../../../"})(),e.p=window.zionBuilderPaths[{}.appName],(()=>{"use strict";const e=zb.editor,o=zb.vue,t={key:0},n={key:0,class:"zu-lightbox-close"},i={key:1,class:"zulb-slider-navigation"},s={class:"nav-buttons prev"},l={key:1,class:"prev-text btn-text"},c={class:"nav-buttons next"},r={key:0,class:"next-text btn-text"},a={name:"zu_lightbox",props:["options","api","element"],computed:{iconConfig(){return this.options.close_icon},prevIconConfig(){return this.options.prev_icon},nextIconConfig(){return this.options.next_icon},getElementClasses(){let e="zu-lightbox--"+this.options.preview;return this.options.disable_backdrop||(e+=" zu-lightbox--has-backdrop"),e},getFullScreenIcon:()=>JSON.parse('"\\uf065"').trim()},render:function(e,a,p,u,g,b){const m=(0,o.resolveComponent)("SortableContent"),d=(0,o.resolveComponent)("ElementIcon");return"zu"!=p.options.zuel_lb&&null!=p.options.zuel_lb&&"hidden"!=p.options.preview?((0,o.openBlock)(),(0,o.createBlock)("div",{key:0,class:["zu-lightbox-builder",b.getElementClasses]},["open"!=p.options.preview?((0,o.openBlock)(),(0,o.createBlock)("div",t,"Lightbox")):(0,o.createCommentVNode)("",!0),(0,o.renderSlot)(e.$slots,"start"),(0,o.createVNode)("div",(0,o.mergeProps)({class:"zu-lightbox-container"},p.api.getAttributesForTag("lightbox_styles"),{class:p.api.getStyleClasses("lightbox_styles")}),[(0,o.createVNode)(m,{key:p.element.uid,element:p.element},null,8,["element"]),"custom"!=p.options.closebtn_option||p.options.has_fullscreen_button?((0,o.openBlock)(),(0,o.createBlock)("div",n,[p.options.has_fullscreen_button?((0,o.openBlock)(),(0,o.createBlock)("span",{key:0,"data-znpbiconfam":"Font Awesome 5 Free Solid","data-znpbicon":b.getFullScreenIcon,class:"zu-lightbox-expand__icon zu-lightbox-fullscreen",role:"button","aria-label":"fullscreen"},null,8,["data-znpbicon"])):(0,o.createCommentVNode)("",!0),p.options.close_icon&&"custom"!=p.options.closebtn_option?((0,o.openBlock)(),(0,o.createBlock)(d,(0,o.mergeProps)({key:1,class:"zu-lightbox-close__icon"},p.api.getAttributesForTag("icon_styles"),{iconConfig:b.iconConfig,class:p.api.getStyleClasses("icon_styles")}),null,16,["iconConfig","class"])):(0,o.createCommentVNode)("",!0)])):(0,o.createCommentVNode)("",!0)],16),p.options.enabled_slider&&"open"==p.options.preview?((0,o.openBlock)(),(0,o.createBlock)("nav",i,[(0,o.createVNode)("span",s,[p.options.prev_icon?((0,o.openBlock)(),(0,o.createBlock)(d,{key:0,class:"zu-lightbox-prev__icon btn-icon",iconConfig:b.prevIconConfig},null,8,["iconConfig"])):(0,o.createCommentVNode)("",!0),p.options.prev_text?((0,o.openBlock)(),(0,o.createBlock)("span",l,(0,o.toDisplayString)(p.options.prev_text),1)):(0,o.createCommentVNode)("",!0)]),(0,o.createVNode)("span",c,[p.options.next_text?((0,o.openBlock)(),(0,o.createBlock)("span",r,(0,o.toDisplayString)(p.options.next_text),1)):(0,o.createCommentVNode)("",!0),p.options.next_icon?((0,o.openBlock)(),(0,o.createBlock)(d,{key:1,class:"zu-lightbox-next__icon btn-icon",iconConfig:b.nextIconConfig},null,8,["iconConfig"])):(0,o.createCommentVNode)("",!0)])])):(0,o.createCommentVNode)("",!0),(0,o.renderSlot)(e.$slots,"end")],2)):(0,o.createCommentVNode)("",!0)}};(0,e.registerElementComponent)({elementType:"zu_lightbox",component:a})})()})();