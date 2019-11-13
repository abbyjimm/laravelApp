<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\People;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $search = $request->search;
        $criteria = $request->criteria;

        if($search==''){
            $users = User::join('people','users.id', '=', 'people.id')
                        ->join('roles','users.idrole', '=', 'roles.id')
                        ->select('people.id','people.name', 'people.type_document',
                                'people.num_document','people.address', 'people.telephone',
                                'people.email','users.user','users.password', 'users.condition',
                                'users.idrole', 'roles.name as role')
                        ->orderBy('people.id','desc')
                        ->paginate(3);
        }else{
            $users = User::join('people','users.id', '=', 'people.id')
                        ->join('roles','users.idrole', '=', 'roles.id')
                        ->select('people.id','people.name', 'people.type_document',
                                'people.num_document','people.address', 'people.telephone',
                                'people.email','users.user','users.password', 'users.condition',
                                'users.idrole', 'roles.name as role')
                        ->where('people.'.$criteria,'like','%'.$search.'%')
                        ->orderBy('people.id','desc')
                        ->paginate(3);
        }        
        
        return [
            'pagination' => [
                'total'         => $users->total(),
                'current_page'  => $users->currentPage(),
                'per_page'      => $users->perPage(),
                'last_page'     => $users->lastPage(),
                'from'          => $users->firstItem(),
                'to'            => $users->lastItem(),                
            ],
            'users' => $users
        ];
    }
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();
            $people = new People();
            $people->name = $request->name;
            $people->type_document = $request->type_document;
            $people->num_document = $request->num_document;
            $people->address = $request->address;
            $people->telephone = $request->telephone;
            $people->email = $request->email;
            $people->save();

            $user = new User();
            $user->user = $request->user;
            $user->password = bcrypt($request->password);
            $user->condition = '1';
            $user->idrole = $request->idrole;
            $user->id = $people->id;
            $user->save();
            
            DB::commit();

        }catch(Exception $e){
            DB::rollBack();
        }        
    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction(); 

            $user = User::findOrFail($request->id);

            $people = People::findORFail($user->id);
            $people->name = $request->name;
            $people->type_document = $request->type_document;
            $people->num_document = $request->num_document;
            $people->address = $request->address;
            $people->telephone = $request->telephone;
            $people->email = $request->email;
            $people->save();

            $user->user = $request->user;
            $user->password = bcrypt($request->password);
            $user->condition = '1';
            $user->idrole = $request->idrole;
            $user->save();
            
            DB::commit();

        }catch(Exception $e){
            DB::rollBack();
        } 
    }
    public function disable(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condition = '0';
        $user->save();
    }
    public function able(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condition = '1';
        $user->save();
    }
}
