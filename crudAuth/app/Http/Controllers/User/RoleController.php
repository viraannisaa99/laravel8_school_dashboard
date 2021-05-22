<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use DataTables;
use Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the role.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the role.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('roles.index');
    }

    /**
     * Show the form for creating a new role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        return view('roles.create', compact('permission'));
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'       => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully');
    }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name'       => 'required|unique:roles,name',
    //         'permission' => 'required',
    //     ]);

    //     if($validator->passes()){
    //         $role = Role::updateOrCreate(['name' => $request->input('name')]);
    //         $role->syncPermissions($request->input('permission'));

    //         return response()->json(['success'=>'Added new roles']);
    //     }

    //     return response()->json(['error'=>$validator->errors()->all()]);
    // }
    /**
     * Display the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);

        //join roles table with role_has_permissions table (seeders: PermissionTableSeeder.php)
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('roles.show', compact('role', 'rolePermissions'));
    }

    /**
     * Show the form for editing the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permission = Permission::get();

        $rolePermissions = DB::table("role_has_permissions")
            ->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('roles.edit', compact('role', 'permission', 'rolePermissions'));
    }

    /**
     * Update the specified role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'       => 'required',
            'permission' => 'required',
        ]);

        $role           = Role::find($id);
        $role->name     = $request->input('name');
        $role->save();
        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index')
                         ->with('success', 'Role updated successfully');
    }
    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table("roles")->where('id', $id)
                          ->delete();
        return redirect()->route('roles.index')
                         ->with('success', 'Role deleted successfully');
    }

    public function dataTable()
    {
        $role = Role::query();
        return Datatables::of($role)
            ->addIndexColumn()
            ->addColumn('action', function ($role) {
                return view('roles.action', [
                    'role'          => $role,
                    'url_show'      => route('roles.show', $role->id),
                    'url_edit'      => route('roles.edit', $role->id),
                    'url_destroy'   => route('roles.destroy', $role->id)
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
