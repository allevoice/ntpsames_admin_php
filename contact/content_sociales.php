

        <div class="col-lg-6">
            <div class="card shadow-lg p-3">

                <div class="d-flex justify-content-between align-items-center card-header bg-white border-0 p-0 mb-3">
                    <h3 class="h5 m-0">🌐 Mes Liens Sociaux</h3>
                    <button type="button" class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#socialModal">
                        Ajouter un lien
                    </button>
                </div>

                <ul class="list-group list-group-flush">

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center" style="width: 30%;">
                            <i class="bi bi-facebook me-2 fs-5 text-primary"></i>
                            <strong>Facebook</strong>
                        </div>

                        <small class="text-truncate" style="width: 50%;">
                            <a href="https://facebook.com/utilisateur" target="_blank">facebook.com/votre_profil_tres_long</a>
                        </small>

                        <div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#socialModal">
                                Éditer
                            </button>
                        </div>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center" style="width: 30%;">
                            <i class="bi bi-twitter-x me-2 fs-5 text-dark"></i>
                            <strong>X (Twitter)</strong>
                        </div>
                        <small class="text-truncate" style="width: 50%;">
                            <a href="https://x.com/utilisateur" target="_blank">x.com/mon_username</a>
                        </small>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#socialModal">
                                Éditer
                            </button>
                        </div>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center text-muted">
                        <div class="d-flex align-items-center" style="width: 30%;">
                            <i class="bi bi-linkedin me-2 fs-5 text-info"></i>
                            <strong>LinkedIn</strong>
                        </div>
                        <small style="width: 50%;">Non renseigné</small>
                        <div>
                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#socialModal">
                                Ajouter
                            </button>
                        </div>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center" style="width: 30%;">
                            <i class="bi bi-youtube me-2 fs-5 text-danger"></i>
                            <strong>YouTube</strong>
                        </div>
                        <small class="text-truncate" style="width: 50%;">
                            <a href="https://youtube.com/channel" target="_blank">youtube.com/mon_channel</a>
                        </small>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#socialModal">
                                Éditer
                            </button>
                        </div>
                    </li>




                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center" style="width: 30%;">
                            <i class="bi bi-globe me-2 fs-5 text-success"></i>
                            <strong>Map</strong>
                        </div>

                        <small class="text-truncate" style="width: 40%;">
                            <a href="https://maps.google.com/?q=VotreAdresse" target="_blank">maps.google.com/...</a>
                        </small>

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editMapModal">
                                Éditer
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#mapViewModal">
                                Voir
                            </button>
                        </div>
                    </li>


                </ul>

            </div>
        </div>

<div class="modal fade" id="socialModal" tabindex="-1" aria-labelledby="socialModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="socialModalLabel">🖋️ Éditer/Ajouter un Lien Social</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="votre_script_de_sauvegarde.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="media_selection" class="form-label">Sélectionner le Média</label>
                        <select class="form-select" id="media_selection" name="media_type" required>
                            <option value="">-- Choisir --</option>
                            <option value="facebook">Facebook</option>
                            <option value="twitter">X (Twitter)</option>
                            <option value="linkedin">LinkedIn</option>
                            <option value="youtube">YouTube</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="social_url" class="form-label">URL du Profil Complet</label>
                        <input type="url" class="form-control" id="social_url" name="social_url" placeholder="Ex: https://facebook.com/votre_profil">
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







        <div class="modal fade" id="editMapModal" tabindex="-1" aria-labelledby="editMapModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMapModalLabel"><i class="bi bi-geo-alt me-2"></i> Éditer le Lien de la Carte</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="votre_script_de_sauvegarde.php" method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="section" value="map_link">

                            <p class="text-muted small">Veuillez coller le lien de partage (URL) de votre service de carte (Google Maps, OpenStreetMap, etc.).</p>

                            <div class="mb-3">
                                <label for="map_url" class="form-label">URL de la Carte ou Lien de Partage</label>
                                <input type="url" class="form-control" id="map_url" name="map_url" placeholder="Ex: https://maps.app.goo.gl/votre_adresse_ici">
                            </div>

                            <div class="mb-3">
                                <label for="map_display_text" class="form-label">Texte à Afficher (Adresse courte)</label>
                                <input type="text" class="form-control" id="map_display_text" name="map_display_text" placeholder="Ex: New York, NY 535022">
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


        <div class="modal fade" id="mapViewModal" tabindex="-1" aria-labelledby="mapViewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mapViewModalLabel"><i class="bi bi-globe me-2"></i> Aperçu de la Localisation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-0">
                        <div class="p-3 text-center" style="height: 500px; background-color: #f8f9fa;">
                            <p class="mt-5 text-muted">Aperçu de la carte (Simulé)</p>
                            <p>
                                <i class="bi bi-map fs-1 text-primary"></i>
                            </p>
                            <p>Cette zone afficherait une carte interactive (par exemple, via Google Maps Embed) en fonction du lien enregistré.</p>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <a href="https://maps.google.com/?q=VotreAdresse" target="_blank" class="btn btn-success">Ouvrir dans Maps</a>
                    </div>
                </div>
            </div>
        </div>