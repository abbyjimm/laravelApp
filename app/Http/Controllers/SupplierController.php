<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Supplier;
use App\People;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $search = $request->search;
        $criteria = $request->criteria;

        if($search==''){
            $supplier = Supplier::join('people','supplier.id', '=', 'people.id')
                                    ->select('people.id','people.name', 'people.type_document',
                                         'people.num_document','people.address', 'people.telephone',
                                         'people.email','supplier.contact','supplier.contact_tel')
                                    ->orderBy('people.id','desc')
                                    ->paginate(3);
        }else{
            $supplier = Supplier::join('people','supplier.id', '=', 'people.id')
                                    ->select('people.id','people.name', 'people.type_document',
                                            'people.num_document','people.address', 'people.telephone',
                                            'people.email','supplier.contact','supplier.contact_tel')
                                    ->where('people.'.$criteria,'like','%'.$search.'%')
                                    ->orderBy('people.id','desc')
                                    ->paginate(3);
        }        
        
        return [
            'pagination' => [
                'total'         => $supplier->total(),
                'current_page'  => $supplier->currentPage(),
                'per_page'      => $supplier->perPage(),
                'last_page'     => $supplier->lastPage(),
                'from'          => $supplier->firstItem(),
                'to'            => $supplier->lastItem(),                
            ],
            'supplier' => $supplier
        ];
    }
    public function selectSupplier(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $filter = $request->filter;
        $supplier = Supplier::join('people', 'supplier.id','=','people.id')
                            ->where('people.name', 'like','%'.$filter.'%')
                            ->orWhere('people.num_document','like','%'.$filter.'%')
                            ->select('people.id','people.name','people.num_document')
                            ->orderBy('people.name','asc')
                            ->get();
        
        return ['supplier' => $supplier];

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

            $supplier = new Supplier();
            $supplier->contact = $request->contact;
            $supplier->contact_tel = $request->contact_tel;
            $supplier->id = $people->id;
            $supplier->save();
            
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

            $supplier = Supplier::findOrFail($request->id);

            $people = People::findORFail($supplier->id);
            $people->name = $request->name;
            $people->type_document = $request->type_document;
            $people->num_document = $request->num_document;
            $people->address = $request->address;
            $people->telephone = $request->telephone;
            $people->email = $request->email;
            $people->save();

            $supplier->contact = $request->contact;
            $supplier->contact_tel = $request->contact_tel;
            $supplier->save();
            
            DB::commit();

        }catch(Exception $e){
            DB::rollBack();
        } 
    }
}
