<template>
<v-app>
    <div v-if="!loading" class="container ma-0 pa-0">
        <v-container class="ma-0 pa-0"> 
            <v-card rounded="0" class='pa-2' color="blue-grey darken-3">
                <v-row align="center" dense class='d-flex' >
                    <v-btn @click.native="fetchContributors()" class='mr-2 blue-grey--text text--darken-4' x-small color='yellow darken-3'>All</v-btn>
                    <v-col id='column-item' class="ma-0 mr-2 pa-0" cols='1'>
                        <v-select :items="items['alphabet']" v-model='selected_alphabet' dense label='A-Z' solo append-icon='arrow_drop_down'></v-select>
                    </v-col>
                    <v-col v-if="path == '/contributors'" id='column-item' class="ma-0 mr-2 pa-0" cols='2'>
                        <v-select :items="items['countries']" v-model='selected_country' dense label='Country' solo append-icon='arrow_drop_down'></v-select>
                    </v-col>
                    <v-btn @click.native="filterContributors()" class='mr-2 blue-grey--text text--darken-4' x-small color='yellow darken-3'>Find</v-btn>
                    <v-btn @click.native="resetSelections()" class="blue-grey--text text--darken-4" x-small color='yellow darken-3'>Clear Selections</v-btn>
                </v-row>
            </v-card>
            <v-card v-if="contributors.length != 0" rounded="0" min-height="640">
                <v-layout row wrap>
                    <v-flex xs4 sm3 md2 lg2 v-for="contributor in contributors" v-bind:key="contributor.id">
                        <EntityCard v-bind:showTitle=true v-bind:hover=true :visit_case="visit_case" v-bind:itemId='contributor.id' v-bind:title='contributor.name' v-bind:poster_path='contributor.poster_path'></EntityCard>
                    </v-flex>
                </v-layout>
            </v-card> 
            <v-layout v-else>
                <v-card class="yellow--text text--darken-3" raised style="margin-left:auto; margin-right: auto; margin-top: 10%; margin-bottom: 10%;">
                    <v-card-title class="justify-center">{{empty_result_message}}</v-card-title>
                    <v-card-actions class="justify-center"><v-btn @click="fetchContributors()" color="yellow darken-3"><v-icon color="blue-grey darken-4">refresh</v-icon></v-btn></v-card-actions>
                </v-card>
            </v-layout>
            <pagination v-if="contributors.length != 0" v-on:filter="filter($event)" v-bind:pagination='pagination'></pagination>
        </v-container>
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
            Pagination
        },

        mixins: [usernameMixin],

        data() {
            return {
                contributors: [],
                items: [],
                visit_case: null,
                selected_alphabet: null,
                selected_country: null,
                pagination: {},
                fetch_case: 1,
                path: null,
                empty_result_message: 'Oops nothing found',
                loading: true,
            }
        },

        created() {
            document.body.setAttribute('data-app', true);
            this.path =  this.$router.currentRoute.path;
            this.fetchContributors();
        },

        methods: {
            fetchContributors(page_url) {
                page_url = page_url || '/api'+this.path; 
                fetch(page_url)
                .then(res => res.json())
                .then(res => { this.contributors = res.data.data, this.items = res.items, this.visit_case = res.visit_case, this.fetch_case = 1, this.makePagination(res.data.current_page, res.data.last_page, res.data.next_page_url, res.data.prev_page_url, res.data.path) })
                .then(() => {
                    this.loading = false;
                });
            },

            filterContributors(page_url){
                let data = {
                    alphabet: this.selected_alphabet,
                    country_id: this.selected_country
                }

                this.resetSelections();

                page_url = page_url || '/api'+this.path+'/filter/'+JSON.stringify(data);
                console.log("ddd");
                fetch(page_url)
                .then(res => res.json())
                .then(res => { this.contributors = res.data.data, this.fetch_case = 2, this.makePagination(res.data.current_page, res.data.last_page, res.data.next_page_url, res.data.prev_page_url, res.data.path) });
            },    

            resetSelections(){
                this.selected_alphabet = null;
                this.selected_country = null;
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
                    this.fetchContributors(page_url);
                }
                else{
                    this.filterContributors(page_url);
                }
            }

        }

    }
</script>