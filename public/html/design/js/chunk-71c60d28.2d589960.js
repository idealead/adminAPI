(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-71c60d28"],{"3cc2":function(t,e,n){"use strict";n.r(e);var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"inputMsg_w"},[n("div",{staticClass:"common_wrap ps0"},[n("transition",{attrs:{"enter-active-class":"animated fadeIn","leave-active-class":"animated fadeOut"}},[n("div",{directives:[{name:"show",rawName:"v-show",value:!t.isEntryInfo,expression:"!isEntryInfo"}],staticClass:"page_index"},[n("div",{staticClass:"move_tag"}),n("div",{staticClass:"page_tags"},t._l(4,(function(e,a){return n("span",{class:e==t.answer_index?"active":""},[t._v(t._s(e))])})),0)])]),t._m(0),n("div",{staticClass:"click_btns flex"},[n("transition",{attrs:{appear:"","appear-active-class":"animated fadeInLeft","enter-active-class":"animated bounceInLeft","leave-active-class":"animated bounceOutLeft"}},[1!=t.answer_index?n("div",{staticClass:"prev_btn click_btn",on:{click:function(e){return t.prevFunc(t.answer_index)}}}):t._e()]),n("transition",{attrs:{"enter-active-class":"animated bounceInRight","leave-active-class":"animated bounceOutRight",appear:"","appear-active-class":"animated fadeInRight"}},[t.isEntryInfo?t._e():n("div",{staticClass:"next_btn click_btn",on:{click:function(e){return t.nextFunc(t.answer_index)}}})])],1)],1),n("router-view")],1)},c=[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"circle_change ps0"},[a("img",{staticClass:"dot_bg",attrs:{src:n("6f66")}}),a("img",{staticClass:"circle",attrs:{src:n("26d3")}})])}],i=(n("f5ef"),n("2f62")),r=n("1209");function s(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(t);e&&(a=a.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,a)}return n}function o(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?s(Object(n),!0).forEach((function(e){u(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):s(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}function u(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}var l={name:"inputMsg",props:{},data:function(){return{}},watch:{answer_index:"move_tags_func"},computed:o({},Object(i["c"])(["answer_index","questionOriginData"]),{isEntryInfo:function(){return"entry-info"==this.$route.name}}),mounted:function(){this.move_tags_func()},methods:{prevFunc:function(t){var e=this;e.$router.go(-1),e.isEntryInfo?e.mtj_baidu("prev","click","toQA_".concat(t)):(e.setAnswerIndex(t-1),e.mtj_baidu("prev","click","toQA_".concat(t-1)))},nextFunc:function(t){var e=this;4===t?(e.$router.push("/inputMsg/entry-info"),e.mtj_baidu("next","click","uploadLogo_product")):t<4&&(e.$router.push({path:"/inputMsg/q".concat(t+1)}),e.mtj_baidu("next","click","toQA_".concat(t+1))),e.setAnswerIndex(t+1)},move_tags_func:function(){var t=this;Object(r["a"])({targets:".move_tag",easing:"easeInOutQuad",duration:400,translateY:function(){return.3*(t.answer_index-1)+"rem"},scale:[{value:.8},{value:1}]})}}},f=l,p=(n("56a0"),n("2877")),d=Object(p["a"])(f,a,c,!1,null,"b499a59a",null);e["default"]=d.exports},"56a0":function(t,e,n){"use strict";var a=n("885c"),c=n.n(a);c.a},"885c":function(t,e,n){}}]);