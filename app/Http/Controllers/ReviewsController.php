<?php

namespace App\Http\Controllers;

use App\Review;
use App\Movie;
use App\Movie_country_association;
use App\Movie_genre;
use App\Movie_languages_association;
use App\Language;
use App\Country;
use App\List_movie_association;
use App\Movies_genres_association;
use App\Constants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewsController extends Controller
{
    /**
     * returns movies the user has reviewed.
     *
     * @return \Illuminate\Http\Response
     */

     public function index(){

     }
     
    public function showReviews($id)
    {
        try{
            $movie_ids = Review::select('movie_id')->where('username', '=', $id)->where('deleted', '=', false)->get();
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
        // if(Auth::check()){
        //     $username = $this->getUsername(); 
            $request_data = json_decode($request, true);
            $username = $request_data['username'];
            $year = $request_data['year'];
            
            $movie_ids = Review::select('movie_id')->where('username', '=', $username)->where('deleted', '=', false)->get();
            $filtered_movie_ids = null;
            if($year != null){
                $year_incremented = $year + 1;

                $date_format = 'Y-m-d H:i:s';
                $date_lower_limit = date_create_from_format($date_format, $year.'-01-01 00:00:00');
                $date_upper_limit = date_create_from_format($date_format, $year_incremented.'-01-01 00:00:00');

                $filtered_movie_ids = Movie::select('id')->where('release_date', '>=', $date_lower_limit)->where('release_date', '<', $date_upper_limit)->get();
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
                    if($filtered_movie_ids != null){
                        $filtered_movie_ids = $query_object['model']::select('movie_id')->where($query_object['field'], '=', $query_object['id'])->whereIn('movie_id', $filtered_movie_ids)->get();
                    }
                    else{
                        $filtered_movie_ids = $query_object['model']::select('movie_id')->where($query_object['field'], '=', $query_object['id'])->get();    
                    }
                }
            }

            $movies = Movie::select('id', 'title', 'poster_image_path')->whereIn('id', $movie_ids)->whereIn('id', $filtered_movie_ids)->orderBy('title')->paginate(24);
            $username = $this->getUsername();
            $movies_lists_associations = List_movie_association::select('title', 'list_id', 'movie_id')->join('movie_lists', 'list_movie_associations.list_id', '=', 'movie_lists.id')->where('username', '=', $username)->get();
        
            return[
                "data" => $movies,
                'movies_lists_associations' => $movies_lists_associations,
                'visit_case' => Constants::MOVIE_VISIT_CASE
            ];
        // }else{
        //     return [
        //         'redirect_route' => route('login'),
        //         'lists' => null
        //     ];
        // }
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
        $status = [];

            if(Auth::check()){
                $request_data = json_decode($request->getContent(), true);
                $title = $request_data['title'];
                $review = $request_data['review'];
                $rating = $request_data['rating'];
                $movie_id = $request_data['movie_id'];
                $username = $this->getUsername();

                try{
                    Review::create([
                        'title' => $title,
                        'review' => $review,
                        'rating' => $rating,
                        'username' => $username,
                        'movie_id' => $movie_id
                    ]);
                    
                    $status['status_code'] = 200;
                    $status['title'] = 'Success';
                    $status['message'] = 'Thank you for your review.';
                    return ['status' => $status];
                }
                catch(\Exception $e){
                    $status['status_code'] = 201;
                    $status['title'] = 'Error';
                    $status['message'] = $e->errorInfo;
                    return ['status' => $status];
                }

            }else{
                $status['status_code'] = 400;
                $status['redirect_route'] = route('login');;
                return ['status' => $status];
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $username = $this->getUsername();
        $reviews = Review::where('movie_id', '=', $id)->where('deleted', '=', false)->orderByRaw('username = ? DESC', $username)->orderBy('created_at', 'DESC')->get();
        return[
            'reviews' => $reviews
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Auth::check()){
            try{
                Review::where('id', '=', $id)->update(['deleted' => true]);
                $status['status_code'] = 200;
                $status['title'] = "success";
                $status['message'] = "Review has been deleted";
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

    private function getUsername(){
        return Auth::check() == true ? Auth::user()->username : '';
    }
}
