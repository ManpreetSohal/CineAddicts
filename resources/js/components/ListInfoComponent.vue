<template>
    <v-app>
        <v-card rounded="0" color="yellow darken-3" fluid>
            <v-card-title class="text-body-1 text-uppercase blue-grey--text text--darken-4">{{title}}</v-card-title>
            <v-card-actions>
                <v-btn @click="visitUser()" outlined class="blue-grey--text text--darken-4">{{username}}</v-btn>
            </v-card-actions>
        </v-card>
        <Movies :list_id="list_id"></Movies>
    </v-app>
</template>

<script>
import Movies from './MoviesComponent.vue'
export default {
    components: {
        Movies
    },

    data(){
        return{
            title: '',
            username: '',
            list_id: null
        }
    },

    created(){
        this.list_id =  this.$router.currentRoute.params.id;
        this.fetchListInfo();
    },

    methods:{
        fetchListInfo(){
            fetch('/api/lists/info/'+this.list_id)
            .then(res => res.json())
            .then(res => {this.title = res.list.title, this.username = res.list.username});
        },
        
        visitUser(){
            window.location ='/users/'+this.username;
        }
    }
}
</script>        