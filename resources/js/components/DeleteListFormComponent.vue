<template>
    <v-dialog v-model="dialog" max-width="600px">
        <v-card color="blue-grey darken-4">
            <v-card-title>
            <span class="text-body-1 text-uppercase yellow--text text--darken-3">Select list to delete</span>
            </v-card-title>
            <v-select class="ml-5 mr-5 mt-5" :items="movie_lists" label="Select list to delete" v-model="selectedList" solo append-icon='arrow_drop_down'></v-select>
            <v-card-actions>
                 <v-spacer></v-spacer>
                <v-btn color="yellow darken-3" text @click.native="dialog = false">Cancel</v-btn>
                <v-btn color="yellow darken-3" text @click.native="deleteList()">Delete</v-btn>
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

    computed: {
        dialog: {
            get: function(){
                return this.showDialog;
            },

            set: function(value){
                if(!value){
                    this.$emit('closeDialog', this.closeParentDialog);
                }
            }
        },
    },

    props:{
        movie_lists:{
            type: Array
        },
        confirmationDialog: false,
        closeParentDialog: false,
        confirmationDialogTitle: '',
        confirmationDialogMessage: '',
        showDialog:{
            type: Boolean,
            default: false
        },
    },

    data(){
        return{
            selectedList: null,
        }
    },

    methods:{
        deleteList(){
            fetch('/api/lists/'+this.selectedList,{
                method: 'delete',
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
                    }
                }
            });
        },

        closeDialog(showDialog){
            if(this.closeParentDialog) this.dialog = false;
            this.confirmationDialog = showDialog;
        }
    }
}
</script>