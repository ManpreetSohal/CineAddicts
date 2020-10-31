<template>
<v-app>
    <div v-if="!loading" class="container ma-0 pa-0">
        <v-container v-if="!result_empty" class="ma-0 pa-0">
            <v-card rounded="0" class='pa-2' color="blue-grey darken-3">
                <v-row align="center" dense class='d-flex' >
                    <v-btn @click.native="fetchMovies()" class='mr-2 blue-grey--text text--darken-4' x-small color='yellow darken-3'>All</v-btn>
                    <v-col id='column-item' class="ma-0 mr-2 pa-0" cols='1'>
                        <v-select :items="items['years']" v-model='selected_year' dense label='Year' solo append-icon='arrow_drop_down'></v-select>
                    </v-col>
                    <v-col id='column-item' class="ma-0 mr-2 pa-0" cols='2'>
                        <v-select :items="items['genres']" v-model='selected_genre' dense label='Genre' solo append-icon='arrow_drop_down'></v-select>
                    </v-col>
                    <v-col id='column-item'  class="ma-0 mr-2 pa-0" cols='2'>
                        <v-select :items="items['countries']" v-model='selected_country' dense label='Country' solo append-icon='arrow_drop_down'></v-select>
                    </v-col>
                    <v-col id='column-item' class="ma-0 mr-2 pa-0" cols='2'>
                        <v-select :items="items['languages']" v-model='selected_language' dense label='Language' solo append-icon='arrow_drop_down'></v-select>
                    </v-col>
                    <v-btn @click.native="filterMovies()" class='mr-2 blue-grey--text text--darken-4' x-small color='yellow darken-3'>Find</v-btn>
                    <v-btn @click.native="resetSelections()" class='blue-grey--text text--darken-4' x-small color='yellow darken-3'>Clear Selections</v-btn>
                </v-row>
            </v-card>
            <v-card v-if="movies.length != 0" rounded="0" min-height="640">
                <v-layout row wrap>
                    <v-flex xs4 sm3 md2 lg2 v-for="movie in movies" v-bind:key="movie.id">
                        <EntityCard :lists="filterLists(movie.id)" :visit_case="visit_case" v-bind:itemId='movie.id' v-bind:hover=true v-bind:showButtons='(username != null && username.legth != 0) ? true : false' v-bind:title='movie.title' v-bind:poster_path='movie.poster_image_path'></EntityCard>
                    </v-flex>
                </v-layout> 
            </v-card>
            <v-layout v-else>
                <v-card class="yellow--text text--darken-3" raised style="margin-left:auto; margin-right: auto; margin-top: 10%; margin-bottom: 10%;">
                    <v-card-title class="justify-center">{{empty_result_message}}</v-card-title>
                    <v-card-actions class="justify-center"><v-btn @click="fetchMovies()" color="yellow darken-3"><v-icon color="blue-grey darken-4">refresh</v-icon></v-btn></v-card-actions>
                </v-card>
            </v-layout>
            <pagination v-if="movies.length != 0" v-on:filter="filter($event)" v-bind:pagination='pagination'></pagination>
        </v-container>
        <v-layout v-else> 
            <v-card class="yellow--text text--darken-3" raised style="margin-left:auto; margin-right: auto; margin-top: 20%;">
                <v-card-title>{{empty_message}}</v-card-title>
            </v-card>
        </v-layout>
    </div>
    <div v-else style="margin-left:auto; margin-right: auto; margin-top: 20%;">
        <v-row justify="center" align="center">
            <v-progress-circular
                indeterminate
                size="50"
                color="yellow darken-3"
            ></v-progress-circular>
        </v-row>
    </div>
    <router-view></router-view>
    </v-app>
</template>

