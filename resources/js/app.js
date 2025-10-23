// import everything from bootstrap
import * as bootstrap from 'bootstrap'
import 'bootstrap-icons/font/bootstrap-icons.css';

// Import Toastr CSS
import 'toastr/build/toastr.min.css';

// Import Toastr JS
import toastr from 'toastr';

import Swal from 'sweetalert2';

// Make it globally accessible
window.Swal = Swal;


// make it globally accessible
window.bootstrap = bootstrap;
window.toastr = toastr;


document.addEventListener('DOMContentLoaded', () => {
    console.log('Bootstrap JS loaded!');
    if (toastr) {
        console.log("Toster Loaded");
    }
});


// Tosters
toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "timeOut": "3000"
};
// Tosters

// Modals (Vanilla JS)

// Small / Medium Modal
function showMdModal(url, title) {
    const modalElement = document.getElementById('modal_md');
    const modalTitle = modalElement.querySelector('.modal-title');
    const modalBody = modalElement.querySelector('.modal-body');

    // Show loading state
    modalTitle.textContent = title || '';
    modalBody.innerHTML = '<div class="text-center p-3">Loading...</div>';

    // Show modal using Bootstrap's native JS API
    const modal = new bootstrap.Modal(modalElement, {
        backdrop: true,
        keyboard: true
    });
    modal.show();

    // Fetch modal content
    fetch(url)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then(html => {
            modalBody.innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading modal content:', error);
            modalBody.innerHTML = `<div class="text-danger p-3 text-center">Failed to load content.</div>`;
        });
}

// Large Modal
function showLgModal(url, title) {
    const modalElement = document.getElementById('modal_lg');
    const modalTitle = modalElement.querySelector('.modal-title');
    const modalBody = modalElement.querySelector('.modal-body');

    modalTitle.textContent = title || '';
    modalBody.innerHTML = '<div class="text-center p-3">Loading...</div>';

    const modal = new bootstrap.Modal(modalElement, {
        backdrop: true,
        keyboard: true
    });
    modal.show();

    fetch(url)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then(html => {
            modalBody.innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading modal content:', error);
            modalBody.innerHTML = `<div class="text-danger p-3 text-center">Failed to load content.</div>`;
        });
}


// ✅ Make globally accessible
window.showMdModal = showMdModal;
window.showLgModal = showLgModal;

import { initializeApp } from "firebase/app";
import { getMessaging, getToken, onMessage } from "firebase/messaging";

// Your Firebase web config
const firebaseConfig = {
    apiKey: "AIzaSyBLzrO5E1q03crRuHbZQppSs7gYkq6_khM",
    authDomain: "doquest-notifications.firebaseapp.com",
    projectId: "doquest-notifications",
    storageBucket: "doquest-notifications.firebasestorage.app",
    messagingSenderId: "435184923179",
    appId: "1:435184923179:web:1ca639f48703b5c438f06e"
};

// Initialize Firebase
const firebaseApp = initializeApp(firebaseConfig);
const messaging = getMessaging(firebaseApp);

// Replace this with your auth token if needed
const authToken = localStorage.getItem('auth_token') || '';

// Register for push notifications
document.addEventListener('DOMContentLoaded', () => {
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    if (!csrfMeta) {
        console.error('CSRF meta tag not found!');
        return;
    }
    const csrfToken = csrfMeta.getAttribute('content');

    async function registerForPush() {
        try {
            const currentToken = await getToken(messaging, { vapidKey: "BBgE3zDQ66YdmqBeUmCss3ZQumHFXmIluoEh8eHA2XLm0xF8JUH9P6yTdMNolAX4OeuFkRrnj3k2EnAW5Nai2ck" });
            if (currentToken) {
                await fetch('/device-token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Authorization': 'Bearer ' + authToken
                    },
                    body: JSON.stringify({ token: currentToken, platform: 'web' })
                });
            }
        } catch (err) {
            console.error('Error getting token', err);
        }
    }

    
    // Handle foreground messages
    onMessage(messaging, (payload) => {
        console.log('Message received:', payload);
    
        // Example: show a toast notification
        if (window.toastr) {
            toastr.info(payload.notification?.title || 'Notification', payload.notification?.body || '');
        }
    });
    
    // Call registration on page load
    registerForPush();
});

