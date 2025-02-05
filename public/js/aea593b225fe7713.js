"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[113],{9113:(n,t,o)=>{o.r(t),o.d(t,{default:()=>c});const e={name:"TaxonomyTable",data:function(){return{check:null,connect:null,disconnect:null,sync:null}},props:{checkRoute:String,connectRoute:String,disconnectRoute:String,syncRoute:String,synced:String,not_synced:String},methods:{checkConnection:function(){var n=this;return axios.get(route(this.checkRoute)).then((function(t){return n.check=t.data})).catch((function(n){return console.log(n)}))},connectTaxonomy:function(){var n=this;return axios.get(route(this.connectRoute)).then((function(t){return n.connect=t.data})).catch((function(n){return console.log(n)}))},disconnectTaxonomy:function(){var n=this;if(confirm("Do you really want to disconnect?"))return axios.get(route(this.disconnectRoute)).then((function(t){return n.disconnect=t.data})).catch((function(n){return console.log(n)}))},syncTaxonomy:function(){var n=this;return this.sync="Syncing... This will take a while, and will *fail* a lot, almost every ~3000 taxa... No progress bar yet.",axios.get(route(this.syncRoute)).then((function(t){return n.sync=t.data})).catch((function(n){return console.log(n)}))}}};const c=(0,o(4486).A)(e,(function(){var n=this,t=n._self._c;return t("div",{staticClass:"taxonomy-table"},[t("div",[n._v("\n    Simple tools for testing. More will be added/modified later ...\n  ")]),n._v(" "),t("hr"),n._v(" "),t("button",{staticClass:"button is-info",attrs:{type:"button"},on:{click:n.checkConnection}},[n._v("Check")]),n._v("\n  Check connection to Taxonomy base.\n  "),t("br"),n._v(" "),t("b",[n._v("Response:")]),n._v(" "+n._s(n.check)+"\n  "),t("hr"),n._v(" "),t("button",{staticClass:"button is-success",attrs:{type:"button"},on:{click:n.connectTaxonomy}},[n._v("Connect")]),n._v("\n  If connected, this Biologer database will receive updates from Taxonomy base as soon they are available.\n  Connecting will also send info about legislation's and red lists to Taxonomy base, to be in sync."),t("br"),n._v(" "),t("b",[n._v("Response:")]),n._v(" "+n._s(n.connect)+"\n  "),t("hr"),n._v(" "),t("button",{staticClass:"button is-danger",attrs:{type:"button"},on:{click:n.disconnectTaxonomy}},[n._v("Disconnect")]),n._v("\n  If disconnected, this Biologer database will NOT receive any updates from taxonomy base. All ID's connected to\n  Taxonomy base will be erased!"),t("br"),n._v(" "),t("b",[n._v("Response:")]),n._v(" "+n._s(n.disconnect)+"\n  "),t("hr"),n._v(" "),t("button",{staticClass:"button is-primary",attrs:{type:"button"},on:{click:n.syncTaxonomy}},[n._v("Sync all taxa")]),n._v("\n  Search for all taxa that are not already updated with Taxonomy base."),t("br"),n._v(" "),t("b",[n._v("Response:")]),n._v(" "+n._s(n.sync)+"\n  "),t("hr"),n._v("\n  Synced: "+n._s(n.synced)+"\n  Not synced: "+n._s(n.not_synced)+"\n")])}),[],!1,null,"67a0b294",null).exports}}]);
//# sourceMappingURL=aea593b225fe7713.js.map