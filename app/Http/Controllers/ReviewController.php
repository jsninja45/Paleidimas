<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Review;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller {

	public function index(Request $request)
	{

		$reviews = Review::orderBy('created_at', 'desc');
		if ($request->has('rating')) {
			$reviews->where('rating', $request->get('rating'));
		}
		$reviews = $reviews->paginate(6);

		$data = [
			'average_rating' => Review::avg('rating'),
			'rating1' => Review::where('rating', 1)->count(),
			'rating2' => Review::where('rating', 2)->count(),
			'rating3' => Review::where('rating', 3)->count(),
			'rating4' => Review::where('rating', 4)->count(),
			'rating5' => Review::where('rating', 5)->count(),
		];

		return view('reviews.index', compact('reviews', 'data'));
	}

	public function store(Request $request)
	{
		$input = $request->all();
		$input['rating'] = ceil($input['rating']);
		$rules = Review::$rules;

		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			return redirect()->to(route('reviews') . '#write')->withInput()->withErrors($validator->errors());
		}

		$review = new Review();
		$review->fill($input);
		$review->save();
		$review->delete();

		return redirect()->to(route('reviews') . '#ok')->with('message', 'Feedback will be visible after administrator confirmation');
	}

	public function adminIndex()
	{
		$reviews = Review::withTrashed()->orderBy('created_at', 'desc')->paginate(20);

		return view('admin.reviews.index', compact('reviews'));
	}

	public function adminConfirm($review_id)
	{
		Review::withTrashed()->findOrFail($review_id)->restore();

		return redirect()->back();
	}

	public function adminDelete($review_id)
	{
		Review::withTrashed()->findOrFail($review_id)->forceDelete();

		return redirect()->back();
	}

}
