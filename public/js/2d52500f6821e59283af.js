(self.webpackChunk=self.webpackChunk||[]).push([[751],{4741:(t,e,n)=>{"use strict";function r(t,e){for(var n=0;n<e.length;n++){var r=e[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}n.d(e,{Z:()=>o});const o=new(function(){function t(){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t)}var e,n,o;return e=t,(n=[{key:"get",value:function(t){var e=JSON.parse(localStorage.getItem(t));return e?new Date(e.expires)<new Date?(localStorage.removeItem(t),null):e.value:null}},{key:"has",value:function(t){return null!==this.get(t)}},{key:"set",value:function(t,e,n){var r=(new Date).getTime(),o=new Date(r+6e4*n);localStorage.setItem(t,JSON.stringify({value:e,expires:o}))}},{key:"delete",value:function(t){this.get(t)&&localStorage.removeItem(t)}}])&&r(e.prototype,n),o&&r(e,o),t}())},6311:(t,e,n)=>{"use strict";n.d(e,{Z:()=>s});var r=n(8718),o=n.n(r),a=n(4741);const s={props:{cacheKey:{default:null},cacheLifetime:{default:1440}},computed:{storageKey:function(){return this.cacheKey?"nz-table.".concat(this.cacheKey):"nz-table.".concat(window.location.host).concat(window.location.pathname)}},methods:{getPersistantKeys:function(){return["sortField","sortOrder","perPage"]},saveState:function(){a.Z.set(this.storageKey,o()(this.$data,this.getPersistantKeys()),this.cacheLifetime)},restoreState:function(){var t=this,e=a.Z.get(this.storageKey);null!=e&&(this.getPersistantKeys().forEach((function(n){void 0!==e[n]&&t.$set(t,n,e[n])})),this.saveState())}}}},1078:(t,e,n)=>{var r=n(2488),o=n(7285);t.exports=function t(e,n,a,s,i){var l=-1,u=e.length;for(a||(a=o),i||(i=[]);++l<u;){var c=e[l];n>0&&a(c)?n>1?t(c,n-1,a,s,i):r(i,c):s||(i[i.length]=c)}return i}},7786:(t,e,n)=>{var r=n(1811),o=n(327);t.exports=function(t,e){for(var n=0,a=(e=r(e,t)).length;null!=t&&n<a;)t=t[o(e[n++])];return n&&n==a?t:void 0}},13:t=>{t.exports=function(t,e){return null!=t&&e in Object(t)}},5970:(t,e,n)=>{var r=n(3012),o=n(9095);t.exports=function(t,e){return r(t,e,(function(e,n){return o(t,n)}))}},3012:(t,e,n)=>{var r=n(7786),o=n(611),a=n(1811);t.exports=function(t,e,n){for(var s=-1,i=e.length,l={};++s<i;){var u=e[s],c=r(t,u);n(c,u)&&o(l,a(u,t),c)}return l}},611:(t,e,n)=>{var r=n(4865),o=n(1811),a=n(5776),s=n(3218),i=n(327);t.exports=function(t,e,n,l){if(!s(t))return t;for(var u=-1,c=(e=o(e,t)).length,f=c-1,d=t;null!=d&&++u<c;){var h=i(e[u]),p=n;if("__proto__"===h||"constructor"===h||"prototype"===h)return t;if(u!=f){var v=d[h];void 0===(p=l?l(v,h,d):void 0)&&(p=s(v)?v:a(e[u+1])?[]:{})}r(d,h,p),d=d[h]}return t}},1811:(t,e,n)=>{var r=n(1469),o=n(5403),a=n(5514),s=n(9833);t.exports=function(t,e){return r(t)?t:o(t,e)?[t]:a(s(t))}},9021:(t,e,n)=>{var r=n(5564),o=n(5357),a=n(61);t.exports=function(t){return a(o(t,void 0,r),t+"")}},222:(t,e,n)=>{var r=n(1811),o=n(5694),a=n(1469),s=n(5776),i=n(1780),l=n(327);t.exports=function(t,e,n){for(var u=-1,c=(e=r(e,t)).length,f=!1;++u<c;){var d=l(e[u]);if(!(f=null!=t&&n(t,d)))break;t=t[d]}return f||++u!=c?f:!!(c=null==t?0:t.length)&&i(c)&&s(d,c)&&(a(t)||o(t))}},7285:(t,e,n)=>{var r=n(2705),o=n(5694),a=n(1469),s=r?r.isConcatSpreadable:void 0;t.exports=function(t){return a(t)||o(t)||!!(s&&t&&t[s])}},5403:(t,e,n)=>{var r=n(1469),o=n(3448),a=/\.|\[(?:[^[\]]*|(["'])(?:(?!\1)[^\\]|\\.)*?\1)\]/,s=/^\w*$/;t.exports=function(t,e){if(r(t))return!1;var n=typeof t;return!("number"!=n&&"symbol"!=n&&"boolean"!=n&&null!=t&&!o(t))||(s.test(t)||!a.test(t)||null!=e&&t in Object(e))}},4523:(t,e,n)=>{var r=n(8306);t.exports=function(t){var e=r(t,(function(t){return 500===n.size&&n.clear(),t})),n=e.cache;return e}},5514:(t,e,n)=>{var r=n(4523),o=/[^.[\]]+|\[(?:(-?\d+(?:\.\d+)?)|(["'])((?:(?!\2)[^\\]|\\.)*?)\2)\]|(?=(?:\.|\[\])(?:\.|\[\]|$))/g,a=/\\(\\)?/g,s=r((function(t){var e=[];return 46===t.charCodeAt(0)&&e.push(""),t.replace(o,(function(t,n,r,o){e.push(r?o.replace(a,"$1"):n||t)})),e}));t.exports=s},327:(t,e,n)=>{var r=n(3448);t.exports=function(t){if("string"==typeof t||r(t))return t;var e=t+"";return"0"==e&&1/t==-Infinity?"-0":e}},5564:(t,e,n)=>{var r=n(1078);t.exports=function(t){return(null==t?0:t.length)?r(t,1):[]}},7361:(t,e,n)=>{var r=n(7786);t.exports=function(t,e,n){var o=null==t?void 0:r(t,e);return void 0===o?n:o}},9095:(t,e,n)=>{var r=n(13),o=n(222);t.exports=function(t,e){return null!=t&&o(t,e,r)}},8306:(t,e,n)=>{var r=n(3369);function o(t,e){if("function"!=typeof t||null!=e&&"function"!=typeof e)throw new TypeError("Expected a function");var n=function(){var r=arguments,o=e?e.apply(this,r):r[0],a=n.cache;if(a.has(o))return a.get(o);var s=t.apply(this,r);return n.cache=a.set(o,s)||a,s};return n.cache=new(o.Cache||r),n}o.Cache=r,t.exports=o},8718:(t,e,n)=>{var r=n(5970),o=n(9021)((function(t,e){return null==t?{}:r(t,e)}));t.exports=o},879:(t,e,n)=>{"use strict";n.d(e,{Z:()=>o});const r={name:"nzPerPageSelect",props:{value:Number,options:Array}};const o=(0,n(1900).Z)(r,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("b-field",[n("b-select",{attrs:{value:t.value,placeholder:"Per page"},on:{input:function(e){return t.$emit("input",e)}}},t._l(t.options,(function(e,r){return n("option",{key:r,domProps:{value:e,textContent:t._s(e)}})})),0)],1)}),[],!1,null,null,null).exports},7318:(t,e,n)=>{"use strict";n.d(e,{Z:()=>o});const r={name:"nzSortableColumnHeader",props:{column:{type:Object,required:!0},sort:Object},computed:{isNotSortedColumn:function(){return this.sort.field!==this.column.field},sortIcon:function(){return this.isNotSortedColumn?"sort":"asc"===this.sort.order?"sort-asc":"sort-desc"}}};const o=(0,n(1900).Z)(r,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("span",{staticClass:"is-flex is-align-items-center"},[t._v("\n  "+t._s(t.column.label)+"\n  "),t.column.sortable?n("b-icon",{staticClass:"ml-2",class:{"has-text-grey-light":t.isNotSortedColumn},attrs:{pack:"fa",icon:t.sortIcon,size:"is-small"}}):t._e()],1)}),[],!1,null,null,null).exports},5751:(t,e,n)=>{"use strict";n.r(e),n.d(e,{default:()=>c});var r=n(6386),o=n(7361),a=n.n(o),s=n(6311),i=n(879),l=n(7318);const u={name:"nzAnnouncementsTable",mixins:[s.Z],components:{NzPerPageSelect:i.Z,NzSortableColumnHeader:l.Z},props:{perPageOptions:{type:Array,default:function(){return[15,30,50,100]},validator:function(t){return t.length}},listRoute:String,editRoute:String,deleteRoute:String,empty:{type:String,default:"Nothing here."},ranks:Array},data:function(){return{data:[],meta:null,total:0,loading:!1,sortField:"name",sortOrder:"asc",page:1,perPage:this.perPageOptions[0],checkedRows:[]}},computed:{showing:function(){if(this.meta)return this.trans("labels.tables.from_to_total",{from:a()(this.meta,"from")||0,to:a()(this.meta,"to")||0,total:a()(this.meta,"total")||0})}},created:function(){this.restoreState(),this.loadAsyncData()},methods:{loadAsyncData:function(){var t=this;return this.loading=!0,axios.get(route(this.listRoute).withQuery({sort_by:"".concat(this.sortField,".").concat(this.sortOrder),page:this.page,per_page:this.perPage})).then((function(e){var n=e.data;t.data=[],t.total=n.meta.total,n.data.forEach((function(e){return t.data.push(e)})),t.meta=n.meta,t.loading=!1}),(function(e){t.data=[],t.meta=null,t.total=0,t.loading=!1}))},onPageChange:function(t){this.page=t,this.loadAsyncData()},onSort:function(t,e){this.sortField=t,this.sortOrder=e,this.saveState(),this.loadAsyncData()},clearFilter:function(){for(var t in this.newFilter)this.newFilter[t]="";this.onFilter()},onFilter:function(){var t=!1;for(var e in this.newFilter)this.filter[e]!==this.newFilter[e]&&(t=!0),this.filter[e]=this.newFilter[e];t&&this.loadAsyncData()},onPerPageChange:function(t){t!==this.perPage&&(this.perPage=t,this.saveState(),this.loadAsyncData())},confirmRemove:function(t){var e=this;this.$buefy.dialog.confirm({message:this.trans("Are you sure you want to delete this record?"),confirmText:this.trans("buttons.delete"),cancelText:this.trans("buttons.cancel"),type:"is-danger",onConfirm:function(){e.remove(t)}})},remove:function(t){var e=this;return axios.delete(route(this.deleteRoute,t.id)).then((function(t){e.$buefy.toast.open({message:e.trans("Record deleted"),type:"is-success"}),e.loadAsyncData()})).catch((function(t){console.error(t)}))},editLink:function(t){return route(this.editRoute,t.id)}},filters:{formatDateTime:function(t){var e=(0,r.Z)(t);return e.isValid()?e.format("D.M.YYYY HH:mm"):""}}};const c=(0,n(1900).Z)(u,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"announcements-table"},[n("b-table",{attrs:{data:t.data,loading:t.loading,paginated:"","backend-pagination":"",total:t.total,"per-page":t.perPage,"pagination-position":"both","backend-sorting":"","default-sort-direction":"asc","default-sort":[t.sortField,t.sortOrder],"mobile-cards":""},on:{"page-change":t.onPageChange,sort:t.onSort},scopedSlots:t._u([{key:"top-left",fn:function(){return[n("nz-per-page-select",{attrs:{value:t.perPage,options:t.perPageOptions},on:{input:t.onPerPageChange}})]},proxy:!0},{key:"bottom-left",fn:function(){return[n("nz-per-page-select",{attrs:{value:t.perPage,options:t.perPageOptions},on:{input:t.onPerPageChange}})]},proxy:!0},{key:"empty",fn:function(){return[n("section",{staticClass:"section"},[n("div",{staticClass:"content has-text-grey has-text-centered"},[n("p",[t._v(t._s(t.empty))])])])]},proxy:!0}])},[t._v(" "),t._v(" "),n("b-table-column",{attrs:{field:"id",label:t.trans("labels.id"),width:"40",numeric:"",sortable:""},scopedSlots:t._u([{key:"default",fn:function(e){var n=e.row;return[t._v("\n        "+t._s(n.id)+"\n      ")]}},{key:"header",fn:function(e){var r=e.column;return[n("nz-sortable-column-header",{attrs:{column:r,sort:{field:t.sortField,order:t.sortOrder}}})]}}])}),t._v(" "),n("b-table-column",{attrs:{field:"title",label:t.trans("labels.announcements.title"),sortable:""},scopedSlots:t._u([{key:"default",fn:function(e){var n=e.row;return[t._v("\n        "+t._s(n.title||"--")+"\n      ")]}},{key:"header",fn:function(e){var r=e.column;return[n("nz-sortable-column-header",{attrs:{column:r,sort:{field:t.sortField,order:t.sortOrder}}})]}}])}),t._v(" "),n("b-table-column",{attrs:{field:"created_at",label:t.trans("labels.created_at"),sortable:""},scopedSlots:t._u([{key:"default",fn:function(e){var n=e.row;return[t._v("\n        "+t._s(t._f("formatDateTime")(n.created_at))+"\n      ")]}},{key:"header",fn:function(e){var r=e.column;return[n("nz-sortable-column-header",{attrs:{column:r,sort:{field:t.sortField,order:t.sortOrder}}})]}}])}),t._v(" "),n("b-table-column",{attrs:{width:"150",numeric:""},scopedSlots:t._u([{key:"default",fn:function(e){var r=e.row;return[n("a",{attrs:{href:t.editLink(r)}},[n("b-icon",{attrs:{icon:"edit"}})],1),t._v(" "),n("a",{on:{click:function(e){return t.confirmRemove(r)}}},[n("b-icon",{attrs:{icon:"trash"}})],1)]}}])})],1)],1)}),[],!1,null,null,null).exports}}]);
//# sourceMappingURL=2d52500f6821e59283af.js.map