(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-96d2f11e"],{"4a1e":function(t,a,e){"use strict";e.r(a);var n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{staticClass:"myMaterial_w"},[e("el-upload",{staticClass:"material_upload material_b",attrs:{action:"http://ht.idealead.hbindex.com/webapi/User_images/upload_file_once?user_id="+t.authorId,"list-type":"picture-card","show-file-list":!1,data:t.picData,"on-success":t.uploadSuccess,"before-upload":t.beforeUpload,"on-error":t.uploadFail}},[e("i",{staticClass:"el-icon-plus"})]),t._l(t.material_own,(function(a,n){return e("div",{directives:[{name:"show",rawName:"v-show",value:!t.loading,expression:"!loading"}],staticClass:"material_b"},[e("img",{attrs:{src:"http://ht.idealead.hbindex.com/uploadFiles/images/"+a.path},on:{load:t.cout_load_img}}),e("span",{staticClass:"delete_material close_btn",on:{click:function(e){return t.deleteFunc(a.check_status,a.id,n)}}},[e("i")])])})),t.loading?e("div",{staticClass:"loading"},[e("span")]):t._e()],2)},i=[],o=(e("f5ef"),e("2f62"),e("22ce"));function r(t){return s(t)||c(t)||l()}function l(){throw new TypeError("Invalid attempt to spread non-iterable instance")}function c(t){if(Symbol.iterator in Object(t)||"[object Arguments]"===Object.prototype.toString.call(t))return Array.from(t)}function s(t){if(Array.isArray(t)){for(var a=0,e=new Array(t.length);a<t.length;a++)e[a]=t[a];return e}}var u={name:"myMaterial",props:{authorId:""},data:function(){return{loading:!1,loading_count:0,picData:{upload_file_once:{}},material_own_origin:[],material_own_cutout:[]}},created:function(){},mounted:function(){},computed:{material_own:function(){return this.material_own_origin.map((function(t){t.check_status=1})),this.material_own_cutout.map((function(t){t.check_status=2})),[].concat(r(this.material_own_origin),r(this.material_own_cutout))}},methods:{cout_load_img:function(){this.loading_count+=1,this.loading_count>=this.material_own.length/2&&(this.loading=!1)},updatePage:function(t){var a=this;a.material_own_origin.unshift({author:a.authorId,check_status:1,id:t.data.file_id,path:t.data.savepath}),a.$message("上传成功！")},beforeUpload:function(t){console.log(t);var a=this,e="image/jpeg"===t.type||"image/png"===t.type,n=t.size/1024/1024<2;return a.$set(a.picData,"upload_file_once",t),e||this.$message.error("上传头像图片只能是 JPG 或者 PNG 格式!"),e&&!n&&this.$message.error("上传头像图片大小不能超过 2MB!"),e&&n},uploadSuccess:function(t,a){console.log(t),this.updatePage(t)},uploadFail:function(t,a,e){this.$message(t)},deleteFunc:function(t,a,e){var n=this;Object(o["g"])(t,a).then((function(a){var i=n.material_own_origin.length;1==t?n.material_own_origin.splice(e,1):n.material_own_cutout.splice(e-i,1),n.$message("删除成功！")})).catch((function(t){console.log(t)}))}},beforeRouteEnter:function(t,a,e){e((function(t){Object(o["l"])().then((function(a){console.log(a),t.material_own_origin=a.dataImage,t.material_own_cutout=a.dataCutout,0!=a.dataImage.length||0!=a.dataCutout.length?t.loading=!0:t.loading=!1})).catch((function(t){console.log(t)}))}))}},d=u,_=(e("e1b2"),e("2877")),p=Object(_["a"])(d,n,i,!1,null,null,null);a["default"]=p.exports},d8a2:function(t,a,e){},e1b2:function(t,a,e){"use strict";var n=e("d8a2"),i=e.n(n);i.a}}]);
//# sourceMappingURL=chunk-96d2f11e.031630e1.js.map