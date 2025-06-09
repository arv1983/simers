<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Create User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Create User</h1>
    <form id="userForm" novalidate>
        <div class="mb-3">
            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="name" name="name" maxlength="255" required>
            <div class="invalid-feedback">Please enter a name (max 255 chars).</div>
        </div>

        <div class="mb-3">
            <label for="cpf" class="form-label">CPF <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="cpf" name="cpf" pattern="\d{11}" title="Exactly 11 digits" required>
            <div class="invalid-feedback">Please enter exactly 11 digits for CPF.</div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="email" name="email" required>
            <div class="invalid-feedback">Please enter a valid email.</div>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="password" name="password" minlength="6" required>
            <div class="invalid-feedback">Password must be at least 6 characters.</div>
        </div>

        <div class="mb-3">
            <label for="birthday" class="form-label">Birthday <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="birthday" name="birthday" required>
            <div class="invalid-feedback">Please enter your birthday.</div>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="phone" name="phone" pattern="\d{11}" title="Exactly 11 digits" required>
            <div class="invalid-feedback">Please enter exactly 11 digits for phone.</div>
        </div>

        <button type="submit" class="btn btn-primary">Save User</button>
    </form>

    <div id="alertPlaceholder" class="mt-4"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
(() => {
    const form = document.getElementById('userForm');
    const alertPlaceholder = document.getElementById('alertPlaceholder');

    function showAlert(message, type = 'success') {
        alertPlaceholder.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        event.stopPropagation();

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        const data = {
            name: form.name.value.trim(),
            cpf: form.cpf.value.trim(),
            email: form.email.value.trim(),
            password: form.password.value,
            birthday: form.birthday.value,
            phone: form.phone.value.trim(),
        };

        try {
            const response = await fetch('/api/users', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                showAlert('User created successfully!', 'success');
                form.reset();
                form.classList.remove('was-validated');
            } else {
                const errorData = await response.json();
                const message = errorData.message || 'Something went wrong.';
                showAlert(`Error: ${message}`, 'danger');
            }
        } catch (error) {
            showAlert(`Network error: ${error.message}`, 'danger');
        }
    });
})();
</script>
</body>
</html>
