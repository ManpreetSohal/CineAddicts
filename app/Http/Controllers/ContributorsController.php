<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Constants;
use App\Contributor;
use App\Country;
use App\Movie;
use App\Location;
use App\Movies_contributors_association;
use App\Contributor_country_association;
use App\Http\Resources\Movie as MovieResource;
use App\Http\Resources\Location as LocationResource;
use App\Http\Resources\Contributor as ContributorResource;
use App\Http\Resources\Movies_contributors_association as Movies_contributors_associationResource;
use App\List_movie_association;
use Illuminate\Http\Request;

class ContributorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contributors = Contributor::select('id', 'stage_name AS name', 'poster_path')->where('stage_name', '<>',"")->orderBy('stage_name')->paginate(24);
        $country_ids = Contributor_country_association::select('country_id')->groupBy('country_id')->get();
        $countries = Country::select('id AS value', 'country AS text')->whereIn('id', $country_ids)->get();
        $alphabet = range('A', 'Z');

        return[
            'data' => $contributors,
            'items' => ['countries' => $countries, 'alphabet' => $alphabet],
            'visit_case' => Constants::CONTRIBUTOR_VISIT_CASE
        ];
    }

    public function filter($request){
        $request_data = json_decode($request, true);
        $country_id = $request_data['country_id'];
        $alphabet = $request_data['alphabet'];
        
        $contributor_ids = [];

        if($alphabet != null){
            $contributor_ids = Contributor::select('id')->where('stage_name', 'LIKE', $alphabet.'%')->get();
        }

        if($country_id != null){
            if($contributor_ids != null){
                $contributor_ids = Contributor_country_association::select('contributor_id')->where('country_id', '=', $country_id)->whereIn('contributor_id', $contributor_ids)->get();
            }
            else{
                $contributor_ids = Contributor_country_association::select('contributor_id')->where('country_id', '=', $country_id)->get();
            }
        }
        
        $contributors = Contributor::select('id', 'stage_name AS name', 'poster_path')->whereIn('id', $contributor_ids)->orderBy('stage_name')->paginate(24);

        return [
            'data' => $contributors,
            'visit_case' => Constants::CONTRIBUTOR_VISIT_CASE
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contributor  $contributor
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $username = $this->getUsername();
        $contributor = Contributor::where('id', '=', $id)->first();
        $location = Location::select('name', 'country_wiki_id')->where('id', '=', $contributor['location_id'])->first();
        $country = Country::select('country')->where('wiki_id', '=', $location['country_wiki_id'])->first();
        $movies_lists_associations = collect([]);
        $movies_array = [];
        $role_key_pairs = ['Acted' => Constants::CAST_MEMBER_ROLE_ID, 'Directed' => Constants::DIRECTOR_ROLE_ID, 'Produced' => Constants::PRODUCER_ROLE_ID, 'Written' => Constants::SCREENWRITER_ROLE_ID];
    
        foreach($role_key_pairs as $role => $role_id){
            $movie_ids = Movies_contributors_association::select('movie_id')->where('contributor_id', '=', $id)->where('role_id', '=', $role_id)->get();
            $movies = Movie::select('id', 'title', 'poster_image_path AS poster_path')->whereIn('id', $movie_ids)->get();
            $movies_associations = List_movie_association::select('title', 'list_id', 'movie_id')->join('movie_lists', 'list_movie_associations.list_id', '=', 'movie_lists.id')->where('username', '=', $username)->whereIn('movie_id', $movie_ids)->get();

            array_push($movies_array, ['entity_type'=> Constants::entity_type_movie, 'title' => $role, 'values' => $movies, 'visit_case' => Constants::MOVIE_VISIT_CASE]);
            $movies_lists_associations = $movies_lists_associations->merge($movies_associations);
        }

        usort($movies_array, function($a, $b){
            if(count($a['values']) == count($b['values'])){
                return 0;
            }

            return (count($a['values']) > count($b['values'])) ? -1 : 1;
        });
        
        return [
            'contributor' => $contributor,
            'data' => $movies_array,
            'place_of_birth' => $location['name'],
            'country' => $country['country'],
            'movies_lists_associations' => $movies_lists_associations,
        ]; 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contributor  $contributor
     * @return \Illuminate\Http\Response
     */
    public function edit(Contributor $contributor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contributor  $contributor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contributor $contributor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contributor  $contributor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contributor $contributor)
    {
        //
    }

    private function getUsername(){
        return Auth::check() == true ? Auth::user()->username : '';
    }
}
