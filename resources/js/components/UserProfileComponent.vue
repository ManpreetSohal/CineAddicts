<template>
<v-app>
<v-container v-if="username != null" height="700" class="vue-div">
        <v-tabs height="640" :key="tabsKey" vertical dark color="yellow darken-3" background-color="blue-grey darken-4" prev-icon="navigate_before"
                        next-icon="navigate_next">
                <v-tab class="justify-start"><v-icon>account_circle</v-icon>{{truncateString(username, 10)}}</v-tab>
                <v-tab class="justify-start"><v-icon>list</v-icon>Lists</v-tab>
                <v-tab class="justify-start"><v-icon>comment</v-icon>Reviewed</v-tab>
                <v-tab @click="fetchFollowLists()" class="justify-start"><v-icon>people_alt</v-icon>Followers</v-tab>
                <v-tab class="justify-start"><v-icon>people_alt</v-icon>Following</v-tab>
                <v-tab v-if="profileUserLoggedIn" class="justify-start"><v-icon>delete_forever</v-icon>Account</v-tab>

            <v-tab-item class="vue-div">
                    <v-container class="mt-n3 mb-n3" fluid>
                    <v-card color="blue-grey darken-4" min-height="640">
                        <v-hover v-slot:default="{ hover }">
                            <v-card :key="imgCardKey" width="200" height="200" class="rounded-card ml-2">
                                <v-img v-if="user_avatar_src == null || user_avatar_src == ''" width="200" height="200">
                                    <v-card-actions v-if="profileUserLoggedIn" v-show="hover ? true : false" class="justify-center">
                                        <v-btn-toggle  borderless background-color="transparent" class="ml-3" v-model="toggle_none">
                                            <v-file-input 
                                                class="mt-n2"
                                                :rules="image_rules"
                                                accept="image/png, image/jpeg, image/bmp"
                                                prepend-icon="camera_alt" 
                                                hide-input
                                                v-model="image"
                                                @change="uploadImage"></v-file-input>
                                        </v-btn-toggle>
                                    </v-card-actions>
                                    <v-avatar  size='200' color='blue-grey darken-1'>
                                        <v-icon size='200'>account_circle</v-icon>
                                    </v-avatar> 
                                </v-img>
                                <v-img v-else width="200" height="200" :src="user_avatar_src">
                                    <v-card-actions v-if="profileUserLoggedIn" v-show="hover ? true : false" class="justify-center">
                                        <v-btn-toggle  borderless :background-color="hover ? 'yellow darken-3' : 'transparent'" class="ml-3" v-model="toggle_none">
                                            <v-file-input 
                                                class="mt-n2"
                                                :rules="image_rules"
                                                accept="image/png, image/jpeg, image/bmp"
                                                prepend-icon="camera_alt" 
                                                hide-input
                                                v-model="image"
                                                @change="uploadImage"></v-file-input>

                                                <v-btn class="mt-2" x-small outlined @click.native="deleteAvatar()"><v-icon color="grey darken-3">delete</v-icon></v-btn>
                                        </v-btn-toggle>
                                </v-card-actions>
                                </v-img>
                            </v-card>
                        </v-hover>
                        <v-card-actions v-if="profileUserLoggedIn">
                            <v-btn outlined class="mt-10 yellow--text text--darken-3" @click.native="openNewListDialog()">+ New List</v-btn>
                            <v-btn outlined class="mt-10 yellow--text text--darken-3" @click.native="openDeleteListDialog()"> Delete a List</v-btn>
                        </v-card-actions>
                        <v-card-actions v-else>
                            <v-btn width="200 " :color="followBtnColor" class="mt-10 white--text" @click.native="updateFollowingStatus()">{{updateFollowingStatusBtnText}}</v-btn>
                        </v-card-actions>
                       </v-card>
                    </v-container>
            </v-tab-item>
            <v-tab-item class="vue-div">
                 <v-tabs :key="listsKey" v-model="active_tab" id="tabs" dark color="yellow darken-3" background-color="blue-grey darken-3" prev-icon="navigate_before"
                        next-icon="navigate_next"
                        show-arrows="always">
                      
                        <v-tab v-text="list['title']" v-for="list in lists" v-bind:key="list['id']" @click="updateTabs(list.id)"></v-tab>
                                <v-tab-item v-for="list in lists" v-bind:key="list.id">
                                    <v-card color="blue-grey darken-3">
                                        <Movies v-bind:list_id="list['id']" :empty_message="'List empty'"></Movies>
                                    </v-card> 
                                </v-tab-item>
                </v-tabs>
            </v-tab-item>
            <v-tab-item class="vue-div">
                <v-card id="tabs" min-height="640">
                    <Movies v-bind:reviewsFor="username" :empty_message="no_reviews_message +' '+ username"></Movies>
                </v-card>
            </v-tab-item>

            <v-tab-item class="vue-div">
                <v-card height="640" max-height="640" class="overflow-auto">
                   <Users :users="followers.values" :empty_message="'Zero Followers'" :visit_case="followers.visit_case"></Users>
                </v-card>
            </v-tab-item>
            <v-tab-item class="vue-div">
                <v-card height="640" max-height="640" class="overflow-auto">
                    <Users :users="following.values" :empty_message="'Following None'" :visit_case="following.visit_case"></Users>
                </v-card>
            </v-tab-item>
            <v-tab-item class="vue-div">
                <v-container class="mt-n3 mb-n3" fluid>
                    <v-card dark color="blue-grey darken-4" height="640">
                        <v-card-title class="yellow--text text--darken-3">Account Deletion</v-card-title>
                        <v-card-text>Deleting your account will remove all of your data including your reviews and lists.</v-card-text>
                        <v-card-actions>
                            <v-btn @click="deleteAccount()" class="blue-grey--text text--darken-4" color="yellow darken-3"><v-icon>delete_forever</v-icon>Delete Permanently</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-container>
            </v-tab-item>
               
        </v-tabs>
        <NewListForm v-on:closeDialog="closeNewListFormDialog($event)" :showDialog="showNewListDialog"></NewListForm>
        <DeleteListForm v-on:closeDialog="closeDeleteListFormDialog($event)" :movie_lists="mappedLists" :showDialog="showDeleteListDialog"></DeleteListForm>
        <ConfirmRemoval :page_url="delete_url" :id="username" :message="confirmationMessage" v-on:closeDeleteReviewDialog="closeConfirmRemovalDialog($event)" :showDialog="showConfirmRemovalDialog"></ConfirmRemoval>

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
import Tabs from './TabComponent.vue'
import Movies from './MoviesComponent.vue'
import NewListForm from './NewListFormComponent.vue'
import DeleteListForm from './DeleteListFormComponent.vue'
import ConfirmRemoval from './ConfirmRemovalDialogComponent.vue'
import Users from './UsersComponent.vue'
import {usernameMixin} from '../mixins.js'

