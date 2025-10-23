<div class="row">
    <div class="col-12">

        <form action="{{ route('saveTask') }}" id="addTaskForm" method="post">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control shadow-none" id="task_title" placeholder="Title"
                            name="title" required>
                        <label for="task_title">Title</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input type="date" class="form-control shadow-none" id="task_date" placeholder="Task Date"
                            name="task_date" required>
                        <label for="task_date">Task Date</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input type="time" class="form-control shadow-none" id="task_time" placeholder="Task Time"
                            name="task_time">
                        <label for="task_time">Task Time</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input type="datetime-local" class="form-control shadow-none" id="reminder_datetime"
                            placeholder="Reminder Date & Time" name="reminder_datetime">
                        <label for="reminder_datetime">Reminder Date & Time</label>
                    </div>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-md-12 text-end">
                    <button class="btn btn-secondary add-buttons" data-bs-dismiss="modal" type="button">Close</button>
                    <button class="btn btn-primary add-buttons" id="saveBtn" type="submit">Save Task</button>
                </div>
            </div>
        </form>

    </div>
</div>