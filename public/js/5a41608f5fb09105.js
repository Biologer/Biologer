(self.webpackChunk=self.webpackChunk||[]).push([[75],{9516:(t,e,n)=>{"use strict";function r(t){return r="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},r(t)}function o(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,(a=o.key,i=void 0,i=function(t,e){if("object"!==r(t)||null===t)return t;var n=t[Symbol.toPrimitive];if(void 0!==n){var o=n.call(t,e||"default");if("object"!==r(o))return o;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===e?String:Number)(t)}(a,"string"),"symbol"===r(i)?i:String(i)),o)}var a,i}n.d(e,{Z:()=>a});const a=new(function(){function t(){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t)}var e,n,r;return e=t,(n=[{key:"get",value:function(t){var e=JSON.parse(localStorage.getItem(t));return e?new Date(e.expires)<new Date?(localStorage.removeItem(t),null):e.value:null}},{key:"has",value:function(t){return null!==this.get(t)}},{key:"set",value:function(t,e,n){var r=(new Date).getTime(),o=new Date(r+6e4*n);localStorage.setItem(t,JSON.stringify({value:e,expires:o}))}},{key:"delete",value:function(t){this.get(t)&&localStorage.removeItem(t)}}])&&o(e.prototype,n),r&&o(e,r),Object.defineProperty(e,"prototype",{writable:!1}),t}())},5442:(t,e,n)=>{"use strict";n.d(e,{Z:()=>i});var r=n(8718),o=n.n(r),a=n(9516);const i={props:{cacheKey:{default:null},cacheLifetime:{default:1440}},computed:{storageKey:function(){return this.cacheKey?"nz-table.".concat(this.cacheKey):"nz-table.".concat(window.location.host).concat(window.location.pathname)}},methods:{getPersistantKeys:function(){return["sortField","sortOrder","perPage"]},saveState:function(){a.Z.set(this.storageKey,o()(this.$data,this.getPersistantKeys()),this.cacheLifetime)},restoreState:function(){var t=this,e=a.Z.get(this.storageKey);null!=e&&(this.getPersistantKeys().forEach((function(n){void 0!==e[n]&&t.$set(t,n,e[n])})),this.saveState())}}}},1078:(t,e,n)=>{var r=n(2488),o=n(7285);t.exports=function t(e,n,a,i,s){var u=-1,l=e.length;for(a||(a=o),s||(s=[]);++u<l;){var c=e[u];n>0&&a(c)?n>1?t(c,n-1,a,i,s):r(s,c):i||(s[s.length]=c)}return s}},7786:(t,e,n)=>{var r=n(1811),o=n(327);t.exports=function(t,e){for(var n=0,a=(e=r(e,t)).length;null!=t&&n<a;)t=t[o(e[n++])];return n&&n==a?t:void 0}},13:t=>{t.exports=function(t,e){return null!=t&&e in Object(t)}},5970:(t,e,n)=>{var r=n(3012),o=n(9095);t.exports=function(t,e){return r(t,e,(function(e,n){return o(t,n)}))}},3012:(t,e,n)=>{var r=n(7786),o=n(611),a=n(1811);t.exports=function(t,e,n){for(var i=-1,s=e.length,u={};++i<s;){var l=e[i],c=r(t,l);n(c,l)&&o(u,a(l,t),c)}return u}},611:(t,e,n)=>{var r=n(4865),o=n(1811),a=n(5776),i=n(3218),s=n(327);t.exports=function(t,e,n,u){if(!i(t))return t;for(var l=-1,c=(e=o(e,t)).length,f=c-1,p=t;null!=p&&++l<c;){var d=s(e[l]),h=n;if("__proto__"===d||"constructor"===d||"prototype"===d)return t;if(l!=f){var v=p[d];void 0===(h=u?u(v,d,p):void 0)&&(h=i(v)?v:a(e[l+1])?[]:{})}r(p,d,h),p=p[d]}return t}},1811:(t,e,n)=>{var r=n(1469),o=n(5403),a=n(5514),i=n(9833);t.exports=function(t,e){return r(t)?t:o(t,e)?[t]:a(i(t))}},9021:(t,e,n)=>{var r=n(5564),o=n(5357),a=n(61);t.exports=function(t){return a(o(t,void 0,r),t+"")}},222:(t,e,n)=>{var r=n(1811),o=n(5694),a=n(1469),i=n(5776),s=n(1780),u=n(327);t.exports=function(t,e,n){for(var l=-1,c=(e=r(e,t)).length,f=!1;++l<c;){var p=u(e[l]);if(!(f=null!=t&&n(t,p)))break;t=t[p]}return f||++l!=c?f:!!(c=null==t?0:t.length)&&s(c)&&i(p,c)&&(a(t)||o(t))}},7285:(t,e,n)=>{var r=n(2705),o=n(5694),a=n(1469),i=r?r.isConcatSpreadable:void 0;t.exports=function(t){return a(t)||o(t)||!!(i&&t&&t[i])}},5403:(t,e,n)=>{var r=n(1469),o=n(3448),a=/\.|\[(?:[^[\]]*|(["'])(?:(?!\1)[^\\]|\\.)*?\1)\]/,i=/^\w*$/;t.exports=function(t,e){if(r(t))return!1;var n=typeof t;return!("number"!=n&&"symbol"!=n&&"boolean"!=n&&null!=t&&!o(t))||(i.test(t)||!a.test(t)||null!=e&&t in Object(e))}},4523:(t,e,n)=>{var r=n(8306);t.exports=function(t){var e=r(t,(function(t){return 500===n.size&&n.clear(),t})),n=e.cache;return e}},5514:(t,e,n)=>{var r=n(4523),o=/[^.[\]]+|\[(?:(-?\d+(?:\.\d+)?)|(["'])((?:(?!\2)[^\\]|\\.)*?)\2)\]|(?=(?:\.|\[\])(?:\.|\[\]|$))/g,a=/\\(\\)?/g,i=r((function(t){var e=[];return 46===t.charCodeAt(0)&&e.push(""),t.replace(o,(function(t,n,r,o){e.push(r?o.replace(a,"$1"):n||t)})),e}));t.exports=i},327:(t,e,n)=>{var r=n(3448);t.exports=function(t){if("string"==typeof t||r(t))return t;var e=t+"";return"0"==e&&1/t==-Infinity?"-0":e}},5564:(t,e,n)=>{var r=n(1078);t.exports=function(t){return(null==t?0:t.length)?r(t,1):[]}},9095:(t,e,n)=>{var r=n(13),o=n(222);t.exports=function(t,e){return null!=t&&o(t,e,r)}},8306:(t,e,n)=>{var r=n(3369);function o(t,e){if("function"!=typeof t||null!=e&&"function"!=typeof e)throw new TypeError("Expected a function");var n=function(){var r=arguments,o=e?e.apply(this,r):r[0],a=n.cache;if(a.has(o))return a.get(o);var i=t.apply(this,r);return n.cache=a.set(o,i)||a,i};return n.cache=new(o.Cache||r),n}o.Cache=r,t.exports=o},8718:(t,e,n)=>{var r=n(5970),o=n(9021)((function(t,e){return null==t?{}:r(t,e)}));t.exports=o},2573:(t,e,n)=>{"use strict";n.d(e,{Z:()=>o});const r={name:"nzPerPageSelect",props:{value:Number,options:Array}};const o=(0,n(1900).Z)(r,(function(){var t=this,e=t._self._c;return e("b-field",[e("b-select",{attrs:{value:t.value,placeholder:"Per page"},on:{input:function(e){return t.$emit("input",e)}}},t._l(t.options,(function(n,r){return e("option",{key:r,domProps:{value:n,textContent:t._s(n)}})})),0)],1)}),[],!1,null,null,null).exports},5997:(t,e,n)=>{"use strict";n.d(e,{Z:()=>o});const r={name:"nzSortableColumnHeader",props:{column:{type:Object,required:!0},sort:Object},computed:{isNotSortedColumn:function(){return this.sort.field!==this.column.field},sortIcon:function(){return this.isNotSortedColumn?"sort":"asc"===this.sort.order?"sort-asc":"sort-desc"}}};const o=(0,n(1900).Z)(r,(function(){var t=this,e=t._self._c;return e("span",{staticClass:"is-flex is-align-items-center"},[t._v("\n  "+t._s(t.column.label)+"\n  "),t.column.sortable?e("b-icon",{staticClass:"ml-2",class:{"has-text-grey-light":t.isNotSortedColumn},attrs:{pack:"fa",icon:t.sortIcon,size:"is-small"}}):t._e()],1)}),[],!1,null,null,null).exports},8075:(t,e,n)=>{"use strict";n.r(e),n.d(e,{default:()=>s});var r=n(5442),o=n(2573),a=n(5997);const i={name:"nzViewGroupsTable",mixins:[r.Z],components:{NzPerPageSelect:o.Z,NzSortableColumnHeader:a.Z},props:{perPageOptions:{type:Array,default:function(){return[15,30,50,100]},validator:function(t){return t.length}},listRoute:String,editRoute:String,deleteRoute:String,empty:{type:String,default:"Nothing here."}},data:function(){return{data:[],total:0,loading:!1,sortField:"id",sortOrder:"desc",page:1,perPage:this.perPageOptions[0],checkedRows:[]}},computed:{showing:function(){var t=this.page*this.perPage<=this.total?this.page*this.perPage:this.total,e=this.page>1?(this.page-1)*this.perPage+1:1;return this.total?this.trans("labels.tables.from_to_total",{total:this.total,from:e,to:t}):""}},created:function(){this.restoreState(),this.loadAsyncData()},methods:{loadAsyncData:function(){var t=this;return this.loading=!0,axios.get(route(this.listRoute).withQuery({sort_by:"".concat(this.sortField,".").concat(this.sortOrder),page:this.page,per_page:this.perPage})).then((function(e){var n=e.data;t.data=[],t.total=n.meta.total,n.data.forEach((function(e){return t.data.push(e)})),t.loading=!1}),(function(e){t.data=[],t.total=0,t.loading=!1}))},onPageChange:function(t){this.page=t,this.loadAsyncData()},onSort:function(t,e){this.sortField=t,this.sortOrder=e,this.saveState(),this.loadAsyncData()},onPerPageChange:function(t){t!==this.perPage&&(this.perPage=t,this.saveState(),this.loadAsyncData())},confirmRemove:function(t){var e=this;this.$buefy.dialog.confirm({message:this.trans("Are you sure you want to delete this record?"),confirmText:this.trans("buttons.delete"),cancelText:this.trans("buttons.cancel"),type:"is-danger",onConfirm:function(){e.remove(t)}})},remove:function(t){var e=this;return axios.delete(route(this.deleteRoute,t.id)).then((function(t){e.$buefy.toast.open({message:e.trans("Record deleted"),type:"is-success"}),e.loadAsyncData()})).catch((function(t){console.error(t)}))},editLink:function(t){return route(this.editRoute,t.id)}}};const s=(0,n(1900).Z)(i,(function(){var t=this,e=t._self._c;return e("div",{staticClass:"view-groups-table"},[e("b-table",{attrs:{data:t.data,loading:t.loading,paginated:"","backend-pagination":"",total:t.total,"per-page":t.perPage,"current-page":t.page,"pagination-position":"both","backend-sorting":"","default-sort-direction":"asc","default-sort":[t.sortField,t.sortOrder],"mobile-cards":""},on:{"page-change":t.onPageChange,sort:t.onSort},scopedSlots:t._u([{key:"top-left",fn:function(){return[e("div",{staticClass:"level-item"},[e("nz-per-page-select",{attrs:{value:t.perPage,options:t.perPageOptions},on:{input:t.onPerPageChange}})],1),t._v(" "),e("div",{staticClass:"level-item"},[t._v(t._s(t.showing))])]},proxy:!0},{key:"bottom-left",fn:function(){return[e("div",{staticClass:"level-item"},[e("nz-per-page-select",{attrs:{value:t.perPage,options:t.perPageOptions},on:{input:t.onPerPageChange}})],1),t._v(" "),e("div",{staticClass:"level-item"},[t._v(t._s(t.showing))])]},proxy:!0},{key:"empty",fn:function(){return[e("section",{staticClass:"section"},[e("div",{staticClass:"content has-text-grey has-text-centered"},[e("p",[t._v(t._s(t.empty))])])])]},proxy:!0}])},[t._v(" "),t._v(" "),e("b-table-column",{attrs:{field:"id",label:t.trans("labels.id"),width:"40",numeric:"",sortable:""},scopedSlots:t._u([{key:"default",fn:function(e){var n=e.row;return[t._v("\n        "+t._s(n.id)+"\n      ")]}},{key:"header",fn:function(n){var r=n.column;return[e("nz-sortable-column-header",{attrs:{column:r,sort:{field:t.sortField,order:t.sortOrder}}})]}}])}),t._v(" "),e("b-table-column",{attrs:{field:"name",label:t.trans("labels.view_groups.name")},scopedSlots:t._u([{key:"default",fn:function(e){var n=e.row;return[t._v("\n        "+t._s(n.name)+"\n      ")]}}])}),t._v(" "),e("b-table-column",{attrs:{width:"150",numeric:""},scopedSlots:t._u([{key:"default",fn:function(n){var r=n.row;return[e("a",{attrs:{href:t.editLink(r)}},[e("b-icon",{attrs:{icon:"edit"}})],1),t._v(" "),e("a",{on:{click:function(e){return t.confirmRemove(r)}}},[e("b-icon",{attrs:{icon:"trash"}})],1)]}}])})],1)],1)}),[],!1,null,null,null).exports}}]);
//# sourceMappingURL=5a41608f5fb09105.js.map