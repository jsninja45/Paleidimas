<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Article extends Model {

	protected $fillable = [
		'title',
		'slug',
		'content',
	];

	public static function boot()
	{
		parent::boot();

		Article::deleting(function($article) {
			File::delete(Article::imagePath() . '/' . $article->id);
		});
	}

	public static function addArticle($data)
	{
		$slug = str_slug($data['title']);

		$slug_exists = Article::where('slug', $slug)->exists();
		while ($slug_exists) {
			$slug = $slug . '1';
			$slug_exists = Article::where('slug', $slug)->exists();
		}

		$article = new Article;
		$article->slug = $slug;
		$article->title = $data['title'];
		$article->content = $data['content'];
		return $article->save();
	}

	public function link()
	{
		return route('post', [$this->slug]);
	}

	public function storeOrUpdate($request)
	{
		if ($this->sameSlugExists($request->slug)) {
			return redirect()->back()->withErrors(['slug' => 'This url already exists'])->withInput();
		}

		$this->fill($request->all());
		$this->save();

		if ($request->hasFile('image') && $request->file('image')->isValid()) {
			$request->file('image')->move(self::imagePath(), $this->id);
		}

		return redirect()->to(route('admin_blog', [$this->id]) . '/edit');
	}

	public  function sameSlugExists($slug)
	{
		$same_slug_exists = Article::where('slug', $slug);
		if ($this->id) {
			$same_slug_exists->where('id', '!=', $this->id);
		}
		$same_slug_exists = $same_slug_exists->exists();

		return $same_slug_exists;
	}

	public static function imagePath()
	{
		return public_path() . '/img/uploads/blogs/';
	}

	public function imageUrl()
	{
		return '/img/uploads/blogs/' . $this->id;
	}

	public function hasImage()
	{
		return File::exists(Article::imagePath() . '/' . $this->id);
	}

}
