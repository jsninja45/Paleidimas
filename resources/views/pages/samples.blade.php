@extends('app')

@section('title'){{ 'Our Transcription Samples' }}@stop

@section('content')

    <div class="audio-samples-body">
        <div class="container">
            <div class="page-heading">@yield('title')</div>

            <p>Here you can find some samples of our work. We wanted to emphasize the difference between the transcription types, therefore the same audio file is done in clean verbatim, full verbatim, as well as two types of timestamping: every two minutes and change of speaker.</p>

            <div class="s2t_sample_center">

                <div class="sample">
                    <div class="s2t_sample_caption">Clean verbatim</div>
                    <audio class="s2t_sample_mp3" id="if1video" controls>
                        <source  src="{{ asset('/samples/STTS_example_interview.mp3') }}" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio><br/>
                    <iframe id="if1" scrolling="yes" src="https://docs.google.com/gview?url={{ URL::to('/samples/Voice_003_Clean_Verbatim.docx') }}&amp;embedded=true" frameborder=0, width="638" height="500"></iframe>
                </div>
                <div class="sample">
                    <div class="s2t_sample_caption">Full verbatim</div>
                    <audio class="s2t_sample_mp3" id="if2video" controls>
                        <source  src="{{ asset('/samples/STTS_example_interview.mp3') }}" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                    <br/>
                    <iframe id="if2" scrolling="yes" src="https://docs.google.com/gview?url={{ URL::to('/samples/Voice_003_Full_verbatim.docx') }}&amp;embedded=true" frameborder=0, width="638" height="500"></iframe>
                </div>

                <div class="sample">
                    <div class="s2t_sample_caption">Clean verbatim with timestamping on speaker change</div>
                    <audio class="s2t_sample_mp3" id="if3video" controls>
                        <source  src="{{ asset('/samples/STTS_example_interview.mp3') }}" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                    <br/>
                    <iframe id="if3" scrolling="yes" src="https://docs.google.com/gview?url={{ URL::to('/samples/Voice_003_Clean_Verbatim_Timestamping.docx') }}&amp;embedded=true" frameborder=0, width="638" height="500"></iframe>
                </div>

                <div class="sample">
                    <div class="s2t_sample_caption">Full verbatim with timestamping every 2 minutes</div>
                    <audio class="s2t_sample_mp3" id="if4video" controls>
                        <source  src="{{ asset('/samples/STTS_example_interview.mp3') }}" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                    <br/>
                    <iframe id="if4" scrolling="yes" src="https://docs.google.com/gview?url={{ URL::to('/samples/Voice_003_Full_Verbatim_Timestamping.docx') }}&amp;embedded=true" frameborder=0, width="638" height="500"></iframe>
                </div>
            </div>
        </div>
    </div>

@stop