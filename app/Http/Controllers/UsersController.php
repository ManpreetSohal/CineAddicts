<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Follower;
use App\Constants;
use Illuminate\Http\Request;
use App\Movie_list;
use App\Movie;
use App\Http\Resources\Movie_list as MovieListResource;

use App\List_movie_association;
use App\Review;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::check()){
            $username = Auth::user()->username;

            $user_lists = Movie_list::select('id', 'title')->where('username', '=', $username)->orderBy('id', 'ASC')->get();
            $reviews_exist = Review::select('id')->where('username', '=', $username)->where('deleted', '=', false)->first() != null ? true : false;

            return [
                'username' => $username,
                'lists' => $user_lists,
                'reviews_exist' => $reviews_exist
            ];
        }
        else{
            return [
                'redirect_route' => route('login'),
                'username' => null,
                'lists' => null
            ];
        }
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
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($username)
    {
        $usernameLoggedIn = $this->getUsername();
        $user = User::where('username', '=', $username)->first();
        
        if($user != null){
            $status_code = $usernameLoggedIn == $username ? 201 : 200;
            $user_lists = Movie_list::select('id', 'title')->where('username', '=', $username)->orderBy('id', 'ASC')->get();
            //$user_lists = Movie_list::leftJoin('list_movie_associations', 'list_movie_associations.list_id', '=', 'movie_lists.id')->select('movie_lists.id AS id', 'movie_lists.title AS title', DB::raw('COUNT(list_movie_associations.list_id) AS count'))->groupBy('movie_lists.id')->where('movie_lists.username', '=', $username)->orderBy('movie_lists.id', 'ASC')->get();
            $avatar_path = $user['avatar_path'] != null || '' ? '/'.$user['avatar_path'] : '';
            return[
                'status_code' => $status_code,
                'username' => $username,
                'lists' => $user_lists,
                'avatar_path' => $avatar_path
            ];
        }
        else{
            return[
                'status_code' => 404,
                'redirect_route' => '/pagenotfound'
            ];
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($username)
    {
        if($this->getUsername() == $username){
            try{
                User::where('username', '=', $username)->delete();
                $status['status_code'] = 200;
                $status['title'] = "success";
                $status['message'] = "Account has been successfully deleted";
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

    public function updateFollowingStatus(Request $request){
        $request_data = json_decode($request->getContent(), true);
        $follows = $request_data['follows'];
        
        if(Auth::check()){
            try{
                $username = $this->getUsername();
                $result = Follower::where('username', '=', $username)->where('follows', '=', $follows)->first();

                if($result != null){
                    $result->delete();
                }
                else{
                    Follower::create([
                        'username' => $username,
                        'follows' => $follows
                    ]);
                }
                
                $status['status_code'] = 200;
                return ['status' => $status];
            }
            catch(\Illuminate\Database\QueryException $e){
                $status['status_code'] = 401;
                return ['status' => $status];
            }
        }
        else{
            $status['status_code'] = 400;
            $status['redirect_route'] = route('login');;
            return ['status' => $status];
        }
    }

    public function fetchFollowLists(Request $request){
        $request_data = json_decode($request->getContent(), true);
        $username = $request_data['follows'];
        
        try{
            $following_ids = Follower::select('follows')->where('username', '=', $username)->get();
            $following = User::select('id', 'username AS title', 'avatar_path AS poster_path')->whereIn('username', $following_ids)->get(); 
            $followers = User::join('followers', 'users.username', '=', 'followers.username')->select('users.id', 'followers.username AS title', 'users.avatar_path AS poster_path')->where('followers.follows', '=', $username)->get();

            $status['status_code'] = 200;
            $follows = false;
            $userLoggedIn = $this->getUsername();
            if($userLoggedIn != null && $userLoggedIn != ''){
                $result = Follower::where('username', '=', $userLoggedIn)->where('follows', '=', $username)->first();
                if($result != null){
                    $follows = true;
                }
            }

            return [
                'status' => $status,
                'following' => ['values' => $following, 'visit_case' => Constants::USER_VISIT_CASE],
                'followers' => ['values' => $followers, 'visit_case' => Constants::USER_VISIT_CASE],
                'follows' => $follows
            ];
        }
        catch(\Illuminate\Database\QueryException $e){
            $status['status_code'] = 400;
            return [
                'status' => $status,
                'error' => $e->errorinfo
            ];
        }
    }

    private function getUsername(){
        return Auth::check() == true ? Auth::user()->username : '';
    }
}
