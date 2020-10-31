<template>
    <v-dialog v-model="dialog" max-width="600px">
        <v-card color="blue-grey darken-4">
            <!-- <v-card-title>
            <span class="headline lime--text">New List</span>
            </v-card-title> -->
            <v-rating
                v-model="rating"
                color="yellow darken-3"
                background-color="grey darken-1"
                empty-icon="$ratingFull"
                half-increments
            >
            </v-rating>
            <v-card-text>
                <v-text-field counter=50 dark v-on:input="clearError()" :error="titleIsEmpty" :error-messages="titleErrorMessage" clearable clear-icon="clear" v-model="title" label="Title*" required></v-text-field>
                <v-textarea max-height='500' counter=5000 dark v-on:input="clearError()" :error="reviewIsEmpty" :error-messages="reviewErrorMessage" clearable clear-icon="clear" v-model="review" label="Review*" required></v-textarea>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="yellow darken-3" text @click.native="dialog = false">Cancel</v-btn>
                <v-btn color="yellow darken-3" text @click.native="saveReview()">Save</v-btn>
            </v-card-actions>
        </v-card>
        <ConfirmationDialog v-on:closeConfirmationDialog="closeDialog($event)" :title="confirmationDialogTitle" :message="confirmationDialogMessage" :showDialog="confirmationDialog"></ConfirmationDialog>
    </v-dialog>
</template>
<script>
    import ConfirmationDialog from './ConfirmationDialogComponent.vue'
    export default {
        components: {
            ConfirmationDialog
        },

        props: {
            confirmationDialog: false,
            closeParentDialog: false,
            confirmationDialogTitle: '',
            confirmationDialogMessage: '',
            showDialog:{
                type: Boolean,
                default: false
            },
            movie_id: null
        },

        data (){
            return {
                titleIsEmpty: false,
                titleErrorMessage: '',
                reviewIsEmpty: false,
                reviewErrorMessage: '',
                title: '',
                review: '',
                rating: 0.5,
            }
        },

        computed: {
            dialog: {
                get: function(){
                    // this.title = '';
                    // this.review = '';
                    this.clearError();
                    return this.showDialog;
                },

                set: function(value){
                    if(!value){
                        this.$emit('closeNewReviewDialog', this.closeParentDialog);
                    }
                }
            }
        },
        
        methods: {
            saveReview(movie_id){
                if(this.title == null || this.title.trim() === '' || this.title.length > 50 ){
                    this.titleIsEmpty = true;
                    this.titleErrorMessage = 'Title is required and length should not exceed 50';
                }
                else if(this.review == null || this.review.trim() === '' || this.review.length > 5000 ){
                    this.reviewIsEmpty = true;
                    this.reviewErrorMessage = 'Review  is required. Maximum characters allowed - 5000';
                }
                else{
                    let data = {
                        title: this.title,
                        review: this.review,
                        rating: this.rating,
                        movie_id: this.movie_id,
                    }
                    fetch('/api/reviews',{
                        method: 'post',
                        body: JSON.stringify(data) 
                    })
                    .then(res => res.json())
                    .then(res => res.status)
                    .then(status => { 
                        if(status.status_code == 400){ window.location = status.redirect_route}
                        else {
                            this.confirmationDialog = true; 
                            this.confirmationDialogTitle=status.title;
                            this.confirmationDialogMessage=status.message;

                            if(status.status_code == 200){
                                this.closeParentDialog = true;
                                this.review = '';
                                this.title = '';
                                this.rating = 0.5;  
                            }
                        }
                    });
                } 
            },

            clearError(){
                this.reviewIsEmpty = false;
                this.reviewErrorMessage = '';
                this.titleIsEmpty = false;
                this.titleErrorMessage = '';
            },

            closeDialog(showDialog){
                if(this.closeParentDialog) this.dialog = false;
                this.confirmationDialog = showDialog;
            }
        }
    }
</script>