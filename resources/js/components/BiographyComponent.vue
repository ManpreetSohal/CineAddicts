<template>
    <v-card class="overflow-y-auto ma-2"
        max-height= '250px'
    >
        <v-banner color='white' class="text-uppercase text-center text-h6 font-weight-bold"
            sticky>
            {{title}}
            <v-chip v-if="release_date != null" outlined class="ml-2" :color="randomColor()">{{ extractYear(release_date) }}</v-chip>
            <v-chip outlined class="ml-2" v-for='language in languages' v-bind:key='language.id' v-text='language.language' :color="randomColor()"></v-chip>
        </v-banner>

        <v-card-text class="blue-grey--text text-justify text-boy-1 font-weight-medium">
            {{content}}
        </v-card-text>
    </v-card>
</template>

<script>
export default {
   props: {
       title: String,
       content: String,
       release_date: String,
       languages: {
           type: Array
       }
   },
   
   methods: {
       extractYear(releaseDate){
           if(releaseDate != null){
               return releaseDate.substring(0, 4);
           }
       },

       randomColor(){
           return '#' + (Math.random()*0xFFFFFF<<0).toString(16);
       }
   }
}
</script>