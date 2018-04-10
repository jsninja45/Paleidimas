<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\File;

class Service extends Model {

	protected $fillable = [
		'slug',
		'parent_service_id',
		'title',
		'short_content',
		'content',
	];

	protected static $image_types = [
		'thumbnail',
	];

	public static function boot()
	{
		parent::boot();

		Service::deleting(function($service) {
			File::delete(Service::imagePath('thumbnail') . '/' . $service->id);
		});
	}

	// ------------------------------------------- relations -----------------------------------------------------------

	public function parent()
	{
		return $this->belongsTo('App\Service', 'parent_service_id');
	}

	public function children()
	{
		return $this->hasMany('App\Service', 'parent_service_id');
	}

	// --------------------------------------------- scopes ------------------------------------------------------------
	public function scopeParents($q)
	{
		$q->whereNull('parent_service_id');
	}


	// ------------------------------------------- methods -------------------------------------------------------------
	public function link()
	{
		return route('service', [$this->id]);
	}

	public function storeOrUpdate($request)
	{
		if ($this->sameSlugExists($request->slug)) {
			return redirect()->back()->withErrors(['slug' => 'This url already exists'])->withInput();
		}

		$input = $request->all();

		if (!$input['parent_service_id']) {
			$input['parent_service_id'] = null;
		}

		$this->fill($input);
		$this->save();

		if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
			$request->file('thumbnail')->move(self::imagePath('thumbnail'), $this->id);
		}

		return redirect()->to(route('admin_service', [$this->id]) . '/edit');
	}

	public  function sameSlugExists($slug)
	{
		$same_slug_exists = self::where('slug', $slug);
		if ($this->id) {
			$same_slug_exists->where('id', '!=', $this->id);
		}
		$same_slug_exists = $same_slug_exists->exists();

		return $same_slug_exists;
	}

	public static function imagePath($type)
	{
		if (!in_array($type, self::$image_types)) {
			print_r('wrong image type'); die(' ' . __FILE__ . ':' . __LINE__);
		}

		if ($type === 'thumbnail') {
			return public_path() . '/img/uploads/services/thumbnails';
		}
	}

	public function imageUrl($type)
	{
		if (!in_array($type, self::$image_types)) {
			print_r('wrong image type'); die(' ' . __FILE__ . ':' . __LINE__);
		}

		return '/img/uploads/services/' . $type . 's/' . $this->id;
	}

	public function hasImage($type)
	{
		if (!in_array($type, self::$image_types)) {
			print_r('wrong image type'); die(' ' . __FILE__ . ':' . __LINE__);
		}

		return File::exists(self::imagePath($type) . '/' . $this->id);
	}

}
