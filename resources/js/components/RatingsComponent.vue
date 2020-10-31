<template>
    <v-card class="ma-2 pb-2 elevation-0" min-width="500">
        <v-card-actions v-if="username != null && username != ''"><v-btn @click="openNewReviewDialog()" color="blue-grey darken-4" class="yellow--text text--darken-3">+ Add your review</v-btn></v-card-actions>
        <v-card-actions v-else><v-btn @click="redirectLogin()" color="blue-grey darken-4" class="yellow--text text--darken-3">Login to add a review</v-btn></v-card-actions>

        <v-container v-if="reviews != null && reviews.length != 0" class="reviewsContainer">
            <v-card color="ml-n1 mb-2 blue-grey darken-4 text-white"  v-for="review in reviews" :key="review.id">
                <v-row align="center">
                    <v-col cols="4">
                        <td>
                            <v-rating
                                v-model="review.rating"
                                color="yellow darken-3"
                                background-color="grey darken-1"
                                empty-icon="$ratingFull"
                                half-increments
                                readonly
                            >
                            </v-rating>
                        </td>
                    </v-col>
                    <v-col cols="2"><td>2020-08-19</td></v-col>
                    <v-col cols="4"><td id="usernameTd" @click="visitUser(review.username)" class="align-content-end yellow--text text--darken-3">{{truncateString(review.username, 10)}}</td></v-col>
                    <v-col cols="2" v-if="username != null && username == review.username"><v-icon id="deleteBtn" @click="deleteReview(review.id)" color="yellow darken-3">delete_forever</v-icon></v-col>
                </v-row>
                <v-card-title>{{review.title}}</v-card-title>
                <v-card-text class="white--text text-justify">{{review.review}}</v-card-text>
            </v-card>
        </v-container>
        <v-container v-else>
            No Reviews Yet
        </v-container>
        <NewReview :movie_id="movie_id" v-on:closeNewReviewDialog="closeNewReviewDialog($event)" :showDialog="showNewReviewDialog"></NewReview>   
        <ConfirmRemoval :page_url="delete_url" :id="reviewId" :message="confirmationMessage" v-on:closeDeleteReviewDialog="closeConfirmRemovalDialog($event)" :showDialog="showConfirmRemovalDialog"></ConfirmRemoval>
    </v-card>
</template>
<script>
import NewReview from './NewReviewComponent.vue'
import ConfirmRemoval from './ConfirmRemovalDialogComponent.vue'
import {usernameMixin} from '../mixins.js'

export default {
    components:{
        NewReview,
        ConfirmRemoval
    },
    
    mixins: [usernameMixin],

    props:{
        movie_id: null,
        username: null,
    },
    
    data(){
        return{
            showNewReviewDialog: false,
            showConfirmRemovalDialog: false,
            reviews: [],
            reviewId: null,
            delete_url: '/api/reviews/',
            confirmationMessage: 'Are you sure you want to delete your review ?'
        }
    },

    created(){
        this.fetchReviews();
    },

    methods:{
        fetchReviews(){
            fetch("/api/reviews/"+this.movie_id)
            .then(res => res.json())
            .then(res => {this.reviews = res.reviews});
        },
        
        openNewReviewDialog(){
            this.showNewReviewDialog = true;
            document.body.removeAttribute('data-app');
        },

        closeNewReviewDialog(refresh_bool){
            document.body.setAttribute('data-app', true);
            this.showNewReviewDialog = false;
            if(refresh_bool)this.fetchReviews();
        },

        redirectLogin(){
            window.location = '/login';
        },

        visitUser(username){
            window.location='/users/'+username;
        },

        deleteReview(id){
            document.body.removeAttribute('data-app');
            this.reviewId = id;
            this.showConfirmRemovalDialog = true;
        },

        closeConfirmRemovalDialog(refresh_bool){
            document.body.setAttribute('data-app', true);
            this.showConfirmRemovalDialog= false;
            if(refresh_bool)this.fetchReviews();
        },
    }
}
</script>

<style>
    .reviewsContainer{
        max-height: 500px !important;
        overflow-y: auto;
    }
    
    #usernameTd{
        cursor: pointer !important;
        text-transform: lowercase !important;
        font-variant: small-caps !important;

    }

    #deleteBtn{
        cursor: pointer !important;
    }

    #deleteBtn:hover{
        color: #E65100 !important;
    }

    #usernameTd:hover{
        color: #0097A7 !important;
        text-decoration-line: underline !important;
    }
</style>