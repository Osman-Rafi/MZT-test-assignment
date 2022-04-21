import Vue from "vue";
import router from "./router";

require("./bootstrap");

window.Vue = require("vue").default;

Vue.component("app-component", require("./components/AppComponent").default);

const app = new Vue({
  el: "#app",
  router,
});
