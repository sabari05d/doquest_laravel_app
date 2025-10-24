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

