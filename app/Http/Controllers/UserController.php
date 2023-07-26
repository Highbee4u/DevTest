<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\User\UserRegistrationFormRequest;
use App\Http\Requests\User\UserLoginFormRequest;
use Illuminate\Support\Str;
use Session;
use Illuminate\Support\Facades\Auth;
use App\Jobs\ProcessUser;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = User::orderBy('created_at', 'DESC')->get();

        return response()->json([
            'status' => true,
            'data' => $response,
            'message' => 'All User'
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRegistrationFormRequest $request)
    {
        $data = array_merge($request->validated(), ['api_token' => Str::random(32)]);

        $response = User::create($data);

        return $response ? 
                response()->json([
                    'status' => true,
                    'data' => $response,
                    'message' => 'User Created Successfully',
                ]) : 
                response()->json([
                    'status' => false,
                    'data' => [],
                    'message' => 'Error! Unable to create User, try again later',
                ]);

    }

   
    public function login(UserLoginFormRequest $request)
    {
       $detail = User::where('api_token', $request->safe()->only('api_token'));

       ProcessUser::dispatchNow($detail['id']);

       return $detail ? 
                response()->json([
                    'status' => true,
                    'data' => $detail,
                    'message' => 'Authenticated User',
                ]) : 
                response()->json([
                    'status' => false,
                    'data' => [],
                    'message' => 'api_token supply doesn\'t match any record',
                ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
