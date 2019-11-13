<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Articles;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $search = $request->search;
        $criteria = $request->criteria;

        if($search==''){
            $articles = Articles::join('categories', 'articles.idcategory','=','categories.id')
            ->select('articles.id','articles.idcategory','articles.code','articles.name','categories.name as name_category','articles.sale_price','articles.stock','articles.description','articles.condition')
            ->orderBy('articles.id','desc')
            ->paginate(3); 
        }else{
            $articles = Articles::join('categories', 'articles.idcategory','=','categories.id')
            ->select('articles.id','articles.idcategory','articles.code','articles.name','categories.name as name_category','articles.sale_price','articles.stock','articles.description','articles.condition')
            ->where('articles.'.$criteria,'like','%'.$search.'%')
            ->orderBy('articles.id','desc')
            ->paginate(3);            
        }
        
        
        return [
            'pagination' => [
                'total'         => $articles->total(),
                'current_page'  => $articles->currentPage(),
                'per_page'      => $articles->perPage(),
                'last_page'     => $articles->lastPage(),
                'from'          => $articles->firstItem(),
                'to'            => $articles->lastItem(),                
            ],
            'articles' => $articles
        ];
    }
    public function listArticles(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $search = $request->search;
        $criteria = $request->criteria;

        if($search==''){
            $articles = Articles::join('categories', 'articles.idcategory','=','categories.id')
            ->select('articles.id','articles.idcategory','articles.code','articles.name','categories.name as name_category','articles.sale_price','articles.stock','articles.description','articles.condition')
            ->orderBy('articles.id','desc')
            ->paginate(10); 
        }else{
            $articles = Articles::join('categories', 'articles.idcategory','=','categories.id')
            ->select('articles.id','articles.idcategory','articles.code','articles.name','categories.name as name_category','articles.sale_price','articles.stock','articles.description','articles.condition')
            ->where('articles.'.$criteria,'like','%'.$search.'%')
            ->orderBy('articles.id','desc')
            ->paginate(10);            
        }
        
        
        return ['articles' => $articles];
    }
    public function searchArticle(Request $request){
        if(!$request->ajax()) return redirect('/');

        $filter = $request->filter;

        $articles = Articles::where('code','=', $filter)
                            ->select('id', 'name')
                            ->orderBy('name', 'asc')
                            ->take(1)
                            ->get();

        return ['articles'=> $articles];
    }
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $article = new Articles();
        $article->idcategory = $request->idcategory;
        $article->code = $request->code;
        $article->name = $request->name;
        $article->sale_price = $request->sale_price;
        $article->stock = $request->stock;
        $article->description = $request->description;
        $article->condition = '1';
        $article->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $article = Articles::findOrFail($request->id);
        $article->idcategory = $request->idcategory;
        $article->code = $request->code;
        $article->name = $request->name;
        $article->sale_price = $request->sale_price;
        $article->stock = $request->stock;
        $article->description = $request->description;
        $article->condition = '1';
        $article->save();
    }

    public function disable(Request $request){
        if (!$request->ajax()) return redirect('/');
        $article = Articles::findOrFail($request->id);
        $article->condition = '0';
        $article->save();
    }

    public function able(Request $request){
        if (!$request->ajax()) return redirect('/');
        $article = Articles::findOrFail($request->id);
        $article->condition = '1';
        $article->save();
    }
}
