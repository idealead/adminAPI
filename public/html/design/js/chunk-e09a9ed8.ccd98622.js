(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-e09a9ed8"],{"2a7e":function(n,e,a){},"61b4":function(n,e,a){"use strict";var s=a("2a7e"),i=a.n(s);i.a},b43f:function(n,e,a){"use strict";a.r(e);var s=function(){var n=this,e=n.$createElement,a=n._self._c||e;return a("div",{staticClass:"myInfo_w"},[a("div",{staticClass:"info_wrap"},[a("p",{staticClass:"info_tt"},[n._v("基本信息")]),a("div",{staticClass:"info_detail"},[a("ul",[a("li",[a("span",[n._v("用户名")]),a("span",{directives:[{name:"show",rawName:"v-show",value:n.info.user_name,expression:"info.user_name"}]},[n._v(n._s(n.info.user_name))])]),a("li",[a("span",[n._v("性别")]),a("span",{directives:[{name:"show",rawName:"v-show",value:""!=n.sex,expression:"sex != ''"}]},[n._v(n._s(n.sex))])]),a("li",[a("span",[n._v("所在团队")]),a("span",{directives:[{name:"show",rawName:"v-show",value:n.info.dep_name,expression:"info.dep_name"}]},[n._v(n._s(n.info.dep_name))])]),a("li",[a("span",[n._v("剩余积分")]),a("span",{directives:[{name:"show",rawName:"v-show",value:n.info.integral,expression:"info.integral"}]},[n._v(n._s(n.info.integral)+"积分")])]),a("li",[a("span",[n._v("消耗积分")]),a("span",{directives:[{name:"show",rawName:"v-show",value:n.info.pay_integral,expression:"info.pay_integral"}]},[n._v(n._s(n.info.pay_integral)+"积分")])])])])]),n.loading?a("div",{staticClass:"loading"},[a("span")]):n._e()])},i=[],o=(a("f5ef"),a("2f62"),a("22ce")),t={name:"info",props:{authorId:""},data:function(){return{info:Object,loading:!1}},mounted:function(){},computed:{sex:function(){return 1===this.info.sex?"男":2===this.info.sex?"女":""}},methods:{},beforeRouteEnter:function(n,e,a){a((function(n){Object(o["n"])().then((function(e){n.info=e.data})).catch((function(n){}))}))}},r=t,f=(a("61b4"),a("2877")),c=Object(f["a"])(r,s,i,!1,null,null,null);e["default"]=c.exports}}]);