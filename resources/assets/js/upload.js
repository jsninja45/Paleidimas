// show progress
function showProgressFile() {
	var file_container = $(".js-file-container");
	var count = file_container.find(".js-file-progress").length;

	if (!count) {
		// add file
		var html = "<div class='js-file-progress'>" + $(".js-file-blueprint").html() + "</div>";
		file_container.prepend(html);
	}
}

// add file
function addFile(data, file_uploaded) {

	var no_files = $('.js-no-files');
	no_files.hide();

	var file_container = $(".js-file-container");

		if (file_uploaded) {
			data['progress'] = '';
			data['tick'] = '<span class="icon uploaded-icon"></span>';
			data['play'] = '<a target="_blank" href="' + data['download'] + '"><span class="icon play-icon"></span></a>';
		} else {
			data['progress'] = '<div class="upload-progress js-progress"><div class="js-progress-bar" style="width:0;"></div></div>';
			data['tick'] = '<span class="icon uploading-icon inactive"></span>';
			data['play'] = '<span class="icon play-icon inactive"></span>';
		}

		if (!file_uploaded) {
			data['comment_img'] = '<span class="icon comment-icon inactive"></span>';
		} else if (data['comment'] == "") {
			data['comment_img'] = '<span class="icon comment-icon js-toggle-comment"></span>';
		} else {
			data['comment_img'] = '<span class="icon comment-icon js-toggle-comment comment-done-icon"></span>';
		}

		if (!file_uploaded) {
			data['cut_img'] = '<span class="icon trim-icon inactive"></span>';
		} else if (data['from'] == 0 && data['till'] == data['original_duration']) {
			data['cut_img'] = '<span class="icon trim-icon js-toggle-duration"></span>';
		} else {
			data['cut_img'] = '<span class="icon trim-icon js-toggle-duration trim-done-icon"></span>';
		}

		// add file
		var html = "<div class='js-file' data-id='::id::'>" + $(".js-file-blueprint").html() + "</div>";

		// set variables
		html =  setData(html, data);
		file_container.prepend(html);
}

// delete file

// set length
// set comment





function setData(html, data) {
	$.each(data, function(k, content) {
		var tag = "::" + k + "::";
		var r = new RegExp(tag, 'g'); // g - all occurrences

		html = html.replace(r, escapeHtml(content));
	});

	$.each(data, function(k, content) {
		var tag = "!!" + k + "!!";
		var r = new RegExp(tag, 'g'); // g - all occurrences

		html = html.replace(r, content);
	});

	return html;
}

function escapeHtml(string) {
	var entityMap = {
		"&": "&amp;",
		"<": "&lt;",
		">": "&gt;",
		'"': '&quot;',
		"'": '&#39;',
		"/": '&#x2F;'
	};

	return String(string).replace(/[&<>"'\/]/g, function (s) {
		return entityMap[s];
	});
}