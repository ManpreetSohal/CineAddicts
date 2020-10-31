<template>
    <div class="container">
        <v-card @click.native='visit(itemId, visit_case)' :hover="hover" :height="height" :width="width" class="pa-5 ma-2">
            <v-img v-if="poster_path != null"
                :src="'https://image.tmdb.org/t/p/w600_and_h900_bestv2/'+ poster_path"
                class="white--text align-end font-weight-bold text-start"
                @mouseenter.native="onMouseEnter()"
                @mouseleave.native="onMouseLeave()"
            >
               <cardactions-component v-on:listUpdate="handleListUpdate($event)" v-if="showButtons" :movie_lists="lists" v-bind:itemId='itemId' v-show='hovered' class="white"></cardactions-component>
            </v-img>
            <v-img v-else
                src="/images/no_poster_movie.jpg"
                class="white--text align-end font-weight-bold text-start"
                @mouseenter.native="onMouseEnter()"
                @mouseleave.native="onMouseLeave()"
            >
                <v-card-text class="text-break-word" v-text="title"></v-card-text>
                <cardactions-component v-on:listUpdate="handleListUpdate($event)" v-if="showButtons" :movie_lists="lists" v-bind:itemId='itemId' v-show="hovered" class="white"></cardactions-component>
            </v-img>
            <v-card-text v-if="showTitle" v-show="showTitle" class="text-truncate ml-0 mr-0 mt-1 mb-n4 pa-0" v-text="title"></v-card-text>
    </v-card>  
     <v-snackbar color="blue-grey darken-4" timeout="2000" elevation="24" v-model="snackbar">
        {{snackbarMessage}}

    <template v-slot:action="{ attrs }">
    <v-btn
        color="yellow darken-3"
        text
        v-bind="attrs"
        @click="snackbar = false"
    >
        Close
    </v-btn>
    </template>
</v-snackbar>
    </div>
</template>

<script>
import {usernameMixin} from '../mixins.js'

    export default {
        mixins: [usernameMixin],

        props: {
            title: String,
            poster_path: String,
            height: {
                type: Number,
                default: undefined
            },
            width: {
                type: Number,
                default: undefined
            },
            hover: {
                type: Boolean,
                default: false
            },
            showButtons: {
                type: Boolean,
                default: false
            },
            showTitle: {
                type: Boolean,
                default: false
            },
            itemId: {
                type: Number
            },
            lists:{
                inSeenList: false,
                inWatchList: false,
                seenListId: null,
                watchListId: null,
                nonContainingLists: [],
                lists: []
            },
            visit_case: 0
            // lists: {
            //     type: Array
            // },
        },

        data() {
            return {
                hovered: false,
                snackbar: false,
                snackbarMessage: ''
            }
        },

        methods: {
            onMouseEnter(){
                if(this.showButtons){
                    this.hovered = true;        
                }
            },

            onMouseLeave(){
                if(this.showButtons){
                    this.hovered = false;        
                }
            },

            handleListUpdate(message){
                this.snackbar = true;
                this.snackbarMessage = message;
            }
        }
    }
</script>