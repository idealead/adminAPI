(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-62ee7b94"],{"279a":function(t,e,a){},bd0e:function(t,e,a){"use strict";a.r(e);var r=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"structureMaterial"},[a("div",{staticClass:"structureNav"},[a("el-tabs",{on:{"tab-click":t.handleClick},model:{value:t.first,callback:function(e){t.first=e},expression:"first"}},t._l(t.structureData,(function(e,r){return a("el-tab-pane",{key:r,attrs:{label:e.data.framework_name,name:""+e.data.id,index:r}},[t._v(t._s(e.data.framework_name))])})),1)],1),t.show?a("div",{staticClass:"materialList"},t._l(t.now_structureData.data.rule,(function(e,r){return a("div",{key:r,staticClass:"materialKind"},[a("el-tag",[t._v(t._s(e.position_name)+"：")]),a("div",{staticClass:"materialDiv"},[t._l(e.files,(function(r,i){return a("div",{key:i,staticClass:"materialBlock"},[a("div",{staticClass:"materialImg",style:{backgroundImage:"url('"+t.api.images+r.file_path+"')"}}),a("div",{staticClass:"materialBtnBlock"},[a("el-tooltip",{attrs:{content:"下载",placement:"bottom",effect:"light"}},[a("div",{staticClass:"materialBtn",on:{click:function(e){return t.donwloadF(t.img)}}},[a("i",{staticClass:"el-icon-download"})])]),a("el-tooltip",{attrs:{content:"撤销",placement:"bottom",effect:"light"}},[a("div",{staticClass:"materialBtn",on:{click:function(a){return t.backF(e.files[i])}}},[a("i",{staticClass:"el-icon-refresh-left"})])])],1)])})),a("div",{staticClass:"materialBlock",staticStyle:{cursor:"pointer"},on:{click:function(a){return t.addImgF(e)}}},[a("i",{staticClass:"el-icon-plus plus",attrs:{size:"normal"}})])],2)],1)})),0):t._e(),t.addImg?a("div",{staticClass:"alertBg"},[a("div",{staticClass:"layoutBlock"},[t._l(t.myMaterial,(function(e,r){return a("div",{key:r,class:{zoom:e.hover,myMaterial:!0,selected:e.select},style:{backgroundImage:"url("+e.url+")"},on:{mouseover:function(e){return t.hoverFunc(r)},mouseleave:function(e){return t.outFunc(r)},click:function(a){return t.selectF(e,r)}}},[a("i",{directives:[{name:"show",rawName:"v-show",value:3==e.status,expression:"item.status==3"}],staticClass:"el-icon-paperclip lock"})])})),a("div",{staticClass:"setBtn"},[a("el-button",{staticClass:"addBtn",attrs:{type:"primary",round:"",size:"small"},on:{click:t.addBtn}},[t._v("添加")]),a("el-button",{staticClass:"cancelBtn",attrs:{type:"info",round:"",size:"small"},on:{click:t.cancelBtn}},[t._v("取消")])],1)],2)]):t._e()])},i=[],n=(a("3acb"),a("536e"),a("c9a0"),a("e11e"),a("82a7"),a("f32a"),a("20a8"),a("9b70"),a("9757"),a("5f5a"),a("69af"),a("90e5"),a("3108"),a("9d37")),s=a("08c1"),c=a("2427"),o=a.n(c);function u(t,e){var a=Object.keys(t);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(t);e&&(r=r.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),a.push.apply(a,r)}return a}function l(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{};e%2?u(Object(a),!0).forEach((function(e){Object(n["a"])(t,e,a[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(a)):u(Object(a)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(a,e))}))}return t}var d={name:"structureMaterial",props:{structureD:{type:Array,default:function(){return[]}}},data:function(){return{replaceData:[{tagName:"主图"},{tagName:"背景"},{tagName:"装饰1"}],activeName:"first",img:"https://upload-images.jianshu.io/upload_images/2509688-d49b8f5bde26566d.jpeg?imageMogr2/auto-orient/strip|imageView2/2/w/640/format/webp",addImg:!1,structure_index:0,structure_id:Number,structure_postion:Number,select_file_id:Number,myMaterial:[{url:"",hover:!1,select:!1}],now_structureData:null,show:!1}},computed:l({},Object(s["b"])({api:function(t){return t.api}}),{structureData:function(){return this.structureD},first:function(){return this.structureData[0]?"".concat(this.structureData[0].data.id):"1"}}),created:function(){var t=this;t.$set(t,"now_structureData",this.structureD[0]),setTimeout((function(){t.$set(t,"show",!0)}),0)},mounted:function(){var t=this;t.getStructureImg(0)},methods:{backF:function(t){var e=this;o()({method:"post",url:e.api.del_framework_material,data:{id:t.id}}).then((function(t){t.data.code})).catch((function(t){}))},addBtn:function(){var t=this;o()({method:"post",url:t.api.add_framework_material,data:{user_id:t.user_data.id,material_id:t.select_file_id,framework_id:t.structure_id,position:t.structure_postion}}).then((function(e){"200"==e.data.code&&(t.$message({message:"添加成功",type:"success"}),t.$set(t,"addImg",!1),t.getStructureImg(t.structure_index))})).catch((function(){}))},getStructureImg:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:0,e=this,a=e.structureData[t].data.id;o()({method:"get",url:"".concat(e.api.find_framework_info,"?framework_id=").concat(a)}).then((function(t){200==t.status&&"200"==t.data.code&&e.$set(e,"now_structureData",t.data)})).catch((function(){}))},handleClick:function(t,e){var a=this;a.$set(a,"structure_index",parseInt(t.index)),a.getStructureImg(a.structure_index)},addImgF:function(t){var e=this;e.$set(e,"addImg",!0),e.$set(e,"structure_id",t.framework_id),e.$set(e,"structure_postion",t.position),e.getImg()},getImg:function(){var t=this,e=t.user_data.id;o()({method:"post",url:t.api.find_file,data:{id:e}}).then((function(e){if("200"==e.data.code){for(var a=e.data.data,r=0;r<a.length;r++)a[r].hover=!1,a[r].select=!1,a[r].url="".concat(t.api.images).concat(a[r].path);t.$set(t,"myMaterial",a)}})).catch((function(t){}))},cancelBtn:function(){var t=this;t.$set(t,"addImg",!1)},hoverFunc:function(t){var e=this;e.$set(e.myMaterial[t],"hover",!0)},outFunc:function(t){var e=this;e.$set(e.myMaterial[t],"hover",!1)},selectF:function(t,e){var a=this;if(3==t.status)a.$message({message:"该图片已经被添加，锁定",type:"warning"});else{for(var r=0;r<a.myMaterial.length;r++)a.myMaterial[r].select=!1;a.$set(a.myMaterial[e],"select",!0),a.$set(a,"select_file_id",t.id)}},donwloadF:function(t){fetch(t).then((function(t){return t.blob().then((function(t){var e=document.createElement("a"),a=window.URL.createObjectURL(t),r="myImg.png";e.href=a,e.download=r,e.click(),window.URL.revokeObjectURL(a)}))}))}}},f=d,m=(a("f945"),a("5511")),p=Object(m["a"])(f,r,i,!1,null,"d599575a",null);e["default"]=p.exports},e11e:function(t,e,a){"use strict";var r=a("18fe"),i=a("93ef"),n=a("afb8"),s=a("d116"),c=a("49bf"),o=a("b995"),u=a("43ae"),l=a("5b20"),d=a("852d"),f=a("387e"),m=a("fdd7").f,p=a("90c9").f,h=a("49bb").f,g=a("1ac6").trim,v="Number",_=i[v],b=_.prototype,w=o(f(b))==v,k=function(t){var e,a,r,i,n,s,c,o,u=l(t,!1);if("string"==typeof u&&u.length>2)if(u=g(u),e=u.charCodeAt(0),43===e||45===e){if(a=u.charCodeAt(2),88===a||120===a)return NaN}else if(48===e){switch(u.charCodeAt(1)){case 66:case 98:r=2,i=49;break;case 79:case 111:r=8,i=55;break;default:return+u}for(n=u.slice(2),s=n.length,c=0;c<s;c++)if(o=n.charCodeAt(c),o<48||o>i)return NaN;return parseInt(n,r)}return+u};if(n(v,!_(" 0o1")||!_("0b1")||_("+0x1"))){for(var y,I=function(t){var e=arguments.length<1?0:t,a=this;return a instanceof I&&(w?d((function(){b.valueOf.call(a)})):o(a)!=v)?u(new _(k(e)),a,I):k(e)},N=r?m(_):"MAX_VALUE,MIN_VALUE,NaN,NEGATIVE_INFINITY,POSITIVE_INFINITY,EPSILON,isFinite,isInteger,isNaN,isSafeInteger,MAX_SAFE_INTEGER,MIN_SAFE_INTEGER,parseFloat,parseInt,isInteger".split(","),C=0;N.length>C;C++)c(_,y=N[C])&&!c(I,y)&&h(I,y,p(_,y));I.prototype=b,b.constructor=I,s(i,v,I)}},f945:function(t,e,a){"use strict";var r=a("279a"),i=a.n(r);i.a}}]);
//# sourceMappingURL=chunk-62ee7b94.9fd44e79.js.map