<!DOCTYPE html>
<html>

<head>
    <title>Landing</title>
</head>

<body>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const hasSeenOnboard = localStorage.getItem("hasSeenOnboard");

            if (!hasSeenOnboard) {
                // First-time user → redirect to onboard
                window.location.href = "{{ route('onboard') }}";
            } else {
                // Returning user → redirect to login
                window.location.href = "{{ route('login') }}";
            }
        });
    </script>
</body>

</html>