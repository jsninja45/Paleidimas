<div class="row">
    @foreach ($services as $service)
        <div class="col-xs-12 col-sm-6 col-lg-4">
            <div class="service">
                <div class="image">
                    @if ($service->hasImage('thumbnail'))
                        <img style="width:109px;height:108px;" src="{{ $service->imageUrl('thumbnail') }}" alt="">
                    @else
                        <span class="sprite service-{{ $service->slug }}"></span>
                    @endif
                </div>

                <div class="content">
                    <h3><a {{--href="{{ url('transcription-services') }}/{{ $service->slug }}"--}}>{{ $service->title }}</a></h3>
                    <p>{{ $service->short_content }}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>

@section('scripts')
    @parent

    <script type="text/javascript">
        // resize service elements to equal height
        jQuery(function ($) {
            $(window).on('resize', function () {
                $service = $(".services-page .service");
                if (window.matchMedia('(min-width: 768px)').matches) {
                    var maxHeight = 0;

                    $service.each(function() {
                        if ($(this).height() > maxHeight) {
                            maxHeight = $(this).height();
                        }
                    });

                    $service.height(maxHeight);
                } else {
                    $service.css('height', 'auto');
                }
            });

            $(window).trigger('resize');
        });
    </script>
@stop