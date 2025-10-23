<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DoQuest | @yield('title', 'Dashboard')</title>
    <link rel="shortcut icon" href="{{ asset('images/icon-512x512.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <script src="{{ asset('js/auth.js') }}" defer></script>
    <script src="{{ asset('js/swal.js') }}" defer></script>

    @if (env('APP_ENV') === 'production')
        <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
        <script src="{{ asset('build/assets/app.js') }}" defer></script>
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif


    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            overflow-x: hidden;
        }

        /* Desktop Sidebar */
        .sidebar-desktop {
            width: 240px;
            position: fixed;
            top: 56px;
            /* below navbar */
            left: 0;
            height: 100vh;
            background-color: #212529;
            padding: 1rem;
            color: white;
            z-index: 1000;
        }

        .sidebar-desktop a {
            color: white;
            text-decoration: none;
            display: block;
            /* margin: 0.5rem 0; */
            font-weight: 500;
        }

        .sidebar-desktop a:hover {
            color: #dc3545;
        }

        /* Main Content */
        .main-content {
            margin-top: 56px;
            /* navbar height */
            padding: 1rem;
            background-color: #f8f9fa;
            min-height: 93dvh;
        }

        @media (min-width: 768px) {
            .main-content {
                margin-left: 240px;
                /* desktop sidebar width */
            }
        }

        .task-add-btn {
            background-color: #fb8500 !important;
            position: fixed;
            bottom: 35px;
            right: 35px;
            border-radius: 100% !important;
            height: 40px;
            width: 40px;
            font-weight: 800;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark fixed-top shadow-sm px-3">
        <div class="container-fluid">
            <!-- Mobile Menu Button -->
            <button class="btn btn-outline-light border-0 d-md-none" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                <i class="bi bi-list fs-4"></i>
            </button>

            <!-- Brand -->
            <a class="navbar-brand fw-bold text-danger ms-2" href="{{ route('dashboard') }}">
                DoQuest
            </a>

            <!-- User Info Dropdown -->
            <div class="dropdown">
                <a class="d-flex align-items-center text-white text-decoration-none" href="#" id="userDropdown"
                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ !empty(Auth::user()->profile) ? asset(Auth::user()->profile) : asset('images/default-avatar.jpg') }}"
                        alt="User Avatar" class="rounded-circle me-2" width="35" height="35">
                    <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'Guest' }}</span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ route('profile') }}">Profile</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                            @csrf
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>


        </div>
    </nav>

    <!-- Desktop Sidebar -->
    <div class="d-none d-md-block sidebar-desktop">
        <nav class="nav flex-column">
            <a href="{{ route('dashboard') }}" class="nav-link text-white fw-semibold">Dashboard</a>
            <a href="{{ route('tasks') }}" class="nav-link text-white fw-semibold">Tasks</a>
            <a href="{{ route('checklist.index')}}" class="nav-link text-white fw-semibold">Check List</a>
        </nav>
    </div>

    <!-- Mobile Offcanvas Sidebar -->
    <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="mobileSidebar"
        aria-labelledby="mobileSidebarLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold text-danger" id="mobileSidebarLabel">DoQuest</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body  border-0">
            <nav class="nav flex-column">
                <a href="{{ route('dashboard') }}" class="nav-link text-white fw-semibold">Dashboard</a>
                <a href="{{ route('tasks') }}" class="nav-link text-white fw-semibold">Tasks</a>
                <a href="{{ route('checklist.index')}}" class="nav-link text-white fw-semibold">Check List</a>
            </nav>
        </div>
    </div>

    <!-- Page Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <button type="button" class="btn task-add-btn" onclick="showMdModal('{{ route('openTaskModal') }}','ADD TASK');">
        <i class="bi bi-plus-lg"></i>
    </button>





    <!-- Modal box Start Md-->
    <div id="modal_md" class="modal fade custom-content" role="dialog">
        <div class="modal-dialog modal-md">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 id="myModalLabel" class="modal-title m-0 text-white"></h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
    <!-- Modal Box End Md-->
    <!-- Modal box Start Lg-->
    <div id="modal_lg" class="modal fade custom-content" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 id="myModalLabel" class="modal-title m-0 text-white"></h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
    <!-- Modal Box End lg-->

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const currentUser = {
                id: "{{ Auth::user()->id }}",
                username: "{{ Auth::user()->username ?? Auth::user()->name }}",
                email: "{{ Auth::user()->email }}",
                profile: "{{ Auth::user()->profile_image ? asset(Auth::user()->profile_image) : asset('images/default-avatar.jpg') }}"
            };

            // Get existing accounts
            let accounts = JSON.parse(localStorage.getItem("doquestUsers")) || [];

            // Check if user already exists
            if (!accounts.find(u => u.email === currentUser.email)) {
                accounts.push(currentUser);
                localStorage.setItem("doquestUsers", JSON.stringify(accounts));
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            if ("Notification" in window) {
                Notification.requestPermission().then(permission => {
                    console.log("Notification permission:", permission);
                });
            }
            let shownReminders = new Set();
            // Poll backend every 30 seconds (optional)
            setInterval(async () => {
                const res = await fetch('/check-reminders');
                const data = await res.json();
                data.reminders.forEach(reminder => {
                    if (!shownReminders.has(reminder.id)) {
                        new Notification("Task Reminder", {
                            body: reminder.title,
                            icon: "{{ asset('images/icon-512x512.png') }}"
                        });
                        shownReminders.add(reminder.id);
                    }
                });
            }, 10000);
        });
    </script>





    <!-- Task Scripts -->
    <script>

        function fetchTasks() {
            fetch('/fetch-tasks', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('tasks-container').innerHTML = data.tasks_html;
                })
                .catch(err => console.error(err));
        }

        fetchTasks();


        // Delete/Update status of Tasks 

        const updateTaskRoute = (id) => "{{ route('updatStatus', ['id' => ':id']) }}".replace(':id', id);
        const deleteTaskRoute = (id) => "{{ route('deleteTask', ['id' => ':id']) }}".replace(':id', id);

        function toggleTaskStatus(taskId, isChecked) {
            fetch(updateTaskRoute(taskId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ status: isChecked ? 1 : 0 })
            })
                .then(res => res.json())
                .then(data => {
                    console.log(data)
                    fetchTasks()
                })
                .catch(err => console.error(err));
        }
        function deleteTask(url) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to delete this task!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                background: '#ffffff',
                color: '#000'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            console.log(data);
                            fetchTasks()
                        })
                        .catch(err => console.error(err));
                }
            });
        }


        // Save/Update Tasks 
        document.addEventListener('DOMContentLoaded', () => {
            // Delegated submit handler for dynamically injected forms
            document.addEventListener('submit', async function (e) {
                const form = e.target;

                // Only handle the forms you care about:
                if (!form || (form.id !== 'addTaskForm' && form.id !== 'editTaskForm')) return;

                e.preventDefault();

                // prevent double-submit (multi-click)
                if (form.dataset.submitting === 'true') return;
                form.dataset.submitting = 'true';

                // find the submit button inside the form (first one)
                const submitBtn = form.querySelector('[type="submit"]');
                const originalBtnHtml = submitBtn ? submitBtn.innerHTML : null;

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving...';
                }

                // Build FormData
                const formData = new FormData(form);

                // Use form.action (works because action remains set in the partial)
                const url = form.action || "{{ route('saveTask') }}"; // fallback if missing

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': form.querySelector('input[name="_token"]')?.value || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    // Try parse JSON safely
                    let data = null;
                    try { data = await response.json(); } catch (err) { /* non-json */ }

                    if (response.ok && data && data.message) {
                        // success
                        toastr.success(data.message);

                        // close modal (works with bootstrap 5)
                        const modalEl = document.getElementById('modal_lg');
                        if (modalEl) {
                            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                            modal.hide();
                        }

                        // reload or update UI — reload after short delay to show toast
                        setTimeout(() => window.location.reload(), 600);
                        return;
                    }

                    // Not OK: show message if available
                    toastr.warning((data && (data.message || data.errors)) ? (data.message || 'Validation error') : 'Something went wrong!');

                    // restore button & state
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnHtml;
                    }
                    form.dataset.submitting = 'false';

                } catch (err) {
                    console.error('Submit error', err);
                    toastr.error('Network or server error occurred.');

                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnHtml;
                    }
                    form.dataset.submitting = 'false';
                }

            }); // end delegated submit listener
        });
    </script>



</body>

</html>