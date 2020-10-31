import Vue from 'vue';
import VueRouter from 'vue-router'
import MoviesComponent from './components/MoviesComponent.vue'
import MovieInfoComponent from './components/MovieInfoComponent.vue'
import PersonInfoComponent from './components/PersonInfoComponent.vue'
import ContributorsComponent from './components/ContributorsComponent.vue'
import CompanyInfoComponent from './components/CompanyInfoComponent.vue'
import UserProfileComponent from './components/UserProfileComponent.vue'
import MovieListsTableComponent from './components/MovieListsTableComponent.vue'
import PageNotFoundComponent from './components/PageNotFoundComponent.vue'
import ListInfoComponent from './components/ListInfoComponent.vue'
import LocationInfoComponent from './components/LocationInfoComponent.vue'

Vue.use(VueRouter);

export default new VueRouter({
    routes: [
        { path: '/movies', component: MoviesComponent },
        { path: '/movies/:id', component: MovieInfoComponent },
        { path: '/contributors', component: ContributorsComponent },
        { path: '/contributors/:id', component: PersonInfoComponent },
        { path: '/companies', component: ContributorsComponent },
        { path: '/companies/:id', component: CompanyInfoComponent },
        { path: '/user', component: UserProfileComponent },
        { path: '/users/:id', component: UserProfileComponent },
        { path: '/lists/:id', component: MoviesComponent },
        { path: '/lists/info/:id', component: ListInfoComponent },
        { path: '/lists', component: MovieListsTableComponent },
        { path: '/pagenotfound', component: PageNotFoundComponent },
        { path: '/locations/:id', component: LocationInfoComponent }

    ],
    mode: 'history'
})  