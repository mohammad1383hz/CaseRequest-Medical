<?php

namespace App\Http\Controllers\V1\Expert\Cases;
use App\Http\Controllers\Controller;
use App\Http\Resources\Expert\CaseReportCollection;
use App\Models\CaseAssignment;
use App\Models\CaseFile;
use App\Models\CaseReport;
use App\Models\CaseReportSurvery;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\CaseRequest;
use App\Models\CurrencyConversionRate;
use App\Models\File;
use App\Models\FinancialAccount;
use App\Models\FinancialDocument;
use App\Notifications\CaseReportNotification;
use Illuminate\Validation\ValidationException;

;


class CaseReportController extends Controller
{

    public function __construct()
        {
            $this->middleware('role.expert_or_admin');
        }
    public function index(Request $request)
    {
        $user_id = $request->user()->id;
        $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified
        $tech = $request->query('tech');
        $interpretation = $request->query('interpretation');
        $diagnosis = $request->query('diagnosis');
        $comment = $request->query('comment');
    
        $query = CaseReport::whereHas('assignment', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        });
    
        if ($tech) {
            $query->where('tech', $tech);
        }
        if ($interpretation) {
            $query->where('interpretation', $interpretation);
        }
        if ($diagnosis) {
            $query->where('diagnosis', $diagnosis);
        }
        if ($comment) {
            $query->where('comment', $comment);
        }
    
        $reports = $query->paginate($perPage);
    
        return new CaseReportCollection($reports);
    }



    public function makeCaseReport(Request $request){
        try {
            $validated = $request->validate([
                'case_score'=> 'nullable',
                'report_score'=> 'nullable',
                'tech'=> 'required',
                'interpretation'=> 'required',
                'diagnosis'=> 'required',
                'comment'=> 'required',
                'case_assignment_id'=> 'required|integer',
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $user_id=$request->user()->id;
        $user=User::find($user_id);
        // dd($request['case_assignment_id']);
       $score=$this->calculateScoreTimeCase($request['case_assignment_id']);

       $caseReport=CaseReport::create([

   'case_assignment_id'=>$request['case_assignment_id'],
        'case_score'=>$request['case_score'],
        'report_score'=>$request['report_score'],
        'time_response'=>Carbon::now(),
        'time_response_score'=>$score,
        'tech'=>$request['tech'],
        'interpretation'=>$request['interpretation'],
        'diagnosis'=>$request['diagnosis'],
        'comment'=>$request['comment'],
       ]);
       if($request->file('file')) {
        foreach($request->file('file') as $file){
            $document = new File;
            $file_name = time().'_'.'report'.$file->getClientOriginalName();
            $file_path = $file->storeAs('case_report_file', $file_name, 'public');
            $path='storage/'.$file_path;
            $document->src=$path;
            $document->name=$file_name;
            $document->type='file';
            $document->parent_id=7;
            $document->save();
            $caseFile = new CaseFile;
            $caseFile->file_id =$document->id;
            $caseFile->case_report_id=$caseReport->id;
            $caseFile->save();
            }
    }
       $caseAssignment = CaseAssignment::find($request['case_assignment_id']);

       $case_request=CaseRequest::where('id', $caseAssignment->case_request_id)->first();
       $financialAccountWallet=FinancialAccount::where('user_id',$user_id)->where('account_type','wallet')->first();

       $financialAccountApp=FinancialAccount::where('account_type','app')->first();
   
       $price=$case_request->getTotalPrice() - $case_request->getCommissionPrice();
       if($user->currency_id != 1){
        $currencyConversionRate= CurrencyConversionRate::where("currency_id",$user->currency_id)->first();
         $rate=$currencyConversionRate->rate;    
         $price=$price*$rate;
     }
               FinancialDocument::create([
               'creditor_id'=>$financialAccountApp->id,
               'debtor_id'=>$financialAccountWallet->id,
               // 'description'
               // 'tracking_code'=>$invoice->invoice_number,
               'date'=>Carbon::now(),
               // 'status'=>'paid',
               'price'=>$price,
               'case_request_id'=>$case_request->id,
   
               'currency_id'=>$user->currency_id,
               // 'invoice_id'=>$invoice->id,
           ]);

           $case_request->update([
            'status'=>'done'
          ]);
          $refrence_index=$case_request->caseCategoryExpert->refrence_index;
          if($refrence_index > 1 && $case_request->times_refernced < $refrence_index){
              $case_request->update([
                  'status'=>'referenced'
                ]);
                $case_request->increment('times_referenced');
  
          } 
 
    //send notif
          $usersNotif=User::where('id',$case_request->user_id)->first();
          foreach ($usersNotif as $user) {
            $user->notify(new CaseReportNotification($user));        
        }
       return response()->json(['success' => true, 'message' => 'true','data'=>[$caseReport]], 200);

    }
    public function scoreCase(Request $request,CaseReport $caseReport){
        $user_id=$request->user()->id;

       $caseReport->update([

        'case_score'=>$request['case_score'],


       ]);

       return response()->json(['success' => true, 'message' => 'true','data'=>[$caseReport]], 200);

    }

    public static function calculateScoreTimeCase($case_assignment_id){
        $caseAssignment=CaseAssignment::where('id',$case_assignment_id)->first();
       $createdAt = $caseAssignment->caseRequest->created_at;
        $currentTime = Carbon::now();
        $differenceInMinutes = $currentTime->diffInMinutes($createdAt);

        if ($differenceInMinutes < 10) {
            $score = 10;
        } elseif ($differenceInMinutes >= 11 && $differenceInMinutes < 20) {
            $score = 8;
        } elseif ($differenceInMinutes >= 21 && $differenceInMinutes < 30) {
            $score = 6;
        }elseif ($differenceInMinutes >= 31 && $differenceInMinutes < 40) {
            $score = 4;
        }elseif ($differenceInMinutes >= 41 && $differenceInMinutes < 50) {
            $score = 2;
        }elseif ($differenceInMinutes >= 51 && $differenceInMinutes < 60) {
            $score = 1;
        }else{
            $score = 0;

        }
        return $score;
    }
}







