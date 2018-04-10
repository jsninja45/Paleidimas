<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use App\UserRating;
use Illuminate\Support\Facades\File;

class Language extends Model {

	protected $fillable = [
		'name',
        'hide',
	];

	// --------------------------------------- relationships -----------------------------------------------------------
	public function users() {
		return $this->belongsToMany('App\User');
	}

    // -------------------------------------scopes ---------------------------------------------------------------------

    public function scopeNotHidden($q)
    {
        $q->where('hide', 0);
    }

    // ---------------------------------------- methods ----------------------------------------------------------------

    public function imagePath($no_file_name = false)
    {
        $path = storage_path() . '/app/flags';

        if ($no_file_name) {
            return $path;
        }

        $path .= '/' . $this->id;

        return $path;
    }

    public static function imageName($name)
    {
        return str_slug($name, '_');
    }

    public function imageUrl()
    {
        return route('languages.image', [$this->id]);
    }

    public function hasImage()
    {
        if (!$this->id) {
            return false;
        }

        return File::exists(Language::imagePath());
    }

}
