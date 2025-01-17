<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\cdr;
use Illuminate\Support\Carbon;
use DataTables;
use SebastianBergmann\Environment\Console;

class HomeController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   

	$chart = DB::select("SELECT DATE_FORMAT(calldate,'%d-%m-%Y') cldate,
        count(CASE WHEN disposition = 'ANSWERED' THEN 'SUCCESS' END) AS count_success,
        count(CASE WHEN disposition != 'ANSWERED' THEN 'FAILURE' END) AS count_failure,
        count(disposition) AS count_total
    FROM cdrs 
    where date(calldate) BETWEEN NOW() - INTERVAL 30 DAY AND NOW()
    GROUP BY cldate
    ORDER BY cldate Asc");

       /* if ($request->ajax()) {
 
            return Datatables::of(cdr::query())
            ->addIndexColumn()
            ->filter(function ($instance) use ($request) {
                
                if ($request->get('approved') == 'ANSWERED') {
                    
                    $instance->where('disposition', $request->get('approved'));
                    
                }
                if ($request->get('approved') == 'NO ANSWER') {
                    
                    $instance->whereNotIn('disposition', array('ANSWERED'));
                    
                }
                if (!empty($request->get('to_date')) and !empty($request->get('from_date'))) {
                       $instance->where(function($w) use($request){
                       $from_date = $request->get('from_date');
                       $end_date = $request->get('to_date');
                        $w->whereDate('calldate','>=',$from_date)->whereDate('calldate','<=',$end_date);
                   });
               }
            })
            ->make(true);

        }*/   
            $total_call=cdr::get()->count();
            $total_success_call=cdr::where('disposition','ANSWERED')->count();
            $total_failure_call=cdr::whereNotIn('disposition',['ANSWERED'])->count();
            $datas=[
                'total_call'=>$total_call,
                'total_success'=>$total_success_call,
                'total_failure'=>$total_failure_call,
               // 'percentage'=>$total_success_call/$total_failure_call*100
		'percentage'=>$total_failure_call == 0 ? 0 : ($total_success_call/$total_failure_call)*100


            ];  
            
           // return view('home', compact('datas'));
		
	return view('home', (['datas' => $datas,'chart' => $chart]));
    }

    public function callfile()  
    {
    return view('file-import');
    }

}   
