@extends('layouts.backend', [
    "page_title" => 'Configuration'
])

<?php
$select_option = [
    (object)[
        'id' => 0,
        'name' => 'Hidden'
    ],
    (object)[
        'id' => 1,
        'name' => 'Show'
    ],
];
?>

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-outline">
                <div class="card-header bg-blue">
                    <h5 class="text-white m-b-0">
                        Basic Configuration
                    </h5>
                </div>
                <div class="card-body">
                    <form method="post">
                        {{csrf_field()}}
                        <div class="form-body">
                            {!! CMSConfig::text('phone') !!}
                            {!! CMSConfig::text('phone2') !!}
                            {!! CMSConfig::text('whatsapp') !!}
                            {!! CMSConfig::text('email') !!}
                            {!! CMSConfig::text('fax') !!}

                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-check"></i>
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row m-t-3">
        <div class="col-lg-12">
            <div class="card card-outline">
                <div class="card-header bg-blue">
                    <h5 class="text-white m-b-0">
                        Mail Settings
                    </h5>
                </div>
                <div class="card-body">
                    <form method="post">
                        {{csrf_field()}}
                        <div class="form-body">
                            {!! CMSConfig::text('mail_admin_subject', 'Subject to Admin', ['placeholder' => 'E-Mail Subject']) !!}
                            {!! CMSConfig::text('mail_admin_recipients', 'Admin E-mail(s)', ['placeholder' => '123@abc.com, 321@abc.com, 231@abc.com']) !!}
                            {!! CMSConfig::text('mail_visitor_subject', 'Subject to Visitor', ['placeholder' => 'E-Mail Subject']) !!}
                            {!! CMSConfig::text('mail_contact_text_header', 'Mail Text Header', ['placeholder' => 'Thank You for contact us . . .']) !!}
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-check"></i>
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row m-t-3">
        <div class="col-lg-12">
            <div class="card card-outline">
                <div class="card-header bg-blue">
                    <h5 class="text-white m-b-0">
                        Social Media
                    </h5>
                </div>
                <div class="card-body">
                    <form method="post">
                        {{csrf_field()}}
                        <div class="form-body">
                            {!! CMSConfig::text('facebook') !!}
                            {!! CMSConfig::text('twitter') !!}
                            {!! CMSConfig::text('linkedin') !!}
                            {!! CMSConfig::text('youtube') !!}
                            {!! CMSConfig::text('instagram') !!}
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-check"></i>
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('form').on('submit', function() {
            $(".loader-container").show();
        });
    </script>
@endsection
