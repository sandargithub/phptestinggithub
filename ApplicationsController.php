<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
class ApplicationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $status = 1;
        $applications = DB::select('SELECT a.*,u.name AS user_name ,s.name AS scholarship_name,s.description AS description, s.image AS image
                                    FROM applications AS a
                                    JOIN users AS u
                                    ON a.user_id = u.id
                                    JOIN scholarship AS s
                                    ON a.scholar_id = s.id
                                    wHERE a.status =' . $status);

        return view('application.index')
        ->with('applications',$applications);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $scholarships = DB::select('select * from scholarship');
        // dd($scholarships);
        return view('application.create')->with('scholarships',$scholarships);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $loginUser = Auth::user();

        if (isset($loginUser) && count($loginUser)>0) {
            $scholar_id = $request->input('scholar_id');
             
            $user_id = $loginUser->id;
            $registeredFlag = DB::select('select*from applications where scholar_id=? AND user_id=?',[$scholar_id,$user_id]);
            // dd($registeredFlag);
            
            if (isset($registeredFlag) && count($registeredFlag)>0) {
                echo "Opp!<br>You already registered to scholarship</br>";
                echo "<a href='/application'>Go To Register Page</a>";
            } 
                else{
                   $scholarship = DB::select('select * from scholarship where id = ' . $scholar_id);
                   //  dd($scholarship);
                    $scholar_amount = $scholarship[0]->scholar_amount;
                    $created_at = date("Y-m-d H:i:s");

                    DB::insert('insert into applications (scholar_id, user_id,scholar_amount, created_at) values (?,?,?,?)',[$scholar_id,$user_id,$scholar_amount, $created_at]);
                    echo "Record inserted successfully.<br/>";
                    echo '<a href="/application">Click Here</a> to go back.';

               }
            }
        else{
                echo "<br> Opp! you did not login!</br> Please login to register";
                echo "<a href='/login'>Go to Login!</a>";
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
        $applications = DB::select('SELECT a.* ,u.name AS user_name , s.name AS scholarship_name
                                    FROM applications AS a
                                    JOIN users AS u
                                    ON a.user_id = u.id
                                    JOIN scholarship AS s
                                    ON a.scholar_id = s.id
                                    wHERE a.id = ? ',[$id]);
        $scholarships = DB::select('select * from scholarship');
        return view('application.edit')
        ->with('applications',$applications)
        ->with('scholarships',$scholarships);
       // dd($scholarships);

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
        $scholar_id = $request->input('scholar_id');
        $scholarship = DB::select('select * from scholarship where id = '. $scholar_id);
        // dd($scholarship);
        $scholar_amount = $scholarship[0]->scholar_amount;
        $updated_at = date("Y-m-d H:i:s");

         $applications=DB::update('update applications set scholar_id=?,scholar_amount=?,updated_at=? where id=?',[$scholar_id,$scholar_amount,$updated_at,$id]);
         
        echo "<b>Record updateed successfully</b><br>";
        echo "<a href='/application'>Click Here!</a><b>to go back</b>";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = 0;
        $updated_at = date("Y-m-d H:i:s");
        DB::update('update applications set status=?, updated_at=? where id=?',[$status,$updated_at,$id]);
        echo "<b>Record deleted successfully!</b>";
        echo "<a href='/application'>Click Here</a><b>to go back</b>";
    }
}
