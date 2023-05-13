"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[389],{389:(t,e,n)=>{n.r(e),n.d(e,{default:()=>v});const i={name:"nzActivityLiteratureObservationCreated",props:{activity:{type:Object,required:!0}}};var r=n(1900);const o=(0,r.Z)(i,(function(){var t=this;return(0,t._self._c)("div",{staticClass:"activity-log-item"},[t._v("\n  "+t._s(t._f("formatDateTime")(t.activity.created_at))+" "+t._s(t.activity.causer.full_name)+" "+t._s(t.trans("activityLog.added_record"))+" "+t._s(t.activity.subject_id)+"\n")])}),[],!1,null,null,null).exports;function a(t){return a="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},a(t)}const c={name:"nzActivityLiteratureObservationUpdated",props:{activity:{type:Object,required:!0}},computed:{formatedChanges:function(){var t=this,e=this.activity.properties.old;return Object.keys(e).map((function(n){var i=t.oldValue(e,n);return"".concat(t.trans("labels.literature_observations."+n))+(i?" (".concat(i,")"):"")})).join(", ")}},methods:{oldValue:function(t,e){var n=t[e];return null==n?null:"object"===a(n)?n.label?this.trans(n.label):null:n}}};const u=(0,r.Z)(c,(function(){var t=this;return(0,t._self._c)("div",{staticClass:"activity-log-item"},[t._v("\n  "+t._s(t._f("formatDateTime")(t.activity.created_at))+" "+t._s(t.activity.causer.full_name)+" "+t._s(t.trans("activityLog.changed"))+" "+t._s(t.formatedChanges)+": "+t._s(t.activity.properties.reason)+"\n")])}),[],!1,null,null,null).exports;var s;function l(t){return l="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},l(t)}function y(t,e,n){return(e=function(t){var e=function(t,e){if("object"!==l(t)||null===t)return t;var n=t[Symbol.toPrimitive];if(void 0!==n){var i=n.call(t,e||"default");if("object"!==l(i))return i;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===e?String:Number)(t)}(t,"string");return"symbol"===l(e)?e:String(e)}(e))in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}const f={name:"nzLiteratureObservationActivityLog",components:(s={},y(s,o.name,o),y(s,u.name,u),s),props:{activities:{type:Array,default:function(){return[]}}},methods:{chooseActivityComponent:function(t){return"nz-activity-literature-observation-".concat(t.description.split("_").join("-"))}}};const v=(0,r.Z)(f,(function(){var t=this,e=t._self._c;return e("div",{staticClass:"activity-log"},t._l(t.activities,(function(n){return e(t.chooseActivityComponent(n),{key:n.id,tag:"component",attrs:{activity:n}})})),1)}),[],!1,null,null,null).exports}}]);
//# sourceMappingURL=0de6256e7f573636.js.map