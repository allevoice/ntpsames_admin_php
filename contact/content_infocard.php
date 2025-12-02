<div class="row">

    <div class="col-lg-3">
        <div class="info-box card p-3 position-relative">

            <div class="d-flex justify-content-between align-items-start w-100 mb-2">
                <div class="d-flex align-items-center">
                    <i class="bi bi-geo-alt me-2 fs-4"></i>
                    <h3 class="h5 m-0">Address</h3>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editAddressModal">
                    Edit
                </button>
            </div>

            <p>A108 Adam Street,<br>New York, NY 535022</p>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="info-box card p-3 position-relative">

            <div class="d-flex justify-content-between align-items-start w-100 mb-2">
                <div class="d-flex align-items-center">
                    <i class="bi bi-telephone me-2 fs-4"></i>
                    <h3 class="h5 m-0">Call Us</h3>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editCallModal">
                    Edit
                </button>
            </div>

            <p>+1 5589 55488 55<br>+1 6678 254445 41</p>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="info-box card p-3 position-relative">

            <div class="d-flex justify-content-between align-items-start w-100 mb-2">
                <div class="d-flex align-items-center">
                    <i class="bi bi-envelope me-2 fs-4"></i>
                    <h3 class="h5 m-0">Email Us</h3>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editEmailModal">
                    Edit
                </button>
            </div>

            <p>info@example.com<br>contact@example.com</p>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="info-box card p-3 position-relative">

            <div class="d-flex justify-content-between align-items-start w-100 mb-2">
                <div class="d-flex align-items-center">
                    <i class="bi bi-clock me-2 fs-4"></i>
                    <h3 class="h5 m-0">Open Hours</h3>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editHoursModal">
                    Edit
                </button>
            </div>

            <p>Monday - Friday<br>9:00AM - 05:00PM</p>
        </div>
    </div>
</div>




<!--les modals-->
<div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAddressModalLabel"><i class="bi bi-geo-alt me-2"></i> Modifier l'Adresse</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="votre_script_de_sauvegarde.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="section" value="address">
                    <div class="mb-3">
                        <label for="address_line1" class="form-label">Ligne d'Adresse 1</label>
                        <input type="text" class="form-control" id="address_line1" name="address_line1" value="A108 Adam Street">
                    </div>
                    <div class="mb-3">
                        <label for="address_line2" class="form-label">Ligne d'Adresse 2</label>
                        <input type="text" class="form-control" id="address_line2" name="address_line2" value="New York, NY 535022">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">💾 Sauvegarder</button>
                </div>
            </form>
        </div>
    </div>
</div>




<div class="modal fade" id="editCallModal" tabindex="-1" aria-labelledby="editCallModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCallModalLabel"><i class="bi bi-telephone me-2"></i> Modifier les Numéros</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="votre_script_de_sauvegarde.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="section" value="call">
                    <div class="mb-3">
                        <label for="phone_main" class="form-label">Numéro Principal</label>
                        <input type="tel" class="form-control" id="phone_main" name="phone_main" value="+1 5589 55488 55">
                    </div>
                    <div class="mb-3">
                        <label for="phone_secondary" class="form-label">Numéro Secondaire</label>
                        <input type="tel" class="form-control" id="phone_secondary" name="phone_secondary" value="+1 6678 254445 41">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">💾 Sauvegarder</button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade" id="editEmailModal" tabindex="-1" aria-labelledby="editEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEmailModalLabel"><i class="bi bi-envelope me-2"></i> Modifier les Adresses Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="votre_script_de_sauvegarde.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="section" value="email">

                    <div class="mb-3">
                        <label for="email_list" class="form-label">Liste des Adresses Email (une par ligne)</label>
                        <textarea class="form-control" id="email_list" name="email_list" rows="4">info@example.com
contact@example.com</textarea>
                        <div class="form-text">Entrez chaque adresse email sur une nouvelle ligne.</div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">💾 Sauvegarder</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editCallModal" tabindex="-1" aria-labelledby="editCallModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCallModalLabel"><i class="bi bi-telephone me-2"></i> Modifier les Numéros</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="votre_script_de_sauvegarde.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="section" value="call">

                    <div class="mb-3">
                        <label for="phone_list" class="form-label">Liste des Numéros de Téléphone (un par ligne)</label>
                        <textarea class="form-control" id="phone_list" name="phone_list" rows="4">+1 5589 55488 55
+1 6678 254445 41</textarea>
                        <div class="form-text">Entrez chaque numéro sur une nouvelle ligne.</div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">💾 Sauvegarder</button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade" id="editHoursModal" tabindex="-1" aria-labelledby="editHoursModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editHoursModalLabel"><i class="bi bi-clock me-2"></i> Modifier les Horaires</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="votre_script_de_sauvegarde.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="section" value="hours">
                    <div class="mb-3">
                        <label for="hours_days" class="form-label">Jours d'Ouverture</label>
                        <input type="text" class="form-control" id="hours_days" name="hours_days" value="Monday - Friday">
                    </div>
                    <div class="mb-3">
                        <label for="hours_time" class="form-label">Plage Horaire</label>
                        <input type="text" class="form-control" id="hours_time" name="hours_time" value="9:00AM - 05:00PM">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">💾 Sauvegarder</button>
                </div>
            </form>
        </div>
    </div>
</div>


