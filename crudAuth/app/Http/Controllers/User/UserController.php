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
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Response\ResponseController as ResponseController;

class UserController extends ResponseController
{

    function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit', ['only' => ['index', 'show']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserRequest $request)
    {
        $roles = Role::pluck('name', 'name')->all();

        return view('users.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function store(UserRequest $request)
    {
        $input = $request->all();
        $password = Str::random(8);
        $input['password'] = Hash::make($password);

        $user = User::updateOrCreate(['id' => $request->id], $input);
        $user->assignRole($request->input('roles'));

        $email = $input['email'];
        $data = [
            'title'    => "Please Change Your Deafault Password",
            'url'      => "localhost:8000",
            'name'     => $input['name'],
            'password' => $password,
        ];
        Mail::to($email)->send(new SendMail($data));

        if($user){
            return $this->sendResponse($user, 'User retrieved successfully.');
        }else{
            return $this->sendError($user, 'Failed');
        }
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
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)
            ->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
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
        
        return $this->sendResponse([], 'User deleted successfully.');
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
