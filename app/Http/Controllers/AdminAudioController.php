<?php namespace App\Http\Controllers;

use App\Audio;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminAudioController extends Controller {

	public function edit($audio_id)
	{
		$audio = Audio::findOrFail($audio_id);

		return view('admin.audios.edit', compact('audio'));
	}

    public function update(Request $request, $audio_id)
    {
        $rules = [
            'editor_price_override' => 'numeric|min:0.01',
            'comment' => '',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $audio = Audio::findOrFail($audio_id);
        $audio->comment = $request->comment;
        $audio->editor_price_override = $request->editor_price_override;
        $audio->save();

        return redirect()->route('admin_audios');
    }

}
