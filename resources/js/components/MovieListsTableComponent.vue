<template>
    <v-app class="vue-div">
        <v-card rounded="0" color="yellow darken-3" fluid>
            <v-card-title class="text-body-1 text-uppercase blue-grey--text text--darken-4">Lists</v-card-title>
            <v-card-text class="text-body-2 blue-grey--text text--darken-4">Here you can find lists from othe users</v-card-text>
        </v-card>
        <div v-if="items.length != 0">
        <v-data-table
            rounded="0"
            dark
            v-model="selected"
            :headers="headers"
            :items="items"
            item-key="list_id"
            class="elevation-2 blue-grey darken-4">

            <template v-slot:[`item.title`]="{ item }">
                <td v-on:click="visitList(item.list_id)" hover=true class="clickables">{{item.title}}</td>
            </template>
            <template v-slot:[`item.image`]="{ item }">
                <v-avatar size="35" v-if="item.avatar_path == null" @click="visitUser(item.username)" class="clickables"><v-icon x-large color="#25a0d9">account_circle</v-icon></v-avatar>
                <v-avatar size="35" v-else @click="visitUser(item.username)" class="clickables"><img width="26" height="26" :src="item.avatar_path"/></v-avatar>
            </template>
            <template v-slot:[`item.username`]="{ item }">
                <td v-on:click="visitUser(item.username)" hover=true class="clickables">{{item.username}}</td>
            </template>
        </v-data-table>
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
export default {
    data(){
        return{
            headers: [
                {text: 'Title', align: 'start', value: 'title', width:"60%"},
                {text: 'Posted on', align: 'start', value: 'created_at', width: "200"},
                {text: '', sortable: false, align: 'start', value: "image", width: "40"},   
                {text: 'Posted by', align: 'start', value: 'username'},

            ],
            items: [],
            selected: null
        }
    },

    created(){
        this.fetchListItems();
    },

    methods: {
        fetchListItems(){
            fetch("/api/lists/")
                .then(res => res.json())    
                .then(res => {this.items = res.lists});
        },

        visitList(list_id){
            window.location ='/lists/info/'+list_id;
        },

        visitUser(username){
            window.location ='/users/'+username;
        }
    }
}
</script>

<style>
    .v-data-footer .v-icon{
        color:#F9A825 !important;
    }
    .clickables{
        cursor: pointer;
    }
    .clickables:hover{
        color: #F9A825;
    }
</style>