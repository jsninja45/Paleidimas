{{--<script>--}}

{{-- javascript file for calculator --}}

    $(function() {
        {{--$(".js-calculator [name=hours]").on("input", calculate).on("change", calculate);--}}
        {{--$(".js-calculator [name=minutes]").on("input", calculate).on("change", calculate);--}}
        {{--$(".js-calculator [name=text_format]").change(calculate);--}}
        {{--$(".js-calculator [name=timestamping]").change(calculate);--}}
        {{--$(".js-calculator [name=speaker]").change(calculate);--}}
        {{--$(".js-calculator [name=tat]").change(calculate);--}}
        $(".js-calculator [name=hours]").on("input", calculate);
        $(".js-calculator [name=minutes]").on("input", calculate);
        $(".js-calculator .js-autoupdate").change(calculate); // on external trigger
        var simple_popovers = $(".js-simple-popover");


        function calculate() {
            var calculator = $(".js-calculator");

            var text_format = $(".js-calculator [name=text_format]:checked").val();
            var timestamping = $(".js-calculator [name=timestamping]:checked").val();
            var subtitle = $(".js-calculator [name=subtitle]:checked").val();
            var speaker = $(".js-calculator [name=speakers]:checked").val();
            var tat = $(".js-calculator [name=tat]:checked").val();
            var language = $(".js-calculator [name=language]").val();

            // minutes (when not in upload form)
            var external_minutes = null;
            if ($(".js-calculator [name=hours]").length) { // exists (from hours minutes select)
                var hours = parseInt($(".js-calculator [name=hours]").val());
                var minutes = parseInt($(".js-calculator [name=minutes]").val());
                if (!hours) {
                    hours = 0;
                }
                if (!minutes) {
                    minutes = 0;
                }
                external_minutes = minutes + hours * 60;
            }

            var post_data = {
                _token: "{{ csrf_token() }}",
                text_format: text_format,
                timestamping: timestamping,
                subtitle: subtitle,
                speakers: speaker,
                tat: tat,
                language: language,
                external_minutes: external_minutes
            };

            var url = "{{ url('upload/update-unpaid-order') }}";
            if (external_minutes !== null) {
                url = url + "2";
            }

            var ajax = $.ajax({
                type: "POST",
                dataType: "json",
                url: url,
                data: post_data
            });
            ajax.done(function(data) {
                calculatorUpdateHTML(data); // output function that must be defined in file to update HTML
            });

            return;











            var total_minutes = 0;
            if ($(".js-calculator [name=hours]").length) { // exists (from hours minutes select)
                var hours = parseInt($(".js-calculator [name=hours]").val());
                var minutes = parseInt($(".js-calculator [name=minutes]").val());
                total_minutes = minutes + hours * 60;
            } else { // from audio files
                var total_duration = 0;
                var durations = $("[data-duration]:visible");
                $.each(durations, function(k, duration) {
                    total_duration += parseInt($(duration).attr("data-duration"));
                });
                total_minutes = total_duration / 60;
            }

            // text formats
            var text_formats = {
                @foreach ($text_formats as $format)
                "{{ $format->slug }}": {{ $format->client_price_per_minute }},
                @endforeach
            };
            var text_format_price = 0;
            $.each (text_formats, function(k, v) {
                if (k === text_format) {
                    text_format_price = v;
                }
            });

            // timestamping
            var timestampings = {
                @foreach ($timestampings as $timestamp)
                "{{ $timestamp->slug }}": {{ $timestamp->client_price_per_minute }},
                @endforeach
            };
            var timestamping_price = 0;
            $.each (timestampings, function(k, v) {
                if (k === timestamping) {
                    timestamping_price = v;
                }
            });

            // speakers
            var speakers = {
                @foreach ($speakers as $speaker)
                "{{ $speaker->slug }}": {{ $speaker->client_price_per_minute }},
                @endforeach
            };
            var speaker_price = 0;
            $.each (speakers, function(k, v) {
                if (k === speaker) {
                    speaker_price = v;
                }
            });

            // turn around time
            var tats = {
                @foreach ($tats as $tat)
                "{{ $tat->slug }}": {{ $tat->client_price_per_minute }},
                @endforeach
            };
            var tat_price = 0;
            $.each (tats, function(k, v) {
                if (k === tat) {
                    tat_price = v;
                }
            });


            var price_per_minute = text_format_price + timestamping_price + speaker_price + tat_price;


            var subtotal =  total_minutes * price_per_minute;
            var discount = {{ $order->discount()['value'] }};
            var you_save = subtotal * discount / 100;
            var total = subtotal - you_save;

            var data = {
                subtotal: subtotal,
                discount: discount,
                you_save: you_save,
                total: total,
                minutes: total_minutes
            };

            calculatorUpdateHTML(data); // output function that must be defined in file to update HTML

        }

        {{--simple_popovers.popover({--}}
            {{--container: 'body',--}}
            {{--template: $(".js-simple-popover-template").html(),--}}
            {{--placement: 'top',--}}
            {{--trigger: 'hover'--}}
        {{--});--}}

    });

{{--</script>--}}