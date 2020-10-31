<template>
    <v-app>
        <v-card v-if="location != null">
        <v-row>
            <v-col cols="4">
                <EntityCard v-bind:title='location.name' v-bind:poster_path='location.poster_path'></EntityCard>
                <v-simple-table dark class="pa-5 ml-5 mr-5">
                    <tr>
                        <td>{{ location.name }}, {{country}}</td>
                    </tr>
                    <tr>
                        <td>{{ location.description }}</td>
                    </tr>
                </v-simple-table>
            </v-col>
            <v-col cols="8">
                <v-row>
                    <v-col cols="12">
                        <Tabs :lists="lists" :movies_lists_associations="movies_lists_associations" v-bind:hover=true v-bind:showButtons='(username != null) ? true : false' v-bind:data='data'></Tabs>
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
import {usernameMixin} from '../mixins.js'

 export default {
        components: {
            EntityCard,
            Tabs,
        },

        mixins: [usernameMixin],

        data() {
            return {
                location: null,
                data: [],
                lists: [],
                movies_lists_associations: [],
                username: '',
                country: ''
            }
        },

        created() {
            document.body.setAttribute('data-app', true);
            var id = this.$router.currentRoute.params.id;
            this.fetchUserInfo();
            this.fetchLocation(id);
        },

        methods: {
            fetchLocation(id) {
                fetch("/api/locations/"+id)
                .then(res => res.json())    
                   .then(res => { this.location = res.location, this.movies_lists_associations = res.movies_lists_associations, this.data = res.data, this.country = res.country });
            },

            fetchUserInfo(){
                fetch("/api/users/")
                .then(res => res.json())    
                .then(res => {
                    this.username = res.username,
                    this.lists = res.lists
                });
            },
        }

    }
</script>