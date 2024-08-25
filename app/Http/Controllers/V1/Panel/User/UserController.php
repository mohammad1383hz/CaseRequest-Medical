<?php

namespace App\Http\Controllers\V1\Panel\User;
use App\Http\Controllers\Controller;
use App\Http\Controllers\V1\Notification\SmsController;
use App\Http\Resources\Panel\UserResource;
use App\Mail\Verify;
use App\Models\CaseFile;
use App\Models\File;
use App\Models\FinancialAccount;
use App\Models\Otp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    public function register(Request $request){
        $validated = $request->validate([
            'first_name'=> 'required',
            'last_name'=> 'required',
            'email'=> 'nullable',
            'phone'=> 'required',
            'password'=> 'required',
            'city'=> 'required',
            'language'=> 'required',
            'role'=> 'required',
            'group_id'=> 'required',

            // 'files' => 'required|array|min:2|max:3', // Ensure files is an array and contains between 2 to 20 elements
            'national_cart' => 'required|file', // Ensure each file in the array is present and a file
            'img_1' => 'nullable|file', // Ensure each file in the array is present and a file
            'img_2' => 'nullable|file', // Ensure each file in the array is present and a file


            // 'group_id'=> 'required',
            // 'currency_id'=> 'required',
        ]);
        $user=User::where('phone',$request->phone)->orWhere('email',$request->email)->first();
        if($user){
            return response()->json(['success' => true, 'message' => 'user_exist'], 200);

        }
        $national_cart=$request->file('national_cart');


        $document = new File;

        $file_name = time().'_'.'img_1'.$national_cart->getClientOriginalName();
        $file_path = $national_cart->storeAs('national_cart', $file_name, 'public');
        $path_national_cart='storage/'.$file_path;
        $document->src=$path_national_cart;
        $document->type='file';
        $document->name=$file_name;
        $document->format=$national_cart->getClientOriginalName();

        $document->parent_id=5;
        $document->save();

    
        $user=User::create([
            'first_name'=>$request['first_name'],
            'last_name'=>$request['last_name'],
            'email'=>$request['email'],
            'phone'=>$request['phone'],
            'city'=>$request['city'],
            'password' => Hash::make($request['password']),
            'currency_id'=>$request['currency_id'],
            'language'=>$request['language'],

            'country_id'=>$request['country_id'],
            'group_id'=>$request['group_id'],
            'national_cart_id'=>$document->id,


        ]);
        $FinancialAccount= FinancialAccount::create([
            'user_id'=>$user->id,
            'account_type'=>'user',
            'name'=>$user->last_name,
        ]);
        $FinancialAccount= FinancialAccount::create([
            'user_id'=>$user->id,
            'account_type'=>'wallet',
            'name'=>$user->last_name,
            'currency_id'=>$request['currency_id'],

        ]);

       
        $role=$request->role;
        if($request->role== 'user'){
        $user->assignRole('user');

        }
        if($request->role== 'expert'){
        $user->assignRole('expert');
        $img_1=$request->file('img_1');
        $img_2=$request->file('img_2');




        $document_img_1 = new File;

        $file_name = time().'_'.'img_1'.$img_1->getClientOriginalName();
        $file_path = $img_1->storeAs('img_1', $file_name, 'public');
        $path_img_1='storage/'.$file_path;
        $document_img_1->src=$path_img_1;
        $document_img_1->type='file';
        $document_img_1->name=$file_name;
        $document_img_1->parent_id=3;
        $document_img_1->format=$img_1->getClientOriginalName();

        $document_img_1->save();

        $document_img_2 = new File;
        $file_name = time().'_'.'img_2'.$img_2->getClientOriginalName();
        $file_path = $img_2->storeAs('img_2', $file_name, 'public');
        $path_img_2='storage/'.$file_path;
        $document_img_2->src=$path_img_2;
        $document_img_2->type='file';
        $document_img_2->format=$img_2->getClientOriginalName();

        $document_img_2->name=$file_name;
        $document_img_2->parent_id=4;
        $document_img_2->save();



              
        

        $user->update([
            'img_1'=>$document_img_1->id,
            'img_2'=>$document_img_2->id,

        ]);

        }

        // $user->sendEmailVerificationNotification();
       
        try {
            if($user->email){
                Mail::to($user->email)->send(new Verify($user));
            }
        } catch (\Exception $e) {
            // در اینجا می‌توانید خطا را دستکاری کرده و یا گزارش دهید
            Log::error('خطا در ارسال ایمیل: ' . $e->getMessage());
        }

        // $user=User::where('phone',$request->phone)->first();
        $otp=Otp::where('phone',$request->phone)->delete();
        $otp = rand(10000, 99999);
        $token=SmsController::send($request->phone,$otp);

        //send sms and save code
        // $token=SmsController::getToken();
        // $token=json_decode($token);
        // $TokenKey=$token->TokenKey;
        // dd($TokenKey);
        // $data = [
        //     "body" => [
        //         "ParameterArray" => [
        //             ["Parameter" => "code", "ParameterValue" => $otp]
        //         ],
        //         "phone" => $request->mobile,
        //         "TemplateId" => "77808"
        //     ]
        // ];
        // //send sms
        // $token=SmsController::sendSms($data);

        Otp::create([
        'code'=>$otp,
        'phone'=>$user->phone,
        ]);









        // $token = $user->createToken($request->server('HTTP_USER_AGENT'));
        // $data = [
        //     'token' => $token->plainTextToken,
        //     'permissions' => $user->getPermissionNames(),
        //     'roles' => $user->getRoleNames(),
        // ];
        return response()->json(['success' => true, 'message' => 'send_sms','data'=>null], 200);
    }
    public function checkotp(Request $request){
        $validated = $request->validate([
            'phone' => 'required',
            'code'=>'required',
        ]);

        $user=User::where('phone',$request->phone)->first();
        $otp=Otp::where('phone',$request->phone)->first();
        if(!$otp){
            return response()->json(['success' => false, 'message' => 'Unauthorized otp code'],200);
        }
        if($otp->code !==$request->code){
            return response()->json(['success' => false, 'message' => 'Unauthorized otp code'],200);
        }
        if (!$user && $otp->code==$request->code){
            return response()->json(['success' => true, 'message' => 'not exist user redirect register user'], 200);
        }
        if ($user){
            $user->update(['is_phone_verified'=>Carbon::now()]);
            $otp->delete();
            $token = $user->createToken($request->server('HTTP_USER_AGENT'));
          
            //            $token->accessToken->ip = $request->json()->get('ip');
            //            $token->accessToken->platform = $request->json()->get('platform');
            //            $token->accessToken->save();
            //
                        $data = [
                            'token' => $token->plainTextToken,
                           'user' => $user,
            //                'roles' => auth()->user()->getRoleNames(),
                        ];
            //            $user=auth()->attempt($validated);
            return response()->json(['success' => true, 'message' => 'true','data'=>$data], 200);

        }


    }
    public function update(Request $request){
        $user_id=$request->user()->id;
        $user=User::where('id',$user_id)->first();



        try {
            $validated = $request->validate([
                'first_name'=> 'required',
                'last_name'=> 'required',
                'email'=> 'nullable',
                'phone'=> 'nullable',
                'password'=> 'nullable',
                'city'=> 'nullable',
                'currency_id'=> 'nullable',
                'language'=> 'nullable',
                // 'group_id'=> 'required',
                // 'currency_id'=> 'required',
            ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }

        if($request->hasFile('national_cart')){
            $national_cart=$request->file('national_cart');
            $document = new File;

            $file_name = time().'_'.'img_1'.$national_cart->getClientOriginalName();
            $file_path = $national_cart->storeAs('national_cart', $file_name, 'public');
            $path_national_cart='storage/'.$file_path;
            $document->src=$path_national_cart;
            $document->type='file';
            $document->name=$file_name;
            $document->parent_id=5;
            $document->save();
        $user->update([
        'src_national_cart'=>$path_national_cart,
          
        ]);
    }
        $user->update([
            'first_name'=>$request['first_name'],
            'last_name'=>$request['last_name'],
            'email'=>$request['email'],
            'phone'=>$request['phone'],
            'city'=>$request['city'],
            'currency_id'=>$request['currency_id'],
            'country_id'=>$request['country_id'],

            'language'=>$request['language'],


            'password' => Hash::make($request['password'])
        ]);
        if($user->role== 'user'){
            $user->assignRole('user');
    
            }
            if($request->role== 'expert'){
            $user->assignRole('expert');
            $path_img_1=null;
            $path_img_2=null;
        if($request->hasFile('img_1')){
            $img_1=$request->file('img_1');





            $document = new File;

            $file_name = time().'_'.'img_1'.$img_1->getClientOriginalName();
            $file_path = $img_1->storeAs('img_1', $file_name, 'public');
            $path_img_1='storage/'.$file_path;
            $document->src=$path_img_1;
            $document->type='file';
            $document->name=$file_name;
            $document->parent_id=3;
            $document->save();
        }
        if($request->hasFile('img_2')){
            $img_2=$request->file('img_2');
            $document = new File;
            $file_name = time().'_'.'img_2'.$img_2->getClientOriginalName();
            $file_path = $img_2->storeAs('img_2', $file_name, 'public');
            $path_img_2='storage/'.$file_path;
            $document->src=$path_img_2;
            $document->type='file';
            $document->name=$file_name;
            $document->parent_id=4;
            $document->save();
    
        }




           
            $user->update([
                'src_img_1'=>$path_img_1,
                'src_img_2'=>$path_img_2,
    
            ]);
    
            }
        return response()->json(['success' => true, 'message' => 'true','data'=>[$user] ], 200);
    }
    public function show(Request $request){
        $user_id=$request->user()->id;
        $user=User::where('id',$user_id)->first();
        return new UserResource($user);
    }

    public function uploadProfile(Request $request){
        $user_id=$request->user()->id;
        $user=User::where('id',$user_id)->first();
        if($request->file('file')) {
            $file=$request->file('file');
                $document = new File;
                $file_name = time().'_'.'avatar'.$file->getClientOriginalName();
                $file_path = $file->storeAs('avatar', $file_name, 'public');
                $path='storage/'.$file_path;
                $document->src=$path;
                $document->name=$file_name;
                $document->type='file';
                $document->parent_id=2;
                $document->save();


        }
        $user->update(['avatar_file_id'=>$file_path]);
        return new UserResource($user);
    }
    }







