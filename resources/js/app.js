/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));
import router from './router.js'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import '@mdi/font/css/materialdesignicons.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'

Vue.use(Vuetify, {
    iconfont: 'mdi'
})

Vue.component('movies-component', require('./components/MoviesComponent.vue').default);
Vue.component('contributors-component', require('./components/ContributorsComponent.vue').default);
Vue.component('entitycard-component', require('./components/EntityCardComponent.vue').default);
Vue.component('cardactions-component', require('./components/CardActionsComponent.vue').default);
Vue.component('movieinfo-component', require('./components/MovieInfoComponent.vue').default);
Vue.component('personinfo-component', require('./components/PersonInfoComponent.vue').default);
Vue.component('biography-component', require('./components/BiographyComponent.vue').default);
Vue.component('additionalinfo-component', require('./components/AdditionalInfoComponent.vue').default);
Vue.component('tab-component', require('./components/TabComponent.vue').default);
Vue.component('chipcard-component', require('./components/ChipCardComponent.vue').default);
Vue.component('companyinfo-component', require('./components/CompanyInfoComponent.vue').default);
Vue.component('pagination-component', require('./components/PaginationComponent.vue').default);
Vue.component('userprofile-component', require('./components/UserProfileComponent.vue').default);
Vue.component('newlistform-component', require('./components/NewListFormComponent.vue').default);
Vue.component('confirmationdialog-component', require('./components/ConfirmationDialogComponent.vue').default);
Vue.component('movieliststable-component', require('./components/MovieListsTableComponent.vue').default);
Vue.component('pagenotfound-component', require('./components/PageNotFoundComponent.vue').default);
Vue.component('ratings-component', require('./components/RatingsComponent.vue').default);
Vue.component('newreview-component', require('./components/NewReviewComponent.vue').default);
Vue.component('listinfo-component', require('./components/ListInfoComponent.vue').default);
Vue.component('locationinfo-component', require('./components/LocationInfoComponent.vue').default);
Vue.component('searchresults-component', require('./components/SearchResultsComponent.vue').default);
Vue.component('confirmremovaldialog-component', require('./components/ConfirmRemovalDialogComponent.vue').default);
Vue.component('users-component', require('./components/UsersComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router, 
    vuetify: new Vuetify()
});