<script>
import EntityCard from './EntityCardComponent.vue'
import Pagination from './PaginationComponent.vue'
import {usernameMixin} from '../mixins.js'

    export default {
        components: {
            EntityCard,
            Pagination,
        },

        mixins: [usernameMixin],

        props: {
            list_id: null,
            reviewsFor: '',
            empty_message: ''
        },

        data() {
            return {
                movies: [],
                items: [],
                movies_lists_associations: [],
                lists: [],
                visit_case: null,
                selected_year: null,
                selected_genre: null,
                selected_country: null,
                selected_language: null,
                pagination: {},
                fetch_case: 1,
                fetch_url: '',
                filter_url: '',
                username: '',
                empty_result_message: 'Oops nothing found',
                loading: true,
                result_empty: false
            }
        },

        created() {
            document.body.setAttribute('data-app', true);
            var currentRoute =  this.$router.currentRoute;
            var path = currentRoute.path;
            this.fetchUserInfo();

            if(path == '/movies'){
                this.fetch_url = '/api/movies';
                this.filter_url = '/api/movies/filter/'
            }
            else{

                // if(path.match('/\lists\.*/')){
                //     this.list_id = currentRoute.params.id;
                // }
                if(this.list_id != null){
                    this.fetch_url = '/api/lists/'+this.list_id;
                    this.filter_url = '/api/lists/filter/';
                }
                else{
                    if(this.reviewsFor == ''){
                        this.reviewsFor = this.username;
                    }
                    this.fetch_url = '/api/reviews/reviewsforuser/'+this.reviewsFor+'';
                    this.filter_url = '/api/reviews/filter/';
                }
            }
            this.fetchMovies();

        },

        methods: {
            fetchUserInfo(){
                fetch("/api/users/")
                .then(res => res.json())    
                .then(res => {
                    this.username = res.username,
                    this.lists = res.lists
                });
            },

            fetchMovies(page_url) {
                page_url = page_url || this.fetch_url;//'/api/movies'; 
                fetch(page_url)
                .then(res => res.json())
                .then(res => { this.movies = res.data.data, this.items = res.items, this.movies_lists_associations = res.movies_lists_associations, this.visit_case = res.visit_case, this.fetch_case = 1, this.makePagination(res.data.current_page, res.data.last_page, res.data.next_page_url, res.data.prev_page_url, res.data.path) })
                .then(() => {
                    this.loading = false;
                    if(this.movies == null || this.movies.length == 0){
                        this.result_empty = true;
                    }
                });
            },

            filterMovies(page_url){
                let data = {
                    country_id: this.selected_country,
                    genre_id: this.selected_genre,
                    language_id: this.selected_language,
                    year: this.selected_year,
                    list_id: this.list_id,
                    username: this.reviewsFor
                }

                this.resetSelections();

                page_url = page_url || this.filter_url+JSON.stringify(data);//'/api/movies/filter/'+JSON.stringify(data);
    
                fetch(page_url)
                .then(res => res.json())
                .then(res => { this.movies = res.data.data, this.movies_lists_associations = res.movies_lists_associations, this.fetch_case = 2, this.makePagination(res.data.current_page, res.data.last_page, res.data.next_page_url, res.data.prev_page_url, res.data.path) });
            },

             resetSelections(){
                this.selected_genre = null;
                this.selected_country = null;
                this.selected_language = null;
                this.selected_year = null;
            },

            makePagination(current, last, next, prev, path){
                var number_of_pages = null;
                var current_page_spot = null;
                if(last >= 11){
                    number_of_pages = 11;
                    current_page_spot = 6;
                }
                else{
                    number_of_pages = last;
                    current_page_spot = current;
                }

                let pagination = {
                    current_page: current,
                    last_page: last,
                    next_page_url: next,
                    prev_page_url: prev,
                    path: path,
                    number_of_pages: number_of_pages,
                    current_page_spot: current_page_spot
                }
                this.pagination = null;
                this.pagination = pagination;
            },

            filter(page_url){
                if(this.fetch_case == 1){
                    this.fetchMovies(page_url);
                }
                else{
                    this.filterMovies(page_url);            
                }
            },
        }
    }
</script>
<style>
    #column-item{
        height: 20px !important;
    }
    .v-text-field.v-text-field--enclosed .v-text-field__details, 
    .v-text-field.v-text-field--enclosed > .v-input__control > .v-input__slot {
        max-height: 20px;
        min-height: 20px !important;
        display: flex!important;
        align-items: center!important   
    }

    .v-input__icon .v-icon{
        color: #263238 !important;
    }
</style>