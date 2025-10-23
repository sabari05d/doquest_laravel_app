@forelse($tasks as $date => $dayTasks)
    <div class="timeline-date mb-2 mt-4 text-center text-muted">
        {{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}
    </div>

    @if(is_iterable($dayTasks))
        <div class="row">
            @foreach($dayTasks as $task)
                @if($task && is_object($task))
                    <div class="col-12 col-md-6 col-lg-4 mb-2" id="task-{{ $task->id }}">
                        <div class="card shadow-sm border-0 d-flex flex-row align-items-center p-2">
                            <!-- Checkbox -->
                            <div class="form-check mx-2">
                                <input class="form-check-input" type="checkbox" {{ $task->status == 1 ? 'checked' : '' }}
                                    onchange="toggleTaskStatus({{ $task->id }}, this.checked)">
                            </div>

                            <!-- Content -->
                            <div class="flex-grow-1">
                                <p class="card-title fw-semibold mb-1 text-capitalize">{{ $task->title }}</p>
                                <p class="card-text text-muted mb-0" style="font-size: 0.9rem;">
                                    {{ \Carbon\Carbon::parse($task->task_date . ' ' . $task->task_time)->diffForHumans() }}
                                </p>
                            </div>

                            <!-- Dropdown -->
                            <div class="dropdown ms-3">
                                <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)"
                                            onclick="showMdModal('{{ route('openTaskModal', $task->id) }}', 'EDIT TASK');">
                                            Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="javascript:void(0)"
                                            onclick="deleteTask('{{ route('deleteTask', $task->id) }}')"> Delete </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
@empty
    <p class="p-2">No tasks found.</p>
@endforelse