@php $prefix = $prefix ?? null; @endphp

<div class="mb-3">
    <label for="{{ $prefix }}_name" class="form-label">Name</label>
    <input type="text" class="form-control" name="name" id="{{ $prefix }}_name" required>
</div>
<div class="mb-3">
    <label for="{{ $prefix }}_cpf" class="form-label">CPF</label>
    <input type="text" class="form-control" name="cpf" id="{{ $prefix }}_cpf" required>
</div>
<div class="mb-3">
    <label for="{{ $prefix }}_email" class="form-label">Email</label>
    <input type="email" class="form-control" name="email" id="{{ $prefix }}_email" required>
</div>
<div class="mb-3">
    <label for="{{ $prefix }}_birthday" class="form-label">Birthday</label>
    <input type="date" class="form-control" name="birthday" id="{{ $prefix }}_birthday" required>
</div>
<div class="mb-3">
    <label for="{{ $prefix }}_phone" class="form-label">Phone</label>
    <input type="text" class="form-control" name="phone" id="{{ $prefix }}_phone" required>
</div>
@if($prefix !== 'edit')
<div class="mb-3">
    <label for="{{ $prefix }}_password" class="form-label">Password</label>
    <input type="password" class="form-control" name="password" id="{{ $prefix }}_password" required>
</div>
@endif
