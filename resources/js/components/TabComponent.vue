<template>
    <v-card  class="overflow-y-hidden ma-2">
        <v-tabs color="yellow darken-3" prev-icon="navigate_before"
                        next-icon="navigate_next"
                        show-arrows="always">
            <v-tab v-text="item['title']" v-for="item in data" v-bind:key="item['title']"></v-tab>
            <v-tab-item v-for="item in data" v-bind:key="item['title']">
                <v-card color="blue-grey darken-4">
                    <v-slide-group
                        prev-icon="navigate_before"
                        next-icon="navigate_next"
                        show-arrows
                    >
                        <v-slide-item  v-for="entity in item['values']" v-bind:key="entity.id">
                            <EntityCard v-if="item.entity_type == 1" :lists="filterLists(entity.id)" :visit_case="item.visit_case" v-bind:showTitle='showTitle' v-bind:hover='hover' v-bind:showButtons='showButtons' v-bind:itemId='entity.id' v-bind:height=215 v-bind:width=150 v-bind:title='entity.title' v-bind:poster_path="entity.poster_path"></EntityCard>
                            <EntityCard v-else :visit_case="item.visit_case" v-bind:showTitle='showTitle' v-bind:hover='hover' v-bind:showButtons='showButtons' v-bind:itemId='entity.id' v-bind:height=215 v-bind:width=150 v-bind:title='entity.title' v-bind:poster_path="entity.poster_path"></EntityCard>
                        </v-slide-item>
                    </v-slide-group>
                </v-card>
        </v-tab-item>
        </v-tabs>
    </v-card>
</template>

<script>
import EntityCard from './EntityCardComponent.vue'
import {usernameMixin} from '../mixins.js'

export default {
    components: {
        EntityCard
    },

    mixins: [usernameMixin],

    props: {
        data: {
            type: Array
        },
        hover:{
            type: Boolean,
            default: false
        },
        showTitle:{
            type: Boolean,
            default: false
        },
        showButtons:{
            type: Boolean,
            default: false
        },
        lists: {
            type: Array
        },
        movies_lists_associations: {
            type: Array
        }
    },

    methods:{
        //  filterLists(movie_id){
        //         if(this.lists == null) return null;
        //         var movie_lists_associations = this.filterMoviesListsAssociations(movie_id)
        //         var lists = Object.keys(this.lists).map((key) => {return this.lists[key] });
        //         console.log(lists);
        //         var nonContainingLists = [];
        //         var inSeenList = false;
        //         var inWatchList = false;
        //         if(movie_lists_associations.find(list => (list.list_id == lists[0].id && list.movie_id == movie_id)) != null) inWatchList = true;
        //         if(movie_lists_associations.find(list => (list.list_id == lists[1].id && list.movie_id == movie_id)) != null) inSeenList = true;
        //         var watchListId = lists[0].id;
        //         var seenListId = lists[1].id;

        //         lists.splice(0, 2);

        //         let i = 0;
        //         while(i < lists.length){
        //             var result = movie_lists_associations.filter((association) => {
        //                     return (association['movie_id'] == movie_id & association['list_id'] == lists[i]['id']);
        //                 });
        //             if(result.length == 0){
        //                 nonContainingLists = nonContainingLists.concat(lists.splice(i,1)); 
        //                 i--; 
        //             }
        //             ++i;
        //         }   

        //         let movie_lists = {
        //             inSeenList: inSeenList,
        //             inWatchList: inWatchList,
        //             seenListId: seenListId,
        //             watchListId: watchListId,
        //             nonContainingLists: nonContainingLists,
        //             lists: lists
        //         };
        //         return movie_lists;
        //     },

        //     filterMoviesListsAssociations(movie_id){
        //         let moviesListsAssociationArray = Object.keys(this.movies_lists_associations).map((key) => {
        //                                             return this.movies_lists_associations[key]
        //                                           });
        //         return moviesListsAssociationArray.filter((movie) => {
        //             return movie['movie_id'] == movie_id;
        //         });
        //     },
    }
}
</script>
<style>
    .v-slide-group__next .v-icon, .v-slide-group__prev .v-icon {
        color: #0097A7 !important;
    }
</style>