<template>
    <v-dialog v-model="dialog" max-width="600px">
        <v-card color="blue-grey darken-3">
            <v-card-title>
            <span class="text-body-1 text-uppercase yellow--text text--darken-3">{{title}}</span>
            </v-card-title>
            <v-card-text class="yellow--text text--darken-3 mt-5">{{message}}</v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="yellow darken-3" text @click.native="dialog = false">Ok</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    props:{
        showDialog: false,
        title:{
            type:String
        },
        message:{
            type: String
        }
    },

    computed: {
            dialog: {
                get: function(){
                    return this.showDialog;
                },

                set: function(value){
                    if(!value){
                        this.$emit('closeConfirmationDialog', value);
                    }
                }
            }
        },
}
</script>