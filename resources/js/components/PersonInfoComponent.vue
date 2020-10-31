<template>
    <v-app>
        <v-card v-if="contributor != null">
            <v-row>
                <v-col cols="4">
                    <EntityCard v-bind:title='contributor.stage_name' v-bind:poster_path='contributor.poster_path'></EntityCard>
                    <v-simple-table dark class="pa-5 ml-5 mr-5">
                        <tr>
                            <td v-if="contributor.facebook_path != null" @click="visitFacebook(contributor.facebook_path)" id="tdimg" class="ml-n1 mr-1"><v-avatar size="30" id="logo_img"><v-icon color="blue darken-2">mdi-facebook</v-icon></v-avatar></td>
                            <td v-if="contributor.instagram_path != null" @click="visitInstagram(contributor.instagram_path)" id="tdimg" class="ml-n1 mr-1"><v-avatar size="30" id="logo_img"><v-icon color="pink darken-1">mdi-instagram</v-icon></v-avatar></td>
                            <td v-if="contributor.twitter_path != null" @click="visitTwitter(contributor.twitter_path)" id="tdimg" class="ml-n1 mr-1"><v-avatar size="30" id="logo_img"><v-icon color="blue">mdi-twitter</v-icon></v-avatar></td>

                            <!-- <td id="tdimg"><img id="logo_img" v-if="contributor.facebook_path != null" @click="visitFacebook(contributor.facebook_path)" class="mr-1" width="22" height="22" src="/images/fb_logo.png" /></td>
                            <td id="tdimg"><img id="logo_img" v-if="contributor.instagram_path != null" @click="visitInstagram(contributor.instagram_path)" width="25" height="25" src="/images/ig_logo.png" /></td>
                            <td id="tdimg"><img id="logo_img" v-if="contributor.twitter_path != null" @click="visitTwitter(contributor.twitter_path)" width="26" height="26" src="/images/twitter_logo.png" /></td> -->
                        </tr>
                        <tr v-if="ageIsValid">
                            <!-- <td>Age: </td> -->
                            <td><tr><td class="info_text">AGE: </td><td>{{ calculateAge(contributor.date_of_birth)}}</td></tr></td>
                        </tr>

                        <tr v-if="ageIsValid">
                            <!-- <td>DOB: </td> -->
                            <td><tr><td class="info_text">DOB: </td><td> {{ convertDate(contributor.date_of_birth)}}</td></tr></td>
                        </tr>

                        <tr>
                            <!-- <td>POB: </td> -->
                            <td><tr><td class="info_text">POB: </td><td> {{ location }}, {{ country }}</td></tr></td>
                        </tr>
                    </v-simple-table>
                </v-col>
                <v-col cols="8">
                    <v-row>
                        <v-col cols="12">
                            <BiographyCard v-bind:title='contributor.stage_name'
                                v-bind:content='contributor.biography'
                            >
                            </BiographyCard>
                        </v-col>
                    </v-row>
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
import BiographyCard from './BiographyComponent.vue'
import {usernameMixin} from '../mixins.js'

 export default {
        components: {
            EntityCard,
            Tabs,
            BiographyCard
        },

        mixins: [usernameMixin],

        data() {
            return {
                contributor: null,
                data: [],
                location: null,
                country: null,
                lists: [],
                username: '',
                movies_lists_associations: [],
                ageIsValid: true
            }
        },

        created() {
            document.body.setAttribute('data-app', true);
            var id = this.$router.currentRoute.params.id;
            this.fetchUserInfo();
            this.fetchPerson(id);
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

            fetchPerson(id) {
                fetch("/api/contributors/"+id)
                .then(res => res.json())    
                   .then(res => { this.contributor = res.contributor, this.movies_lists_associations = res.movies_lists_associations, this.data = res.data, this.location = res.place_of_birth, this.country = res.country });
            },

            // visitMovie(id){
            //     window.location = "/movies/"+id;
            // },

            convertDate(date){
                var values = date.split(/\D/);
                return new Date(values[0], values[1]-1, values[2]).toLocaleString('en-CA', {month:'long', day:'numeric', year:'numeric'});
            },

            calculateAge (birthDate) {
                birthDate = new Date(birthDate);
                var todaysDate = new Date();

                var years = (todaysDate.getFullYear() - birthDate.getFullYear());

                if (todaysDate.getMonth() < birthDate.getMonth() || 
                    todaysDate.getMonth() == birthDate.getMonth() && todaysDate.getDate() < birthDate.getDate()) {
                    years--;
                }
                if(years <= 0){
                    this.ageIsValid = false;
                }

                return years;
            },

            visitFacebook(path){
                window.open('https://www.facebook.com/'+path, '_blank');
            },

            visitInstagram(path){
                window.open('https://www.instagram.com/'+path, '_blank');
            },

            visitTwitter(path){
                window.open('https://www.twitter.com/'+path, '_blank');
            }
        }

    }
</script>

<style scoped>
    #logo_img{
        cursor: pointer !important;
    }
    #tdimg{
        display:inline-block !important;
        float: left !important;
    }
    .info_text{
        vertical-align: text-top !important;
        padding-right: 5px !important;
    }

    /* width */
    ::-webkit-scrollbar {
        width: 8px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        background: #f1f1f1; 
    }
    
    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: #888; 
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: #555; 
    }
</style>