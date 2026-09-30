/**
 * Reusable SweetAlert2 helpers.
 *
 * Requires SweetAlert2 to already be loaded on the page:
 * <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
 *
 * Usage:
 *   confirmPopUp(
 *       'delete-form-1',
 *       'Hapus User?',
 *       'Data ini akan dihapus permanen!'
 *   );
 *
 *   confirmWithInput(
 *       'cancel-form',
 *       'Batalkan Peminjaman?',
 *       'Yakin ingin membatalkan kegiatan ini?',
 *       'Alasan pembatalan'
 *   );
 *
 *   fireToast('success', 'User berhasil dihapus');
 */


/* =========================================================
 * CONFIRMATION POPUP
 * ========================================================= */

const PopUp = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-success",
        cancelButton: "btn btn-danger",
    },
    buttonsStyling: true,
});


/**
 * Show a confirmation dialog before submitting a form.
 *
 * @param {string} formId - id of the <form> to submit
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
            const form = document.getElementById(formId);

            if (form) {
                form.submit();
            }
        }
    });
}


/* =========================================================
 * CONFIRMATION POPUP WITH INPUT
 * ========================================================= */


/**
 * Show a confirmation dialog with a textarea input
 * before submitting a form.
 *
 * @param {string} formId - id of the <form> to submit
 * @param {string} title - dialog title
 * @param {string} text - dialog body text
 * @param {string} inputLabel - label displayed above textarea
 * @param {string} inputPlaceholder - placeholder inside textarea
 * @param {string} inputName - hidden input id where the value will be stored
 */
function confirmWithInput(
    formId,
    title,
    text,
    inputLabel = "Alasan",
    inputPlaceholder = "Masukkan alasan...",
    inputName = "alasan"
) {

    PopUp.fire({
        title: title,

        html: text,

        input: "textarea",

        inputLabel: inputLabel,

        inputPlaceholder: inputPlaceholder,

        inputValidator: (value) => {

            if (!value || value.trim().length === 0) {
                return `${inputLabel} wajib diisi.`;
            }

        },

        icon: "warning",

        showCancelButton: true,

        confirmButtonText: "Ya",

        cancelButtonText: "Batal",

        reverseButtons: true,

    }).then((result) => {

        if (result.isConfirmed) {

            const form = document.getElementById(formId);

            const input = document.getElementById(inputName);

            if (!form) {
                console.error(`Form dengan id "${formId}" tidak ditemukan.`);
                return;
            }

            if (!input) {
                console.error(`Input dengan id "${inputName}" tidak ditemukan.`);
                return;
            }

            input.value = result.value;

            form.submit();
        }

    });
}

/* =========================================================
 * TOAST
 * ========================================================= */

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
 *
 * @param {string} icon - 'success' | 'error' | 'warning' | 'info' | 'question'
 * @param {string} title - message to display
 */
function fireToast(icon, title) {
    Toast.fire({
        icon: icon,
        title: title
    });
}
