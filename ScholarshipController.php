<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class ScholarshipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         
         $scholarships = DB::select('select * from scholarship');
        return view('scholarship.index')
        ->with('scholarships', $scholarships);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('scholarship.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request,[
        'name'=>'required',
        'scholar_amount'=>'required|integer',
        'status'=>'required'
        ]);

        $name               = $request->input('name');
        $description        = $request->input('description');
        $image              = $request->file('image');
        $img_name_original  = $image->getClientOriginalName();
        $img_ext            = $image->getClientOriginalExtension();
        $img_name           = uniqid().".".$img_ext;
        $path               = base_path().'/public/images/upload';
        
        if(!file_exists($path)){
            mkdir($path,0777,true);
        }
        $image->move($path,$img_name);
        $scholar_amount = $request->input('scholar_amount');
        $status         = $request->input('status');
        $created_at     = date("Y-m-d H:i:s");

        DB::insert('insert into scholarship(name,description,image,scholar_amount,status,created_at) values(?,?,?,?,?,?)',[$name,$description,$img_name,$scholar_amount,$status,$created_at]);
        return redirect()->route('scholarship');

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
        $scholarships = DB::select('select * from scholarship where id = ?',[$id]);
        return view('scholarship.edit',['scholarships'=>$scholarships]);
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
         $this->validate($request,[
        'name'=>'required',
        'scholar_amount'=>'required|integer',
        'status'=>'required'
        ]);

        $name               = $request->input('name');
        $description        = $request->input('description');
        $scholar_amount =$request->input('scholar_amount');
        $status         =$request->input('status');
        $updated_at     = date("Y-m-d H:i:s");

        if($request->hasFile('image')){
            $image              = $request->file('image');
            $img_name_original  = $image->getClientOriginalName();
            $img_ext            = $image->getClientOriginalExtension();
            $img_name           = uniqid().".".$img_ext;
            $path               = base_path().'/public/images/upload';
        
            if(!file_exists($path)){
                mkdir($path,0777,true);
            }
            $image->move($path,$img_name);

            DB::update('update scholarship set name = ?, description = ?, image=?, scholar_amount=?, status = ?, updated_at = ? where id = ?',[$name,$description,$img_name,$scholar_amount,$status,$updated_at,$id]);
        }
        else{
            DB::update('update scholarship set name = ?, description = ?, scholar_amount=?, status = ?, updated_at = ? where id = ?',[$name,$description,$scholar_amount,$status,$updated_at,$id]);
        }

        return redirect()->route('scholarship');
    

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
        DB::update('update scholarship set status = ?, updated_at = ? where id = ?',[$status,$updated_at,$id]);

        return redirect()->route('scholarship');
    }
}
