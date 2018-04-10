<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Article;

class ArticleController extends Controller {

	public function index()
	{
		$articles = Article::orderBy('created_at', 'desc')->paginate(9);

		return view('articles.index', compact('articles'));
	}

	public function show($slug)
	{
		$article = Article::where('slug', $slug)->firstOrFail();
		$news = Article::where('id', '!=', $article->id)->orderBy('created_at', 'desc')->limit(3)->get();

		return view('articles.show', compact('article', 'news'));
	}

}
