<?php

namespace App\Http\Controllers\V1\Admin\Currency;
use App\Http\Controllers\Controller;

use App\Http\Resources\Admin\CouponCollection;
use App\Http\Resources\Admin\CouponResource;

use App\Http\Resources\Panel\CurrencyConversionRateCollection;
use App\Http\Resources\Panel\CurrencyConversionRateResource;
use App\Models\Currency;
use App\Models\CurrencyConversionRate;
use App\Models\FinancialAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CurrencyConversionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:currencyConversion.index'])->only('index');
        $this->middleware(['permission:currencyConversion.store'])->only('store');
        $this->middleware(['permission:currencyConversion.show'])->only('show');
        $this->middleware(['permission:currencyConversion.update'])->only('update');
        $this->middleware(['permission:currencyConversion.destroy'])->only('destroy');

    }
    public function index(Request $request)
    {
        $sortBy = $request->query('sort_by', 'id'); // Default to sorting by 'id' if not specified
        $currencyConversionRate = CurrencyConversionRate::orderBy($sortBy, 'desc')->get();
        return new CurrencyConversionRateCollection($currencyConversionRate);
    }
    public function show(CurrencyConversionRate $currencyConversion){

        return new CurrencyConversionRateResource($currencyConversion);

    }

    public function store(Request $request){
        try {
            $validated = $request->validate([
                'rate'=> 'required',
                'currency_id'=> 'required',
               
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $currencyConversionRate=CurrencyConversionRate::create([

            'rate'=>$request['rate'],
            'currency_id'=>$request['currency_id'],

        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$currencyConversionRate]], 200);
    }

    public function update(Request $request,CurrencyConversionRate $currencyConversion){
        try {
            $validated = $request->validate([
                'rate'=> 'required',
                'currency_id'=> 'required',
               
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $currencyConversion->update([
            'rate'=>$request['rate'],
            'currency_id'=>$request['currency_id'],

        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$currencyConversion]], 200);
    }
    public function destroy(CurrencyConversionRate $currencyConversion){
        $currencyConversion->delete();
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);

    }
    }







