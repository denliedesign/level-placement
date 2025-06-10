@extends('layouts.app')
@section('content')

    <h1 class="text-center mt-5">MDU Level Placement</h1>
    <div class="container my-5 d-flex align-items-center justify-content-center">
        <div>
            <h2 class="text-center">Register a new account with the main email from your studio account</h2>
            <ul>
                <li>If you have multiple dancers, just create one account.</li>
{{--                <li>If you try to Register a new account and you receive a message in red that says "the email has already been taken", then use the Login option.</li>--}}
{{--                <li>If you forgot your password, click “forgot your password”, enter your email address, and click send password reset link.</li>--}}
                <li>Level placements are only provided for the style (Ballet, Jazz, Tap) that you took. If there is a 0 noted, that means your dancer did not participate in that style this past season.</li>
                <li>If you don’t see your dancer’s info, please try one of your other email addresses.</li>
            </ul>
        </div>
    </div>


@endsection
