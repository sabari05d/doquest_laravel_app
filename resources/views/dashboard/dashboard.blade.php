@extends('index')
@section('title', 'Dashboard')
@section('content')

    <div class="container">
        <p class="fs-3 fw-semibold mb-0 text-danger">Welcome, Your Grace! {{ $user->name }} </p>


        <div class="row">
            <div class="col-12">
                <?= $unfinishedHtml ?? '' ?>
            </div>
            <div class="col-12">
                <?= $upcomingHtml ?? '' ?>
            </div>
        </div>

    </div>

@endsection