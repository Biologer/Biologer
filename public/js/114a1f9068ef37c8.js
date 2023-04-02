"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[375],{7375:(t,n,e)=>{e.r(n),e.d(n,{default:()=>m});const o={name:"nzActivityTaxonCreated",props:{activity:{type:Object,required:!0}}};var i=e(1900);const r=(0,i.Z)(o,(function(){var t=this;return(0,t._self._c)("div",{staticClass:"activity-log-item"},[t._v("\n  "+t._s(t._f("formatDateTime")(t.activity.created_at))+" "+t._s(t.activity.causer.full_name)+" "+t._s(t.trans("activityLog.added_record"))+" "+t._s(t.activity.subject_id)+"\n")])}),[],!1,null,null,null).exports;function a(t){return a="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},a(t)}const c={name:"nzActivityTaxonUpdated",props:{activity:{type:Object,required:!0}},computed:{formatedChanges:function(){var t=this,n=this.activity.properties.old;return Object.keys(n).map((function(e){var o=t.oldValue(n,e);return"".concat(t.trans("labels.taxa."+e))+(o?" (".concat(o,")"):"")})).join(", ")}},methods:{oldValue:function(t,n){var e=t[n];return null==e?null:"object"===a(e)?e.label?this.trans(e.label):null:e}}};const u=(0,i.Z)(c,(function(){var t=this;return(0,t._self._c)("div",{staticClass:"activity-log-item"},[t._v("\n  "+t._s(t._f("formatDateTime")(t.activity.created_at))+" "+t._s(t.activity.causer.full_name)+" "+t._s(t.trans("activityLog.changed"))+" "+t._s(t.formatedChanges)+": "+t._s(t.activity.properties.reason)+"\n")])}),[],!1,null,null,null).exports;var l;function s(t){return s="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},s(t)}function y(t,n,e){return(n=function(t){var n=function(t,n){if("object"!==s(t)||null===t)return t;var e=t[Symbol.toPrimitive];if(void 0!==e){var o=e.call(t,n||"default");if("object"!==s(o))return o;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===n?String:Number)(t)}(t,"string");return"symbol"===s(n)?n:String(n)}(n))in t?Object.defineProperty(t,n,{value:e,enumerable:!0,configurable:!0,writable:!0}):t[n]=e,t}const f={name:"nzTaxonActivityLog",components:(l={},y(l,r.name,r),y(l,u.name,u),l),props:{activities:{type:Array,default:function(){return[]}}},methods:{chooseActivityComponent:function(t){return"nz-activity-taxon-".concat(t.description.replace("_","-"))}}};const m=(0,i.Z)(f,(function(){var t=this,n=t._self._c;return n("div",{staticClass:"activity-log"},t._l(t.activities,(function(e){return n(t.chooseActivityComponent(e),{key:e.id,tag:"component",attrs:{activity:e}})})),1)}),[],!1,null,null,null).exports}}]);
//# sourceMappingURL=114a1f9068ef37c8.js.map