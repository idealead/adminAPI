(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-a2d97c40"],{3394:function(t,n,e){"use strict";e.r(n);var i=function(){var t=this,n=t.$createElement,i=t._self._c||n;return i("div",{staticClass:"collect_w"},[t._l(t.filter_collect_data,(function(n,o){return i("div",{directives:[{name:"show",rawName:"v-show",value:!t.loading,expression:"!loading"}],staticClass:"material_b"},[t.loading?i("img",{attrs:{src:"/uploadFiles/images/"+n.path},on:{load:t.cout_load_img}}):t._e(),i("img",{attrs:{src:n.watermark_path}}),i("span",{staticClass:"delete_material close_btn",on:{click:function(e){return t.delete_collect_func(n.template_id,n.level,o)}}},[i("i")]),i("div",{staticClass:"design_control_block"},[i("span",[t._v("未命名")]),i("img",{staticClass:"download_btn",attrs:{src:e("4f68"),alt:"download"},on:{click:function(e){return t.download_collection_func(n.path,n.template_id)}}})])])})),t.loading?i("div",{staticClass:"loading"},[i("span")]):t._e(),t.none_content?i("p",{staticClass:"none_content"},[t._v("还没收藏的设计稿")]):t._e()],2)},o=[],a=e("f5ef"),c=(e("2f62"),e("22ce")),l={name:"collect",props:{},data:function(){return{loading:!1,none_content:!1,loading_count:0,origin_design_data:Array}},created:function(){},mounted:function(){},computed:{filter_collect_data:function(){return this.origin_design_data}},methods:{cout_load_img:function(){this.loading_count+=1,this.loading_count>=this.origin_design_data.length&&(this.loading=!1)},delete_collect_func:function(t,n,e){var i=this,o=0;o="permanent"==n?2:1,Object(c["b"])(t,o).then((function(t){console.log(t),i.origin_design_data.splice(e,1),console.log(i.filter_collect_data),i.$message("取消成功")})).catch((function(t){console.log(t)}))},download_collection_func:function(t,n){a["a"].$emit("download",{path:t,id:n})}},beforeRouteEnter:function(t,n,e){e((function(t){Object(c["j"])().then((function(n){if(console.log(n.data),0!=n.data.length){var e=n.data;t.loading=!0,e.map((function(n){t.watermark("http://ht.idealead.hbindex.com/uploadFiles/images/"+n.path).then((function(t){return n.watermark_path=t}))})),setTimeout((function(){t.origin_design_data=e}),500)}else t.loading=!1,t.none_content=!0})).catch((function(t){console.log(t)}))}))}},d=l,s=(e("713f"),e("2877")),u=Object(s["a"])(d,i,o,!1,null,"0947f690",null);n["default"]=u.exports},"4f68":function(t,n){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAPCAYAAADUFP50AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTM4IDc5LjE1OTgyNCwgMjAxNi8wOS8xNC0wMTowOTowMSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTcgKFdpbmRvd3MpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjI4MzEwODBGMEQwQzExRUFCODJCQ0ZFMUMzNjAxMjYzIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjI4MzEwODEwMEQwQzExRUFCODJCQ0ZFMUMzNjAxMjYzIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MjgzMTA4MEQwRDBDMTFFQUI4MkJDRkUxQzM2MDEyNjMiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6MjgzMTA4MEUwRDBDMTFFQUI4MkJDRkUxQzM2MDEyNjMiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6+7DzUAAAAY0lEQVR42tSSUQrAIAxD2+HBc/NsoEMJrQ7HPhYQpM37SeMkLZAOXQ2HbeqHIFogDIIx2aFOrlTbA9fC7R/BFYzRq2AGQ30RqDAiT0lCQ/LvVWLvnD+8BF/dsUyK/U1zTgEGAELx0U+YOrnGAAAAAElFTkSuQmCC"},"54da":function(t,n,e){},"713f":function(t,n,e){"use strict";var i=e("54da"),o=e.n(i);o.a}}]);
//# sourceMappingURL=chunk-a2d97c40.d0af755f.js.map