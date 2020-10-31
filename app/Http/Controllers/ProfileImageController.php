<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;


class ProfileImageController extends Controller
{
    //
    public function index(){

    }

    public function getAvatar(){
        if(Auth::check()){
            $username = Auth::user()->username;
            $avatar_path = User::select('avatar_path')->where('username', '=', $username)->first();
            return $avatar_path['avatar_path'] != null || '' ?            
                                 ['avatar_path' => $avatar_path['avatar_path'].'#'.Carbon::now()->toDateTimeString()] : '';
        }
        else{
            return ['avatar_path' => ''];
        }
    }

    private function removeAllPicturesForUser($path){
        $extensions = ['.png', '.jpg', '.bmp'];
        foreach($extensions as  $extension){
            try{
                unlink($path.$extension);
            }catch(\Exception $e){}
        }
    }

    public function deleteAvatar(){
        $status = [];

        if(Auth::check()){
            $folderName = 'user_avatars/';
            $username = Auth::user()->username;
            $this->removeAllPicturesForUser($folderName.$username);
            User::where('username', '=', $username)->update(['avatar_path' => NULL]);

            $status['status_code'] = 200;
            $status['user_avatar_path'] = '';
            return ['status' => $status];
        }
        else{
            $status['status_code'] = 400;
            $status['redirect_route'] = route('login');;
            return ['status' => $status];
        }
    }

    public function store(Request $request)
    {
        $status = [];

        if(Auth::check()){
            $username = Auth::user()->username;
            $fileExtension = $request->image->getClientOriginalExtension();
            $folderName = 'user_avatars/';
            $path = $username.'.'.$fileExtension;
            $image = $request->file('image');

            $this->removeAllPicturesForUser($folderName.$username);
            $image->move($folderName, $path);

            User::where('username', '=', $username)->update(['avatar_path' => $folderName.$path]);
           
            $status['status_code'] = 200;
            $status['user_avatar_path'] = $folderName.$path;
            return ['status' => $status];
        }else{
            $status['status_code'] = 400;
            $status['redirect_route'] = route('login');;
            return ['status' => $status];
        }
    }
}   