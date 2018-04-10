<?php

// calls delete on every collection element
function deleteAll($model_collection) {
	foreach ($model_collection as $unit) {
		$unit->delete();
	}
}

function deleteOne($eloquent_object) {
	if ($eloquent_object) {
		$eloquent_object->delete();
	}
}

function test() {
	echo 'testing';
}

// time left to certain date
// 1 hour
// 3 days
// 45 minutes
function timeLeft($datetime) {
	$diff = leftSeconds($datetime);

	return duration2($diff);
}

function leftSeconds($datetime) {
	return strtotime($datetime) - time();
}

// 3 days 33 minutes 3 seconds
function timeLeftFull($left_seconds) {
	if ($left_seconds < 0) {
		return 'Past due (' . timeLeftFull(-$left_seconds) . ' days)';
	}

	$seconds = $left_seconds;

	$days = floor($seconds / 86400);
	$seconds %= 86400;

	$hours = floor($seconds / 3600);
	$seconds %= 3600;

	$minutes = floor($seconds / 60);
	$seconds %= 60;

	return "$days days $hours hours $minutes minutes $seconds seconds";
}

function timeLeftFullWithoutSeconds($left_seconds) {
	if ($left_seconds < 0) {
        return 'Past due (' . timeLeftFullWithoutSeconds(-$left_seconds) . ')';
	}

	$seconds = $left_seconds;

	$days = floor($seconds / 86400);
	$seconds %= 86400;

	$hours = floor($seconds / 3600);
	$seconds %= 3600;

	$minutes = floor($seconds / 60);
	$seconds %= 60;

	return "$days days $hours hours $minutes minutes";
}

// seconds to human
// 1 hour
// 3 days
// 45 minutes
function duration2($duration_in_seconds) {
	if ($duration_in_seconds < 0) {
		$diff_days = abs(round($duration_in_seconds / (3600 * 24)));
		return 'Past due (' . $diff_days . ' days)';
	}

	if ($duration_in_seconds > 3600 * 24) {
		$days = $duration_in_seconds / (3600 * 24);
		return round($days) . ' days';
	}

	if ($duration_in_seconds > 3600) {
		$hours = $duration_in_seconds / (3600);
		return round($hours) . ' hours';
	}

	if ($duration_in_seconds > 60) {
		$minutes = $duration_in_seconds / (60);
		return round($minutes) . ' minutes';
	}
}

// 00:00:00
function secondsToTime($total_seconds) {
	//$output = '';

	$total_seconds = round($total_seconds);

	$hours = 0;
	if ($total_seconds >= 3600) {
		$hours = floor($total_seconds / 3600);
		//$output .= $hours . ' h ';
	}

	$minutes = 0;
	if ($total_seconds >= 60) {
		$minutes = floor(($total_seconds - $hours * 3600) / 60);
		//$output .= $minutes . ' min ';
	}

	$seconds = $total_seconds  - $hours * 3600 - $minutes * 60;
	//$output .= $seconds . ' s';

	return str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT) . ':' . str_pad($seconds, 2, '0', STR_PAD_LEFT);
}


/**
 * Formats date like: April 21st, 2015
 *
 * @param $date 2000-01-01
 * @return bool|string
 */
function owl_date($date) {
	return date('F jS, Y', strtotime($date));
}

// 23h 15m 6s
function duration($total_seconds) {
	$total_seconds = round($total_seconds);
	$output = '';

	$hours = 0;
	if ($total_seconds > 3600) {
		$hours = floor($total_seconds / 3600);
		$output .= $hours . 'h ';
	}

	$minutes = 0;
	if ($total_seconds > 60) {
		$minutes = floor(($total_seconds - $hours * 3600) / 60);
		$output .= $minutes . 'm ';
	}

	$seconds = $total_seconds  - $hours * 3600 - $minutes * 60;
	$output .= $seconds . 's';

//	return str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT) . ':' . str_pad($seconds, 2, '0', STR_PAD_LEFT);
	return $output;
}


function filterBySlug($collection, $slug)
{
	return $collection->filter(function($row) use ($slug)
	{
		echo $row->slug;

		if ($row->slug === $slug) {
			return true;
		}
	});
}

function fileExtension($filename) {
	$parts = explode('.', $filename);
	$extension = end($parts);

	return $extension;
}

// 12:32:23 -> 42344
function decodeHumanTime($human_time) {
	$parts = explode(':', trim($human_time));

	if (count($parts) !== 3) {
		return false;
	}

	$hours = $parts[0];
	$minutes = $parts[1];
	$seconds = $parts[2];

	$total_seconds = $hours * 3600 + $minutes * 60 + $seconds;

	return $total_seconds;
}

/**
 * Get video data. Null on error
 *
 * @param $video_url
 * @param $api_key
 * @return Audio|null
 */
