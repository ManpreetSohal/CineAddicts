<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Constants;
use App\Movie;
use App\Movie_genre;
use App\Language;
use App\Country;
use App\Movie_country_association;
use App\Movie_languages_association;
use App\Movies_genres_association;
use App\List_movie_association;
use App\Movie_list;
use Doctrine\DBAL\Driver\PDOSqlsrv\Statement;
use Illuminate\Http\Request;
use SebastianBergmann\Environment\Console;

class MovieListController extends Controller
{
    public function index(){
        $username = $this->getUsername();
        $lists = Movie_list::join('users', 'users.username', '=', 'movie_lists.username')->join('list_movie_associations', 'list_movie_associations.list_id', '=', 'movie_lists.id')->select('list_movie_associations.list_id AS lmaId', 'movie_lists.id AS list_id', 'users.id AS user_id', 'movie_lists.title', 'movie_lists.username', 'users.avatar_path', 'movie_lists.created_at')->where('users.username', '<>', $username)->whereNotIn('title', [Constants::SEEN_LIST_TITLE, Constants::WATCH_LIST_TITLE])->groupBy('lmaId')->havingRaw('count(list_movie_associations.list_id) > 0')->orderBy('created_at', 'DESC')->get();
        return ['lists' => $lists];
    }

    public function filter($request){
        $request_data = json_decode($request, true);
        
        $year = $request_data['year'];
        $list_id = $request_data['list_id'];
        $movie_ids = List_movie_association::select('movie_id')->where('list_id', '=', $list_id)->get();

        if($year != null){
            $year_incremented = $year + 1;

            $date_format = 'Y-m-d H:i:s';
            $date_lower_limit = date_create_from_format($date_format, $year.'-01-01 00:00:00');
            $date_upper_limit = date_create_from_format($date_format, $year_incremented.'-01-01 00:00:00');

            $movie_ids = Movie::select('id')->whereIn('id', $movie_ids)->where('release_date', '>=', $date_lower_limit)->where('release_date', '<', $date_upper_limit)->get();
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
                $movie_ids = $query_object['model']::select('movie_id')->where($query_object['field'], '=', $query_object['id'])->whereIn('movie_id', $movie_ids)->get();
            }
        }

        $movies = Movie::select('id', 'title', 'poster_image_path')->whereIn('id', $movie_ids)->orderBy('title')->paginate(12);
        $username = $this->getUsername();
        $movies_lists_associations = List_movie_association::select('title', 'list_id', 'movie_id')->join('movie_lists', 'list_movie_associations.list_id', '=', 'movie_lists.id')->where('username', '=', $username)->get();
       
        return[
            "data" => $movies,
            'movies_lists_associations' => $movies_lists_associations,
            'visit_case' => Constants::MOVIE_VISIT_CASE
        ];
    }

    public function create(){

    }

    public function destroy($id){
        if(Auth::check()){
            try{
                Movie_list::where('id', '=', $id)->delete();
                $status['status_code'] = 200;
                $status['title'] = "success";
                $status['message'] = "List has been removed";
                return ['status' => $status];
            }
            catch(\Illuminate\Database\QueryException $e){
                $status['status_code'] = 401;
                $status['title'] = "Error";
                //$status['message'] = $e->errorInfo;
                $status['message'] = $e->errorInfo;
                return ['status' => $status];
            }
        }
        else{
            $status['status_code'] = 400;
            $status['redirect_route'] = route('login');;
            return ['status' => $status];
        }
    }

    public function store(Request $request){
        $username = $this->getUsername();
        $status = [];

        if($username != ''){
            $request_data = json_decode($request->getContent(), true);
            $list_title = $request_data['list_name'];

            try{
                Movie_list::create([
                                'title' => $list_title,
                                'username' => $username,
                                'list_type_id' => intval(Constants::list_type_id_other)
                            ]);

                $status['status_code'] = 200;
                $status['title'] = "success";
                $status['message'] = "List created. To add movies to a custom made list, click on the list icon while hovering over a movie and select a list to which you want to add.";
                return ['status' => $status];
            }
            catch(\Illuminate\Database\QueryException $e){
                $status['status_code'] = 401;
                $status['title'] = "Error";
                //$status['message'] = $e->errorInfo;
                $status['message'] = $e->errorInfo;
                return ['status' => $status];
            }           
        }
        else{
            $status['status_code'] = 400;
            $status['redirect_route'] = route('login');;
            return ['status' => $status];
        }
    }

    public function listInfo($id){
        $list = Movie_list::where('id', '=', $id)->first();
        return ['list' => $list];
    }

    public function show($id){
        $movie_ids = List_movie_association::select('movie_id')->where('list_id', '=', $id)->get();
        $movies = Movie::select('id', 'title', 'poster_image_path')->whereIn('id', $movie_ids)->orderBy('title')->paginate(18);

        
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
            'data' => $movies,
            'items' => ['years' => $years, 'countries' => $countries, 'genres' => $genres, 'languages' => $languages],
            'movies_lists_associations' => $movies_lists_associations,
            'visit_case' => Constants::MOVIE_VISIT_CASE
        ];
    }

    private function getUsername(){
        return Auth::check() == true ? Auth::user()->username : '';
    }
}
