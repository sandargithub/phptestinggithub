<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
class ApplicationHistory extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loginUser = Auth::user();
        $id = $loginUser->id;
        $status = 1;

        $applications = DB::select('SELECT a.*,u.name AS user_name, s.name AS scholarship_name,s.description AS description, s.scholar_amount AS scholar_amount,s.image AS image
                                FROM 
                                applications AS a
                                JOIN users AS u
                                ON a.user_id = u.id
                                JOIN scholarship AS s
                                ON a.scholar_id = s.id
                                WHERE a.user_id ='. $id);
       //dd($applications);
        $users = DB::select('select*from users where role_id=2');
        //dd($users);
        return view('history.index')
            ->with('users',$users)
            ->with('applications',$applications);
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
    public function destroy($id)
    {
        //
    }
}
