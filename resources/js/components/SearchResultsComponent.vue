<template>
<v-app>
    <v-card rounded="0" color="yellow darken-3" fluid>
        <v-card-title class="text-body-1 text-uppercase blue-grey--text text--darken-4">Results for '{{search_str}}'</v-card-title>
    </v-card>
    <v-card rounded="0">
         <v-tabs dark color="yellow darken-3" background-color="blue-grey darken-3" prev-icon="navigate_before"
            next-icon="navigate_next">
                      
            <v-tab :disabled="(item.values.length == 0) ? true : false" v-text="item.title+'('+item.values.length+')'" v-for="item in data" v-bind:key="item.title"></v-tab>
                <v-tab-item v-for="item in data" v-bind:key="item.title">
                   <v-card rounded="0" min-height="640">
                    <v-container v-if="item.entity_type == 6">
                        <v-chip v-for="entity in item.values" v-bind:key="entity.id" label id="chip" class="mt-2 mb-2" text-color="yellow darken-3" color="blue-grey darken-4" @click="visit(entity.id, item.visit_case)">{{entity.title}}</v-chip>
                    </v-container>
                    <Users v-else-if="item.entity_type == 5" :users="item.values" :visit_case="item.visit_case"></Users>
                    <v-layout v-else row wrap>
                        <v-flex xs4 sm3 md2 lg2 v-for="entity in item.values" v-bind:key="entity.id">
                            <EntityCard v-if="item.entity_type == 1" :lists="filterLists(entity.id)" :visit_case="item.visit_case" v-bind:hover='true' v-bind:showButtons='(username != null) ? true : false' v-bind:itemId='entity.id' v-bind:title='entity.title' v-bind:poster_path="entity.poster_path"></EntityCard>
                            <!-- <v-div v-else-if="item.entity_type == 5">    
                                <v-card width="200" height="200" class="rounded-card ml-2">
                                    <v-avatar v-if="entity.poster_path != null" id="user_avatar" size="100" class="ml-5 mr-5 mt-2 justify-center" @click="visit(entity.title, item.visit_case)"><img :src="'/'+entity.poster_path"/></v-avatar>
                                    <v-avatar v-else id="user_avatar" size="100" class="ml-5 mr-5 mt-2 justify-center" color='blue-grey darken-4' @click="visit(entity.title, item.visit_case)"><v-icon size='100'>account_circle</v-icon></v-avatar>
                                </v-card>
                                    <p class="text-center text-caption">{{truncateString(entity.title, 20)}}</p>
                            </v-div> -->
                            <EntityCard v-else :visit_case="item.visit_case" v-bind:showTitle='true' v-bind:hover='true' v-bind:itemId='entity.id' v-bind:title='entity.title' v-bind:poster_path="entity.poster_path"></EntityCard>
                        </v-flex>
                    </v-layout> 
                   </v-card>
                </v-tab-item>
        </v-tabs>
    </v-card>
</v-app>
</template>
<script>
import EntityCard from './EntityCardComponent.vue'
import Users from './UsersComponent.vue'
import {usernameMixin} from '../mixins.js'

    export default {
        components: {
            EntityCard,
            Users
        },

        mixins: [usernameMixin],

        props: {
            search_str: '',
            data: [],
            movies_lists_associations: [],
        },

        data() {
            return {
                lists: [],
                username: ''
            }
        },
        
        created(){
            document.body.setAttribute('data-app', true);
            this.fetchUserInfo();
        },

        methods: {
            fetchUserInfo(){
                fetch("/api/users/")
                .then(res => res.json())    
                .then(res => {
                    this.username = res.username,
                    this.lists = res.lists
                });
            }
        }
    }
</script>
<style>
    .rounded-card{
     border-radius:50% !important;
    }

    #user_avatar{
        display: block;
        margin-left: auto !important;
        margin-right: auto !important;
        cursor: pointer;
    }

    #chip{
        cursor:  pointer !important;
        width: 100% !important;
    }
</style>