<?php

namespace App\Http\Controllers\V1\Admin\Acl;


use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PermissionCollection;
use App\Http\Resources\Admin\PermissionResource;
use App\Http\Resources\Admin\RoleCollection;
use App\Http\Resources\Admin\RoleResource;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:role.index'])->only('index');
        $this->middleware(['permission:role.store'])->only('store');
        $this->middleware(['permission:role.show'])->only('show');
        $this->middleware(['permission:role.update'])->only('update');
        $this->middleware(['permission:role.destroy'])->only('destroy');
    }


    public function index(Request $request): RoleCollection
    {
        $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified
        $roles = Role::paginate($perPage);
        return new RoleCollection($roles);
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


        $role=Role::create([
            'name' => $request['name'],
            'name_fa' => $request['name_fa'],
            'guard_name' => 'web',

        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$role]], 200);

    }


    public function show(Role $role): RoleResource
    {
        return new RoleResource($role);
    }


    public function update(Request $request, Role $role): JsonResponse
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


        $role->update([
            'name' => $validated['name'],
            'name_fa' => $validated['name_fa'],

            'guard_name' => 'web',
        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$role]], 200);
    }


    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);
    }

    public function giveRole(User $user,Request $request)
    {
        $user->syncRoles($request['roles']);
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);
    }
    public function givePermissionToRole(Role $role,Request $request)
    {
        // dd($role);
        DB::table('role_has_permissions')->where('role_id', $role->id)->delete();
        foreach ($request['permissions'] as $permission_id) {
            DB::table('role_has_permissions')->insert([
                'role_id' => $role->id,
                'permission_id' => $permission_id,
             
    
            ]);
        }
       
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);
    }
}
