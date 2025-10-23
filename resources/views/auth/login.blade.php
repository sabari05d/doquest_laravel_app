<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="shortcut icon" href="{{ asset('images/icon-512x512.png') }}" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DoQuest | Login</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    @if (env('APP_ENV') === 'local')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
        <script src="{{ asset('build/assets/app.js') }}" defer></script>
    @endif

</head>

<body class="bg-light d-flex justify-content-center align-items-center min-vh-100 my-4">

    <div class="card shadow-lg border-0 rounded-4 p-4 m-3 m-md-0" style="width: 400px;">
        <div class="text-center mb-4">
            <h4 class="fw-bold text-primary">Login to DoQuest</h4>
        </div>


        <div id="continueBox" class="d-none">
            <p class="text-center mb-0">Logged in Accounts</p>
            <div class="continue-box text-center mx-auto p-2 rounded-4 bg-white mb-0">
                <div id="savedAccountsContainer"></div>
            </div>
            <hr>
        </div>




        {{-- Login Form --}}
        <form id="loginForm" method="POST" action="{{ route('checkUser') }}">
            @csrf

            {{-- Email / Username --}}
            <div class="form-floating mb-3">
                <input type="text" class="form-control shadow-none" id="email"
                    placeholder="Email, username or mobile number" name="username" required>
                <label for="email">Username, email, or mobile number</label>
            </div>

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

            <div class="form-check my-2 mb-3">
                <input class="form-check-input" type="checkbox" name="remember_me" id="rememberMe">
                <label class="form-check-label" for="rememberMe">
                    Remember Me
                </label>
            </div>


            {{-- Submit button --}}
            <button type="submit" class="btn btn-primary w-100 rounded-3">Login</button>

            <hr>

            <p class="text-center">
                Don't have an account? <a class="text-decoration-none fw-semibold" href="{{ route('signUp') }}">Sign
                    Up</a>
            </p>

        </form>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const form = this;
            const password = document.getElementById('password').value.trim();
            const passwordError = document.getElementById('passwordError');

            // Frontend password validation
            const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$/;
            if (!pattern.test(password)) {
                passwordError.classList.remove('d-none');
                return;
            } else {
                passwordError.classList.add('d-none');
            }

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

                // ✅ Handle success
                if (response.ok && data.success) {
                    toastr.success(data.success);
                    setTimeout(() => {
                        window.location.href = data.redirect || '/dashboard';
                    }, 1000);
                }
                // ✅ Handle validation errors (422 with errors object)
                else if (response.status === 422 && data.errors) {
                    Object.values(data.errors).forEach(errArray => {
                        toastr.warning(errArray[0]);
                    });
                }
                // ✅ Handle custom error messages (like invalid credentials)
                else if (data.error) {
                    toastr.error(data.error); // <-- this will show "Invalid credentials."
                }
                // Default fallback
                else {
                    toastr.warning('Something went wrong!');
                }

            } catch (error) {
                console.error('Error:', error);
                toastr.error('Request failed. Please try again.');
            }
        });


    </script>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const accounts = JSON.parse(localStorage.getItem("doquestUsers")) || [];
            const container = document.getElementById('savedAccountsContainer');
            const continueBox = document.getElementById('continueBox');

            if (accounts.length > 0) {
                continueBox.classList.remove('d-none');

                accounts.forEach(user => {
                    const accountDiv = document.createElement('div');
                    accountDiv.classList.add('mb-2', 'p-2', 'border', 'rounded-3', 'shadow-sm');

                    accountDiv.innerHTML = `
                        <div class="d-flex align-items-center mb-2">
                            <img src="${user.profile}" class="rounded-circle border border-2 me-3" width="50" height="50">
                            <div class="text-start flex-grow-1">
                                <p class="mb-0 fw-semibold">@${user.username}</p>
                                <small class="text-muted">${user.email}</small>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-sm w-100 mb-1 continueBtn">Continue as @${user.username}</button>
                        <button class="btn btn-outline-danger btn-sm w-100 removeBtn">Remove account</button>
                    `;

                    // Continue button
                    accountDiv.querySelector('.continueBtn').addEventListener('click', () => {
                        document.getElementById('email').value = user.email;
                        document.getElementById('password').focus();
                    });

                    // Remove button
                    accountDiv.querySelector('.removeBtn').addEventListener('click', () => {
                        const updatedAccounts = accounts.filter(u => u.email !== user.email);
                        localStorage.setItem("doquestUsers", JSON.stringify(updatedAccounts));
                        accountDiv.remove();

                        if (updatedAccounts.length === 0) {
                            continueBox.classList.add('d-none');
                        }
                    });

                    container.appendChild(accountDiv);
                });
            }
        });
    </script>




    <script>
        document.getElementById('removeAccount').addEventListener('click', () => {
            localStorage.removeItem("doquestUser");
            document.getElementById('continueBox').classList.add('d-none');
        });
    </script>



    <script>
        // Frontend password validation
        document.getElementById('loginForm').addEventListener('submit', function (e) {
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
    </script>


</body>

</html>