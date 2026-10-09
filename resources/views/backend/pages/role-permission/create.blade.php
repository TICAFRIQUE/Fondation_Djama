@extends('backend.layouts.master')
@section('title')
   Permission
@endsection
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('backend.components.breadcrumb')
        @slot('li_1')
            Liste
        @endslot
        @slot('title')
            Permissions
        @endslot
    @endcomponent

    <div class="row">
        <form action="{{ route('permission.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Créer un nouveau rôle</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="role_name" class="form-label">Nom du rôle</label>
                                <input type="text" class="form-control" id="role_name" name="name" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Autorisations / Permissions</h5>
                            <button type="button" class="btn btn-sm btn-primary" id="toggle-all-modules">Tout cocher/décocher</button>
                        </div>
                    </div>
                </div>
                @foreach ($modules_with_permissions as $module)
                    <div class="col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">{{ $module->name }}</h5>
                                <button type="button" class="btn btn-sm btn-primary toggle-all"
                                    data-module="{{ $module->id }}">Tout cocher/décocher</button>
                            </div>
                            <div class="card-body">
                                @foreach ($module->permissions as $permission)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input module-{{ $module->id }}" type="checkbox"
                                            name="permissions[]" value="{{ $permission->id }}"
                                            id="permission_{{ $permission->id }}">
                                        <label class="form-check-label" for="permission_{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
                </div>
            </div>
        </form>


    </div>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.toggle-all');

            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const moduleId = this.getAttribute('data-module');
                    const checkboxes = document.querySelectorAll(`.module-${moduleId}`);

                    const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

                    checkboxes.forEach(checkbox => {
                        checkbox.checked = !allChecked;
                    });

                    this.textContent = allChecked ? 'Tout cocher' : 'Tout décocher';
                });
            });


        // Fonction pour tout cocher en même temps
        const cocherTout = document.getElementById('toggle-all-modules');
        cocherTout.addEventListener('click', function() {
            const toutesLesCheckboxes = document.querySelectorAll('input[type="checkbox"]');
            const toutCoche = Array.from(toutesLesCheckboxes).every(checkbox => checkbox.checked);
            
            toutesLesCheckboxes.forEach(checkbox => {
                checkbox.checked = !toutCoche;
            });
            
            cocherTout.textContent = toutCoche ? 'Tout cocher' : 'Tout décocher';
            
            // Mettre à jour le texte des boutons de chaque module
            const toggleButtons = document.querySelectorAll('.toggle-all');
            toggleButtons.forEach(button => {
                button.textContent = toutCoche ? 'Tout cocher' : 'Tout décocher';
            });
        });
            
        });
    </script>
@endsection
