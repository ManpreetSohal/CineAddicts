<template>
    <v-container>
        <v-layout v-if="users.length != 0" row wrap>
            <v-flex  xs4 sm3 md2 lg2 v-for="entity in users" v-bind:key="entity.id">
                <v-div>    
                    <v-avatar v-if="entity.poster_path != null" id="user_avatar" size="100" class="ml-5 mr-5 mt-2 justify-center" @click="visit(entity.title, visit_case)"><img :src="'/'+entity.poster_path"/></v-avatar>
                    <v-avatar v-else id="user_avatar" size="100" class="ml-5 mr-5 mt-2 justify-center" color='blue-grey darken-4' @click="visit(entity.title, visit_case)"><v-icon size='100'>account_circle</v-icon></v-avatar>
                    <p id="usernameTextField" class="text-center text-caption">{{truncateString(entity.title, 20)}}</p>
                </v-div>
            </v-flex>
        </v-layout> 
        <v-layout v-else> 
            <v-card class="yellow--text text--darken-3" raised style="margin-left:auto; margin-right: auto; margin-top: 20%;">
                <v-card-title>{{empty_message}}</v-card-title>
            </v-card>
        </v-layout>
    </v-container>
</template>
<script>
import {usernameMixin} from '../mixins.js'

export default {
    props: {
        users: [],
        visit_case: null,
        empty_message: ''
    },

    mixins: [usernameMixin],
}
</script>
<style scoped>
    #user_avatar{
        display: block;
        margin-left: auto !important;
        margin-right: auto !important;
        cursor: pointer;
    }

    #usernameTextField{
        text-transform: lowercase;
    }

    /* .usersContainer{
        max-height: 500px !important;
        overflow: auto;
    } */
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