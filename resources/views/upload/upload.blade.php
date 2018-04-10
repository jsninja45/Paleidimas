@extends('app')

@section('title'){{ 'Create order' }} @stop

@section('scripts')
    @parent

    <script>
        // paste url (popover)
        (function() {
            $(function () {
                // submit
                $("body").on('click', '.js-add-links', function () {
                    var $that = $(this);
                    var buttonText = $that.text();
                    $that.text($that.data('loading-text'));

                    var links = $(this).parent().find('textarea').val();
                    var request = $.post('{{ url('upload/add-links') }}', {_token: "{{ csrf_token() }}", links: links});
                    request.done(function (data) {
                        $("#pasteURLButton").popover('hide');
                        $that.parent().find('textarea').val('');
                        $that.text(buttonText);

                        $.each(data, function (k, audio) {
                            addFile(audio, true);
                        });

                        recalculatePrice();
                    });
                });
            });
        })(jQuery);
    </script>
@stop

@section('content')
    <div class="upload-body">
        <div class="container">

            <h3 class="heading">Create your transcription order</h3>

            <div class="upload-header">
                <div class="button-medium orange button-upload button-icon fileinput-button">
                    <span class="icon upload-icon"></span>
                    Upload files
                    <input id="fileupload" type="file" name="files[]" accept="audio/*,video/*" multiple> {{-- accept=".mp3,.avi" --}}
                </div>

                <div class="button-medium button-paste button-icon" id="pasteURLButton" data-toggle="popover" data-placement="bottom" data-class="textarea-popup" data-target="#uploadByURL">
                    <span class="icon link-icon"></span>
                    Paste URL
                </div>

                <div class="clearfix visible-sm"></div>

                @include('upload.partial.progress', ['step' => 1])

                <div id="uploadByURL" style="display:none">
                    <textarea class="form-control" placeholder="Paste enter URLs (one line per URL)"></textarea>
                    <div class="button-medium pull-right js-add-links" data-loading-text="Wait...">Add</div>
                    <div class="clearfix"></div>
                </div>

            </div>

            <div class="row">
                <div class="col-xs-12 col-lg-9 ">

                    @if (Session::has('error'))
                        <div class="alert alert-danger">
                            <ul><li>{{ Session::get('error') }}</li></ul>
                        </div>
                    @endif

                    <div class="alert alert-danger js-error-alert" role="alert" style="margin-top:10px;display:none;">
                        <button type="button" class="close js-error-alert-button" aria-label="Close" style="right: 0;"><span aria-hidden="true">&times;</span></button>
                        There was <b>problem</b> with uploading <strong>"<span class="js-error-filename"></span>"</strong>. Try to convert file to other format or contact us at info@speechtotextservice.com for help.
                    </div>

                    <div class="upload-table files-table">
                        <div class="header clearfix">
                            <div class="column column-remove hidden-xs"></div>
                            <div class="column column-title">File Name / URL</div>
                            <div class="column column-status hidden-xs">Status</div>
                            <div class="column column-play hidden-xs">Play</div>
                            <div class="column column-trim hidden-xs">Trim</div>
                            <div class="column column-comment hidden-xs">Comment</div>
                            <div class="column column-length hidden-xs">Length</div>
                            <div class="column column-size hidden-xs">Size</div>
                        </div>

                        <div class="files-list js-file-container">

                            {{-- files --}}

                        </div>

                        <div class="files-list js-no-files">
                            <div class="uploaded-file no-files">No files uploaded</div>
                        </div>

                        <div class="js-calculator order-settings" style="display:none;">

                            {{-- Text format --}}
                            <div class="setting">
                                <div class="setting-name">
                                    <span class="icon text-format-icon"></span>
                                    Text format
                                </div>

                                @include('components.calculator.partial.text_format')
                            </div>

                            {{-- Timestamping --}}
                            <div class="setting">
                                <div class="setting-name">
                                    <span class="icon timestamping-icon"></span>
                                    Timestamping
                                    <span class="icon help-icon" data-toggle="tooltip" title="Timestamping helps to keep track of the progression of the audio. In other words, our transcribers can mark preferred time points of the audio into the text."></span>
                                </div>

                                @include('components.calculator.partial.timestamping')
                            </div>

                            {{-- Turnaround time --}}
                            <div class="setting">
                                <div class="setting-name turnaround-time">
                                    <span class="icon turnaround-icon"></span>
                                    Turnaround time
                                </div>
                                @include('components.calculator.partial.turnaround_time')
                            </div>

                            {{-- Number of speakers --}}
                            <div class="setting">
                                <div class="setting-name speakers">
                                    <span class="icon speakers-icon"></span>
                                    Number of speakers
                                </div>
                                @include('components.calculator.partial.number_of_speakers')
                            </div>

                            {{-- Subtitles and CC --}}
                            <div class="setting">
                                <div class="setting-name subtitles">
                                    <span class="icon subtitles-icon"></span>
                                    Subtitles and CC
                                </div>
                                @include('components.calculator.partial.subtitles')
                            </div>

                            {{-- Language --}}
                            <div class="setting">
                                <div class="setting-name language">
                                    <span class="icon language-icon"></span>
                                    Language
                                </div>
                                @include('components.calculator.partial.language')
                            </div>
                        </div>
                    </div>
                </div>


                {{-- Right --}}
                <div class="col-xs-12 col-lg-3">

                    <div class="order-summary">

                        <div class="header"><h3>Order summary</h3></div>

                        <div class="table-row">
                            <span class="label">Transcriptions:</span>
                            <span class="result js-transcription-count">0</span>
                        </div>

                        <div class="table-row">
                            <span class="label">Total length:</span>
                            <span class="result js-total-length">00:00:00</span>
                        </div>

                       {{-- discount --}}
                        <div class="js-discount-container" @if (!$order->discount()['value']) style="display:none" @endif>
                            <div class="table-row table-row-dark order-subtotal-price">
                                <span class="subtotal-label">Subtotal:</span>
                                <span class="subtotal-price result">$<span class="js-subtotal">0.00</span></span>
                            </div>

                            <div class="table-row table-row-dark order-discount">
                                You've got <span class="js-discount">{{ $order->discount()['value'] }}</span>% discount!
                                <div class="discount-save">
                                    You save:
                                    <strong>$<span class="js-you-save">0.00</span></strong>!
                                </div>
                            </div>
                        </div>

                        <div class="table-row table-row-dark order-total-price">
                            <span class="total-label">Total:</span>
                            <span class="total-price result">$<span class="js-total">0.00</span></span>
                        </div>
                    </div>

                    <a class="button-medium button-icon button-order" href="{{ route('upload_register') }}">
                        <span class="icon cart-icon"></span>
                        Proceed to order
                    </a>

                    <div class="info-box">
                        <h4>Supported file formats</h4>
                        <p>
                            mp3, wav, wma, wmv, avi, flv, mpg, mpeg, mp4, m4a, m4v, mov, ogg, webm, aif, aiff, amr, 3gp, 3ga, mts, ovg
                        </p>
                    </div>

                    <div class="info-box">
                        <h4>FAQ</h4>
                        <ul>
                            @foreach ($faqs as $faq)
                                <li><a target="_blank" class="text-grey" href="{{ $faq->link() }}">{{ $faq->question }}</a></li>
                            @endforeach
                        </ul>
                    </div>

                </div>

            </div>

        </div>

        {{-- File template --}}
        <template class="js-file-blueprint">
            <div class="uploaded-file" data-duration="::duration::">
                <div class="column column-remove">
                    <span class="icon delete-icon js-delete-audio"></span>
                </div>

                <div class="column column-title">
                    !!progress!!
                    <div class="cell-title js-filename-popover" data-toggle="tooltip" title="::original_filename::">::original_filename::</div>
                </div>

                <div class="column column-status">
                    <div class="visible-xs">
                        <div class="cell-middle">::duration_for_humans::</div>
                    </div>
                    <div class="hidden-xs">!!tick!!</div>
                </div>

                <div class="column column-play">
                    <div class="visible-xs">
                        <div class="cell-middle">::size::MB</div>
                    </div>
                    <div class="hidden-xs">!!play!!</div>
                </div>

                <div class="clearfix visible-xs"></div>

                <div class="column column-trim">
                    !!cut_img!!
                </div>

                <div class="column column-comment">
                    !!comment_img!!
                </div>

                <div class="column column-length">
                    <div class="hidden-xs js-duration-for-humans">
                        <div class="cell-middle">::duration_for_humans::</div>
                    </div>
                    <div class="visible-xs">!!tick!!</div>
                </div>

                <div class="column column-size">
                    <div class="hidden-xs">
                        <div class="cell-middle">::size::MB</div>
                    </div>
                    <div class="visible-xs">!!play!!</div>
                </div>
            </div>

            {{-- poopup comment --}}
            <div class="js-comment" style="display: none">
                <div class="file-popup comment clearfix">
                    <textarea class="form-control">::comment::</textarea>
                    <button class="button-medium pull-right js-save-comment">Save</button>
                </div>
            </div>


            {{-- popup duration --}}
            <div class="js-duration" style="display: none">
                <div class="file-popup trim clearfix">
                    <div class="form-time form-input clearfix">
                        <label for="timestampFrom">Start timestamp</label>
                        <input class="js-from" id="timestampFrom" type="text" value="::from_human::">
                    </div>

                    <div class="form-time form-input clearfix">
                        <label for="timestampTill">End timestamp</label>
                        <input class="js-till" id="timestampTill" type="text" value="::till_human::">
                    </div>

                    <div class="form-time form-button clearfix">
                        <div class="button-medium js-save-duration">Save</div>
                    </div>
                </div>
            </div>

        </template>
    </div>


    {{-- simple popover --}}
    <div class="hidden js-simple-popover-template">
        <div class="popover popover-blue" role="tooltip">
            <div class="arrow"></div>
            <h3 class="popover-title"></h3>
            <div class="popover-content"></div>
        </div>
    </div>

    <script src="{{ asset('js/fileupload.js') }}"></script>
    <script src="{{ asset('js/upload.js') }}"></script>

    <script>

        // just upload
        $(function () {
            'use strict';
            // Change this to the location of your server-side upload handler:
            var url = '{{ url('/vendor/upload/server/php/index.php') }}';
            $('#fileupload').fileupload({
                url: url,
                dataType: 'json',
                sequentialUploads: true,
                add: function (e, data) {
                    // add temporary id to file
                    $.each(data.files, function (index, file) {
                        data.files[index].tmp_id = Math.floor((Math.random() * 10000000) + 1); // rand id
                    });
                    data.submit();
                },
                submit: function (e, data) {

                    $.each(data.files, function (index, file) {
                        addFile({
                            id: file.tmp_id,
                            original_filename: file.name,
                            size: '-',
                            duration_for_humans: '-'
                        });

                    });
//                    showProgressFile();
                },
                done: function (e, data) {

                    // add permanent files
                    $.each(data.files, function (index, file) {
                        var laravel_file = $.post( "{{ url('upload/take-file') }}", { _token: "{{ csrf_token() }}", file: file.name } );
                        laravel_file.done(function(data ) {

                            removeTemporary(file.tmp_id);

                            addFile(data, true); // permanent

                            recalculatePrice();
                        });
                        laravel_file.fail(function(data) {
                            removeTemporary(file.tmp_id);
                            $('.js-error-filename').text(file.name);
                            $('.js-error-alert').slideDown();
                            showHideOptions();
                        });
                    });
                },
                progress: function (e, file) {
                    var progress = parseInt(file.loaded / file.total * 100, 10);
                    var tmp_id = file.files[0].tmp_id; // file tmp id

                    $(".js-file-container .js-file[data-id=" + tmp_id + "] .js-progress-bar").css('width', progress + '%');
                },
                progressall: function (e, data) {
//                    var progress = parseInt(data.loaded / data.total * 100, 10);
//                    $('.js-progress-bar').css(
//                            'width',
//                            progress + '%'
//                    );
                },
                fail: function (e, data) {
                    removeTemporary(data.files[0].tmp_id);
                    $('.js-error-filename').text(data.files[0].name);
                    $('.js-error-alert').slideDown();
                    showHideOptions();
                }
            }).prop('disabled', !$.support.fileInput) // ?????????
                    .parent().addClass($.support.fileInput ? undefined : 'disabled');

            // remove files with progress
            function removeTemporary(tmp_id) {
                // single file
                if (tmp_id) {
                    var file = $(".js-file-container .js-file[data-id=" + tmp_id + "]");
                    file.remove();
                    return true;
                }

                // all files
                var files = $(".js-file-container .js-file");
                $.each(files, function(k, file) {
                    var temporary = $(file).find(".js-progress").length;
                    if (temporary) {
                        $(file).remove();
                    }
                });
            }
        });

        // other
        $(function() {

            $("body").on("click", ".js-delete-audio", delete_audio);
            $("body").on("click", ".js-toggle-comment", toggle_comment);
            $("body").on("click", ".js-save-comment", update_comment);
            $("body").on("click", ".js-toggle-duration", toggle_duration);
            $("body").on("click", ".js-save-duration", update_duration);
            $('.js-error-alert-button').click(hideAlert);


            function delete_audio() {
                //console.log('here');
                var file = $(this).closest(".js-file");
                var id = file.attr("data-id");

                file.slideUp(function() {
                    file.remove();

                });

                var post = $.post( "{{ url('upload/delete-audio') }}", { _token: "{{ csrf_token() }}", audio_id: id } );
                post.done(function() {
                    recalculatePrice();
                });
            }

            function toggle_comment() {
                var comment = $(this).closest(".js-file").find(".js-comment");
                if (comment.is(":visible")) {
                    close_all_popups();
                } else {
                    close_all_popups();
                    comment.slideDown();
                }
            }

            function toggle_duration() {
                var duration = $(this).closest(".js-file").find(".js-duration");
                if (duration.is(":visible")) {
                    close_all_popups();
                } else {
                    close_all_popups();
                    duration.slideDown();
                }
            }

            function update_comment() {
                // save
                var file = $(this).closest(".js-file");
                var comment = file.find(".js-comment").find("textarea").val();
                var audio_id = file.attr("data-id");
                var data = {
                    _token: "{{ csrf_token() }}",
                    audio_id: audio_id,
                    comment: comment
                };
                $.post('{{ url('upload/update-comment') }}', data);

                // change image
                var img = file.find(".js-toggle-comment").addClass('comment-done-icon');

                close_all_popups();
            }

            function update_duration() {
                // save
                var file = $(this).closest(".js-file");
                var audio_id = file.attr("data-id");
                var from = file.find(".js-from").val();
                var till = file.find(".js-till").val();
                var data = {
                    _token: "{{ csrf_token() }}",
                    audio_id: audio_id,
                    from: from,
                    till: till
                };
                var post = $.post('{{ url('upload/update-duration') }}', data);
                post.done(function(audio) {
                    file.children(":first").attr("data-duration", audio.duration); // what it is used for? do we still need it?

                    file.find('.js-duration-for-humans').text(audio.duration_for_humans);
                    file.find('.js-from').val(audio.from_human);
                    file.find('.js-till').val(audio.till_human);

                    recalculatePrice();
                });

                // change image
                var img = file.find(".js-toggle-duration").addClass('trim-done-icon');

                close_all_popups();
            }

            function close_all_popups() {
                $(".js-duration:visible").slideUp();
                $(".js-comment:visible").slideUp();
            }

            function hideAlert() {
                $('.js-error-alert').slideUp();
            }

        });

        // calculator
        @include('components.calculator.partial.js.calculatorjs')

        // calculator updates values
        function calculatorUpdateHTML(data) {
            var calculator = $(".js-calculator");

          //console.log(data);


            if (data['discount']) {
                $('.js-discount-container').slideDown();
            } else {
                $('.js-discount-container').slideUp();
            }

            var subtotal_input = $(".js-subtotal");
            subtotal_input.text(round(data["subtotal"], 2));

            var discount = $(".js-discount");
            discount.text(round(data["discount"], 2));

            var you_save_input = $(".js-you-save");
            you_save_input.text(round(data["you_save"], 2));

            var total_input = $(".js-total");
            total_input.text(round(data["total"], 2));


            // transcription count
            var transcription_count = $("[data-duration]:visible").length;
            $(".js-transcription-count").text(transcription_count);

            // total length
            $(".js-total-length").text(secondsToHumanTime(data['minutes'] * 60));
        }



        // add files (when page loads)
        $(function() {
            @foreach ($order->audios as $audio)
            var data = {!! $audio->toJson() !!};
            {{--var data = {--}}
                {{--id: {{ $audio->id }},--}}
                {{--original_filename: "{{ $audio->original_filename }}",--}}
                {{--duration_for_humans: "{{ $audio->duration_for_humans }}",--}}
                {{--size: {{ $audio->size }},--}}
                {{--duration: {{ $audio->duration }},--}}
                {{--from: "{{ secondsToTime($audio->from) }}",--}}
                {{--till: "{{ secondsToTime($audio->till) }}",--}}
                {{--comment: "{{ $audio->comment }}"--}}
            {{--};--}}
            addFile(data, true); // permanent
            @endforeach

            // update data
            recalculatePrice();


        });

        function recalculatePrice() {
            $(".js-calculator .js-autoupdate:first").change();

            showHideOptions();
        }

        // tat, speakers, text format...
        function showHideOptions() {
            var options = $('.js-calculator');
            var files = $('.js-file-container');
            var no_files = $('.js-no-files');

            if (files.find('.js-file').length) {
                if (!options.is(':visible')) {
                    options.slideDown();
                    no_files.slideUp();
                }
            } else {
                options.slideUp();
                no_files.slideDown();
            }

//            $('.js-filename-popover').popover({
//                container: 'body',
//                template: $(".js-simple-popover-template").html(),
//                placement: 'top',
//                trigger: 'hover'
//            });
        }


    </script>







@stop