function youtubeAudio($video_url) {

	$api_key = env('YOUTUBE_API_KEY');

	// video id from url
	$parsed_link = parse_url($video_url);
	if ($parsed_link['host'] === 'www.youtube.com') {
		parse_str(parse_url($video_url, PHP_URL_QUERY), $get_parameters);
		$video_id = $get_parameters['v'];
	} elseif ($parsed_link['host'] === 'youtu.be') {
		$video_id = substr($parsed_link['path'], 1);
	} else {
		return null;
	}

	// video json data
	$json_result = file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=snippet,contentDetails&id=$video_id&key=$api_key");
	$result = json_decode($json_result, true);

	// video duration data
	if (!count($result['items'])) {
		return null;
	}

	// duration
	$duration_encoded = $result['items'][0]['contentDetails']['duration'];
	$interval = new DateInterval($duration_encoded);
	$seconds = $interval->days * 86400 + $interval->h * 3600 + $interval->i * 60 + $interval->s;

	// filename
	$filename = $result['items'][0]['snippet']['title'];

	$audio = new App\Audio;
	$audio->original_duration = $seconds;
	$audio->original_filename = $filename;
	$audio->url = $video_url;

	return $audio;
}


/**
 * Vimeo video details
 *
 * @param $video_url
 * @return Audio|null
 */
function vimeoAudio($video_url) {

	$video_id = (int)substr(parse_url($video_url, PHP_URL_PATH), 1);

	$json_url = 'http://vimeo.com/api/v2/video/' . $video_id . '.xml';

	$ch = curl_init($json_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$data = curl_exec($ch);
	curl_close($ch);

	if (!isXML($data)) {
		return null;
	}

	$data = new SimpleXmlElement($data, LIBXML_NOCDATA);

	if (!isset($data->video->duration)) {
		return null;
	}

	$duration = $data->video->duration;
	$title = $data->video->title;

	$audio = new App\Audio;
	$audio->original_duration = $duration;
	$audio->original_filename = $title;
	$audio->url = $video_url;

	return $audio;
}

/**
 * Converts UTC time to user timezone time
 *
 * Takes PHP date, then outputs JavaScript code
 * JavaScript function converts this output to user time
 *
 * @param $php_datetime
 * @param $js_time_format
 * @return string
 */
function timezoned($php_datetime, $js_time_format) {
	$js_datetime = date('m/d/Y H:i:s', strtotime($php_datetime)) . ' UTC'; // '6/29/2011 4:52:48 PM UTC'

	return '<span data-time="' . $js_time_format . '">' . $js_datetime . '</span>';
}

function styleEmailContent($html)
{
	$styled_html = str_replace('<h1 style=""', '<h1 style="margin-bottom: 30px; margin-top: 30px; text-align: center; font-size: 23px; font-weight: bold; line-height: 32px;"', $html);
	$styled_html = str_replace('<h2 style=""', '<h2 style="margin: 30px 0 20px 0; font-size: 19px; font-weight: bold;"', $styled_html);
	$styled_html = str_replace('<p style=""', '<p style="margin: 0 0 15px 0; line-height: 22px; text-align: justify;"', $styled_html);
	$styled_html = str_replace('<p center style=""', '<p style="margin: 0 0 15px 0; line-height: 22px; text-align: center;"', $styled_html);
	$styled_html = str_replace('<a style=""', '<a style="color: #7ab900;"', $styled_html);
	$styled_html = str_replace('<table style=""', '<table width="100%" border="0" cellpadding="5" cellspacing="0" style="margin-bottom: 15px; border: 1px solid green;"', $styled_html);

	return $styled_html;
}

function styleEmailFooter($html)
{
	$styled_html = str_replace('<h1 style=""', '<h1 style="margin: 0; font-size: 22px; color: white; font-weight: bold; text-align: left;"', $html);
	$styled_html = str_replace('<p style=""', '<p style="margin: 20px 0 20px 0; font-size: 17px; color: white; text-align: left; line-height: 24px;"', $styled_html);
	$styled_html = str_replace('<a style=""', '<a target="_blank" style="color: #FFFFFF;"', $styled_html);

	return $styled_html;
}

function emailButton($href, $text) {
	?>

	<p style="margin: 40px 0; text-align: center;">
		<a target="_blank" style="padding: 15px 25px; background-color: #f9b000; border: none; border-radius: 3px; color: #fff; font-size: 20px; font-weight: 600; text-decoration: none; box-shadow: 0 3px 0 0 #e1a004; " href="<?php echo $href; ?>"><?php echo strtoupper($text); ?></a>
   	</p>

	<?php
}

function isXML($data) {
	return strpos(trim($data), '<?xml') === 0;
}

// current url without parameters
function currentUrl() {
	return strtok($_SERVER["REQUEST_URI"],'?');
}

// calculate editor deadline for audio editing
// give 5 or 10 times more time than file duration
function editorDeadlineAt(\App\Audio $audio) {
    $duration_in_minutes = ceil($audio->duration / 60); // in minutes
    if ($duration_in_minutes < 30) {
        $time_multiplier = 10;
    } else { // more than 30 minutes
        $time_multiplier = 5;
    }
    $plus_minutes = $duration_in_minutes * $time_multiplier;
    if ($plus_minutes < 60) { // give at least 1 hour
        $plus_minutes = 60;
    }

    $deadline_at = date('Y-m-d H:i:s', strtotime(' +' . $plus_minutes . ' minute'));

    return $deadline_at;
}

// prefix positive number
function prefixNumberWithSign($number) {
	if ($number > 0) {
		$number = '+' . $number;
	}

	return $number;
}