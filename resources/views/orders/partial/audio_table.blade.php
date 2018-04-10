    <table class="order-table audio-table">
        <thead>
            <tr>
                <th>
                    Delete
                    <span class="icon help-white-icon" title="You can delete the transcription after 7 days from its completion. You can't reclaim the files after deleting them." data-toggle="tooltip" data-placement="auto"></span>
                </th>
                <th>Uploaded</th>
                <th>Name</th>
                <th>Length</th>
                <th>Job status</th>
                <th>Play</th>
                <th>Transcription</th>
                <th>Subtitles</th>
                <th>Transcription accuracy</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($audios as $audio)
                <tr>
                    <td class="column-icon" data-th="Delete">
                        @if ($audio->canClientDeleteFile())
                            <a onclick="return confirm('Delete?');" href="{{ route('delete_audio_file', [$audio->id]) }}"><span class="icon delete-icon"></span></a>
                        @else
                            <div class="empty">-</div>
                        @endif
                    </td>
                    <td class="column-icon" data-th="Uploaded">
                        <div class="date">{{ date('Y.m.d', strtotime($audio->created_at)) }}</div>
                    </td>
                    <td class="column-filename column-icon" data-th="Name">
                        <div class="filename">{{ $audio->original_filename }}</div>
                    </td>
                    <td class="column-icon" data-th="Length">{{ secondsToTime($audio->duration) }}</td>
                    <td class="column-icon" data-th="Job status">
                        @if ($audio->status === 'finished')
                            <span class="icon uploaded-icon" data-toggle="tooltip" data-placement="auto" title="Your order is completed!"></span>
                        @else
                            <span class="icon uploading-icon" data-toggle="tooltip" data-placement="auto" title="Your order is in progress!"></span>
                        @endif
                    </td>
                    <td class="column-icon" data-th="Play">
                        @if ($audio->isFileDeleted())
                           <span class="icon play-icon"></span>
                        @else
                            <a href="{{ $audio->download() }}" target="_blank">
                                <span class="icon play-icon"></span>
                            </a>
                        @endif
                    </td>
                    <td class="column-icon" data-th="Transcription">
                        @if (!$audio->transcriptions->isEmpty())
                            <a href="{{ $audio->transcription->link() }}">
                                <span class="icon ms-word-icon"></span>
                            </a>
                        @else
                            <div class="empty">-</div>
                        @endif
                    </td>
                    <td class="column-icon" data-th="Subtitles">
                        @if ($audio->subtitle && !$audio->subtitle->isFileDeleted())
                            <a target="_blank" href="{{ $audio->subtitle->link() }}">
                                <span class="icon subtitles-icon"></span>
                            </a>
                        @else
                            <div class="empty">-</div>
                        @endif
                    </td>
                    <td class="column-feedback column-icon">
                        @if ($audio->status === 'finished')
                            <div class="feedback-rating" data-toggle="popover" data-class="textarea-popup" data-trigger="manual" data-placement="bottom" data-target="#feedbackPopover" data-audio-id="{{ $audio->id }}">
                                <input type="hidden" name="rating" class="rating-input" value="{{ $audio->rating }}">
                                @for ($i = 1; $i <= 5; $i++)
                                    <div class="rating-star @if ($audio->rating >= $i) active @endif"></div>
                                @endfor
                            </div>
                        @else
                            <div class="empty">-</div>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Feedback popup content template --}}
    <div id="feedbackPopover" style="display:none">
        <textarea class="form-control" placeholder="Please leave a detailed comment to help us improve the quality of our services"></textarea>
        <div class="button-medium pull-right js-submit-review-comment" data-loading-text="Wait...">Submit</div>
        <div class="clearfix"></div>
    </div>

    {{-- Order settings --}}
    <div class="order-settings">

        {{-- Text format --}}
        <div class="setting">
            <div class="setting-name">
                <span class="icon text-format-icon"></span>
                Text format
            </div>

            <div class="options">
                @if ($order->textFormat->slug === 'clean_verbatim')
                    <label>Clean verbatim</label>
                    <span class="icon help-icon" data-toggle="tooltip" title="The transcribed text does not include speech errors, false starts and various filler words, such as: um, uh, hmm, so, you know, sort of, etc."></span>
                @elseif ($order->textFormat->slug === 'full_verbatim')
                    <label>Full verbatim</label>
                    <span class="icon help-icon" data-toggle="tooltip" title="The text is transcribed exactly as it sounds and includes all utterances of the speakers (e.g. Mm-hmm, uh-huh, umm, uh, etc.)."></span>
                @endif
            </div>
        </div>

        {{-- Timestamping --}}
        <div class="setting">
            <div class="setting-name">
                <span class="icon timestamping-icon"></span>
                Timestamping
                <span class="icon help-icon" data-toggle="tooltip" title="Timestamping helps to keep track of the progression of the audio. In other words, our transcribers can mark preferred time points of the audio into the text."></span>
            </div>

            <div class="options">
                @if ($order->timestamping->slug === 'not_required')
                    <label>Not required</label>
                @elseif ($order->timestamping->slug === 'every_2_minutes')
                    <label>Every 2 minutes</label>
                @elseif ($order->timestamping->slug === 'change_of_speaker')
                    <label>On speaker change</label>
                @endif
            </div>
        </div>

        {{-- Subtitles --}}
        <div class="setting">
            <div class="setting-name">
                <span class="icon subtitles-icon"></span>
                Subtitles and CC
            </div>

            <div class="options">
                <label>{{ $order->subtitle->name }}</label>
            </div>
        </div>

        {{-- Turnaround time --}}
        <div class="setting">
            <div class="setting-name">
                <span class="icon turnaround-icon"></span>
                Turnaround time
            </div>

            <div class="options">
                @if ($order->tat->slug === 'days_1')
                    <label>1 Day</label>
                @elseif ($order->tat->slug === 'days_3')
                    <label>3 Days</label>
                @elseif ($order->tat->slug === 'days_7')
                    <label>7 Days</label>
                @elseif ($order->tat->slug === 'days_14')
                    <label>14 Days</label>
                @endif
            </div>
        </div>

        {{-- Number of speakers --}}
        <div class="setting">
            <div class="setting-name">
                <span class="icon speakers-icon"></span>
                Number of speakers
            </div>

            <div class="options">
                @if ($order->speaker->slug === 'max_2')
                    <label>1-2</label>
                @elseif ($order->speaker->slug === 'min_3')
                    <label>3 and more</label>
                @endif
            </div>
        </div>
    </div>

@section('scripts')
    @parent

    <script>
        $(function() {
            $("html").on('click', function () {
                $(".feedback-rating").each(function() {
                    $(this).popover('hide');
                });
            });

            $("body").on('click', '.feedback-rating, .textarea-popup', function(e) {
                e.stopPropagation();
            });

            $(".rating-input").on('change', function() {
                var audioId = $(this).parent().data('audio-id');
                $.post("/audios/" + audioId + "/rate", { _token: "{{ csrf_token() }}", rating: $(this).val() });

                // show comment popover
                $(this).parent().popover('show');
                $('body').data('audio-id', audioId);
            });

            // submit
            $("body").on("click", ".js-submit-review-comment", function() {
                var buttonText = $(this).text();
                $(this).text($(this).data('loading-text'));

                var audioId = $("body").data('audio-id');
                var comment = $(this).parent().find('textarea').val();

                $.post("/audios/" + audioId + "/rate", { _token: "{{ csrf_token() }}", comment: comment });
                $('.feedback-rating').popover('hide');
                $(this).text(buttonText);
            });
        });
    </script>
@stop