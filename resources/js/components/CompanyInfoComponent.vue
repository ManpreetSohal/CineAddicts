<template>
    <v-app>
        <v-container v-if="company != null" class="ma-0 pa-0">
            <v-card >
            <v-row>
                <v-col cols="4">
                    <EntityCard v-bind:title='company.company' v-bind:poster_path='company.logo_path'></EntityCard>
                    <v-simple-table dark class="pa-5 ml-5 mr-5">
                        <tr>
                            <td>{{ company.company }}</td>
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
        </v-container>
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
                company: null,
                data: [],
                lists: [],
                movies_lists_associations: [],
                username: ''
            }
        },

        created() {
            document.body.setAttribute('data-app', true);
            var id = this.$router.currentRoute.params.id;
            this.fetchUserInfo();
            this.fetchCompany(id);
        },

        methods: {
            fetchCompany(id) {
                fetch("/api/companies/"+id)
                .then(res => res.json())    
                   .then(res => { this.company = res.company, this.movies_lists_associations = res.movies_lists_associations, this.data = res.data });
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