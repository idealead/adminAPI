(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-8f410caa"],{"525e":function(t,e,n){"use strict";n.r(e);var i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"shadow"},[t.showText?n("div",{staticClass:"setTextPositionBlock"},[n("h4",[t._v("配置文本替换位置")]),n("div",{staticClass:"textSwitch"},[n("el-switch",{attrs:{checked:t.saveTextRule,"active-text":"上传规则","inactive-text":"不上传规则"},on:{change:t.change},model:{value:t.textval,callback:function(e){t.textval=e},expression:"textval"}})],1),t._l(t.textRepalceF.textPositionArr,(function(e){return n("el-tag",{key:e,attrs:{closable:"","disable-transitions":!1},on:{close:function(n){return t.handleClose(e)}}},[t._v(t._s(e))])})),t.textRepalceF.inputVisible?n("el-input",{ref:"saveTagInput",staticClass:"input-new-tag",attrs:{size:"small"},on:{blur:t.handleInputConfirm},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.handleInputConfirm(e)}},model:{value:t.textRepalceF.inputValue,callback:function(e){t.$set(t.textRepalceF,"inputValue",e)},expression:"textRepalceF.inputValue"}}):n("el-button",{staticClass:"button-new-tag",attrs:{size:"small"},on:{click:t.showInput}},[t._v("+ New Tag")]),n("div",{staticClass:"buttonBlock"},[n("el-button",{attrs:{type:"primary",round:"",size:"medium"},on:{click:t.saveTextPosition}},[t._v("保存")]),n("el-button",{attrs:{type:"primary",round:"",size:"medium"},on:{click:t.hideTextFunc}},[t._v("取消")])],1)],2):t._e()])},o=[],a=(n("3acb"),n("c9a0"),n("dbe6"),n("57d6"),n("82a7"),n("f32a"),n("20a8"),n("69af"),n("9d37")),s=n("08c1");function r(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(t);e&&(i=i.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,i)}return n}function c(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?r(Object(n),!0).forEach((function(e){Object(a["a"])(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):r(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}var u={name:"mouldFunc",props:{openBlock:String,textPositionArr:Array,saveTextRule:Boolean},model:{prop:"saveTextRule",event:"change"},computed:c({},Object(s["b"])({user_data:function(t){return t.user.user_data},api:function(t){return t.api}})),data:function(){return{showText:!1,textRepalceF:{textPositionArr:[],inputVisible:!1,inputValue:""},textval:!1}},beforeDestroy:function(){},created:function(){var t=this;t.$set(t,"textval",t.saveTextRule)},mounted:function(){var t=this;"text"===t.openBlock&&(t.$set(t,"showText",!0),t.$set(t.textRepalceF,"textPositionArr",_.cloneDeep(t.textPositionArr)))},methods:{change:function(t){this.$emit("change",t)},hideTextFunc:function(){var t=this;t.$set(t,"showText",!1),t.$emit("close-mould-funcshow")},saveTextPosition:function(){var t=this;t.$emit("update-text-position",t.textRepalceF.textPositionArr),t.$emit("close-mould-funcshow")},handleClose:function(t){this.textRepalceF.textPositionArr.splice(this.textRepalceF.textPositionArr.indexOf(t),1)},showInput:function(){var t=this;this.textRepalceF.inputVisible=!0,this.$nextTick((function(e){t.$refs.saveTagInput.$refs.input.focus()}))},handleInputConfirm:function(){var t=this.textRepalceF.inputValue;t&&this.textRepalceF.textPositionArr.push(t),this.textRepalceF.inputVisible=!1,this.inputValue=""}}},l=u,p=(n("ebd2"),n("5511")),f=Object(p["a"])(l,i,o,!1,null,"3c009140",null);e["default"]=f.exports},ebd2:function(t,e,n){"use strict";var i=n("f78a"),o=n.n(i);o.a},f78a:function(t,e,n){}}]);
//# sourceMappingURL=chunk-8f410caa.6e781cf4.js.map