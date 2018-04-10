<?php namespace App\Http\Controllers;

use App\Article;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;

class AdminBlogController extends Controller {

	use CRUDTrait;

	protected static $model = 'App\Article';
	protected static $route = 'admin/blogs'; // routes
	protected static $views = 'admin.blogs'; // view folder


	public function index()
	{
		$rows = Article::orderBy('created_at', 'desc')->paginate(15);
		$route = self::$route;

		return view('admin.blogs.index', compact('rows', 'route'));
	}

	public function store(Request $request)
	{
		$article = new Article();
		return $article->storeOrUpdate($request);
	}

	public function update(Request $request, $article_id)
	{
		$article = Article::findOrFail($article_id);
		return $article->storeOrUpdate($request);
	}

	public function imageList()
	{
        if (!File::isDirectory(public_path().'/img/uploads/wysiwyg')) {
            File::makeDirectory(public_path().'/img/uploads/wysiwyg');
        }

		$imgList = array();
		$files = File::allfiles(public_path().'/img/uploads/wysiwyg');
		foreach ($files as $file) {

			$explode = explode('/', (string)$file);
			$filename = end($explode);

			$imgList[] = [
				"title"=> $filename,
				"value"=> '/img/uploads/wysiwyg/' . $filename,
			];

		}
		return json_encode($imgList);
	}

	public function upload()
	{
		$valid_exts = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
		$max_size = 2000 * 1024; // max file size (200kb)
		$path = public_path() . '/img/uploads/wysiwyg/'; // upload directory
		$fileName = NULL;
		$file = Input::file('image');
		// get uploaded file extension
		//$ext = $file['extension'];
		$ext = $file->guessClientExtension();
		// get size
		$size = $file->getClientSize();
		// looking for format and size validity
        $name = rand(1, 999999999) . $file->getClientOriginalName();
		if (in_array($ext, $valid_exts) AND $size < $max_size) {
			// move uploaded file from temp to uploads directory
			if ($file->move($path, $name)) {
				$status = 'Image successfully uploaded!';
				$fileName = $name;
			} else {
				return $status = 'Upload Fail: Unknown error occurred!';
			}
		} else {

			return $status = 'Upload Fail: Unsupported file format or It is too large to upload!';
		}

		//echo out script that TinyMCE can handle and update the image in the editor

		?>
		<script>
			top.$('.mce-btn.mce-open').parent().find('.mce-textbox').val('/img/uploads/wysiwyg/<?php echo $fileName; ?>').closest('.mce-window').find('.mce-primary').click();
		</script>
		<?php

	}
}
