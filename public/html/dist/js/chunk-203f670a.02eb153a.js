(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-203f670a"],{1774:function(t,e,n){"use strict";n.r(e);var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"structure"},[n("h1",[t._v("选择模板架构"+t._s(t.testextends))]),n("div",{staticClass:"structure_block"},[n("div",{staticClass:"select_div",on:{click:t.newStructure}},[t._v("添加架构")]),n("div",{staticClass:"select_div",attrs:{toid:""},on:{click:t.selectDiv}},[t._v("normal")])]),n("div",{staticClass:"structure_block"},t._l(t.structureData,(function(e,a){return n("div",{key:a,staticClass:"select_div temp_div",style:t.backgroundImage(e.data.template_info)},[t._v(t._s(e.data.framework_name))])})),0),t.setNew?n("div",{staticClass:"alertBg"},[n("div",{staticClass:"layoutBlock"},[n("div",{staticClass:"structureName"},[n("p",[t._v("架构名称：")]),n("el-input",{staticClass:"inputArea",attrs:{placeholder:"请输入名称"},model:{value:t.structureName,callback:function(e){t.structureName=e},expression:"structureName"}})],1),n("div",{staticClass:"replaceName"},[n("p",[t._v("替换标签：")]),n("div",{staticClass:"inputArea"},[t._l(t.dynamicTags,(function(e){return n("el-tag",{key:e,attrs:{closable:"","disable-transitions":!1},on:{close:function(n){return t.handleClose(e)}}},[t._v(t._s(e))])})),t.inputVisible?n("el-input",{ref:"saveTagInput",staticClass:"input-new-tag",attrs:{size:"small"},on:{blur:t.handleInputConfirm},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.handleInputConfirm(e)}},model:{value:t.inputValue,callback:function(e){t.inputValue=e},expression:"inputValue"}}):n("el-button",{staticClass:"button-new-tag",attrs:{size:"small"},on:{click:t.showInput}},[t._v("+ New Tag")])],2)]),n("div",{staticClass:"setBtn"},[n("el-button",{staticClass:"createNewBtn",attrs:{type:"primary",round:"",size:"small"},on:{click:t.buildModel}},[t._v("创建")]),n("el-button",{staticClass:"cancelBtn",attrs:{type:"info",round:"",size:"small"},on:{click:function(e){return t.showAddFunc(!1)}}},[t._v("取消")])],1)])]):t._e()])},s=[],r=(n("3acb"),n("536e"),n("c9a0"),n("dbe6"),n("57d6"),n("82a7"),n("f32a"),n("20a8"),n("9757"),n("69af"),n("9d37")),i=n("08c1"),u=n("2427"),c=n.n(u);function o(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(t);e&&(a=a.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,a)}return n}function l(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?o(Object(n),!0).forEach((function(e){Object(r["a"])(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):o(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}var d={name:"structure",props:{structureD:{type:Array,default:function(){return[]}}},data:function(){return{setNew:!1,structureName:"",dynamicTags:["主图","背景","装饰1"],inputVisible:!1,inputValue:"",testextends:""}},computed:l({},Object(i["b"])({user_type:function(t){return t.user.user_type},api:function(t){return t.api}}),{structureData:function(){return this.structureD},backgroundImage:function(){var t=this;return function(e){return""!=e?{backgroundImage:"url(".concat(t.api.images).concat(e[0].preview_path,")")}:""}}}),created:function(){},mounted:function(){},methods:{selectDiv:function(t){var e=this,n=t.target.attributes.toid.value;e.$store.dispatch("changeStructureIdFunc",n),e.$store.dispatch("changeNewStructureIdFunc",""),e.$router.push({path:"/canvas?userType=designer"})},newStructure:function(){var t=this;t.showAddFunc(!0)},showAddFunc:function(t){var e=this;t?e.$set(e,"setNew",!0):e.$set(e,"setNew",!1)},handleClose:function(t){this.dynamicTags.splice(this.dynamicTags.indexOf(t),1)},showInput:function(){var t=this;this.inputVisible=!0,this.$nextTick((function(e){t.$refs.saveTagInput.$refs.input.focus()}))},handleInputConfirm:function(){var t=this.inputValue;t&&this.dynamicTags.push(t),this.inputVisible=!1,this.inputValue=""},buildModel:function(){for(var t=this,e={},n=t.structureName,a=[],s=0;s<t.dynamicTags.length;s++){var r={};r.position=s,r.position_name=t.dynamicTags[s],a.push(r)}e.framework_name=n,e.rule=a,c()({method:"post",url:t.api.add_framework_info,data:e}).then((function(e){"200"==e.data.code&&(t.$store.dispatch("ChangeRenderFunc",{key:"mould_name",value:n}),t.$message({message:"架构创建成功，将跳转至模板编辑页面",type:"success"}),setTimeout((function(){t.$store.dispatch("changeNewStructureIdFunc",parseInt(e.data.id)),t.$store.dispatch("ChangeRenderFunc",{key:"structure_position",value:a}),t.showAddFunc(!1),t.$set(t,"structureName",""),t.$set(t,"dynamicTags",["主图","背景","装饰1"]),t.$router.push({path:"/canvas?userType=designer"})}),1e3))})).catch((function(){}))}}},p=d,f=(n("bd2b"),n("5511")),h=Object(f["a"])(p,a,s,!1,null,"957cd47e",null);e["default"]=h.exports},"57d6":function(t,e,n){"use strict";var a=n("5352"),s=n("0fe1"),r=n("7767"),i=n("30d6"),u=n("7dad"),c=n("83e8"),o=n("1165"),l=n("2e68"),d=Math.max,p=Math.min,f=9007199254740991,h="Maximum allowed length exceeded";a({target:"Array",proto:!0,forced:!l("splice")},{splice:function(t,e){var n,a,l,v,m,b,g=u(this),y=i(g.length),w=s(t,y),_=arguments.length;if(0===_?n=a=0:1===_?(n=0,a=y-w):(n=_-2,a=p(d(r(e),0),y-w)),y+n-a>f)throw TypeError(h);for(l=c(g,a),v=0;v<a;v++)m=w+v,m in g&&o(l,v,g[m]);if(l.length=a,n<a){for(v=w;v<y-a;v++)m=v+a,b=v+n,m in g?g[b]=g[m]:delete g[b];for(v=y;v>y-a+n;v--)delete g[v-1]}else if(n>a)for(v=y-a;v>w;v--)m=v+a-1,b=v+n-1,m in g?g[b]=g[m]:delete g[b];for(v=0;v<n;v++)g[v+w]=arguments[v+2];return g.length=y-a+n,l}})},bd2b:function(t,e,n){"use strict";var a=n("e40a"),s=n.n(a);s.a},dbe6:function(t,e,n){"use strict";var a=n("5352"),s=n("1403").indexOf,r=n("0a27"),i=[].indexOf,u=!!i&&1/[1].indexOf(1,-0)<0,c=r("indexOf");a({target:"Array",proto:!0,forced:u||c},{indexOf:function(t){return u?i.apply(this,arguments)||0:s(this,t,arguments.length>1?arguments[1]:void 0)}})},e40a:function(t,e,n){}}]);
//# sourceMappingURL=chunk-203f670a.02eb153a.js.map