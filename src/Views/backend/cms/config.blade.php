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
                            {!! CMSConfig::text('phone_dial_call') !!}
                            {!! CMSConfig::text('email') !!}

                            {!! CMSConfig::text('facebook') !!}
                            {!! CMSConfig::text('twitter') !!}
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
                            {!! CMSConfig::text('mail_recipients', 'Recipients', ['placeholder' => '123@abc.com, 321@abc.com, 231@abc.com']) !!}
                            {!! CMSConfig::text('mail_subject', 'Subject', ['placeholder' => 'E-Mail Subject']) !!}
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
