<template>
    <v-app>
        <v-card v-if="movie != null">
            <v-row>
                <v-col cols="4">
                    <EntityCard  :lists="filterLists(this.movie_id)" v-bind:itemId='movie.id' v-bind:showButtons='(username != null ? true : false)' v-bind:title='movie.title' v-bind:poster_path='movie.poster_image_path'></EntityCard>
                    <v-simple-table dark class="pa-5 ml-5 mr-5 grey darken-4">
                        <tr>
                            <td>Duration: </td>
                            <td>{{ runtimeToString(movie.runtime)}}</td>
                        </tr>

                        <tr>
                            <td>Budget: </td>
                            <td>{{ costToString(movie.budget)}}</td>
                        </tr>

                        <tr>
                            <td>Box office: </td>
                            <td>{{ costToString(movie.box_office) }}</td>
                        </tr>
                    </v-simple-table>
                    <ChipCard v-bind:titles='genres'></ChipCard>
                </v-col>
                <v-col cols="8">
                    <v-row>
                        <v-col cols="12">
                            <BiographyCard v-bind:title='movie.title'
                                v-bind:release_date='movie.release_date'
                                v-bind:content='movie.synopsis'
                                v-bind:languages="languages"
                            >
                            </BiographyCard>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12">
                            <Tabs v-bind:showTitle=true v-bind:hover=true v-bind:data="data"></Tabs>
                        </v-col>
                    </v-row>
                <v-row>
                        <v-col cols="12">
                            <RatingsCard :movie_id="movie_id" :username="username"></RatingsCard>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>
        </v-card>
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
import Tabs from './TabComponent.vue'
import ChipCard from './ChipCardComponent.vue'
import BiographyCard from './BiographyComponent.vue'
import RatingsCard from './RatingsComponent.vue'
import {usernameMixin} from '../mixins.js'

 export default {
        components: {
            EntityCard,
            Tabs,
            ChipCard,
            BiographyCard,
            RatingsCard
        },

        mixins: [usernameMixin],

        data() {
            return {
                movie: null,
                movie_id: null,
                data: [],
                genres: [],
                languages: [],
                countries: [],
                movie_lists_associations: [],
                username: '',
                lists: [],
                movie_lists:{
                    inSeenList: false,
                    inWatchList: false,
                    seenListId: null,
                    watchListId: null,
                    nonContainingLists: [],
                    lists: []
                }
            }
        },

        created() {
            document.body.setAttribute('data-app', true);
            var id = this.$router.currentRoute.params.id;
            this.movie_id = id;
            this.fetchUserInfo();
            this.fetchMovie(id);
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

            filterLists(movie_id){
                if(this.lists == null) return null;
                var lists = Object.keys(this.lists).map((key) => {return this.lists[key] });
                console.log(lists);
                var nonContainingLists = [];
                var inSeenList = false;
                var inWatchList = false;
                if(this.movie_lists_associations.find(list => (list.list_id == lists[0].id && list.movie_id == movie_id)) != null) inWatchList = true;
                if(this.movie_lists_associations.find(list => (list.list_id == lists[1].id && list.movie_id == movie_id)) != null) inSeenList = true;
                var watchListId = lists[0].id;
                var seenListId = lists[1].id;

                lists.splice(0, 2);

                let i = 0;
                while(i < lists.length){
                    var result = this.movie_lists_associations.filter((association) => {
                            return (association['movie_id'] == movie_id & association['list_id'] == lists[i]['id']);
                        });
                    if(result.length == 0){
                        nonContainingLists = nonContainingLists.concat(lists.splice(i,1)); 
                        i--; 
                    }
                    ++i;
                }   

                let movie_lists = {
                    inSeenList: inSeenList,
                    inWatchList: inWatchList,
                    seenListId: seenListId,
                    watchListId: watchListId,
                    nonContainingLists: nonContainingLists,
                    lists: lists
                };
                console.log(movie_lists);
                return movie_lists;
            },

            fetchMovie(id) {
                fetch("/api/movies/"+id)
                .then(res => res.json())    
                .then(res => { this.movie = res.movie, this.movie_lists_associations = res.movie_lists_associations, this.data = res.data, this.genres = res.genres, this.languages = res.languages, this.countries = res.countries });
            },
            
            costToString(value){
                if(value != 0){
                    return (Math.round(value * 100)/100).toString() + ' million USD';
                }
                else{
                    return "N/A";
                }
            },

            runtimeToString(value){
                if(value != 0){
                    return value.toString() + " minutes";
                }
                else{
                    return "N/A";
                }
            },
        }

    }
</script>