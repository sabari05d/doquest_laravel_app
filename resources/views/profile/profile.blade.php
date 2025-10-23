@extends('index')
@section('title', 'Profile')
@section('content')

    <div class="container ">
        <div class="row justify-content-center">
            <div class="col-11 col-md-9 col-lg-6">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="card-title fs-4 fw-semibold text-center mb-3">
                            Profile Details
                        </div>
                        <form method="post" action="{{ route('updateProfile') }}" id="profileForm">
                            @csrf

                            {{-- Email / Username --}}
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control shadow-none" id="name" placeholder="Name" name="name"
                                    required value="<?= $user->name ?>">
                                <label for="name">Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control shadow-none" id="username" placeholder="Username"
                                    name="username" required value="<?= $user->username ?>">
                                <label for="username">Username</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control shadow-none" id="email" placeholder="Email"
                                    name="email" required value="<?= $user->email ?>">
                                <label for="email">Email</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control shadow-none" id="mobile" placeholder="Mobile Number"
                                    name="mobile" value="<?= $user->mobile ?>">
                                <label for="mobile">Mobile Number</label>
                            </div>
                            <div id="mobileError" class="text-danger small mb-2 d-none"></div>

                            <button type="submit" class="btn btn-success w-100">Update Profile</button>
                        </form>

                        <div class="row">
                            <div class="col-12 mt-3">
                                <p class="mb-0 fw-bold">
                                    Email verify
                                    <span class="float-end">
                                        <?php if ($user->is_email_verified) { ?>
                                        <p class="text-success">
                                            Email Verified at <?= date('d-m-Y', strtotime($user->email_verified_at)) ?>
                                        </p>
                                        <?php } else { ?>
                                        <span class="cursor text-warning fw-5">
                                            <button type="button" class="btn btn-outline-warning">Verify Email</button>
                                        </span>
                                        <?php }  ?>
                                    </span>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>


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
            const form = document.getElementById('loginForm');
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

    <!-- Save -->
    <script>
        document.getElementById('profileForm').addEventListener('submit', function (e) {
            e.preventDefault(); // stop normal form submission

            const form = this;
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {

                    if (data.message) {
                        toastr.success(data.message);
                        window.location.reload();
                    } else if (data.errors) {
                        toastr.warning('Something went wrong!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.warning('Request Error!');
                });
        });
    </script>


@endsection