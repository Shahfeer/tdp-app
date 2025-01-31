<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\cdr;
use Illuminate\Support\Carbon;
use DataTables;
use DB;
use SebastianBergmann\Environment\Console;

class ReportController extends Controller
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
    public function detailReport(Request $request)
    {   
        if ($request->ajax()) {
 
            return Datatables::of(cdr::query())
            ->addIndexColumn()
            ->filter(function ($instance) use ($request) {
                
                if ($request->get('detail_approved') == 'ANSWERED') {
                    
                    $instance->where('disposition', $request->get('detail_approved'));
                    
                }
                if ($request->get('detail_approved') == 'NO ANSWER') {
                    
                    $instance->whereNotIn('disposition', array('ANSWERED'));
                    
                }
                if (!empty($request->get('detail_to_date')) and !empty($request->get('detail_from_date'))) {
                       $instance->where(function($w) use($request){
                       $detail_from_date = $request->get('detail_from_date');
                       $detail_end_date = $request->get('detail_to_date');
                        $w->whereDate('calldate','>=',$detail_from_date)->whereDate('calldate','<=',$detail_end_date)
			  ->orderBy('id', 'desc');
                        
                   });
               }
            })
            ->make(true);

        }  

        return view ('detailreport');
    }

 
public function summaryReport(Request $request)
    { 

if ($request->ajax()) {
 
        return Datatables::of(cdr::query())
        ->addIndexColumn()
        ->filter(function ($call_date) use ($request) {
            
            
            if (!empty($request->get('summary_to_date')) and !empty($request->get('summary_from_date'))) {
                   
                   $summary_from_date = $request->get('summary_from_date');
                   $summary_end_date = $request->get('summary_to_date');
                   $call_date->selectRaw('calldate')
			->selectRaw('DATE(calldate) AS cdate')
                	->selectRaw("count(CASE WHEN disposition = 'ANSWERED' THEN 'SUCCESS' END) AS count_success")
                	->selectRaw("count(CASE WHEN disposition != 'ANSWERED' THEN 'FAILURE' END) AS count_failure")
                	->selectRaw("count(disposition) AS count_total")
			->groupBy(DB::raw('DATE(calldate)'))
                	->whereDate('calldate','>=',$summary_from_date)->whereDate('calldate','<=',$summary_end_date)
			->pluck('cdate');	
			
			//->selectRaw('calldate')
                    //->selectRaw('DATE(calldate) AS cdate')
                   // ->groupBy(DB::raw('DATE(calldate)'))
                    //->whereBetween('calldate', [$summary_from_date, $summary_end_date])
                    //->pluck('cdate');      
              
           }
        })
        ->editColumn('calldate', function($data){ $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->calldate)->format('Y-m-d'); return $formatedDate; })
        
          ->addColumn('total_call', function($total_call) use ($request){
                                     
              
         return $total_call->count_total;
      })
      
      ->addColumn('total_success', function($total_success) use ($request){

        return $total_success->count_success;
    })
    
    ->addColumn('total_failure', function($total_failure) use ($request) {

      return $total_failure->count_failure;
  })  

  ->rawColumns(['total_call','total_success', 'total_failure'])
      ->make(true);

    }  

return view ('summaryreport');


}




public function callfile()  
    {
    return view('file-import');
    } 


}   