export default {
    components: {
        Tabs,
        Movies,
        NewListForm,
        DeleteListForm,
        ConfirmRemoval,
        Users
    },

    mixins: [usernameMixin],

    data() {
        return {
            showNewListDialog: false,
            showDeleteListDialog: false,
            toggle_none: null,
            username: null,
            lists: [],
            mappedLists: [],
            image: null,
            user_avatar_src: '',
            image_rules: [
                value => !value || value.size < 1000000 || 'Image size should be less than 1 MB!',
            ],
            profileUserLoggedIn: false,
            title: null,
            tabsKey: 0,
            listsKey: 0,
            active_tab: 0,
            imgCardKey: 0,
            showNewReviewDialog: false,
            showConfirmRemovalDialog: false,
            delete_url: '/api/users/',
            confirmationMessage: 'Are you sure you want to permanently delete your account?',
            followText: "Follow",
            unfollowText: "Unfollow",
            updateFollowingStatusBtnText: '',
            following: [],
            followers: [],
            follows: false,
            followBtnColor: '',
            btnActive: 'light-green',
            btnInactive: 'red darken-4',
            no_reviews_message: 'No reviews from'
        }
    },

    created() {
        var currentRoute = this.$router.currentRoute;
        var path = currentRoute.path;

        if(path == '/user'){
            this.profileUserLoggedIn = true;
            this.fetchUserInfo();
            this.fetchUserAvatar();
        }
        else if(!path.includes('user_avatars')){
            var id = currentRoute.params.id;
            this.fetchProfile(id);
        }
    },

    methods: {
        fetchUserInfo(){
             fetch("/api/users/")
                .then(res => res.json())    
                .then(res => {
                    if(res.username == null){
                        window.location = res.redirect_route;
                    }else{
                        this.username = res.username,
                        this.lists = res.lists,
                        this.createMappedListsObj(res.lists)
                        this.fetchFollowLists();
                    }
                });
        },

        fetchProfile(username){
            fetch('/api/users/'+username)
            .then(res => res.json())
            .then(res => {
                if(res.status_code == 404){
                    window.location = res.redirect_route;
                }
                else if(res.status_code == 200 || res.status_code == 201){
                    if(res.status_code == 201){
                      this.profileUserLoggedIn = true;
                    }
                    this.username = res.username;
                    this.lists = res.lists;
                    this.user_avatar_src = res.avatar_path;
                    this.createMappedListsObj(res.lists);
                    this.fetchFollowLists();
                }
            });
        },
                        
        createMappedListsObj(res_items){
            var items = JSON.parse(JSON.stringify(res_items));
            items.splice(0, 2);
            this.mappedLists = items.map(list => ({text: list.title, value: list.id}));
        },

        fetchUserAvatar(){
            fetch("/api/avatars/get")
                .then(res => res.json())
                .then(res => {this.user_avatar_src = res.avatar_path});
        },

        deleteAvatar(){
            fetch("/api/avatars/delete")
                .then(res => res.json())
                .then(res => res.status)
                .then(status => {
                    if(status.status_code == 400){ window.location = status.redirect_route}
                    else if(status.status_code == 200){
                        this.user_avatar_src = status.avatar_path;
                    }
                });
        },

        uploadImage(event){
            let data = new FormData();
            data.append('image', this.image);

            fetch('/api/avatars/store',{
                method: 'post',
                body: data 
            })
            .then(res => res.json())
            .then(res => res.status)
            .then(status => {
                 if(status.status_code == 400){ window.location = status.redirect_route}
                 else if(status.status_code == 200){
                     this.fetchUserAvatar();
                     this.imgCardKey +=1;
                 }
            });
        },

        openNewListDialog(){
            this.showNewListDialog = true;
            document.body.removeAttribute('data-app');
        },

        openDeleteListDialog(){
            this.showDeleteListDialog = true;
            document.body.removeAttribute('data-app');
        },

        closeNewListFormDialog(refresh_bool){
            document.body.setAttribute('data-app', true);
            this.showNewListDialog = false;
            if(refresh_bool)this.fetchUserInfo();
            this.tabsKey += 1;
        },

        closeDeleteListFormDialog(refresh_bool){
            document.body.setAttribute('data-app', true);
            this.showDeleteListDialog = false;
            if(refresh_bool)this.fetchUserInfo();
            this.tabsKey += 1;
        },
        
        updateTabs(listId){
            this.listsKey += 1;
            this.active_tab = listId;
        },
        deleteAccount(){
            document.body.removeAttribute('data-app');
            this.showConfirmRemovalDialog = true;
        },

        closeConfirmRemovalDialog(refresh_bool){
            document.body.setAttribute('data-app', true);
            this.showConfirmRemovalDialog= false;
            if(refresh_bool)window.location="/movies";
        },

        fetchFollowLists(username){
            let data = {
                follows: this.username,
            }

            fetch('/api/users/fetchFollowLists',{
                method: 'post',
                body: JSON.stringify(data) 
            })
            .then(res => res.json())
            .then(res => {
                if(res.status.status_code == 200){
                    this.following = res.following;
                    this.followers = res.followers;
                    if(res.follows){
                        this.updateFollowingStatusBtnText = this.unfollowText;
                        this.followBtnColor = this.btnInactive;
                    }
                    else{
                        this.updateFollowingStatusBtnText = this.followText;
                        this.followBtnColor = this.btnActive;
                    }
                }else{
                    alert(res.error);
                }                
            });
        },

        switchFollowBtn(){
            this.updateFollowingStatusBtnText === this.followText ? this.updateFollowingStatusBtnText = this.unfollowText : this.updateFollowingStatusBtnText = this.followText;
            this.followBtnColor === this.btnActive ? this.followBtnColor = this.btnInactive : this.followBtnColor = this.btnActive;
        },

        updateFollowingStatus(){
            this.switchFollowBtn();
            let data = {
                follows: this.username,
            }

            fetch('/api/users/updatefollowstatus',{
                method: 'post',
                body: JSON.stringify(data) 
            })
            .then(res => res.json())
            .then(res => res.status)
            .then(status => {
                if(status.status_code == 200){
                    // this.fetchFollowLists();
                    //this.tabsKey += 1;
                }
                else if(status.status_code == 401){
                    this.switchFollowBtn();
                    alert('An error occured');  
                }else
                {
                    window.location = status.redirect_route;
                }      
            }); 
        }
    }
}
</script>
<style>
    #tabs{
        max-width: 975px !important;
    }
    .rounded-card{
     border-radius:50% !important;
    }
</style>