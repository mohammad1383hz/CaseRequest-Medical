<?php

namespace App\Console\Commands;

use App\Models\CaseRequest;
use App\Models\FinancialAccount;
use App\Models\FinancialDocument;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CaseRequestCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:case-request-check';

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
        $sevenDaysAgo = Carbon::now()->subDays(7);

    $caserequests = CaseRequest::where('status', 'submitted')->get();

    foreach ($caserequests as $caserequest) {
        // Check if invoiceItem exists
        if ($caserequest->invoiceItem) {
            $invoiceItemCreatedAt = Carbon::parse($caserequest->invoiceItem->created_at);
            
            if ($invoiceItemCreatedAt->greaterThan($sevenDaysAgo)) {
                $caserequest->update(['status'=>'canceling']);
                $invoice=Invoice::where('id',$caserequest->invoiceItem->invoice_id)->first();
                $user=User::where('id',$invoice->user_id)->first();
                $invoice->update(['status'=> 'canceled']);
                $financialAccountApp=FinancialAccount::where('account_type','app')->first();
                $financialAccountWallet=FinancialAccount::where('user_id',$invoice->user_id)->where('account_type','wallet')->first();
                FinancialDocument::create([
                    'creditor_id'=>$financialAccountApp->id,
                    'debtor_id'=>$financialAccountWallet->id,
                    // 'description'
                    // 'tracking_code'=>$invoice->invoice_number,
                    'date'=>Carbon::now(),
                    'status'=>'paid',
                    'price'=>$invoice->invoice_payable,
                    'currency_id'=>$user->currency_id,
                    'invoice_id'=>$invoice->id,
                ]);
            } 
        }
    }
    
    }
}
