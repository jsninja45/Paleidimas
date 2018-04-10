<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Language;
use Illuminate\Support\Facades\File;

class LanguageController extends Controller {

	public function create()
	{
		$language = new Language;

		return view('languages.edit', compact('language'));
	}

	public function store(Request $request)
	{
		$language = new Language;
        self::storeOrUpdate($language, $request);

		return redirect()->route('admin_config');
	}

	public function edit($id)
	{
		$language = Language::findOrFail($id);

		return view('languages.edit', compact('language'));
	}

	public function update(Request $request, $id)
	{
		$language = Language::findOrFail($id);
        self::storeOrUpdate($language, $request);

		return redirect()->route('admin_config');
	}

	public function destroy($id)
	{
		Language::destroy($id);

		return redirect()->back();
	}

    public function image($language_id)
    {
        $language = Language::findOrFail($language_id);
        $image_path = $language->imagePath();
        if (File::exists($image_path)) {
            return response()->download($image_path);
        }
    }


    public static function storeOrUpdate(Language $language, Request $request)
    {
        $language->fill($request->all());
        if (!$request->has('hide')) {
            $language->hide = 0;
            $language->save();
        }
        $language->save();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            File::delete($language->imagePath());
            $request->file('image')->move($language->imagePath(true), $language->id);
        }
    }

}
