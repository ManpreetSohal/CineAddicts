<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Log;
use App\Contributor;
use App\Movie;
use App\Company;
use App\Constants;
use App\Country;
use App\Location;
use App\Language;
use App\Movie_genre;
use App\Movie_filming_location_association;
use App\Movie_narrative_location_association;
use App\Movie_languages_association;
use App\Movie_country_association;
use App\Movies_contributors_association;
use App\Movies_companies_association;
use App\List_movie_association;
use App\Http\Resources\Movie as MovieResource;
use App\Http\Resources\Company as CompanyResource;
use App\Http\Resources\Country as CountryResource;
use App\Http\Resources\Location as LocationResource;
use App\Http\Resources\Language as LanguageResource;
use App\Http\Resources\Movie_genre as Movie_genreResource;
use App\Http\Resources\Movie_filming_location_association as Movie_filming_location_associationResource;
use App\Http\Resources\Movie_narrative_location_association as Movie_narrative_location_associationResource;
use App\Http\Resources\Movie_country_association as Movie_country_associationResource;
use App\Http\Resources\Movie_languages_association as Movie_languages_associationResource;
use App\Http\Resources\Contributor as ContributorResource;
use App\Http\Resources\Movies_contributors_association as Movies_contributors_associationResource;
use App\Http\Resources\Movies_companies_association as Movies_companies_associationResource;
use App\Movie_list;
use App\Movies_genres_association;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use SebastianBergmann\Environment\Console;

class MoviesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $redirect_to_register = '/register';

    public function index()
    {
        try{
            $movies = Movie::leftjoin('movie_country_association', 'movies.id', '=', 'movie_country_association.movie_id')->select('movies.id', 'movies.title', 'movies.poster_image_path')->groupBy('movies.id')->orderBy(DB::raw('CASE WHEN movie_country_association.country_id IN (1, 2, 3) THEN 0 ELSE 1 END'))->orderBy('movies.release_date', 'DESC')->paginate(24);

            $movie_country_ids = Movie_country_association::select('country_id')->groupBy('country_id')->get();

            $genres = Movie_genre::select('id AS value', 'genre AS text')->get();
            $countries = Country::select('id AS value', 'country AS text')->whereIn('id', $movie_country_ids)->get();
            $languages = Language::select('id AS value', 'language AS text')->get();
            
            $years = [];

            $current_year = date('Y');
            for($year = $current_year; $year >= 1950; $year--){
                array_push($years, $year);
            }
            $username = $this->getUsername();
            $movies_lists_associations = List_movie_association::select('title', 'list_id', 'movie_id')->join('movie_lists', 'list_movie_associations.list_id', '=', 'movie_lists.id')->where('username', '=', $username)->get();
        
            return[
                'status_code' => 200,
                "data" => $movies,
                'items' => ['years' => $years, 'countries' => $countries, 'genres' => $genres, 'languages' => $languages],
                'movies_lists_associations' => $movies_lists_associations,
                'visit_case' => Constants::MOVIE_VISIT_CASE
            ];
        }
        catch(\Exception $e){
            return ['status_code' => 400];
        }
    }

    public function filter($request){   
        $request_data = json_decode($request, true);
        
        $year = $request_data['year'];
        
        $movie_ids = [];

        if($year != null){
            $year_incremented = $year + 1;

            $date_format = 'Y-m-d H:i:s';
            $date_lower_limit = date_create_from_format($date_format, $year.'-01-01 00:00:00');
            $date_upper_limit = date_create_from_format($date_format, $year_incremented.'-01-01 00:00:00');

            $movie_ids = Movie::select('id')->where('release_date', '>=', $date_lower_limit)->where('release_date', '<', $date_upper_limit)->get();
        //$movie_ids = $movies->map->id;
        }
        
        $genre_id = $request_data['genre_id'];
        $language_id = $request_data['language_id'];
        $country_id = $request_data['country_id'];

        $query_objects = [['field' => 'genre_id', 'id' => $genre_id, 'model' => Movies_genres_association::class], 
                            ['field' => 'country_id', 'id' => $country_id, 'model' => Movie_country_association::class],
                            ['field' => 'language_id', 'id' => $language_id, 'model' => Movie_languages_association::class]];
                            
        foreach($query_objects as $query_object){
            if($query_object['id'] != null){
                if($movie_ids != null){
                    $movie_ids = $query_object['model']::select('movie_id')->where($query_object['field'], '=', $query_object['id'])->whereIn('movie_id', $movie_ids)->get();
                }
                else{
                    $movie_ids = $query_object['model']::select('movie_id')->where($query_object['field'], '=', $query_object['id'])->get();    
                }
            }
        }

        $movies = Movie::select('id', 'title', 'poster_image_path')->whereIn('id', $movie_ids)->orderBy('release_date', 'DESC')->paginate(24);
        $username = $this->getUsername();
        $movies_lists_associations = List_movie_association::select('title', 'list_id', 'movie_id')->join('movie_lists', 'list_movie_associations.list_id', '=', 'movie_lists.id')->where('username', '=', $username)->get();
       
        return[
            "data" => $movies,
            'movies_lists_associations' => $movies_lists_associations,
            'visit_case' => Constants::MOVIE_VISIT_CASE
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
     * Store movie in a list
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $status = [];

            if(Auth::check()){
                $request_data = json_decode($request->getContent(), true);
            
                $username = Auth::user()->username;
                $movie_id = $request_data['movie_id'];
                //$list_title = $request_data['list_name'];
                $list_id = $request_data['list_id'];
                $list_title = Movie_list::select('title')->where('username', '=', $username)->where('id', '=', $list_id)->first();

                // $list = Movie_list::select('id')->where('username', '=', $username)->where('title', '=', $list_title)->first();
                // $list_id = $list['id'];
                $movies_list_association = List_movie_association::where('list_id', '=', $list_id)->where('movie_id', '=', $movie_id)->first();
               
                try{
                    if(is_null($movies_list_association)){
                        List_movie_association::create([
                            'list_id' => $list_id,
                            'movie_id' => intval($movie_id)
                        ]);
                    }else if($list_title['title'] == Constants::WATCH_LIST_TITLE || $list_title['title'] == Constants::SEEN_LIST_TITLE){
                        List_movie_association::where('list_id', '=', $list_id)->where('movie_id', '=', $movie_id)->delete();
                    }
                    $status['status_code'] = 200;
                    $status['title'] = 'Success';
                    $status['message'] = 'Added to list';
                }
                catch(\Exception $e){
                    $status['status_code'] = 201;
                    $status['title'] = 'Error';
                    $status['message'] = 'Failed to add to list';
                }
                
                return ['status' => $status];
            }        
            else{
                $status['status_code'] = 400;
                $status['redirect_route'] = route('login');;
                return ['status' => $status];
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */

     public function show($id)
    {
        $movie = Movie::where('id', '=', $id)->first();
       
        $data_array = [];
        $contributor_role_key_pairs = ['Cast' => Constants::CAST_MEMBER_ROLE_ID, 'Director' => Constants::DIRECTOR_ROLE_ID, 'Producer' => Constants::PRODUCER_ROLE_ID, 'Screenwriter' => Constants::SCREENWRITER_ROLE_ID];
        $company_role_key_pairs = ['Distributor' => Constants::DISTRIBUTION_COMPANY_ROLE_ID, 'Production' => Constants::PRODUCTION_COMPANY_ROLE_ID];
        
        foreach($contributor_role_key_pairs as $role => $role_id){
            $contributor_ids = Movies_contributors_association::select('contributor_id')->where('movie_id', '=', $id)->where('role_id', '=', $role_id)->get();
            $contributors = Contributor::select('id', 'stage_name AS title', 'poster_path')->where('stage_name', "<>", "")->whereIn('id', $contributor_ids)->get();
            
            array_push($data_array, ['entity_type'=> Constants::entity_type_person, 'title' => $role, 'values' => $contributors, 'visit_case' => Constants::CONTRIBUTOR_VISIT_CASE]);
        }
        
        foreach($company_role_key_pairs as $role => $role_id){
            $company_ids = Movies_companies_association::select('company_id')->where('movie_id', '=', $id)->where('role_id', '=', $role_id)->get();
            $companies = Company::select('id', 'company AS title', 'logo_path AS  poster_path')->whereIn('id', $company_ids)->get();
            array_push($data_array, ['entity_type'=> Constants::entity_type_company, 'title' => $role, 'values' => $companies, 'visit_case' => Constants::COMPANY_VISIT_CASE]);
        }

        $fliming_location_ids = Movie_filming_location_association::select('location_id')->where('movie_id', '=', $id)->get();
        $narrative_location_ids = Movie_narrative_location_association::select('location_id')->where('movie_id', '=', $id)->get();
       
        $filming_locations = Location::select('id', 'name AS title', 'poster_path')->whereIn('id', $fliming_location_ids)->get();
        $narrative_locations = Location::select('id', 'name AS title', 'poster_path')->whereIn('id', $narrative_location_ids)->get();
       
        array_push($data_array, ['entity_type'=> Constants::entity_type_location, 'title' => 'Filming locations', 'values' => $filming_locations, 'visit_case' => Constants::LOCATION_VISIT_CASE]);
        array_push($data_array, ['entity_type'=> Constants::entity_type_location, 'title' => 'Narrative locations', 'values' => $narrative_locations, 'visit_case' => Constants::LOCATION_VISIT_CASE]);

        $language_ids = Movie_languages_association::select('language_id')->where('movie_id', '=', $id)->get();
        $country_ids = Movie_country_association::select('country_id')->where('movie_id', '=', $id)->get();
        $genre_ids = Movies_genres_association::select('genre_id')->where('movie_id', '=', $id)->get();
       
        $languages = Language::select('id', 'language')->whereIn('id', $language_ids)->get();
        $countries = Country::select('id', 'country')->whereIn('id', $country_ids)->get();
        $genres = Movie_genre::select('id', 'genre')->whereIn('id', $genre_ids)->geT();
        
        $username = $this->getUsername();
        $movie_lists_associations = List_movie_association::select('title', 'list_id', 'movie_id')->join('movie_lists', 'list_movie_associations.list_id', '=', 'movie_lists.id')->where('movie_id', '=', $id)->where('username', '=', $username)->get();
       
        return [
            'movie' => $movie,
            'data' => $data_array,
            'languages' => $languages,
            'countries' => $countries,
            'movie_lists_associations' => $movie_lists_associations,
            'genres' => $genres   
        ]; 
        // 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function edit(Movie $movie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movie $movie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $status = [];

            if(Auth::check()){
                $request_data = json_decode($request->getContent(), true);
                
                $movie_id = $request_data['movie_id'];
                $list_id = $request_data['list_id'];

                try{
                    List_movie_association::where('list_id', '=', $list_id)->where('movie_id', '=', $movie_id)->delete();
                    $status['status_code'] = 200;
                    $status['title'] = 'Success';
                    $status['message'] = 'Removed from list';
                }
                catch(\Exception $e){
                    $status['status_code'] = 201;
                    $status['title'] = 'Error';
                    $status['message'] = 'Failed to remove from list';
                }

                return ['status' => $status];
            }        
            else{
                $status['status_code'] = 400;
                $status['redirect_route'] = route('login');;
                return ['status' => $status];
            }
    }

    public function movie_ids($username){

    }

    private function getUsername(){
        return Auth::check() == true ? Auth::user()->username : '';
    }
}
