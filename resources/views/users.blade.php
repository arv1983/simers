<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gerenciamento de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-4">
    <h1>Usuários</h1>

    <!-- Formulário para adicionar usuário -->
    <form id="addUserForm" class="mb-4">
        <h4>Adicionar Usuário</h4>
        <div class="row g-3">
            <div class="col-md-4">
                <input type="text" class="form-control" name="name" placeholder="Nome" required />
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" name="cpf" placeholder="CPF (11 dígitos)" required />
            </div>
            <div class="col-md-4">
                <input type="email" class="form-control" name="email" placeholder="Email" required />
            </div>
            <div class="col-md-4">
                <input type="password" class="form-control" name="password" placeholder="Senha" required />
            </div>
            <div class="col-md-4">
                <input type="date" class="form-control" name="birthday" placeholder="Data de Nascimento" required />
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" name="phone" placeholder="Telefone (11 dígitos)" required />
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Adicionar Usuário</button>
    </form>

    <!-- Tabela de usuários -->
    <table class="table table-bordered" id="usersTable">
        <thead class="table-light">
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Email</th>
                <th>Data de Nascimento</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <!-- Usuários carregados via JS -->
        </tbody>
    </table>
</div>

<script>
    const apiBase = '/api/users';

    // Carregar usuários
    async function loadUsers() {
        const res = await fetch(apiBase);
        const users = await res.json();
        const tbody = document.querySelector('#usersTable tbody');
        tbody.innerHTML = '';
        users.forEach(user => {
            tbody.innerHTML += `
                <tr data-id="${user.id}">
                    <td>${user.name}</td>
                    <td>${user.cpf}</td>
                    <td>${user.email}</td>
                    <td>${user.birthday}</td>
                    <td>${user.phone}</td>
                    <td>
                        <button class="btn btn-sm btn-warning btn-edit">Editar</button>
                        <button class="btn btn-sm btn-danger btn-delete">Excluir</button>
                    </td>
                </tr>
            `;
        });
        attachButtonsEvents();
    }

    // Adicionar usuário
    document.getElementById('addUserForm').addEventListener('submit', async e => {
        e.preventDefault();
        const form = e.target;
        const data = new FormData(form);
        const jsonData = Object.fromEntries(data.entries());

        try {
            const res = await fetch(apiBase, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(jsonData),
            });
            if (!res.ok) throw await res.json();
            form.reset();
            await loadUsers();
            alert('Usuário adicionado com sucesso!');
        } catch(err) {
            alert('Erro: ' + (err.message || JSON.stringify(err)));
        }
    });

    // Atachar eventos dos botões Editar/Excluir
    function attachButtonsEvents() {
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.onclick = async function () {
                const tr = this.closest('tr');
                const id = tr.dataset.id;

                // Exemplo simples com prompt para editar só o nome (pode ser modal)
                const newName = prompt('Digite o novo nome:', tr.children[0].innerText);
                if (!newName) return;

                // Pode adicionar mais campos para editar aqui

                try {
                    const res = await fetch(apiBase + '/' + id, {
                        method: 'PUT',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({name: newName}),
                    });
                    if (!res.ok) throw await res.json();
                    await loadUsers();
                    alert('Usuário atualizado com sucesso!');
                } catch(err) {
                    alert('Erro: ' + (err.message || JSON.stringify(err)));
                }
            };
        });

        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.onclick = async function () {
                if (!confirm('Tem certeza que deseja excluir este usuário?')) return;

                const tr = this.closest('tr');
                const id = tr.dataset.id;

                try {
                    const res = await fetch(apiBase + '/' + id, {
                        method: 'DELETE',
                    });
                    if (!res.ok) throw await res.json();
                    await loadUsers();
                    alert('Usuário excluído com sucesso!');
                } catch(err) {
                    alert('Erro: ' + (err.message || JSON.stringify(err)));
                }
            };
        });
    }

    loadUsers();
</script>
</body>
</html>
