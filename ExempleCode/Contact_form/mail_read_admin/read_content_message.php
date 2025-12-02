<div class="container-fluid py-4 min-vh-100 d-flex flex-column">

    <div class="row  flex-grow-1">

        <div class="col-12 col-xl-12">

            <div class="card shadow-sm">


                <div class="card-body border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-circle fs-3 me-3 text-secondary"></i>
                        <div>
                            <p class="mb-0 fw-bold">Support Technique | Contact Form</p>
                            <small class="text-muted">
                                De : **support@tech.com** &lt;support@tech.com&gt;
                            </small>
                            <br>
                            <small class="text-muted">
                                À : vous@votredomaine.com
                            </small>
                        </div>
                        <small class="ms-auto text-end text-muted flex-shrink-0">
                            <?= $data->traduireDateFixe($value['created_at'])['fr']?> <br>
                            <?= $data->traduireDateFixe($value['created_at'])['us']?> <br>
                            <?= $data->getEtiquetteTemporelle($value['created_at'])?> <br>
                        </small>
                    </div>

                    <h4 class="card-title fw-bold">
                        <?=$value['subject']?>
                    </h4>

                </div>

                <div class="card-body">
                    <?=$value['content']?>
                </div>

                <div class="card-footer d-flex gap-2 py-3">
                    <button class="btn btn-md btn-primary" type="button" title="Répondre">
                        <i class="bi bi-reply-fill me-1"></i> Répondre
                    </button>

                    <button class="btn btn-outline-secondary" type="button" title="Transférer">
                        <i class="bi bi-share-fill me-1"></i> Transférer
                    </button>
                </div>

                <div class="card-footer bg-light">
                    <h6 class="mb-2"><i class="bi bi-paperclip me-1"></i> Pièces jointes (2)</h6>
                    <a href="#" class="btn btn-sm btn-outline-info me-2 mb-1">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Rapport_Bug.pdf (150 KB)
                    </a>
                    <a href="#" class="btn btn-sm btn-outline-info mb-1">
                        <i class="bi bi-file-earmark-image me-1"></i> Capture_ecran.png (56 KB)
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>