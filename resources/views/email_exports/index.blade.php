@extends('simple_app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Email Export</b></div>

                    <div class="panel-body">

                        <table class="table table-striped table-hover">
                            <tr>
                               <th>Choose</th>
                            </tr>
                            <tr>
                                <td><a href="{{ url('/') }}/email_export/client_emails">Client Emails</a></td>
                            </tr>
                            <tr>
                                <td><a href="{{ url('/') }}/email_export/editor_emails">Editor Emails</a></td>
                            </tr>
                            <tr>
                                <td><a href="{{ url('/') }}/email_export/transcriber_emails">Transcriber Emails</a></td>
                            </tr>
                        </table>

                        <table class="table table-striped table-hover">
                            <tr>
                                <th>Choose</th>
                            </tr>
                            <tr>
                                <td><a href="{{ url('/') }}/email_export/deleted_user_emails">Deleted user emails</a></td>
                            </tr>
                            <tr>
                                <td><a href="{{ url('/') }}/email_export/not_agreed_to_receive_newsletter">Not agreed to receive newsletter</a></td>
                            </tr>
                        </table>

                    </div>
                </div>

            </div>

        </div>
    </div>
@stop