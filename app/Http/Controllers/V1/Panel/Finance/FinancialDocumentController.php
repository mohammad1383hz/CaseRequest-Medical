<?php

namespace App\Http\Controllers\V1\Panel\Finance;
use App\Http\Controllers\Controller;



use App\Http\Resources\Panel\FinancialDocumentCollection;
use App\Http\Resources\Panel\FinancialDocumentResource;
use App\Models\FinancialDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class FinancialDocumentController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['permission:financialaccount.index'])->only('index');
        // $this->middleware(['permission:financialaccount.store'])->only('store');
        // $this->middleware(['permission:financialaccount.show'])->only('show');
        // $this->middleware(['permission:financialaccount.update'])->only('update');
        // $this->middleware(['permission:financialaccount.destroy'])->only('destroy');

    }
    public function index(Request $request)
    {
        $user_id = $request->user()->id;
        $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified
    
        $financialDocuments = FinancialDocument::where(function ($query) use ($user_id) {
            $query->whereHas('creditorAccount', function ($subQuery) use ($user_id) {
                $subQuery->where('user_id', $user_id);
            })->orWhereHas('debtorAccount', function ($subQuery) use ($user_id) {
                $subQuery->where('user_id', $user_id);
            });
        })->paginate($perPage);
    
        return new FinancialDocumentCollection($financialDocuments);
    }
    public function show(FinancialDocument $financialDocument){
        return new FinancialDocumentResource($financialDocument);

    }

   
    }







