(()=>{var t={};t.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(t){if("object"==typeof window)return window}}(),(()=>{var e;t.g.importScripts&&(e=t.g.location+"");var o=t.g.document;if(!e&&o&&(o.currentScript&&(e=o.currentScript.src),!e)){var n=o.getElementsByTagName("script");n.length&&(e=n[n.length-1].src)}if(!e)throw new Error("Automatic publicPath is not supported in this browser");e=e.replace(/#.*$/,"").replace(/\?.*$/,"").replace(/\/[^\/]+$/,"/"),t.p=e+"../../../"})(),t.p=window.zionBuilderPaths[{}.appName],(()=>{"use strict";const t=zb.editor,e=zb.vue,o={name:"zu_back_to_top",props:["options","api","element"],computed:{getElementOptions(){return JSON.stringify({speed:this.options.speed,visibility:this.options.backbtn_visibility,easing:this.options.easing,selector:this.options.section_selector?this.options.section_selector:"",offset:this.options.offset?this.options.offset:0})}},render:function(t,o,n,i,s,r){const c=(0,e.resolveComponent)("SortableContent");return"zu"==n.options.el_valid||null==n.options.el_valid||n.options.hide_back_button?(0,e.createCommentVNode)("",!0):((0,e.openBlock)(),(0,e.createBlock)("div",{key:0,class:["back"==n.options.use_for?"backtotop":"jump-to-section"],"data-zuscroll-config":r.getElementOptions},[(0,e.renderSlot)(t.$slots,"start"),(0,e.createVNode)(c,{key:n.element.uid,element:n.element},null,8,["element"]),(0,e.renderSlot)(t.$slots,"end")],10,["data-zuscroll-config"]))}};(0,t.registerElementComponent)({elementType:"zu_back_to_top",component:o})})()})();