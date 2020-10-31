export const usernameMixin = {
    methods: {
        // fetchUser(){
        //     var username = null;
        //     try{
        //         username = document.querySelector("meta[name='username']").getAttribute('content');
        //     }catch(error){

        //     }finally{
        //         return username;
        //     }
        // },

        visit(id, caseId){
            switch(caseId){
                case 1:
                    window.location = "/contributors/"+id;
                break;
                case 2:
                    window.location = "/movies/"+id;
                    break;
                case 3:
                    window.location = "/companies/"+id;
                    break;
                case 4:
                    window.location = "/locations/"+id;
                    break;
                case 5:
                    window.location = "/users/"+id;
                    break;
                case 6:
                    window.location = "/lists/info/"+id;
                    break;
            }
        },

         filterLists(movie_id){
                if(this.lists == null) return null;
                var movie_lists_associations = this.filterMoviesListsAssociations(movie_id)
                var lists = Object.keys(this.lists).map((key) => {return this.lists[key] });
                console.log(lists);
                var nonContainingLists = [];
                var inSeenList = false;
                var inWatchList = false;
                if(movie_lists_associations.find(list => (list.list_id == lists[0].id && list.movie_id == movie_id)) != null) inWatchList = true;
                if(movie_lists_associations.find(list => (list.list_id == lists[1].id && list.movie_id == movie_id)) != null) inSeenList = true;
                var watchListId = lists[0].id;
                var seenListId = lists[1].id;

                lists.splice(0, 2);

                let i = 0;
                while(i < lists.length){
                    var result = movie_lists_associations.filter((association) => {
                            return (association['movie_id'] == movie_id & association['list_id'] == lists[i]['id']);
                        });
                    if(result.length == 0){
                        nonContainingLists = nonContainingLists.concat(lists.splice(i,1)); 
                        i--; 
                    }
                    ++i;
                }   

                let movie_lists = {
                    inSeenList: inSeenList,
                    inWatchList: inWatchList,
                    seenListId: seenListId,
                    watchListId: watchListId,
                    nonContainingLists: nonContainingLists,
                    lists: lists
                };
                return movie_lists;
            },

            filterMoviesListsAssociations(movie_id){
                let moviesListsAssociationArray = Object.keys(this.movies_lists_associations).map((key) => {
                                                    return this.movies_lists_associations[key]
                                                  });
                return moviesListsAssociationArray.filter((movie) => {
                    return movie['movie_id'] == movie_id;
                });
            },

            truncateString(str, n){
                return (str.length > n) ? str.substr(0, n-1) + '...' : str;
            }
        // addToList(movie_id, list_id){
        //     let data = {
        //         movie_id: movie_id,
        //         list_id: list_id
        //     }

        //     fetch('/api/movies',{
        //         method: 'post',
        //         body: JSON.stringify(data) 
        //     })
        //     .then(res => res.json())
        //     .then(res => res.status)
        //     .then(status => { if(status.status_code == 400){ window.location = status.redirect_route}})
        // },

        // removeFromList(movie_id, list_id){
        //     let data = {
        //         movie_id: movie_id,
        //         list_id: list_id
        //     }

        //     fetch('/api/movies/removefromlist',{
        //         method: 'post',
        //         body: JSON.stringify(data) 
        //     })
        //     .then(res => res.json())
        //     .then(res => res.status)
        //     .then(status => { 
        //         if(status.status_code == 400){ window.location = status.redirect_route}
        //         else{
        //             this.$emit('removeFromCustomList', status.message);
        //         }})
        // }
    }
}