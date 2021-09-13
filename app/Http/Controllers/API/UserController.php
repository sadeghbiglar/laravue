<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return  User::orderBy('id')->get();
        return  User::latest()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validatedData=$request->validate([
            'name'=>'string|required',
            'email'=>'required|unique:users',
            'password'=>'required',
            'type'=>'required'
        ]);

        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'type' => $request['type'],
            'bio' => $request['bio'],
            'password' => Hash::make($request['password']),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    public function profile()
    {
        return auth('api')->user();
    }
    public function updateProfile(Request $request)
    {
        $user= auth('api')->user();

        $validatedData=$request->validate([
            'name'=>'string|required',
            'email'=>'required|email|unique:users,email,'.$user->id,
            'password'=>'sometimes|required|min:8',
        ]);

        $currentPhoto=$user->photo;
        if($request->photo !=$currentPhoto){
            $name=time().'.'.explode('/',
                    explode(':',
                        substr($request->photo,0,
                            strpos($request->photo,';')))[1])[1];
            Image::make($request->photo)->save(public_path('img/profile/').$name);
            $request->merge(['photo'=>$name]);
            $userPhoto=public_path('img/profile/').$currentPhoto;
            if(file_exists($userPhoto)){
                @unlink($userPhoto);
            }
        }

        if(!empty($request->password)){
            $request->merge(['password'=>Hash::make($request['password'])]);
        }
        $user->update($request->all());
        return ["msg"=>"success"];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user=User::findOrFail($id);

        $validatedData=$request->validate([
            'name'=>'string|required',
            'email'=>'required|email|unique:users,email,'.$user->id,
            'password'=>'sometimes|min:8',
            'type'=>'required'
        ]);

        $user->update($request->all());
        return ["msg"=>"user updated"];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user=User::findOrFail($id);
        $user->delete();
        return ["msg"=>"user deleted"];
    }
}
