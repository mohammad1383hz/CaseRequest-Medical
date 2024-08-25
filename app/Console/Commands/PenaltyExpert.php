<?php

namespace App\Console\Commands;

use App\Models\CaseRequest;
use App\Models\CurrencyConversionRate;
use App\Models\FinancialAccount;
use App\Models\FinancialDocument;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PenaltyExpert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:penalty-expert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $caserequests = CaseRequest::whereHas('assignment')->get();
        foreach ($caserequests as $caserequest) {
            if($caserequest->caseCategoryExpert->haspenalty) {
                if(! $caserequest->assignment->caseReport){
                    $penalty_time=$caserequest->caseCategoryExpert->penalty_time;
                    $penalty_type=$caserequest->caseCategoryExpert->penalty_type;
                    $penalty_value=$caserequest->caseCategoryExpert->penalty_value;

                    $timeAssignCase=$caserequest->assignment->created_at;

                    $currentTime = Carbon::now();

                    $differenceInMinutes = $currentTime->diffInMinutes($timeAssignCase);

                    if($differenceInMinutes > $penalty_time) {

                        $caserequest->assignment->update(['status'=>'canceled']);
                        $user_id=$caserequest->assignment->user_id;
                        $user=User::find($user_id);
                        $financialAccountWallet=FinancialAccount::where('user_id',$user_id)->where('account_type','wallet')->first();
                
                        $financialAccountApp=FinancialAccount::where('account_type','app')->first();

                        $currency=$user->currency_id;
                        $price=$penalty_value;
                        if($currency != 1){
                           $currencyConversionRate= CurrencyConversionRate::where("currency_id",$user->currency_id)->first();
                            $rate=$currencyConversionRate->rate; 
                            $price=$penalty_value*$rate;   
                        }
                
                        FinancialDocument::create([
                        'creditor_id'=>$financialAccountWallet->id,
                        'debtor_id'=>$financialAccountApp->id,
                        // 'description'
                        // 'tracking_code'=>$invoice->invoice_number,
                        'date'=>Carbon::now(),
                        // 'status'=>'paid',
                        'price'=>$price,
                        'currency_id'=>$user->currency_id,
                        // 'invoice_id'=>$invoice->id,
                    ]);

                    //notif for expert












                    }
                }
                
            }
        }
    }
}
