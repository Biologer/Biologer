"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[843],{3843:(i,t,e)=>{e.r(t),e.d(t,{default:()=>n});const s={name:"nzFieldObservationApproval",props:{approveUrl:String,markAsUnidentifiableUrl:String,redirectUrl:{type:String,required:!0},fieldObservation:{type:Object,required:!0}},data:function(){return{approving:!1,markingAsUnidentifiable:!1}},computed:{busy:function(){return this.approving||this.markingAsUnidentifiable}},methods:{confirmApprove:function(){this.$buefy.dialog.confirm({message:this.trans("You are about to approve this field observation"),confirmText:this.trans("buttons.approve"),cancelText:this.trans("buttons.cancel"),type:"is-primary",onConfirm:this.approve.bind(this)})},approve:function(){this.approving=!0,axios.post(this.approveUrl,{field_observation_ids:[this.fieldObservation.id]}).then(this.successfullyApproved).catch(this.failedToApprove)},successfullyApproved:function(){var i=this;this.$buefy.toast.open({message:this.trans("Observation has been approved"),type:"is-success"}),setTimeout((function(){i.approving=!1,window.location.href=i.redirectUrl}),1e3)},failedToApprove:function(i){this.approving=!1,this.$buefy.toast.open({message:this.trans("Observation cannot be approved"),type:"is-danger",duration:5e3})},confirmMarkingAsUnidentifiable:function(){var i=this,t=this.$buefy.dialog.prompt({message:this.trans("You are about to mark observation as unidentifiable. What's the reason?"),confirmText:this.trans("buttons.mark_unidentifiable"),cancelText:this.trans("buttons.cancel"),type:"is-warning",inputAttrs:{placeholder:this.trans("Reason"),required:!0,maxlength:255},onConfirm:this.markAsUnidentifiable.bind(this)});t.$nextTick((function(){i.validateReason(t)}))},markAsUnidentifiable:function(i){this.markingAsUnidentifiable=!0,axios.post(this.markAsUnidentifiableUrl,{field_observation_ids:[this.fieldObservation.id],reason:i}).then(this.successfullyMarkedAsUnidentifiable).catch(this.failedToMarkAsUnidentifiable)},successfullyMarkedAsUnidentifiable:function(){var i=this;this.$buefy.toast.open({message:this.trans("Observation has been marked as unidentifiable"),type:"is-success"}),setTimeout((function(){i.markingAsUnidentifiable=!1,window.location.href=i.redirectUrl}),1e3)},failedToMarkAsUnidentifiable:function(i){this.markingAsUnidentifiable=!1,this.$buefy.toast.open({message:this.trans("This observation cannot be marked as unidentifiable"),type:"is-danger",duration:5e3})},validateReason:function(i){var t=this;i.$refs.input.addEventListener("invalid",(function(i){i.target.setCustomValidity(""),i.target.validity.valid||i.target.setCustomValidity(t.trans("This field is required and can contain max 255 chars."))})),i.$refs.input.addEventListener("input",(function(t){i.validationMessage=null}))}}};const n=(0,e(4486).A)(s,(function(){var i=this,t=i._self._c;return t("div",{staticClass:"level-right"},[i.approveUrl?t("div",{staticClass:"level-item"},[t("button",{staticClass:"button",class:{"is-loading":i.approving},attrs:{type:"button",disabled:i.busy},on:{click:i.confirmApprove}},[t("b-icon",{staticClass:"has-text-success",attrs:{icon:"check"}}),i._v(" "),t("span",[i._v(i._s(i.trans("buttons.approve")))])],1)]):i._e(),i._v(" "),i.markAsUnidentifiableUrl?t("div",{staticClass:"level-item"},[t("button",{staticClass:"button",class:{"is-loading":i.markingAsUnidentifiable},attrs:{type:"button",disabled:i.busy},on:{click:i.confirmMarkingAsUnidentifiable}},[t("b-icon",{staticClass:"has-text-danger",attrs:{icon:"times"}}),i._v(" "),t("span",[i._v(i._s(i.trans("buttons.unidentifiable")))])],1)]):i._e()])}),[],!1,null,null,null).exports}}]);
//# sourceMappingURL=e02bbdea65c27c53.js.map