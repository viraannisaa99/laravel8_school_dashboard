<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Illuminate\Support\Str;
use DB;
use Hash;
use DataTables;
use Validator;

class UserController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = Role::pluck('name','name')->all();

        return view('users.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     $roles = Role::pluck('name','name')->all();
    //     return view('users.create',compact('roles'));
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // public function store(Request $request)
    // {
    //     $this->validate($request, [
    //         'name'      => 'required',
    //         'email'     => 'required|email|unique:users,email',
    //         'roles'     => 'required'
    //     ]);

    //     $input = $request->all();
    //     $password = Str::random(8);
    //     $input['password'] = Hash::make($password);

    //     $user = User::create($input);
    //     $user->assignRole($request->input('roles'));

    //     $email = $input['email'];
    //     $data = [
    //         'title'    => "Please Change Your Deafault Password",
    //         'url'      => "localhost:8000",
    //         'name'     => $input['name'],
    //         'password' => $password,
    //     ];
    //     Mail::to($email)->send(new SendMail($data));

    //     return redirect()->route('users.index')
    //                      ->with('success','User created successfully');
    // }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email',
            'roles'     => 'required'
        ]);

        if ($validator->passes()) {
            $input = $request->all();
            $password = Str::random(8);
            $input['password'] = Hash::make($password);

            $user = User::updateOrCreate(['id' => $request->id], $input);
            $user->assignRole($request->input('roles'));

            // $email = $input['email'];
            // $data = [
            //     'title'    => "Please Change Your Deafault Password",
            //     'url'      => "localhost:8000",
            //     'name'     => $input['name'],
            //     'password' => $password,
            // ];
            // Mail::to($email)->send(new SendMail($data));

            return response()->json(['success' => 'Added new user']);
        }

        return response()->json(['error' => $validator->errors()->all()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();

        return view('users.edit',compact('user','roles','userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email,'.$id,
            'password'  => 'same:confirm-password',
            'roles'     => 'required'
        ]);

        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)
                                    ->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
                         ->with('success','User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }

    public function dataTable(Request $request)
    {
        if ($request->ajax()) {
            $data = User::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('roles', function (User $user) {
                    return $user->getRoleNames()->implode(', ');
                })
                ->addColumn('action', function ($user) {
                    return view('users.action', [
                        'user'          => $user,
                        'url_show'      => route('users.show', $user->id),
                        'url_edit'      => route('users.edit', $user->id),
                        'url_destroy'   => route('users.destroy', $user->id)
                    ]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
