import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

const routes = [
  {
    path: "/",
    component: () => import("./views/Welcome.vue"),
  },
  {
    path: "/companies-listing",
    component: () => import("./views/CompaniesListing.vue"),
  },
  {
    path: "/company/:id",
    component: () => import("/views/Candidates.vue"),
  },
  /* {
        path:'*',
        component: () => import("./components/NotFound")
    }, */
];
export default new VueRouter({
  mode: "history",
  routes,
});
