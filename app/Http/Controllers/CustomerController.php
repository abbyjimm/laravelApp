<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\People;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $search = $request->search;
        $criteria = $request->criteria;

        if($search==''){
            $people = People::orderBy('id','desc')->paginate(3); //doesn-t need "use Illuminate\Support\Facades\DB;"
        }else{
            $people = People::where($criteria,'like','%'.$search.'%')->orderBy('id','desc')->paginate(3);
        }
        
        
        return [
            'pagination' => [
                'total'         => $people->total(),
                'current_page'  => $people->currentPage(),
                'per_page'      => $people->perPage(),
                'last_page'     => $people->lastPage(),
                'from'          => $people->firstItem(),
                'to'            => $people->lastItem(),                
            ],
            'people' => $people
        ];
    }
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $people = new People();
        $people->name = $request->name;
        $people->type_document = $request->type_document;
        $people->num_document = $request->num_document;
        $people->address = $request->address;
        $people->telephone = $request->telephone;
        $people->email = $request->email;
        $people->save();
    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $people = People::findOrFail($request->id);
        $people->name = $request->name;
        $people->type_document = $request->type_document;
        $people->num_document = $request->num_document;
        $people->address = $request->address;
        $people->telephone = $request->telephone;
        $people->email = $request->email;
        $people->save();
    }
}
