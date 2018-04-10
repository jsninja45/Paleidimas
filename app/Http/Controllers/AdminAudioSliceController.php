<?php namespace App\Http\Controllers;

use App\Audio;
use App\AudioSlice;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminAudioSliceController extends Controller {

    public function edit($slice_id)
    {
        $slice = AudioSlice::findOrFail($slice_id);

        return view('admin.audio_slices.edit', compact('slice'));
    }

    public function update(Request $request, $slice_id)
    {
        $rules = [
            'transcriber_price_override' => 'numeric|min:0.01',
            'rating' => 'integer|min:1|max:5',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $slice = AudioSlice::findOrFail($slice_id);
        $slice->transcriber_price_override = $request->transcriber_price_override;
        $slice->rating = $request->rating;
        $slice->save();

        return redirect()->route('admin_audio_slices');
    }

}