<?php
namespace App\Http\Controllers;
use App\Events\WebSocketDataEvent;
use App\Models\Call;
use App\Models\cdr;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\resources\views\createcampaignblade;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use SebastianBergmann\Environment\Console;
set_time_limit(0);


class CreatecampaignController extends Controller
{


  //create import

  public function fileImportExport()
    {
       return view('file-import');
    }


    public function sendMessage(Request $request)
    {
/*	if(Auth::id() == '') {
                return redirect()->route('login');
        }

         Log::info('Submit button clicked at '.now());

        $input_file_path = config('app.input_file_path');
        $output_file_path = config('app.output_file_path');
        $time_interval = config('app.time_interval');
        $file_count = config('app.file_count');
//import excel or csv file method
        $validated = $request->validate([
            'file' => 'required'
        ]);



	//Get the variable from blade to import the date picker value into database
        $schedule = $request->get('schedule_at');
        $schedule_at = date("Y-m-d H:i:s", strtotime($schedule));
        $curdtm = date("YmdHi");
        $context = $request->get('context');
        $caller_id = $request->get('caller_id');


        $txt_max_retry_count = $request->get('txt_max_retry_count');
        $txt_retry_time = $request->get('txt_retry_time');

        Excel::import(new UsersImport($request->schedule_at, $request->context,$caller_id, $time_interval, $file_count, $request->input_file_path, $request->output_file_path, $request->context."_".$curdtm), $re$

         $request->file('file')->store('obd_call');

         Log::info('Import the file successfully into database at'.now());
$data = Call::where('flag','=','i')->get();

        $count = Call::where('flag','=','i')->count();


       $queryArray=[];

        for($i = 0;$i<$count;$i++)
        {

            $filename=$data[$i]->mobile;

            $query = [
                "Action" => "DialOutboundIVR",
                "ActionID" => "test1235",
                "OutTo" => $filename,
                "FromExt" => $caller_id,
                "IvrID" => "7000",
            ];

            $queryArray[] = $query;

        }
$msg = 'Campaign -'.$request->context."_".$curdtm.' Created and Scheduled Sucessfully. After complete this Campaign, then you can create new Campaign!!';

        return Redirect::to('createcampaign')->with('success', $msg)->with('queryArray', $queryArray);  */

$requestData = ["Action" => "DialOutboundIVR", "ActionID" => "test1235", "OutTo" => "9025167792", "FromExt" => "8002", "IvrID" => "7000"];



        if (!empty($requestData)) {
//	dd($queryArray);
        event(new \App\Events\NeronRequestEvent($requestData));
        Log::info("Neron Request Event Sent: " . json_encode($requestData));
       // event(new NeronResponseEvent($data));


        //$msg = 'Campaign -'.$request->context."_".$curdtm.' Created and Scheduled Sucessfully. After complete this Campaign, then you can create new Campaign!!';

        //return Redirect::to('createcampaign')->with('success', $msg)->with('queryArray', $queryArray);

        $msg = 'Campaign - Created and Scheduled Sucessfully. After complete this Campaign, then you can create new Campaign!!';


        return Redirect::to('createcampaign')->with('success', $msg);

    }
else {

        dd("error");
}


    }


    public function saveReceivedMessage(Request $request)
    {
     	// $message = new cdr();
        // $message->message = $request->input('message');
        // $message->save();

        $messageContent = $request->input('message');

        $messageData = json_decode($messageContent, true);
$src = $messageData['data']['src'];
        $dst = $messageData['data']['dst'];
        $start = $messageData['data']['start'];
        $callid = $messageData['data']['callid'];
        $direction = $messageData['data']['direction'];
        $disposition = $messageData['data']['disposition'];
        $billsec = $messageData['data']['billsec'];
        $recordurl = $messageData['data']['recordurl'];
        $answer = $messageData['data']['answer'];
        $end = $messageData['data']['end'];

        // Create a new ReceivedMessage instance and save it to the database
        cdr::create([
            'src' => $src,
            'dst' => $dst,
            'start' => $start,
            'callid' => $callid,
            'direction' => $direction,
            'disposition' => $disposition,
            'billsec' => $billsec,
            'recordurl' => $recordurl,
            'answer' => $answer,
            'end' => $end,
        ]);

	return response()->json(['success' => true]);
    }

    public function fileExport()
    {
     	return Excel::download(new UsersExport, 'users-collection.xlsx');
    }

public function createCampaign()
    {
     	return view ('createcampaign');
    }

    public function cancel()
    {
     	$command="pkill -9 Copy";
        shell_exec($command);

        return view('createcampaign');
    }

}

