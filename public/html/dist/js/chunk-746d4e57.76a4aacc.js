(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-746d4e57"],{2367:function(t,e,a){"use strict";a.r(e);var r=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"split"},[a("div",{staticClass:"structureNav"},[a("el-tabs",{on:{"tab-click":t.handleClick},model:{value:t.first,callback:function(e){t.first=e},expression:"first"}},t._l(t.structureD,(function(e,r){return a("el-tab-pane",{key:r,attrs:{label:e.data.framework_name,name:""+e.data.id,index:r}},[t._v(t._s(e.data.framework_name))])})),1)],1),a("div",{directives:[{name:"loading",rawName:"v-loading",value:t.loading,expression:"loading"}],staticClass:"smodelBlock"},[a("div",{staticClass:"demo-image__preview"},t._l(t.splitModelArr,(function(e,r){return a("div",{key:r,staticClass:"splitModel"},[a("el-image",{staticStyle:{width:"150px",height:"200px"},attrs:{src:1==e.path?t.url:e.path,"preview-src-list":[1==e.path?t.url:e.path],fit:"cover"}}),a("el-tooltip",{attrs:{content:"通过",placement:"bottom",effect:"light"}},[a("i",{staticClass:"el-icon-success"})]),a("el-tooltip",{attrs:{content:"渲染组合",placement:"bottom",effect:"light"}},[a("i",{staticClass:"el-icon-picture-outline-round",on:{click:function(a){return t.renderF(e,r)}}})])],1)})),0)]),a("el-button",{staticClass:"moreSplit",attrs:{type:"primary"},on:{click:t.moreF}},[t._v("查看更多")])],1)},n=[],i=(a("3acb"),a("536e"),a("c9a0"),a("82a7"),a("f32a"),a("20a8"),a("9b70"),a("9757"),a("69af"),a("fd4b")),o=a("9d37"),s=a("075f"),c=a("08c1"),u=a("2427"),l=a.n(u),p=a("10aa");function d(t,e){var a=Object.keys(t);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(t);e&&(r=r.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),a.push.apply(a,r)}return a}function f(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{};e%2?d(Object(a),!0).forEach((function(e){Object(o["a"])(t,e,a[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(a)):d(Object(a)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(a,e))}))}return t}var m={name:"split",props:{structureD:{type:Array}},data:function(){return{url:"https://fuss10.elemecdn.com/8/27/f01c15bb73e1ef3793e64e6b7bbccjpeg.jpeg",srcList:["https://fuss10.elemecdn.com/8/27/f01c15bb73e1ef3793e64e6b7bbccjpeg.jpeg"],splitModelArr:[],structureIndex:0,page:0,now_arr:[],now_page_arr:[],loading:!1,application:null}},computed:f({},Object(c["b"])({user_type:function(t){return t.user.user_type},user_data:function(t){return t.user.user_data},api:function(t){return t.api}}),{structureData:function(){return this.structureD},first:function(){return this.structureData?"".concat(this.structureData[0].data.id):"1"}}),created:function(){var t=this;t.application=new s["Application"]({backgroundColor:16119285,width:2500,height:2500,antialias:1})},mounted:function(){var t=this;t.$set(t,"page",0),t.getSplitPermutation(parseInt(t.structureData[0].data.id))},methods:{renderF:function(t,e){var a=this;if(1==t.path&&0==t.template_id){for(var r=[],n=0;n<t.combination.length;n++){var i={position:n,file_id:t.combination[n][0],src:"".concat(a.api.images).concat(t.combination[n][1])};r.push(i)}a.$set(a,"loading",!0);var o=a.structureData[a.structureIndex].data.template_id;p["a"].tempInit(p["a"],{tempId:o,app:a.application,expand:!1,split:!0,splitData:r},(function(r){a.renderSubmit.bind(a)(r,t,e)}))}else a.$message({message:"此组合已经渲染过啦",type:"warning"})},renderSubmit:function(t,e,a){var r=this,n="http://ht.idealead.hbindex.com"+t.render_preview_img;l()({method:"post",url:r.api.change_combination_template,data:{framework_id:parseInt(e.framework_id),template_id:t.renderTempId,path:n,combination:e.combination_res}}).then((function(t){if(200==t.status&&"200"==t.data.code){var e=r.splitModelArr;e[a].path=n,r.$set(r,"splitModelArr",e),r.$set(r,"loading",!1)}})).catch((function(t){}))},handleClick:function(t,e){var a=this;a.structureIndex!==parseInt(t.index)&&(a.$set(a,"page",0),a.$set(a,"structureIndex",parseInt(t.index)),setTimeout((function(){a.getSplitPermutation(parseInt(a.structureData[a.structureIndex].data.id))}),0))},getSplitPermutation:function(t){var e=this;l()({method:"get",url:"".concat(e.api.get_combination,"?framework_id=").concat(t)}).then((function(t){200==t.status&&"200"==t.data.code&&(t.data.data.length>0?(e.$set(e,"now_page_arr",[]),e.$set(e,"splitModelArr",[]),e.$set(e,"now_arr",t.data.data),e.separateF(e.getPermutationModelData).then((function(t){t()})).catch((function(){e.$set(e,"loading",!1)}))):(e.$message({message:"还没有素材裂变",type:"warning"}),e.$set(e,"now_arr",t.data.data),e.$set(e,"splitModelArr",[]),e.$set(e,"loading",!1)))})).catch((function(t){}))},moreF:function(){var t=this;t.separateF(t.getPermutationModelData).then((function(t){t()})).catch((function(){t.$set(t,"loading",!1)}))},getPermutationModelData:function(){var t=this,e={framework_id:parseInt(t.structureData[0].data.id),data:JSON.stringify(t.now_page_arr)};l()({method:"post",url:t.api.get_framework_combination,data:e}).then((function(e){200==e.status&&"200"==e.data.code&&(t.$set(t,"splitModelArr",[].concat(Object(i["a"])(t.splitModelArr),Object(i["a"])(e.data.data))),t.$set(t,"page",t.page+1),t.$set(t,"loading",!1))})).catch((function(){t.$set(t,"loading",!1)}))},separateF:function(t){var e=this;e.$set(e,"loading",!0);var a=10*e.page,r=a+9,n=[];if(r>e.now_arr.length+9)e.$message({message:"已经没有更多了",type:"warning"});else{for(var i=a;i<=r;i++)e.now_arr[i]&&n.push(e.now_arr[i]);e.$set(e,"now_page_arr",n)}return new Promise((function(a,n){"function"==typeof t&&r<=e.now_arr.length+9?a(t):(e.now_arr.length,n("error"))}))}}},g=m,h=(a("7b32"),a("5511")),b=Object(h["a"])(g,r,n,!1,null,"15187e41",null);e["default"]=b.exports},7155:function(t,e,a){},"7b32":function(t,e,a){"use strict";var r=a("7155"),n=a.n(r);n.a}}]);
//# sourceMappingURL=chunk-746d4e57.76a4aacc.js.map