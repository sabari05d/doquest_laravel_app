@extends('index')
@section('title', 'Tasks')
@section('content')

    <div class="container mt-1">
        <p class="fw-semibold fs-5">Tasks</p>

        <div class="row mt-3" id="tasks-container">
            @include('tasks.partials.tasks_list', ['tasks' => $tasks])
        </div>
    </div>

@endsection