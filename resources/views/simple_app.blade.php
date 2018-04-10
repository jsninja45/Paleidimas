<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SpeechToTextService.com</title>

	<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="{{ url('/') }}">SpeechToTextService.com</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
                    @if (Auth::check() && Auth::user()->hasRole('admin'))
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Administration <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                {{--<li><a href="{{ route('admin_late_audios') }}">Late audios</a></li>--}}
                                {{--<li><a href="{{ route('admin_late_audio_slices') }}">Late audio slices</a></li>--}}
                                {{--<li class="divider"></li>--}}
                                <li><a href="{{ route('admin_orders') }}">Orders</a></li>
                                <li><a href="{{ route('admin_audios', ['paid' => 1]) }}">Audios</a></li>
                                <li><a href="{{ route('admin_audio_slices') }}">Audio slices</a></li>
                                <li class="divider"></li>
                                <li><a href="{{ route('admin_clients') }}">Clients</a></li>
                                <li><a href="{{ route('admin_transcribers') }}">Transcribers</a></li>
                                <li><a href="{{ route('admin_editors') }}">Editors</a></li>
                                <li><a href="{{ route('admin_subtitlers') }}">Subtitlers</a></li>
                                <li class="divider"></li>
                                <li><a href="{{ route('admin_salaries') }}">Salaries</a></li>
                                <li><a href="{{ route('admin.bonuses.index') }}">Bonuses</a></li>
                                <li class="divider"></li>
                                <li><a href="{{ route('admin_coupons') }}">Coupons</a></li>
                                <li><a href="{{ route('admin_config') }}">Configuration</a></li>
                                <li class="divider"></li>
                                <li><a href="{{ route('admin_project_stats') }}">Project Statistics</a></li>
                                <li><a href="{{ route('email_export') }}">Email Export</a></li>
                                <li><a href="{{ route('admin_client_ratings') }}">Client ratings</a></li>
                            </ul>
                        </li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Content <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ route('admin_blogs') }}">Blog</a></li>
                                <li><a href="{{ route('admin_services') }}">Services</a></li>
                                <li><a href="{{ route('admin_faqs') }}">FAQ</a></li>
                                <li><a href="{{ route('admin_reviews') }}">Reviews</a></li>
                                <li><a href="{{ route('admin.messages.index') }}">Chats</a></li>
                            </ul>
                        </li>
                    @endif

                    @if (Auth::check() && Auth::user()->hasRole('transcriber'))
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Transcription Jobs <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ route('available_transcription_jobs') }}">1. Available</a></li>
                                <li><a href="{{ route('in_progress_transcription_jobs') }}">2. In Progress</a></li>
                                <li><a href="{{ route('finished_transcription_jobs') }}">3. Finished</a></li>
                            </ul>
                        </li>
                    @endif

                    @if (Auth::check() && Auth::user()->hasRole('editor'))
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Editing Jobs <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ route('available_for_editing_jobs') }}">1. Available</a></li>
                                <li><a href="{{ route('in_progress_editing_jobs') }}">2. In Progress</a></li>
                                <li><a href="{{ route('finished_editing_jobs') }}">3. Finished</a></li>
                            </ul>
                        </li>
                    @endif

                    @if (Auth::check() && Auth::user()->hasRole('subtitler'))
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Subtitling Jobs <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ route('subtitling_jobs.available') }}">1. Available</a></li>
                                <li><a href="{{ route('subtitling_jobs.in_progress') }}">2. In Progress</a></li>
                                <li><a href="{{ route('subtitling_jobs.finished') }}">3. Finished</a></li>
                            </ul>
                        </li>
                    @endif

                    <li><a href="{{ route('worker_stats', [Auth::id()]) }}">Stats</a></li>

                    @if (Auth::check() && !Auth::user()->hasRole('admin'))
                        <li><a href="{{ route('messages_with', [1]) }}">Contact Administrator</a></li>
                    @endif


                    <?php
                    $messages = Auth::user()->messages()->distinct()->groupBy('sender_id')->notSeen()->get();
                    $not_seen_message_count = $messages->count();
                    if ($messages->isEmpty()) {
                        $messages = Auth::user()->messages()->distinct()->groupBy('sender_id')->get();
                    }
                    $messages->load('sender');
                    ?>
                    @if (!$messages->isEmpty())
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Messages @if ($not_seen_message_count) <span class="badge" style="background-color: red;">{{ $not_seen_message_count }}</span> @endif <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                @foreach ($messages as $message)
                                    <li><a href="{{ $message->link() }}">{{ $message->sender->email }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
				</ul>

				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}">Login</a></li>
						<li><a href="{{ url('/auth/register') }}">Register</a></li>
					@else
						<li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->email }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ route('profile', [Auth::id()]) }}">Profile</a></li>
								<li><a href="{{ url('/auth/logout') }}">Logout</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>

	@yield('content')

	<!-- Scripts -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>

    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>

    <script>

        $(function() {
//            tinymce.init({
//                selector: ".js-wysiwyg"
//            });

            $( ".js-datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });

            tinymce.init({
                selector: ".js-wysiwyg",
                resize: "both",
                relative_urls: false,
                plugins: ["autoresize", "image", "code", "lists", "code", "link"],
                indentation : '20pt',
                file_browser_callback: function(field_name, url, type, win) {
                    if (type == 'image') $('#my_form input').click();
                },
                image_list: "/wysiwyg/images",
                toolbar: [
                    "undo redo | styleselect | bold italic | link image | alignleft aligncenter alignright | preview | spellchecker"
                ],
                style_formats: [
                    {
                        title: 'Image Left',
                        selector: 'img',
                        styles: {
                            'float': 'left',
                            'margin': '10px 10px 10px 0'
                        }
                    },
                    {
                        title: 'Image Right',
                        selector: 'img',
                        styles: {
                            'float': 'right',
                            'margin': '10px 0 10px 10px'
                        }
                    }
                ]
            });
        });

    </script>

</body>
</html>
