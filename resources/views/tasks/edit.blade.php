<div class="row">
    <div class="col-12">
        <form action="{{ route('saveTask', $task->id) }}" id="editTaskForm" method="post">
            @csrf
            <div class="row">
                {{-- Task Title --}}
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control shadow-none" id="task_title" placeholder="Title"
                            name="title" required value="{{ $task->title }}">
                        <label for="task_title">Title</label>
                    </div>
                </div>

                {{-- Task Date --}}
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input type="date" class="form-control shadow-none" id="task_date" placeholder="Task Date"
                            name="task_date" required
                            value="{{ \Carbon\Carbon::parse($task->task_date)->format('Y-m-d') }}">
                        <label for="task_date">Task Date</label>
                    </div>
                </div>

                {{-- Task Time --}}
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input type="time" class="form-control shadow-none" id="task_time" placeholder="Task Time"
                            name="task_time" value="{{ \Carbon\Carbon::parse($task->task_time)->format('H:i') }}">
                        <label for="task_time">Task Time</label>
                    </div>
                </div>

                {{-- Reminder DateTime --}}
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input type="datetime-local" class="form-control shadow-none" id="reminder_datetime"
                            placeholder="Reminder Date & Time" name="reminder_datetime"
                            value="{{ \Carbon\Carbon::parse($task->reminder_datetime)->format('Y-m-d\TH:i') }}">
                        <label for="reminder_datetime">Reminder Date & Time</label>
                    </div>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="row mt-1">
                <div class="col-md-12 text-end">
                    <button class="btn btn-secondary add-buttons" data-bs-dismiss="modal" type="button">Close</button>
                    <button class="btn btn-primary add-buttons" id="updateBtn" type="submit">Update Task</button>
                </div>
            </div>
        </form>
    </div>
</div>
