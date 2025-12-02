<div class="card-header py-2">
    <div class="d-flex flex-wrap align-items-center gap-2">

        <button class="btn btn-outline-secondary flex-shrink-0" type="button" title="Répondre">
            <i class="bi bi-arrow-left"></i>
            <span class="d-none d-md-inline ms-1">Retour</span>
        </button>

        <button class="btn btn-outline-secondary flex-shrink-0" type="button" title="Transférer">
            <i class="bi bi-share-fill"></i>
            <span class="d-none d-md-inline ms-1">Transférer</span>
        </button>

        <button class="btn btn-outline-warning flex-shrink-0" type="button" title="Marquer comme important">
            <i class="bi bi-star"></i>
            <span class="d-none d-md-inline ms-1">Important</span>
        </button>

        <button class="btn btn-outline-danger flex-shrink-0" type="button" title="Supprimer">
            <i class="bi bi-trash"></i>
            <span class="d-none d-md-inline ms-1">Supprimer</span>
        </button>

        <div class="dropdown flex-shrink-0">

            <button class="btn btn-secondary dropdown-toggle"
                    type="button"
                    id="dropdownMailbox"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">

                <i class="bi bi-folder-fill me-2"></i>

                <span class="d-none d-md-inline">Dossiers</span>

            </button>

            <ul class="dropdown-menu" aria-labelledby="dropdownMailbox">
                <li>
                    <a class="dropdown-item text-success fw-bold bg-light" href="#">
                        <i class="bi bi-pencil-square me-2"></i>
                        Nouveau message
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item active" href="#" aria-current="true">
                        <i class="bi bi-inbox-fill me-2"></i>
                        Boîte de réception
                        <span class="badge bg-danger rounded-pill ms-2">5</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#">
                        <i class="bi bi-star-fill me-2 text-warning"></i>
                        Importants
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="#">
                        <i class="bi bi-send-fill me-2"></i>
                        Envoyés
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#">
                        <i class="bi bi-file-earmark-code me-2"></i>
                        Brouillons
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#">
                        <i class="bi bi-trash-fill me-2"></i>
                        Corbeille
                    </a>
                </li>
            </ul>
        </div>

    </div>
</div>