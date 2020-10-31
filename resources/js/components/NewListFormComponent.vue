<template>
    <v-dialog v-model="dialog" max-width="600px">
        <v-card color="blue-grey darken-4">
            <v-card-title>
            <span class="text-body-1 text-uppercase yellow--text text--darken-3">New List</span>
            </v-card-title>
            <v-card-text class="mt-5">
                <v-text-field counter=50 dark v-on:input="clearError()" :error="isEmpty" :error-messages="errorMessage" clearable clear-icon="clear" v-model="list_title" label="List Name*" required></v-text-field>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="yellow darken-3" text @click.native="dialog = false">Cancel</v-btn>
                <v-btn color="yellow darken-3" text @click.native="saveList()">Save</v-btn>
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
        },

        data (){
            return {
                isEmpty: false,
                errorMessage: '',
                list_title: '',
            }
        },
        
        computed: {
            dialog: {
                get: function(){
                    this.list_title = '';
                    this.clearError();
                    return this.showDialog;
                },

                set: function(value){
                    if(!value){
                        this.$emit('closeDialog', this.closeParentDialog);
                    }
                }
            }
        },
        
        methods: {
            saveList(){
                if(this.list_title == null || this.list_title.trim() === '' || this.list_title.length > 50 ){
                    this.isEmpty = true;
                    this.errorMessage = 'List name is required and length should not exceed 50';
                }
                else{
                    let data = {
                        list_name: this.list_title
                    }
                    fetch('/api/lists',{
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
                            }
                        }
                    });
                } 
            },

            clearError(){
                this.isEmpty = false;
                this.errorMessage = '';
            },

            closeDialog(showDialog){
                if(this.closeParentDialog) this.dialog = false;
                this.confirmationDialog = showDialog;
            }
        }
    }
</script>