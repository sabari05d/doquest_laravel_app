@extends('index')
@section('title', 'Check Lists')
@section('content')
    <div class="container mt-4">
        <div class="card border-0 shadow mb-3">
            <div class="card-body">
                <h2 class="text-center mb-4">Check List</h2>
                {{-- Add New Group --}}
                <form action="{{ route('checklist.addGroup') }}" method="POST" class="mb-4">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="title" class="form-control shadow-none" placeholder="Add New Group"
                            required>
                        <button class="btn btn-primary">Add Group</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Groups --}}
        <div class="row g-3">
            @forelse ($groups as $group)
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card shadow border-0 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="fw-semibold text-capitalize">{{ $group->title }}</h5>
                                <div>
                                    <form action="{{ route('checklist.clearItems', $group->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-warning btn-sm" title="Clear all items">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('checklist.deleteGroup', $group->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" title="Delete Group">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- Add new item --}}
                            <form action="{{ route('checklist.addItem', $group->id) }}" method="POST" class="mb-3">
                                @csrf
                                <div class="input-group">
                                    <input type="text" name="text" class="form-control shadow-none"
                                        placeholder="Add New Checklist" required>
                                    <button class="btn btn-success">Add</button>
                                </div>
                            </form>

                            {{-- Items list --}}
                            @if ($group->items->count() > 0)
                                <ul class="list-group">
                                    @foreach ($group->items as $item)
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                            <form action="{{ route('checklist.toggleItem', $item->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" {{ $item->status === 'finished' ? 'checked' : '' }} onChange="this.form.submit()">
                                                    <label
                                                        class="form-check-label {{ $item->status === 'finished' ? 'text-decoration-line-through text-muted' : '' }}">
                                                        {{ $item->text }}
                                                    </label>
                                                </div>
                                            </form>

                                            <form action="{{ route('checklist.deleteItem', $item->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-center text-muted">No checklists added yet</div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted mt-4">No checklist groups yet</div>
            @endforelse
        </div>
    </div>
@endsection