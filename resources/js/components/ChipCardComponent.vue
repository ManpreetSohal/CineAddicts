<template>
    <div class="container">
        <v-card color="grey darken-4" class="pa-5 ml-2 mr-2">
            <v-layout row wrap>
                <v-chip outlined class="mb-2 ml-2" v-for='title in titles' v-bind:key='title.id' v-text="title.genre" :color="randomColor()"></v-chip>
            </v-layout>
        </v-card>
    </div>
</template>

<script>
export default {
    props: {
        titles: {
            type: Array
        }
    }, 

    methods: {
        randomColor(){
            return '#' + (Math.random()*0xFFFFFF<<0).toString(16);
        }
    }
}
</script>