<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="p-4">

    <div class="container">
        <h1>Usuários</h1>

        <!-- form -->
        <form id="addUserForm" class="mb-4">
            <div class="row g-3">
                <div class="col-md-2">
                    <input type="text" class="form-control" id="addName" placeholder="Nome" required />
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" id="addCpf" placeholder="CPF (11 dígitos)" pattern="\d{11}" required />
                </div>
                <div class="col-md-2">
                    <input type="email" class="form-control" id="addEmail" placeholder="Email" required />
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" id="addBirthday" placeholder="Data de Nascimento" required />
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" id="addPhone" placeholder="Telefone (11 dígitos)" pattern="\d{11}" required />
                </div>
                <div class="col-md-2">
                    <input type="password" class="form-control" id="addPassword" placeholder="Senha" required />
                </div>
            </div>
            <div class="mt-3 d-grid col-md-2">
                <button type="submit" class="btn btn-success">Adicionar</button>
            </div>
        </form>

        <table class="table table-bordered" id="usersTable">
            <thead>
                <tr>
                    <th>ID</th><th>Nome</th><th>CPF</th><th>Email</th><th>Data Nasc.</th><th>Telefone</th><th>Ações</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <!-- Modal edição -->
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserLabel" aria-hidden="true">
          <div class="modal-dialog">
            <form id="editUserForm" class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="editUserLabel">Editar Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
              </div>
              <div class="modal-body">
                  <input type="hidden" id="editUserId" />

                  <div class="mb-3">
                    <label for="editName" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="editName" required />
                  </div>

                  <div class="mb-3">
                    <label for="editCpf" class="form-label">CPF (11 dígitos)</label>
                    <input type="text" class="form-control" id="editCpf" pattern="\d{11}" required />
                  </div>

                  <div class="mb-3">
                    <label for="editEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="editEmail" required />
                  </div>

                  <div class="mb-3">
                    <label for="editBirthday" class="form-label">Data de Nascimento</label>
                    <input type="date" class="form-control" id="editBirthday" required />
                  </div>

                  <div class="mb-3">
                    <label for="editPhone" class="form-label">Telefone (11 dígitos)</label>
                    <input type="text" class="form-control" id="editPhone" pattern="\d{11}" required />
                  </div>

                  <div class="mb-3">
                    <label for="editPassword" class="form-label">Senha (deixe vazio para não alterar)</label>
                    <input type="password" class="form-control" id="editPassword" />
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Salvar</button>
              </div>
            </form>
          </div>
        </div>

    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const usersTableBody = document.querySelector('#usersTable tbody');
    const editUserModal = new bootstrap.Modal(document.getElementById('editUserModal'));
    const editUserForm = document.getElementById('editUserForm');
    const addUserForm = document.getElementById('addUserForm');

    ////////
    function loadUsers() {
        fetch('/api/users')
            .then(res => res.json())
            .then(users => {
                usersTableBody.innerHTML = '';
                users.forEach(user => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${user.id}</td>
                        <td>${user.name}</td>
                        <td>${user.cpf}</td>
                        <td>${user.email}</td>
                        <td>${user.birthday}</td>
                        <td>${user.phone}</td>
                        <td>
                            <button class="btn btn-sm btn-warning btn-edit" 
                                data-id="${user.id}" 
                                data-name="${user.name}" 
                                data-cpf="${user.cpf}"
                                data-email="${user.email}" 
                                data-birthday="${user.birthday}" 
                                data-phone="${user.phone}">
                                Editar
                            </button>
                            <button class="btn btn-sm btn-danger btn-delete" data-id="${user.id}">Excluir</button>
                        </td>
                    `;
                    usersTableBody.appendChild(tr);
                });
            });
    }

    loadUsers();

    // md
    usersTableBody.addEventListener('click', e => {
        if (e.target.classList.contains('btn-edit')) {
            const btn = e.target;
            document.getElementById('editUserId').value = btn.dataset.id;
            document.getElementById('editName').value = btn.dataset.name;
            document.getElementById('editCpf').value = btn.dataset.cpf;
            document.getElementById('editEmail').value = btn.dataset.email;
            document.getElementById('editBirthday').value = btn.dataset.birthday;
            document.getElementById('editPhone').value = btn.dataset.phone;
            document.getElementById('editPassword').value = '';

            editUserModal.show();
        }
        else if (e.target.classList.contains('btn-delete')) {
            if (confirm('Deseja realmente excluir este usuário?')) {
                const id = e.target.dataset.id;
                fetch(`/api/users/${id}`, { method: 'DELETE' })
                    .then(res => {
                        if (res.ok) {
                            alert('Usuário excluído com sucesso.');
                            loadUsers();
                        } else {
                            alert('Erro ao excluir usuário.');
                        }
                    });
            }
        }
    });

    // edi
    editUserForm.addEventListener('submit', e => {
        e.preventDefault();

        const id = document.getElementById('editUserId').value;
        const data = {
            name: document.getElementById('editName').value,
            cpf: document.getElementById('editCpf').value,
            email: document.getElementById('editEmail').value,
            birthday: document.getElementById('editBirthday').value,
            phone: document.getElementById('editPhone').value,
            password: document.getElementById('editPassword').value,
        };

        fetch(`/api/users/${id}`, {
            method: 'PUT',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        }).then(res => {
            if (res.ok) {
                alert('Usuário atualizado com sucesso!');
                editUserModal.hide();
                loadUsers();
            } else {
                res.json().then(data => alert('Erro: ' + (data.message || 'unknown')));
            }
        });
    });


    ////////////
    addUserForm.addEventListener('submit', e => {
        e.preventDefault();

        const data = {
            name: document.getElementById('addName').value,
            cpf: document.getElementById('addCpf').value,
            email: document.getElementById('addEmail').value,
            birthday: document.getElementById('addBirthday').value,
            phone: document.getElementById('addPhone').value,
            password: document.getElementById('addPassword').value,
        };

        fetch('/api/users', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        }).then(res => {
            if (res.ok) {
                alert('Usuário criado com sucesso!');
                addUserForm.reset();
                loadUsers();
            } else {
                res.json().then(data => alert('Erro: ' + (data.message || 'unknown')));
            }
        });
    });
});
</script>

</body>
</html>
