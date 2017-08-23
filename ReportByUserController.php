<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
class ReportByUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $status         = 1;
        $applications   = DB::select('SELECT a.*,u.name AS user_name,s.name AS scholarship_name, s.descripton AS descripton, s.scholar_amount AS scholar_amount 
                                FROM 
                                applications AS a
                                JOIN users AS u
                                ON a.user_id = u.id
                                JOIN scholarship AS s
                                ON a.scholar_id = s.id
                                WHERE a.status =' .$status);
        //dd($applications);


        $users = DB::select('select * from users where role_id = 2');

        return view('report.report_by_user')
            ->with('users', $users)
            ->with('applications', $applications);
    }

    public function search($type = null)
    {
        if($type == null || $type == 0){
            $applications = DB::select('SELECT a.*,u.name AS user_name,s.name AS scholarship_name 
                                FROM 
                                applications AS a 
                                JOIN users AS u
                                ON a.user_id = u.id
                                JOIN scholarship AS s
                                ON a.scholar_id = s.id');
        }
        else{
            $applications = DB::select('SELECT a.*,u.name AS user_name,s.name AS scholarship_name 
                                FROM 
                                applications AS a 
                                JOIN users AS u
                                ON a.user_id = u.id
                                JOIN scholarship AS s
                                ON a.scholar_id = s.id
                                WHERE a.user_id ='.$type);

        }
        

        $users = DB::select('select * from users where role_id = 2');
        return view('report.report_by_user')
            ->with('users', $users)
            ->with('applications', $applications);
    }

    public function excel($type = null)
    {
        ob_end_clean();
        ob_start();

        if($type == null || $type == 0){
           $applications = DB::select('SELECT a.*,u.name AS user_name,s.name AS scholarship_name 
                                FROM 
                                applications AS a 
                                JOIN users AS u
                                ON a.user_id = u.id
                                JOIN scholarship AS s
                                ON a.scholar_id = s.id');
        }
            else{
                // $applications = DB::select('SELECT r.*,u.name AS user_name,c.name AS course_name 
                //                     FROM 
                //                     applications AS r 
                //                     JOIN users AS u
                //                     ON r.user_id = u.id
                //                     JOIN scholarship AS c
                //                     ON r.scholar_id = c.id
                //                     WHERE r.scholar_id =' .$type);
               $applications = DB::select('SELECT a.*,u.name AS user_name,s.name AS scholarship_name
                                        FROM
                                        applications AS a
                                        JOIN users AS u
                                        ON a.user_id = u.id
                                        JOIN scholarship AS s
                                        ON a.scholar_id = s.id
                                        WHERE a.scholar_id=' .$type); 
                //dd($applications);
                }

                $totalAmount = 0;
                foreach($applications as $application){
                    $totalAmount += $application->scholar_amount;
                }
                Excel::create('applicationreport',function($excel)use($applications,$totalAmount){
                    $excel->sheet('applicationreport',function($sheet)use($applications,$totalAmount){

                        $displayArray = array();
                        $count = 0;
                        foreach($applications as $application){
                            $count++;
                            $displayArray[$application->id]["Scholarship Name"] = 
                            $application->user_name;
                            $displayArray[$application->id]["Scholar Name"] = $application->scholarship_name;
                            $displayArray[$application->id]["Registered at"] = $application->created_at;
                            $displayArray[$application->id]["Scholar Fee"] = $application->scholar_amount;
                        }

                        if(count($displayArray) == 0){
                        $sheet->fromArray($displayArray);
                        }
                        else{
                        $count = $count +2;
                        $sheet->cells('A1:D1', function($cells) {
                            $cells->setBackground('#1976d3');
                            $cells->setFontSize(13);
                            $cells->setFontColor('#ffffff');
                        });
                        $sheet->fromArray($displayArray);

                        $appendedRow = array();

                        $appendedRow[0] = "";
                        $appendedRow[1] = "";
                        $appendedRow[2] = "Grand Total";
                        $appendedRow[3] = $totalAmount;                                        

                        $sheet->appendRow(
                            $appendedRow
                        );
                        $sheet->cells('A'.$count.':D'.$count, function($cells) {
                            $cells->setBackground('#1976d3');
                            $cells->setFontSize(13);
                            $cells->setFontColor('#ffffff');
                        });
                    }
                });
            })
                ->download('xls');
            ob_flush();
            return Redirect();
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
