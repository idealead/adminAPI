(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-fa57febe"],{"3cc2":function(t,e,n){"use strict";n.r(e);var s=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"inputMsg_w"},[n("keep-alive",{attrs:{exclude:"entry-info"}},[n("router-view")],1),n("div",{staticClass:"common_wrap ps0"},[n("transition",{attrs:{"enter-active-class":"animated fadeIn","leave-active-class":"animated fadeOut"}},[n("div",{directives:[{name:"show",rawName:"v-show",value:!t.isEntryInfo,expression:"!isEntryInfo"}],staticClass:"page_index"},[n("div",{staticClass:"move_tag"}),n("div",{staticClass:"page_tags"},t._l(5,(function(e,s){return n("span",{class:e==t.answer_index?"active":""},[t._v(t._s(e))])})),0)])]),t._m(0),n("div",{staticClass:"click_btns flex"},[n("div",{staticClass:"prev_btn click_btn flex",on:{click:function(e){return t.prevFunc(t.answer_index)}}},[n("div",{staticClass:"deco flex"}),n("span",{staticClass:"one"},[t._v("上一步 prev")])]),n("transition",{attrs:{"enter-active-class":"animated bounceInRight","leave-active-class":"animated bounceOutRight"}},[t.isEntryInfo?t._e():n("div",{staticClass:"next_btn click_btn flex",on:{click:function(e){return t.nextFunc(t.answer_index)}}},[n("div",{staticClass:"deco flex"}),n("span",[t._v("下一步 next")])])])],1)],1)],1)},a=[function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"circle_change ps0"},[s("img",{staticClass:"dot_bg",attrs:{src:n("6f66")}}),s("img",{staticClass:"circle",attrs:{src:n("26d3")}})])}],r=n("9523"),i=n.n(r),c=(n("f5ef"),n("2f62")),o=n("1209");function u(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(t);e&&(s=s.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,s)}return n}function l(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?u(Object(n),!0).forEach((function(e){i()(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):u(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}var f={name:"inputMsg",props:{},data:function(){return{}},watch:{answer_index:"move_tags_func"},computed:l({},Object(c["c"])(["answer_index"]),{isEntryInfo:function(){return"entry-info"==this.$route.name}}),mounted:function(){this.move_tags_func()},methods:l({},Object(c["b"])(["setAnswerIndex"]),{prevFunc:function(t){var e=this;e.$router.go(-1),e.isEntryInfo||e.setAnswerIndex(t-1)},nextFunc:function(t){var e=this;5===t?e.$router.push("/inputMsg/entry-info"):t<5&&e.$router.push({path:"/inputMsg/q"+(t+1)}),e.setAnswerIndex(t+1)},move_tags_func:function(){var t=this;Object(o["a"])({targets:".move_tag",easing:"easeInOutQuad",duration:400,translateY:function(){return.3*(t.answer_index-1)+"rem"},scale:[{value:.8},{value:1}]})}})},v=f,d=(n("a927"),n("2877")),p=Object(d["a"])(v,s,a,!1,null,"24b7972d",null);e["default"]=p.exports},a927:function(t,e,n){"use strict";var s=n("b446"),a=n.n(s);a.a},b446:function(t,e,n){}}]);
//# sourceMappingURL=chunk-fa57febe.3c8669b1.js.map