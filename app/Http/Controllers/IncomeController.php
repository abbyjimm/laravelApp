<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Income;
use App\DetailIncome;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $search = $request->search;
        $criteria = $request->criteria;

        if($search==''){
            $income = Income::join('people','income.idsupplier', '=', 'people.id')
                        ->join('users','income.iduser', '=', 'users.id')
                        ->select('income.id','income.type_voucher', 'income.series_voucher',
                                'income.num_voucher','income.date_time', 'income.tax',
                                'income.total','people.name','users.user')
                        ->orderBy('income.id','desc')
                        ->paginate(3);
        }else{
            $income = Income::join('people','income.idsupplier', '=', 'people.id')
                        ->join('users','income.iduser', '=', 'users.id')
                        ->select('income.id','income.type_voucher', 'income.series_voucher',
                                'income.num_voucher','income.date_time', 'income.tax',
                                'income.total','people.name','users.user')
                        ->where('income.'.$criteria,'like','%'.$search.'%')
                        ->orderBy('income.id','desc')
                        ->paginate(3);
        }        
        
        return [
            'pagination' => [
                'total'         => $income->total(),
                'current_page'  => $income->currentPage(),
                'per_page'      => $income->perPage(),
                'last_page'     => $income->lastPage(),
                'from'          => $income->firstItem(),
                'to'            => $income->lastItem(),                
            ],
            'income' => $income
        ];
    }
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();

            $mytime = Carbon::now('America/Monterrey');

            $income = new Income();
            $income->idsupplier = $request->idsupplier;
            $income->iduser = \Auth::user()->id;;
            $income->type_voucher = $request->type_voucher;
            $income->series_voucher = $request->series_voucher;
            $income->num_voucher = $request->num_voucher;
            $income->date_time = $mytime->toDateString();
            $income->tax = $request->tax;
            $income->total = $request->total;
            $income->status = 'Registrado';
            $income->save();

            $detailIncome = $request->data;

            foreach($detailIncome as $ep=>$det)
            {
                $detail = new DetailIncome();
                $detail->idincome = $income->id;
                $detail->idarticles = $det['idarticles'];
                $detail->quantity = $det['quantity'];
                $detail->price = $det['price'];
                $detail->save();
            }

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }        
    }
    public function disable(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $income = Income::findOrFail($request->id);
        $income->status = 'Anulado';
        $income->save();
    }
}
