<template>
    <v-card-actions v-if="movie_lists != null" id="toolbar" >
        <v-spacer></v-spacer>
        <v-btn @click.native='addToSeenList()' v-on:click.stop :small='sizeSmall' :x-small='sizeXSmall' icon ><v-icon :color='seenBtnColor'>remove_red_eye</v-icon></v-btn>
        <v-btn @click.native='addToWatchList()' v-on:click.stop :small='sizeSmall' :x-small='sizeXSmall' icon><v-icon :color='watchBtnColor'>watch_later</v-icon></v-btn>
        <v-menu top offset-x transition="scale-transition">
            <template v-slot:activator="{ on, attrs }">
                <v-btn v-on="on" v-bind="attrs" :small='sizeSmall' :x-small='sizeXSmall' icon><v-icon color="blue-grey lighten-2">list</v-icon></v-btn>
            </template>
            <v-list tile dense>
                <v-list-item>
                    <v-menu :key="addToListMenuKey" top offset-x transition="scale-transition">
                        <template v-slot:activator="{ on, attrs }">
                            <v-list-item-title class="text-overline" v-on="on" v-bind="attrs">Add to list</v-list-item-title>
                            <!-- <v-btn v-on="on" v-bind="attrs" :small='sizeSmall' :x-small='sizeXSmall'> Add to list</v-btn> -->
                        </template>
                        <v-list tile class="overflow-y-auto" max-width="200" dense v-if="movie_lists.nonContainingLists.length != 0" max-height="300">
                            <v-list-item
                                v-for="list in movie_lists.nonContainingLists"
                                :key="list.id"
                                @click="addToCustomList(itemId, list.id)">
                                <v-list-item-title class="text-overline">{{list.title}}</v-list-item-title>
                            </v-list-item>
                        </v-list>
                         <v-list tile dense v-else>
                            <v-list-item>
                                <v-list-item-title class="text-overline">N/A</v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </v-list-item>
                <v-list-item>
                    <v-menu :key="removeFromListMenuKey" top offset-x transition="scale-transition">
                        <template v-slot:activator="{ on, attrs }">
                            <v-list-item-title class="text-overline" v-on="on" v-bind="attrs">Remove from list</v-list-item-title>
                            <!-- <v-btn v-on="on" v-bind="attrs" :small='sizeSmall' :x-small='sizeXSmall'>Remove from list</v-btn> -->
                        </template>
                        <v-list tile class="overflow-y-auto" max-width="200" dense v-if="movie_lists.lists.length != 0" max-height="300">
                            <v-list-item
                                v-for="list in movie_lists.lists"
                                :key="list.id"
                                @click="removeFromCustomList(itemId, list.id)">
                                <v-list-item-title class="text-overline">{{list.title}}</v-list-item-title>
                            </v-list-item>
                        </v-list>
                        <v-list tile dense v-else>
                            <v-list-item>
                                <v-list-item-title class="text-overline">N/A</v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </v-list-item>
        </v-list>
      </v-menu>
        <v-spacer></v-spacer>
    </v-card-actions>
</template>

<script>
import {usernameMixin} from '../mixins.js'

export default {
    mixins: [usernameMixin],

    props:{
        itemId: {
            type: Number
        },
        movie_lists:{
            inSeenList: false,
            inWatchList: false,
            seenListId: null,
            watchListId: null,
            nonContainingLists: [],
            lists: []
        }
    },

    data(){
        return {
            btnActiveColor: 'green darken-3',
            btnInactiveColor: 'blue-grey lighten-2',
            seenBtnColor: '',
            watchBtnColor: '',
            sizeSmall:true,
            sizeXSmall: false,
            username: null,
            addToListMenuKey: 0,
            removeFromListMenuKey: 0,
        }
    },

    created(){
        var path =  this.$router.currentRoute.path;
        if(path == '/user' || path.match('/users\.*/')){
            this.sizeSmall = false;
            this.sizeXSmall = true;
        }
        this.setBtnColors();
    },

    methods:{
        setBtnColors(){
            this.movie_lists.inWatchList == true ? this.watchBtnColor = this.btnActiveColor : this.watchBtnColor = this.btnInactiveColor;
            this.movie_lists.inSeenList == true ? this.seenBtnColor = this.btnActiveColor : this.seenBtnColor = this.btnInactiveColor;
        },
        
        //if movie already added to seen list, controller will remove it instead
        addToSeenList(){
            var success = this.addToList(this.itemId, this.movie_lists.seenListId);
            this.switchSeenBtnColor();
        },

        //if movie already added to seen list, controller will remove it instead
        addToWatchList(){
           this.addToList(this.itemId, this.movie_lists.watchListId);
           this.switchWatchBtnColor();
        },

        switchWatchBtnColor(){
            this.watchBtnColor === this.btnActiveColor ? this.watchBtnColor = this.btnInactiveColor : this.watchBtnColor = this.btnActiveColor;
        },

        switchSeenBtnColor(){
            this.seenBtnColor === this.btnActiveColor ? this.seenBtnColor = this.btnInactiveColor : this.seenBtnColor = this.btnActiveColor;
        },

        addToCustomList(item_id, list_id){
            this.addToList(item_id, list_id);
        },

        removeFromCustomList(movie_id, list_id){
            let data = {
                movie_id: movie_id,
                list_id: list_id
            }

            fetch('/api/movies/removefromlist',{
                method: 'post',
                body: JSON.stringify(data) 
            })
            .then(res => res.json())
            .then(res => res.status)
            .then(status => { 
                if(status.status_code == 400){ window.location = status.redirect_route}
                else{
                    if(status.status_code == 200){
                        let i = this.movie_lists.lists.map(list => list.id).indexOf(list_id); // find index of your object
                        this.movie_lists.nonContainingLists.push(this.movie_lists.lists[i])
                        this.movie_lists.lists.splice(i, 1);
                        this.removeFromListMenuKey += 1;
                    }
                    this.$emit('listUpdate', status.message); //int val 0 at index 0 used to indicate list type. 0 = custom list, 1 = seen or watch
                }})
        },

        addToList(movie_id, list_id){
            let data = {
                movie_id: movie_id,
                list_id: list_id
            }

            fetch('/api/movies',{
                method: 'post',
                body: JSON.stringify(data) 
            })
            .then(res => res.json())
            .then(res => res.status)
            .then(status => { 
                if(status.status_code == 400){ window.location = status.redirect_route}
                else{
                    if(this.movie_lists.seenListId != list_id && this.movie_lists.watchListId != list_id){
                        if(status.status_code == 200){
                            let i = this.movie_lists.nonContainingLists.map(list => list.id).indexOf(list_id); // find index of your object
                            this.movie_lists.lists.push(this.movie_lists.nonContainingLists[i])
                            this.movie_lists.nonContainingLists.splice(i, 1);
                            this.addToListMenuKey += 1;
                        }
                        this.$emit('listUpdate', status.message);
                    }
                    else if(this.movie_lists.seenListId == list_id){
                        if(status.status_code != 200){
                            this.switchSeenBtnColor();
                            this.$emit('listUpdate', 'An error occured');
                        }
                    }
                    else{
                         if(status.status_code != 200){
                            this.switchWatchBtnColor();
                            this.$emit('listUpdate', 'An error occured');
                        }
                    }
                }})
            },
    }
}
</script>

<style>
    #toolbar{
        height: 30px;
        border-left: thin solid rgb(218, 218, 218) !important;
        border-right: thin solid rgb(218, 218, 218) !important;
        border-bottom: thin solid rgb(218, 218, 218) !important;
    }
</style>