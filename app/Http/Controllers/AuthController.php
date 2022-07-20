<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\UrlTrait;
use Illuminate\Support\Facades\Http;
use Validator;

class AuthController extends Controller
{
    use UrlTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.auth.index');
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
        Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ])->validate();
        $login = Http::post($this->url_dynamic() . 'auth', [
            'email' => $request->email,
            'password' => $request->password
        ]);

        $login = json_decode($login->body());
        if($login->success) {
            
            $request->session()->put('token', $login->credentials->token);
            $request->session()->put('email', $login->credentials->email);
            $request->session()->put('full_name', $login->credentials->full_name);
            $request->session()->put('userId', $login->credentials->userId);

            return redirect()->route('public.index')->with('success', 'Welcome, ' . $login->credentials->full_name)->with('status', $login->message);
        } else {
            return redirect()->back()->with('error', $login->message);
        }
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
    public function destroy(Request $request)
    {
		$logout = Http::post($this->url_dynamic() . 'auth/logout', [
            'jwtToken' =>  $request->session()->get('token')
        ]);
        $logout = json_decode($logout->body());
        if ($logout->success){
            session()->flush();
            return redirect()->route('auth.index')->with('status', $logout->message)->with('success', 'Success');
        } else {
            return redirect()->route('auth.index')->with('status', $logout->message);
        }
    }
}
