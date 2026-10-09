<!-- start page title -->
<div class="adm-page-head">
    <div>
        <h1 class="adm-page-title">{{ $title }}</h1>
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Administration</a></li>
            <li class="breadcrumb-item">{{ $li_1 }}</li>
            <li class="breadcrumb-item active">{{ $title }}</li>
        </ol>
    </div>

    <a href="{{ route('dashboard.index') }}" class="btn btn-light adm-back" id="goBack">
        <i class="ri-arrow-left-line"></i> Retour
    </a>
</div>
<script>
    // Retour à la page précédente ; sans page précédente (lien direct, nouvel onglet), retour au tableau de bord
    document.getElementById('goBack').addEventListener('click', function(event) {
        if (document.referrer && document.referrer !== window.location.href) {
            event.preventDefault();
            window.location.href = document.referrer;
        }
    });
</script>
<!-- end page title -->
