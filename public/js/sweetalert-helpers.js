/**
 * Reusable SweetAlert2 helpers.
 * Requires SweetAlert2 to already be loaded on the page:
 * <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
 *
 * Usage:
 *   <button type="button" onclick="confirmPopUp('delete-form-1', 'Hapus User?', 'Data ini akan dihapus permanen!')">Delete</button>
 *   fireToast('success', 'User berhasil dihapus');
 */

const PopUp = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-success",
        cancelButton: "btn btn-danger",
    },
    buttonsStyling: true,
});

/**
 * Show a confirmation dialog before submitting a form.
 * @param {string} formId - id of the <form> to submit when confirmed
 * @param {string} title - dialog title
 * @param {string} text - dialog body text
 */
function confirmPopUp(formId, title, text) {
    PopUp.fire({
        title: title,
        text: text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya",
        cancelButtonText: "Batal",
        reverseButtons: true, 
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}

const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    showCloseButton: true,

    showClass: {
        popup: "",
        icon: "",
    },
    hideClass: {
        popup: "",
    },

    didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
});

/**
 * Show a toast notification.
 * @param {string} icon - 'success' | 'error' | 'warning' | 'info' | 'question'
 * @param {string} title - message to display
 */
function fireToast(icon, title) {
    Toast.fire({ 
        icon: icon, 
        title: title 
    });
}
