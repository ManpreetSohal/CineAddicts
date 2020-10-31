<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Location;
use App\Constants;
use App\Contributor;
use App\Movie;
use App\Country;
use App\Movie_filming_location_association;
use App\Movie_narrative_location_association;
use App\List_movie_association;

class LocationsController extends Controller
{
    public function show($id)
    {
        $username = $this->getUsername();
        $location = Location::where('id', '=', $id)->first();
        $country = Country::select('country')->where('wiki_id', '=', $location['country_wiki_id'])->first();
        $movies_lists_associations = collect([]);
        $data_array = [];
        $location_tables= ['Filmed' => Movie_filming_location_association::class, 'Set In' => Movie_narrative_location_association::class];
        
        foreach($location_tables as $role => $table){
            $movie_ids = $table::select('movie_id')->where('location_id', '=', $id)->get();
            $movies = Movie::select('id', 'title', 'poster_image_path AS poster_path')->whereIn('id', $movie_ids)->get();
            $movies_associations = List_movie_association::select('title', 'list_id', 'movie_id')->join('movie_lists', 'list_movie_associations.list_id', '=', 'movie_lists.id')->where('username', '=', $username)->whereIn('movie_id', $movie_ids)->get();
            array_push($data_array, ['entity_type'=> Constants::entity_type_movie, 'title' => $role, 'values' => $movies, 'visit_case' => Constants::MOVIE_VISIT_CASE]);
            $movies_lists_associations = $movies_lists_associations->merge($movies_associations);
        }

        $stars_born = Contributor::select('id', 'stage_name AS title', 'poster_path')->where("stage_name", "<>", "")->where('location_id', '=', $location['id'])->get();
        $tab_title = 'stars born';
        array_push($data_array, ['entity_type'=> Constants::entity_type_person, 'title' => $tab_title, 'values' => $stars_born, 'visit_case' => Constants::CONTRIBUTOR_VISIT_CASE]);

        usort($data_array, function($a, $b){
            if(count($a['values']) == count($b['values'])){
                return 0;
            }

            return (count($a['values']) > count($b['values'])) ? -1 : 1;
        });

        return [
            'location' => $location,
            'country' => $country['country'],
            'data' => $data_array,
            'movies_lists_associations' => $movies_lists_associations,
        ];
    }

    private function getUsername(){
        return Auth::check() == true ? Auth::user()->username : '';
    }
}
