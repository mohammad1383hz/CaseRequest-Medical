<?php

namespace App\Http\Controllers\V1\Panel\Finance;
use App\Http\Controllers\Controller;
use App\Http\Resources\Panel\CurrencyCollection;
use App\Http\Resources\Panel\UserResource;
use App\Models\CaseRequest;
use App\Models\Coupon;
use App\Models\Currency;
use App\Models\CurrencyConversionRate;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class CouponController extends Controller
{


        public static function checkCoupon(Request $request){
            // dd(9);
            $coupon=Coupon::where('code',$request['coupon_code'])->first();
            $caseRequest=CaseRequest::where('id', $request['case_request_id'])->first();
            $currency_user=User::find($request->user()->id)->currency_id;

            
            $case_category=$caseRequest->categories[0];

            $case_group_id=$case_category->case_group_id;

            $price=$caseRequest->getTotalPrice();
            if($currency_user != 1) {
                $currencyConversionRate= CurrencyConversionRate::where("currency_id",$currency_user)->first();
                $rate=$currencyConversionRate->rate;    
                $price = $price*$rate;
            }
            if(!$coupon){
                return response()->json(['success' => false, 'message' => 'not exist coupon','data'=>$price], 200);

            }
            if (Carbon::now()->gt($coupon->end_date)) {
                return response()->json(['success' => false, 'message' => 'Coupon has expired', 'data' => $price], 200);
            }
            if ($coupon->filter_user) {
                $userIds = $coupon->users->pluck('id')->toArray(); // Get the ids of users associated with the coupon
                $authenticatedUserId = $request->user()->id(); // Get the id of the authenticated user
            
                if (!in_array($authenticatedUserId, $userIds)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Coupon does not belong to you',
                        'data' => $price
                    ], 200);
                }
            }
            
            if($coupon->use_count == $coupon->count){
                    return response()->json(['success' => false, 'message' => 'Coupon does max use count', 'data' => $price], 200);
            }
            // if($coupon->case_category_id){
            //     if ($coupon->case_category_id != $request['case_category_id']) {
            //         return response()->json(['success' => false, 'message' => 'Coupon does not belong to category', 'data' => null], 200);
            //     }
            // }
            if($coupon->case_group_id){
                if ($coupon->case_group_id != $case_group_id) {
                    return response()->json(['success' => false, 'message' => 'Coupon does not belong to group ', 'data' => $price], 200);
                }
            }

            if ($coupon->type === 'static') {
                // اعمال تخفیف ثابت
                $finalPrice = $price - $coupon->discount;
         

                if($currency_user != 1) {
                    $currencyConversionRate= CurrencyConversionRate::where("currency_id",$currency_user)->first();
                    $rate=$currencyConversionRate->rate;    
                    $discount = $coupon->discount*$rate;
                $finalPrice = $price - $discount;

                    // dd($finalPrice);    
                }
            } else {
                // اعمال تخفیف درصدی
                $finalPrice = $price - ($price * $coupon->discount / 100);
            }
            return response()->json([
                'success' => true,
                'message' => 'Discount applied successfully',
                'data' => $finalPrice,
                'coupon' => [
                    'code' => $coupon->code,
                    'discount' => $coupon->discount,
                    'type' => $coupon->type,
                ]
            ], 200);
            





        }

    }







