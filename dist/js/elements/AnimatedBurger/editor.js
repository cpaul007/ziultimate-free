(()=>{var e={};e.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(e){if("object"==typeof window)return window}}(),(()=>{var t;e.g.importScripts&&(t=e.g.location+"");var r=e.g.document;if(!t&&r&&(r.currentScript&&(t=r.currentScript.src),!t)){var o=r.getElementsByTagName("script");o.length&&(t=o[o.length-1].src)}if(!t)throw new Error("Automatic publicPath is not supported in this browser");t=t.replace(/#.*$/,"").replace(/\?.*$/,"").replace(/\/[^\/]+$/,"/"),e.p=t+"../../../"})(),e.p=window.zionBuilderPaths[{}.appName],(()=>{"use strict";const e=zb.editor,t=zb.vue,r={key:0},o=(0,t.createVNode)("span",{class:"hamburger-box"},[(0,t.createVNode)("span",{class:"hamburger-inner"})],-1),n={key:0,class:"hamburger-label"},s={name:"zu_burger",props:["options","element"],methods:{toggleBurgerClass:function(e){e.currentTarget.classList.contains("is-active")?e.currentTarget.classList.remove("is-active"):e.currentTarget.classList.add("is-active")}},computed:{toggleClass(){if("yes"==this.options.preview_dropdown)return"zu-burger-sub-menu--open"}},render:function(e,s,a,i,l,c){const u=(0,t.resolveComponent)("SortableContent");return"zu"!=a.options.el_valid&&null!=a.options.el_valid?((0,t.openBlock)(),(0,t.createBlock)("div",r,[(0,t.renderSlot)(e.$slots,"start"),(0,t.createVNode)("button",{class:["hamburger ",["hamburger--"+a.options.varient+("r"==a.options.varient_type?"-r":""),{"is-active":a.options.is_active}]],type:"button","aria-label":"{{options.aria_label}}","aria-controls":"navigation",onClick:s[1]||(s[1]=(...e)=>c.toggleBurgerClass&&c.toggleBurgerClass(...e))},[o,a.options.menu_text?((0,t.openBlock)(),(0,t.createBlock)("span",n,(0,t.toDisplayString)(a.options.menu_text),1)):(0,t.createCommentVNode)("",!0)],2),"yes"==a.options.has_dropdown?((0,t.openBlock)(),(0,t.createBlock)("div",{key:0,class:["zu-burger-sub-menu",c.toggleClass]},[(0,t.createVNode)(u,{key:a.element.uid,element:a.element},null,8,["element"])],2)):(0,t.createCommentVNode)("",!0),(0,t.renderSlot)(e.$slots,"end")])):(0,t.createCommentVNode)("",!0)}};(0,e.registerElementComponent)({elementType:"zu_burger",component:s})})()})();