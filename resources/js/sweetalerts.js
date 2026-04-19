/**
 * SweetAlert2 Standardized Functions
 * Centralized alert management for consistent UI/UX
 */

// Success Alert
export function showSuccessAlert(title = "Berhasil!", message = "") {
    return Swal.fire({
        target: "body",
        icon: "success",
        title: title,
        text: message,
        confirmButtonColor: "#3b82f6",
        timer: 3000,
        timerProgressBar: true,
        heightAuto: false,
    });
}

// Error Alert
export function showErrorAlert(title = "Error!", message = "") {
    return Swal.fire({
        target: "body",
        icon: "error",
        title: title,
        html: message,
        confirmButtonColor: "#ef4444",
        heightAuto: false,
    });
}

// Info/Warning Alert
export function showWarningAlert(title = "Perhatian", message = "") {
    return Swal.fire({
        target: "body",
        icon: "warning",
        title: title,
        html: message,
        confirmButtonColor: "#f59e0b",
        heightAuto: false,
    });
}

// Confirmation Dialog - Create
export function showCreateConfirmation() {
    return Swal.fire({
        target: "body",
        icon: "info",
        title: "Tambah Data",
        text: "Anda akan menambahkan data baru",
        showCancelButton: true,
        confirmButtonColor: "#3b82f6",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Ya, Lanjutkan",
        cancelButtonText: "Batal",
        heightAuto: false,
    });
}

// Confirmation Dialog - Edit
export function showEditConfirmation() {
    return Swal.fire({
        target: "body",
        icon: "info",
        title: "Edit Data",
        text: "Anda akan memperbarui data",
        showCancelButton: true,
        confirmButtonColor: "#3b82f6",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Ya, Simpan",
        cancelButtonText: "Batal",
        heightAuto: false,
    });
}

// Confirmation Dialog - Delete
export function showDeleteConfirmation(dataName) {
    return Swal.fire({
        target: "body",
        icon: "warning",
        title: "Hapus Data?",
        html: `<p>Anda akan menghapus data: <br><strong>${dataName}</strong></p><p class="text-sm text-slate-600 mt-2">Tindakan ini tidak dapat dibatalkan.</p>`,
        showCancelButton: true,
        confirmButtonColor: "#dc2626",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Ya, Hapus",
        cancelButtonText: "Batal",
        heightAuto: false,
    });
}

// Loading Alert
export function showLoadingAlert(message = "Memproses...") {
    return Swal.fire({
        target: "body",
        title: message,
        allowOutsideClick: false,
        allowEscapeKey: false,
        heightAuto: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
}

// Session Success Handler
export function handleSessionSuccess(message) {
    showSuccessAlert("Berhasil!", message);
}

// Session Error Handler
export function handleSessionError(errors) {
    const errorMessage = Array.isArray(errors) ? errors.join("<br>") : errors;
    showErrorAlert("Error!", errorMessage);
}

// Delete with form submission
export function deleteUserWithConfirmation(userId, userName) {
    showDeleteConfirmation(userName).then((result) => {
        if (result.isConfirmed) {
            showLoadingAlert("Menghapus...");
            const form = document.createElement("form");
            form.method = "POST";
            form.action = `/users/${userId}`;

            // Create CSRF token from meta tag
            const csrfToken =
                document.querySelector('meta[name="csrf-token"]')?.content ||
                "";

            form.innerHTML = `
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="DELETE">
            `;

            document.body.appendChild(form);
            form.submit();
        }
    });
}

if (typeof window !== "undefined") {
    window.showSuccessAlert = showSuccessAlert;
    window.showErrorAlert = showErrorAlert;
    window.showWarningAlert = showWarningAlert;
    window.showCreateConfirmation = showCreateConfirmation;
    window.showEditConfirmation = showEditConfirmation;
    window.showDeleteConfirmation = showDeleteConfirmation;
    window.showLoadingAlert = showLoadingAlert;
    window.handleSessionSuccess = handleSessionSuccess;
    window.handleSessionError = handleSessionError;
    window.deleteUserWithConfirmation = deleteUserWithConfirmation;
}
