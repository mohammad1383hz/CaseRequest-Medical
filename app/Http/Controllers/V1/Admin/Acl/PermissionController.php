<?php

namespace App\Http\Controllers\V1\Admin\Acl;


use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PermissionCollection;
use App\Http\Resources\Admin\PermissionResource;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:permission.index'])->only('index');
        $this->middleware(['permission:permission.store'])->only('store');
        $this->middleware(['permission:permission.show'])->only('show');
        $this->middleware(['permission:permission.update'])->only('update');
        $this->middleware(['permission:permission.destroy'])->only('destroy');
    }


    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified
        $permissions = Permission::paginate($perPage);
        return new PermissionCollection($permissions);
    }


    public function store(Request $request)
    {
   
        try {
            $validated = $request->validate([
                'name'=> 'required',
                'name_fa'=> 'nullable',

          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
       $permission =Permission::create([
            'name' => $request['name'],
            'name_fa' => $request['name_fa'],

            'guard_name' => 'web',
        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$permission]], 200);
    }


    public function show(Permission $permission): PermissionResource
    {
        return new PermissionResource($permission);
    }


    public function update(Request $request, Permission $permission): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name'=> 'required',
                'name_fa'=> 'nullable',

          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $permission->update([
            'name' => $request['name'],
            'name_fa' => $request['name_fa'],

            'guard_name' => 'web',
        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$permission]], 200);
    }


    public function destroy(Permission $permission)
    {
        $permission->delete();

        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);
    }



    public function givePermission(User $user,Request $request)
    {
        // $user->givePermissionTo([$request['permissions']]);
        //bug package
        DB::table('model_has_permissions')->where('model_type', 'App\Models\User')->where('model_id', $user->id)->delete();
        foreach ($request['permissions'] as $permission_id) {
            DB::table('model_has_permissions')->insert([
                'model_type' => 'App\Models\User',
                'model_id' => $user->id,
                'permission_id' => $permission_id,
             
         
            ]);
        }
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);
    }
}
