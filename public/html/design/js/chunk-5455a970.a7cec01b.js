(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-5455a970"],{"05b0":function(t,e,i){"use strict";var n=i("822a"),s=i.n(n);s.a},"0dcc":function(t,e,i){},"44e83":function(t,e,i){t.exports=i.p+"img/design_1.a01787d6.jpg"},"822a":function(t,e,i){},"9dd1":function(t,e,i){t.exports=i.p+"img/design_3.37f2672a.jpg"},b60b:function(t,e,i){"use strict";var n=i("0dcc"),s=i.n(n);s.a},d72d:function(t,e,i){"use strict";i.r(e);var n=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"design_w flex_c"},[n("div",{staticClass:"part_top flex_c"},[t._m(0),n("div",{staticClass:"design_tag_w"},[n("div",{staticClass:"feature_tag feature_tag_all",class:{active:-1==t.select_label_index},on:{click:function(e){return t.selectLabel("all")}}},[t._v("全部")]),t._l(t.design_labels,(function(e,i){return n("div",{staticClass:"feature_tag",class:{active:t.select_label_index==i},on:{click:function(e){return t.selectLabel("other",i)}}},[t._v(t._s(e))])}))],2)]),n("div",{staticClass:"part_bottom"},[n("div",{staticClass:"design_lists"},t._l(t.filter_design_data,(function(e,s){return n("div",{staticClass:"design_list cards"},[n("img",{attrs:{src:e.design_img}}),n("div",{staticClass:"design_block"},[n("div",{staticClass:"block_top"},[n("p",[n("span",[t._v(t._s(e.integral))]),t._v("积分")]),n("div",{staticClass:"block_btns"},[n("img",{staticClass:"download_btn",attrs:{src:i("ebf3"),alt:""},on:{click:function(e){return t.downsTips(s)}}}),n("div",{staticClass:"collect_btn",class:{collected:e.isCollect},on:{click:function(i){return t.collectDesign(s,e.template_id)}}},[n("svg",{staticClass:"collect_icon",attrs:{xmlns:"http://www.w3.org/2000/svg","xmlns:xlink":"http://www.w3.org/1999/xlink",width:"100%",height:"100%",preserveAspectRatio:"xMinYMin meet",viewBox:"0 0 17 17"}},[n("path",{attrs:{"fill-rule":"evenodd",stroke:"rgb(255, 72, 56)","stroke-width":"2px","stroke-linecap":"butt","stroke-linejoin":"miter",fill:"rgb(255, 255, 255)",d:"M8.813,8.021 L7.686,5.755 L5.353,4.170 L3.019,5.755 L3.019,10.507 L9.242,15.260 L15.464,10.904 L15.464,5.359 L13.131,4.170 L10.797,5.755 L9.670,8.021 "}})])])])]),n("div",{staticClass:"block_bottom"},t._l(e.labels,(function(e,i){return n("span",[t._v(t._s(e))])})),0)])])})),0)]),n("el-dialog",{attrs:{visible:t.download_tips_dia,width:"4.2rem",customClass:"download_tips"},on:{"update:visible":function(e){t.download_tips_dia=e}}},[n("p",{staticClass:"tips_title"},[t._v("确定下载？")]),n("p",{staticClass:"tips_doc"},[t._v("该次下载将消耗你10个积分")]),n("div",{staticClass:"comfirm_down hoverable",on:{click:t.downsFunc}},[n("div",{staticClass:"anim"}),t._v("确定下载")])]),n("div",{staticClass:"common_wrap ps0"},[n("div",{staticClass:"click_btns flex"},[n("div",{staticClass:"prev_btn click_btn flex",on:{click:t.prevFunc}},[n("div",{staticClass:"deco flex"}),n("span",{staticClass:"one"},[t._v("上一步 prev")])])])])],1)},s=[function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"design_title_w"},[n("p",{staticClass:"design_t"},[t._v("根据"),n("span",[t._v("你的信息")]),t._v("做了设计")]),n("img",{staticClass:"icon1_t",attrs:{src:i("5ce5")}}),n("img",{staticClass:"icon2_t",attrs:{src:i("baf3"),alt:""}})])}],l=(i("f5ef"),i("2f62")),c=i("bc3a"),a=i.n(c);i("1209");function o(t,e){var i=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),i.push.apply(i,n)}return i}function d(t){for(var e=1;e<arguments.length;e++){var i=null!=arguments[e]?arguments[e]:{};e%2?o(Object(i),!0).forEach((function(e){r(t,e,i[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(i)):o(Object(i)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(i,e))}))}return t}function r(t,e,i){return e in t?Object.defineProperty(t,e,{value:i,enumerable:!0,configurable:!0,writable:!0}):t[e]=i,t}var _={name:"design",props:{},data:function(){return{download_tips_dia:!1,download_tips_index:Number,select_label_index:-1,need_render:2,origin_design_data:[{template_id:1,design_img:i("44e83"),integral:10,isCollect:!1,labels:["新年","红包","对联"]},{template_id:1,design_img:i("d7dd"),integral:10,isCollect:!1,labels:["灯笼","红包","对联"]},{template_id:1,design_img:i("9dd1"),integral:10,isCollect:!1,labels:["祝福","红包","对联"]},{template_id:1,design_img:i("d7dd"),integral:10,isCollect:!1,labels:["利是","红包","对联"]},{template_id:1,design_img:i("d7dd"),integral:10,isCollect:!1,labels:["灯笼","红包","对联"]},{template_id:1,design_img:i("9dd1"),integral:10,isCollect:!1,labels:["祝福","红包","对联"]},{template_id:1,design_img:i("d7dd"),integral:10,isCollect:!1,labels:["利是","红包","对联"]},{template_id:1,design_img:i("d7dd"),integral:10,isCollect:!1,labels:["利是","红包","对联"]},{template_id:1,design_img:i("d7dd"),integral:10,isCollect:!1,labels:["灯笼","红包","对联"]},{template_id:1,design_img:i("9dd1"),integral:10,isCollect:!1,labels:["祝福","红包","对联"]},{template_id:1,design_img:i("d7dd"),integral:10,isCollect:!1,labels:["利是","红包","对联"]}],design_labels:["新年","红包","对联","祝福","灯笼","利是"]}},watch:{},computed:{download_path:function(){return this.filter_design_data[this.download_tips_index].design_img},filter_design_data:function(){var t=this.select_label_index;if(-1!=t){var e=this.design_labels[t],i=(new RegExp(e,"ig"),[]);return this.origin_design_data.map((function(t){t.labels.map((function(n){-1!=n.search(e)&&i.push(t)}))})),i}return this.origin_design_data}},mounted:function(){this.hoverFunc()},methods:d({},Object(l["b"])(["setAnswerIndex"]),{prevFunc:function(){var t=this;t.$router.go(-1)},collectDesign:function(t,e){var i=this,n=this.filter_design_data[t];n.isCollect=!n.isCollect,n.isCollect?i.to_collect_func(e):i.cancel_collect_func(e)},to_collect_func:function(t){var e=this;a.a.post("/Collection/add_collection",{template_id:t,is_user:e.need_render}).then((function(t){console.log(t),this.$message("收藏成功！")})).catch((function(t){console.log(t)}))},cancel_collect_func:function(t){var e=this;a.a.get("/Collection/change_collection?template_id=".concat(t,"&is_user=").concat(e["need_render"])).then((function(t){console.log(t),this.$message("取消成功")})).catch((function(t){console.log(t)}))},downsTips:function(t){this.download_tips_dia=!0,this.download_tips_index=t},downsFunc:function(){this.downs(this.download_path,!0),this.download_tips_dia=!1},selectLabel:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:-1;this.select_label_index=e},hoverFunc:function(){var t=document.querySelectorAll(".cards"),e=15,i=function(t,i){return(t/i*e-e/2).toFixed(1)},n=void 0;t.forEach((function(t){t.addEventListener("mousemove",(function(e){var s=e.x,l=e.y;n&&window.cancelAnimationFrame(n),n=window.requestAnimationFrame((function(){var e=i(l,window.innerHeight),n=i(s,window.innerWidth);t.style.transform="translateX("+-n+"px) translateY("+e+"px)"}))}),!1)}))}}),updated:function(){this.hoverFunc()},beforeRouteEnter:function(t,e,i){i((function(t){t.hoverFunc()}))}},g=_,u=(i("b60b"),i("05b0"),i("2877")),p=Object(u["a"])(g,n,s,!1,null,"ecb1e298",null);e["default"]=p.exports},d7dd:function(t,e,i){t.exports=i.p+"img/design_2.19b71781.jpg"},ebf3:function(t,e){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAPCAYAAADUFP50AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTM4IDc5LjE1OTgyNCwgMjAxNi8wOS8xNC0wMTowOTowMSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTcgKFdpbmRvd3MpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjE1QTZCNTgzMEM1MjExRUFCODlEQTQ5M0QwNDM5QjAyIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjE1QTZCNTg0MEM1MjExRUFCODlEQTQ5M0QwNDM5QjAyIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MTVBNkI1ODEwQzUyMTFFQUI4OURBNDkzRDA0MzlCMDIiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6MTVBNkI1ODIwQzUyMTFFQUI4OURBNDkzRDA0MzlCMDIiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7GcCkdAAAAYUlEQVR42mL876bHgAX8R+MzoitgYiATDEGNDdAA+Y8lYBjQ5BrQNTYSYVEjukZiNMM1YfMjLs0omnAFDrpmDE0gwILDWQ042IikBExy/3ElKxzgP0XxyIInYdMm5QAEGAC54BcHy6nwqAAAAABJRU5ErkJggg=="}}]);
//# sourceMappingURL=chunk-5455a970.a7cec01b.js.map