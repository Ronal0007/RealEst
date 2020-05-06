<?php

namespace App\Http\Controllers;

use App\Gender;
use App\Jobs\ActivityLogJob;
use App\Permission;
use App\Role;
use App\Status;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->granted('user_view')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $users = User::paginate(15);
        $roles = Role::all();

        //Logging
        $activity = [
            'name'=>'User',
            'description'=>'Viewing users',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity));

        return view('user.index',compact('users','roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->granted('user_add')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $roles = Role::pluck('name','id');
        $statuses = Status::pluck('name','id');
        $genders = Gender::pluck('name','id');

        //Logging
        $activity = [
            'name'=>'User',
            'description'=>'Attempting to add new user',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity));

        return view('user.create',compact('users','roles','statuses','genders'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->granted('user_add')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $this->validate($request,[
            'fname'=>'required',
            'lname'=>'required',
            'email'=>'required',
            'phone' => 'required',
            'role_id'=>'required|min:1',
            'status_id'=>'required|min:1',
            'gender_id'=>'required|min:1'
        ]);
        $input =  $request->all();
        $input['password'] = Hash::make($request->lname);
        $user = User::create($input);
        $this->assignPermissionPerRole($user);

        //Logging
        $activity = [
            'name'=>'User',
            'description'=>'New user added',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity,$user));

        return redirect(route('user.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        if (!auth()->user()->granted('user_edit')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $user = User::whereSlug($slug)->first();
        $roles = Role::pluck('name','id');
        $statuses = Status::pluck('name','id');
        $genders = Gender::pluck('name','id');

        //Logging
        $activity = [
            'name'=>'User',
            'description'=>'Attempting to edit user',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity,$user));

        return view('user.edit',compact('user','genders','roles','statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        if (!auth()->user()->granted('user_edit')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $this->validate($request,[
            'fname'=>'required',
            'lname'=>'required',
            'email'=>'required',
            'phone' => 'required',
            'role_id'=>'required|min:1',
            'status_id'=>'required|min:1',
            'gender_id'=>'required|min:1'
        ]);
        $user = User::whereSlug($slug)->first();
        $oldUser = $user;
        if ($request->get('role_id')!=$user->role->id){
            $user->update($request->except(['_method','_token']));
            $this->assignPermissionPerRole(User::whereSlug($slug)->first());
        }else{
            $user->update($request->except(['_method','_token']));
        }

        //Logging
        $activity = [
            'name'=>'User',
            'description'=>'User updated',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now(),
            'old'=>$oldUser
        ];

        $this->dispatch(new ActivityLogJob($activity,$user));

        return redirect(route('user.index'))->with('message','User ('.$user->name.') updated!');
    }

    /**
     * Remove user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        if(!auth()->user()->granted('user_delete')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }

        $user = User::whereSlug($slug)->first();
        $user->permissions()->delete();
        $user->status_id=2;
        $user->save();

        //Logging
        $activity = [
            'name'=>'User',
            'description'=>'Delete user',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now(),
            'old'=>$user
        ];

        $this->dispatch(new ActivityLogJob($activity));

        $user->delete();
        return $user;
    }

    /**
     * View user permissions
     *
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function permission($slug){
        if (!auth()->user()->granted('user_permission')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }
        $user = User::whereSlug($slug)->first();

        //Logging
        $activity = [
            'name'=>'User',
            'description'=>'View user Permission(s)',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity,$user));

        return view('user.permission',compact('user'));
    }

    /**
     * Grant User permissions
     *
     * @param Request $request
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function grant(Request $request,$slug){
        if (!auth()->user()->granted('user_permission')){
            return abort(403,'Unauthorized request, Contact Administrator');
        }

        $user = User::whereSlug($slug)->first();
        $user->permissions()->delete();
        $permissions = $request->except('_token');
        if (count($permissions)<1){
            return redirect()->back()->with('message','Grant atleast one permission!');
        }
        $this->assignPermissionPerRole($user,$permissions);

        //Logging
        $activity = [
            'name'=>'User',
            'description'=>'User permission changed',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now(),
            'old'=>$permissions
        ];

        $this->dispatch(new ActivityLogJob($activity,$user));

        return redirect(route('user.permission',$user->slug))->with('pmessage','User Permission updated!');
    }

    /**
     * Assign Permission to user
     *
     * @param $user
     * @param array|null $permissions
     */
    protected function assignPermissionPerRole($user,array $permissions = null){
        $role_name = $user->role->name;
        $user->permissions()->delete();  //remove previous permissions
        if (empty($permissions)){
            $data_entry = [
                'project_view','project_add','locality_view','locality_add','block_view','block_add','plot_view','plot_add'
            ];
            $manager = array_merge($data_entry,['user_view','control_view','defaulter_view','constant_view']);
            $land = ['control_view','payment_view'];
            switch($role_name){
                case "admin":
                    break;
                case "manager":
                    foreach ($manager as $permission) {
                        $perm = new Permission(['name'=>$permission]);
                        $user->permissions()->save($perm);
                    }
                    break;
                case "dataentry":
                    foreach ($data_entry as $permission) {
                        $perm = new Permission(['name'=>$permission]);
                        $user->permissions()->save($perm);
                    }
                    break;
                case "land":
                    foreach ($land as $permission) {
                        $perm = new Permission(['name'=>$permission]);
                        $user->permissions()->save($perm);
                    }
                    break;
            }
        }else{
            foreach ($permissions as $permission => $value) {
                $perm = new Permission(['name'=>$permission]);
                $user->permissions()->save($perm);
            }
        }
    }

    /**
     * View user change password form
     *
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function password($slug){
        $user = User::whereSlug($slug)->first();

        return view('auth.password',compact('user'));
    }


    /**
     * Reset user password
     *
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword($slug){   //reset password
        $user = User::whereSlug($slug)->first();
        $user->password = Hash::make(strtolower($user->lname));
        $user->save();



        //Logging
        $activity = [
            'name'=>'User',
            'description'=>'User password reseted',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity,$user));

        return redirect()->back()->with('message','Password Reseted!');
    }

    /**
     * Changes user password
     *
     * @param Request $request
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function changePassword(Request $request,$slug){
        $this->validate($request,[
            'password'=>'required',
            'confirm_password'=>'required'
        ]);
        if ($request->get('confirm_password')!=$request->get('password')){
            return redirect()->back()->withErrors(['password'=>'Password Mismatch']);
        }

        $user = User::whereSlug($slug)->first();
        $user->password = Hash::make($request->get('password'));
        $user->save();



        //Logging
        $activity = [
            'name'=>'User',
            'description'=>'User changed password',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity,$user));

        return redirect(route('home'));
    }


    /**
     * Print users permissions
     *
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function printPermission($slug){
        $user = User::whereSlug($slug)->first();
        $data = array();
        $p = array();
        foreach ($user->permissions()->pluck('name') as $permission) {
            $data[] = array_reverse(explode('_',$permission));
        }
        foreach ($data as $item) {
            $p[] = ucfirst($item[0]).' '.($item[1]=='control'?'Sale':$item[1]);
        }

        $permissions = collect($p);



        //Logging
        $activity = [
            'name'=>'User',
            'description'=>'Printing user permissions',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];

        $this->dispatch(new ActivityLogJob($activity,$user));

        return view('user.printpermission',compact('user','permissions'));
    }
}
