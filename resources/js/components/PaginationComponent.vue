<template>
    <v-card rounded="0" class='pa-2' color="blue-grey darken-4">
        <v-row justify="center" align="center" dense class='d-flex' >
            <v-btn class='mr-2 blue-grey--text text--darken-4' :class=" { 'disable-events': pagination.last_page == 1 ? true : false }" :color="pagination.last_page == 1 ? 'blue-grey lighten-2' : 'yellow darken-3'" @click.native=" filter(wrapPrevUrl(pagination.prev_page_url))" x-small><v-icon>navigate_before</v-icon></v-btn>
            
            <v-btn class='mr-2' :class=" { 'disable-events': i == pagination.current_page_spot? true : false }" v-for='i in pagination.number_of_pages' v-bind:key='i' text x-small :color="i == pagination.current_page_spot? 'blue-grey lighten-2' : 'yellow darken-3'" @click.native=" filter(pagination.path + '?page='+getPage(i))">{{ getPage(i) }}</v-btn>

            <v-btn class="blue-grey--text text--darken-4" :class=" { 'disable-events': pagination.last_page == 1 ? true : false }" :color="pagination.last_page == 1 ? 'blue-grey lighten-2' : 'yellow darken-3'" @click.native=" filter(wrapNextUrl(pagination.next_page_url))" x-small><v-icon>navigate_next</v-icon></v-btn>
        </v-row>
    </v-card>
</template>

<script>
export default {
    props: {
        pagination: {},
    },

    methods: {
        filter(page_url){
            this.$emit('filter', page_url);
        },
        
        getPage(i){
                if(this.pagination.number_of_pages < 11){
                    return i;
                }
                else{
                    var page_number = this.pagination.current_page - this.pagination.current_page_spot + i;
                    
                    if(page_number > this.pagination.last_page){
                        page_number = page_number - this.pagination.last_page;                            
                    }
                    else if(page_number < 1){
                        page_number = page_number + this.pagination.last_page;
                    }

                    return page_number;
                }
            },
             wrapPrevUrl(url){
                if(url == null){
                    return this.pagination.path + '?page='+ this.pagination.last_page;
                }
                else{
                    return url;
                }
            },

            wrapNextUrl(url){
                if(url == null){
                    return this.pagination.path + '?page=1';
                }
                else{
                    return url;
                }
            }
    }
}
</script>
<style>
    .disable-events {
        pointer-events: none
    }
</style>