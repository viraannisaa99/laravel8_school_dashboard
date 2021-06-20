<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:student-list|student-create|student-edit', ['only' => ['index', 'show']]);
        $this->middleware('permission:student-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:student-edit', ['only' => ['edit', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    { 
        $users = User::all();
        return view('articles.index', compact('users'));
    }

    public function dataTable(Request $request){
        if ($request->ajax()) {
            $data = Article::latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('users', function (Article $article) {
                        return $article->users->name;
                    })
                    ->addColumn('action', function($article){
                        return view('articles.action', [
                            'article'       => $article,
                            'url_show'      => route('articles.show', $article->id),
                            'url_edit'      => route('articles.edit', $article->id),
                            'url_destroy'   => route('articles.destroy', $article->id)
                        ]);
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
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
    public function store(Request $request)
    {
        Article::updateOrCreate(['id' => $request->id],
                                ['title' => $request->title, 'detail' => $request->detail, 'userId' => Auth::user()->id]);
   
        return response()->json(['success'=>'Article saved successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::findOrFail($id);
        return view('articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = Article::find($id);
        return response()->json($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Article::find($id)->delete();
     
        return response()->json(['success'=>'Article deleted successfully.']);
    }
}
