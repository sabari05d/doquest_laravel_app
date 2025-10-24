<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to DoQuest</title>
    <link rel="shortcut icon" href="{{ asset('images/icon-512x512.png') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <script src="{{ asset('js/auth.js') }}" defer></script>
    <script src="{{ asset('js/swal.js') }}" defer></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="d-flex flex-column align-items-center justify-content-center vh-100 m-3 m-md-0">

    <div class="card  text-center shadow p-4 w-100 d-flex justify-content-center align-items-center border-0 m-3 m-md-0"
        style="max-width: 500px; border-radius: 1rem; height: 50dvh;">
        <div id="onboard-carousel" class="carousel slide" data-bs-interval="false">
            <div class="carousel-inner">

                <!-- Slide 1 -->
                <div class="carousel-item active">
                    <div class="mb-3 d-flex justify-content-center">
                        <img src="{{ asset('images/icon-512x512.png') }}" alt="Logo"
                            style="max-width: 150px; border-radius: 50%; padding: 5px;">
                    </div>
                    <h1 class="mb-3 fw-bold" style="color: #0d6efd;">Welcome to DoQuest</h1>
                    <p class="text-muted mb-4 fs-5">
                        Turn your daily tasks into achievements and embark on your personal quest.
                    </p>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item">
                    <h1 class="mb-3 fw-bold" style="color: #0d6efd;">What You Can Do</h1>
                    <ul class="list-group text-center mb-4 border-0">
                        <li class="list-group-item p-2 border-0">✅ Create & manage tasks</li>
                        <li class="list-group-item p-2 border-0">⏳ Set deadlines & reminders</li>
                        <li class="list-group-item p-2 border-0">🎯 Track your progress like a quest</li>
                    </ul>
                </div>

                <!-- Slide 3 -->
                <div class="carousel-item">
                    <h1 class="mb-3 fw-bold" style="color: #0d6efd;">Your Privacy Matters</h1>
                    <p class="text-muted mb-3 fs-6">
                        We respect your privacy. Your data is stored safely on your device.
                    </p>
                    <div class="form-check d-flex justify-content-center mb-3">
                        <input class="form-check-input" type="checkbox" id="termsCheck">
                        <label class="form-check-label ms-2" for="termsCheck">I agree to the terms</label>
                    </div>
                </div>
            </div>

            <!-- Navigation buttons -->
            <div class="d-flex justify-content-between mt-3">
                <button class="btn btn-secondary" id="prevBtn">Back</button>
                <button class="btn btn-primary" id="nextBtn">Next</button>
                <button class="btn btn-success d-none" id="getStarted">Get Started</button>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const hasSeenOnboard = localStorage.getItem("hasSeenOnboard");

            if (hasSeenOnboard) {
                // Returning user → redirect to login
                window.location.href = "{{ route('signUp') }}";
            }
        });
    </script>


    <script>


        document.addEventListener("DOMContentLoaded", () => {
            const carousel = document.querySelector("#onboard-carousel");
            const bsCarousel = new bootstrap.Carousel(carousel, { interval: false });

            const prevBtn = document.getElementById("prevBtn");
            const nextBtn = document.getElementById("nextBtn");
            const getStartedBtn = document.getElementById("getStarted");
            const termsCheck = document.getElementById("termsCheck");
            const slides = carousel.querySelectorAll(".carousel-item");
            let currentIndex = 0;

            const updateButtons = () => {
                prevBtn.style.visibility = currentIndex === 0 ? "hidden" : "visible";
                nextBtn.classList.toggle("d-none", currentIndex === slides.length - 1);
                getStartedBtn.classList.toggle("d-none", currentIndex !== slides.length - 1);
            };

            prevBtn.addEventListener("click", () => {
                if (currentIndex > 0) {
                    currentIndex--;
                    bsCarousel.to(currentIndex);
                    updateButtons();
                }
            });

            nextBtn.addEventListener("click", () => {
                if (currentIndex < slides.length - 1) {
                    currentIndex++;
                    bsCarousel.to(currentIndex);
                    updateButtons();
                }
            });

            getStartedBtn.addEventListener("click", () => {
                if (!termsCheck.checked) {
                    // alert("Please agree to the terms to continue.");
                    toastr.warning("Please agree to the terms to continue.");
                    return;
                }
                localStorage.setItem("hasSeenOnboard", "true");
                window.location.href = "{{ route('signUp') }}";
            });

            updateButtons(); // initialize
        });
    </script>

</body>

</html>