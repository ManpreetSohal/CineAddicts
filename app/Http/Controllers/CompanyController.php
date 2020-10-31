<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Company;
use App\Constants;
use App\Movie;
use App\Movies_companies_association;
use App\List_movie_association;

class CompanyController extends Controller
{
    public function index(){
        $contributors = Company::select('id', 'company AS name', 'logo_path AS poster_path')->orderBy('company')->paginate(24);
        $alphabet = range('A', 'Z');

        return[
            'data' => $contributors,
            'items' => ['alphabet' => $alphabet],
            'visit_case' => Constants::COMPANY_VISIT_CASE
        ];
    }

    public function filter($request){
        $request_data = json_decode($request, true);
        $alphabet = $request_data['alphabet'];
        
        $contributor_ids = [];

        if($alphabet != null){
            $contributor_ids = Company::select('id')->where('company', 'LIKE', $alphabet.'%')->get();
        }
        
        $contributors = Company::select('id', 'company AS name', 'logo_path AS poster_path')->whereIn('id', $contributor_ids)->paginate(24);

        return [
            'data' => $contributors,
            'visit_case' => Constants::COMPANY_VISIT_CASE
        ];


    }

    public function show($id)
    {
        $username = $this->getUsername();
        $company = Company::where('id', '=', $id)->first();
        $movies_lists_associations = collect([]);
        $movies_array = [];
        $role_key_pairs = ['Produced' => Constants::PRODUCTION_COMPANY_ROLE_ID, 'Distributed' => Constants::DISTRIBUTION_COMPANY_ROLE_ID];
        
        foreach($role_key_pairs as $role => $role_id){
            $movie_ids = Movies_companies_association::select('movie_id')->where('company_id', '=', $id)->where('role_id', '=', $role_id)->get();
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
            'company' => $company,
            'data' => $movies_array,
            'movies_lists_associations' => $movies_lists_associations,
        ];
    }

    private function getUsername(){
        return Auth::check() == true ? Auth::user()->username : '';
    }
}
