<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Movie;
use App\Contributor;
use App\Company;
use App\Location;
use App\Constants;
use App\List_movie_association;
use App\Movie_list;
use App\User;
use Illuminate\Support\Facades\Auth;



class SearchController extends Controller
{
    public function search(Request $request){
        $search_str = $request->searchstr;
        $username = $this->getUsername();

        $data_array = [];

        $query_params = [['entity' => 'movies', 'model' => Movie::class, 'title' => 'title', 'poster_path' => 'poster_image_path AS poster_path', 'entity_type' => Constants::entity_type_movie, 'visit_case' => Constants::MOVIE_VISIT_CASE],
                  ['entity' => 'contributors', 'model' => Contributor::class, 'title' =>'stage_name', 'poster_path' => 'poster_path', 'entity_type' => Constants::entity_type_person, 'visit_case' => Constants::CONTRIBUTOR_VISIT_CASE],
                  ['entity' => 'companies', 'model' => Company::class, 'title' => 'company', 'poster_path' => 'logo_path AS poster_path', 'entity_type' => Constants::entity_type_company, 'visit_case' => Constants::COMPANY_VISIT_CASE],
                  ['entity' => 'locations', 'model' => Location::class, 'title' => 'name', 'poster_path' => 'poster_path', 'entity_type' => Constants::entity_type_location, 'visit_case' => Constants::LOCATION_VISIT_CASE],
                  ['entity' => 'users', 'model' => User::class, 'title' => 'username', 'poster_path' => 'avatar_path AS poster_path', 'entity_type' => Constants::entity_type_user, 'visit_case' => Constants::USER_VISIT_CASE],
                  ['entity' => 'lists', 'model' => Movie_list::class, 'title' => 'title', 'poster_path' => 'list_type_id', 'entity_type' => Constants::entity_type_list, 'visit_case' => Constants::LIST_VISIT_CASE]];
        

        foreach($query_params as $query_param){
            $data = $query_param['model']::select('id', $query_param['title'].' AS title', $query_param['poster_path'])->where($query_param['title'], 'LIKE', '%'.$search_str.'%')->orderByRaw('POSITION("'.$search_str.'" IN '.$query_param['title'].')', 'ASC')->take(100)->get();
            array_push($data_array, ['entity_type'=> $query_param['entity_type'], 'title' => $query_param['entity'], 'values' => $data, 'visit_case' => $query_param['visit_case']]);
        }
       
        $movie_ids = collect($data_array[0]['values'])->pluck('id')->toArray();
        $movies_associations = List_movie_association::select('title', 'list_id', 'movie_id')->join('movie_lists', 'list_movie_associations.list_id', '=', 'movie_lists.id')->where('username', '=', $username)->whereIn('movie_id', $movie_ids)->get();
       
        usort($data_array, function($a, $b){
            if(count($a['values']) == count($b['values'])){
                return 0;
            }

            return (count($a['values']) > count($b['values'])) ? -1 : 1;
        });

        $data = array(
            'search_str' => $search_str,
            'data_array' => $data_array,
            'movies_lists_associations' => $movies_associations);
        return view('search/search')->with('str',$search_str)->with($data);
    }

    private function getUsername(){
        return Auth::check() == true ? Auth::user()->username : '';
    }
}
