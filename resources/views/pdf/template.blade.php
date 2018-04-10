<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>OWL transcriptions</title>

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:600,400' rel='stylesheet' type='text/css' />
</head>

<body style="margin: 0; padding: 0;">

<div style="margin: 0 auto; max-width: 703px; font-family: 'Open Sans', sans-serif, Arial; color: #6d727b; font-size: 16px;">

    {{-- header --}}
    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#FFFFFF">
        <tr>
            <td>
                <a href="{{ URL::to('/') }}">
                    <img border="0" src="{{ URL::to('/img/pdf/logo7.png') }}" alt="OwlTranscription" />
                </a>
            </td>
        </tr>
    </table>

    {{-- content --}}
    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#FFFFFF">
        <tr>
            <td style="padding: 0 30px 10px 30px;">

                <?php echo styleEmailContent($__env->yieldContent('content')); ?>

                {{--@if (isset($style))--}}
                {{----}}
                {{--@else--}}
                {{--@yield('content')--}}
                {{--@endif--}}

            </td>
        </tr>
    </table>

    {{-- footer --}}
    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#7ab900">
        <tr>
            <td>
                <img src="{{ URL::to('/img/pdf/owl6.png') }}" alt="owl" />
            </td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#7ab900" style="padding: 20px 30px 30px 30px;">
        <tr>
            <td>
                <?php $email_footer = $__env->yieldContent('footer'); ?>
                @if (!$email_footer) {{-- default footer --}}

                @section('footer')
                    <p style="">
                        Sincerely,<br />
                        The OwlTranscription.com Team
                    </p>
                @endsection

                @endif

                {!! styleEmailFooter($__env->yieldContent('footer')) !!}
            </td>
        </tr>
    </table>

</div>





</body>
</html>