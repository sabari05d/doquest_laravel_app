<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="shortcut icon" href="{{ asset('images/icon-512x512.png') }}" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DoQuest | Registeration</title>

    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <script src="{{ asset('js/auth.js') }}" defer></script>
    <script src="{{ asset('js/swal.js') }}" defer></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light d-flex justify-content-center align-items-center min-vh-100">

    <div class="card shadow-lg border-0 rounded-4 p-4 m-3 m-md-0" style="width: 400px;">
        <div class="text-center mb-2">
            <h4 class="fw-bold text-primary">Welcome to DoQuest</h4>
        </div>

        {{-- Register Form --}}
        <form id="registerForm" method="POST" action="{{ route('registerUser') }}">
            @csrf

            {{-- Email / Username --}}
            <div class="form-floating mb-3">
                <input type="text" class="form-control shadow-none" id="name" placeholder="Name" name="name" required>
                <label for="name">Name</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control shadow-none" id="username" placeholder="Username" name="username"
                    required>
                <label for="username">Username</label>
            </div>
            <div id="usernameError" class="text-danger small mb-2 d-none"></div>

            <div class="form-floating mb-3">
                <input type="email" class="form-control shadow-none" id="email" placeholder="Email" name="email"
                    required>
                <label for="email">Email</label>
            </div>
            <div id="emailError" class="text-danger small mb-2 d-none"></div>


            <div class="form-floating mb-3">
                <input type="text" class="form-control shadow-none" id="mobile" placeholder="Mobile Number"
                    name="mobile" inputmode="numeric" pattern="[0-9]{10}">
                <label for="mobile">Mobile Number</label>
            </div>
            <div id="mobileError" class="text-danger small mb-2 d-none"></div>


            {{-- Password --}}
            <div class="form-floating mb-3 position-relative">
                <input type="password" class="form-control pe-5 shadow-none" id="password" name="password"
                    placeholder="Enter Password" required
                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$"
                    title="Password must be at least 8 characters long, include uppercase, lowercase, number, and special character." />
                <label for="password">Password</label>

                <!-- Eye toggle -->
                <span id="togglePassword" style="
                    position: absolute;
                    right: 10px;
                    top: 50%;
                    transform: translateY(-50%);
                    cursor: pointer;
                    color: #555;
                ">
                    <i class="bi bi-eye-slash" id="toggleIcon"></i>
                </span>
            </div>




            {{-- Validation message --}}
            <div id="passwordError" class="text-danger small mb-2 d-none">
                Password must be at least 8 characters long and include uppercase, lowercase, number, and special
                character.
            </div>

            {{-- Submit button --}}
            <button type="submit" class="btn btn-primary w-100 rounded-3">Sign Up</button>

            <hr>

            <p class="text-center">
                Already have an account?
                <a class="text-decoration-none fw-semibold" href="{{ route('login') }}">Sign In</a>
            </p>

        </form>
    </div>

    <!-- Password Validation + Submit -->
    <script>
        document.getElementById('registerForm').addEventListener('submit', async function (e) {
            e.preventDefault(); // prevent full reload

            const form = this;
            const password = document.getElementById('password').value.trim();
            const passwordError = document.getElementById('passwordError');
            const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$/;

            // Password check
            if (!pattern.test(password)) {
                passwordError.classList.remove('d-none');
                return;
            } else {
                passwordError.classList.add('d-none');
            }

            // Send form data
            const formData = new FormData(form);
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.message) {
                    toastr.success(data.message);

                    // Redirect after 1s delay so user sees success
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);
                } else if (data.errors) {
                    Object.values(data.errors).forEach(errArray => {
                        toastr.warning(errArray[0]);
                    });
                } else {
                    toastr.warning('Something went wrong!');
                }

            } catch (error) {
                console.error('Error:', error);
                toastr.error('Request failed. Please try again.');
            }
        });
    </script>


    <!-- Tosters -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Success from session
            @if(session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            // Error from session
            @if(session('error'))
                toastr.error("{{ session('error') }}");
            @endif

            // Validation errors
            @if($errors->any())
                @foreach($errors->all() as $error)
                    toastr.warning("{{ $error }}");
                @endforeach
            @endif
        });
    </script>


    <!-- Validate Fields -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const fields = ['username', 'email', 'mobile'];

            fields.forEach(field => {
                const input = document.getElementById(field);
                const errorDiv = document.getElementById(field + 'Error');

                input.addEventListener('blur', () => {
                    const value = input.value.trim();
                    if (!value) return;

                    fetch("{{ route('check.unique') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ field, value })
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.exists) {
                                errorDiv.textContent = field.charAt(0).toUpperCase() + field.slice(1) + " already exists!";
                                errorDiv.classList.remove('d-none');
                                toastr.error(errorDiv.textContent);
                            } else {
                                errorDiv.textContent = '';
                                errorDiv.classList.add('d-none');
                            }
                        })
                        .catch(err => console.error(err));
                });
            });

            // Password validation
            const form = document.getElementById('registerForm');
            form.addEventListener('submit', function (e) {
                const password = document.getElementById('password').value;
                const passwordError = document.getElementById('passwordError');
                const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$/;

                if (!pattern.test(password)) {
                    e.preventDefault();
                    passwordError.classList.remove('d-none');
                } else {
                    passwordError.classList.add('d-none');
                }
            });
        });

    </script>


</body>

</html>