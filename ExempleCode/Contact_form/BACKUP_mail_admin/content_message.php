


<div class="list-group list-group-flush border rounded shadow-sm">

    <?php
    if (empty($mails_to_display)): ?>
        <div class="alert alert-info text-center m-0 py-4">
            <?php if (!empty($search_term)): ?>
                Aucun résultat trouvé pour la recherche "<?= htmlspecialchars($search_term); ?>".
            <?php else: ?>
                Votre boîte de réception est vide.
            <?php endif; ?>
        </div>
    <?php
    endif;

    // Boucle sur les MAILS DE LA PAGE ACTUELLE
    foreach ($mails_to_display as $mail):
        // Définir les classes d'affichage
        $text_class = $mail['is_read'] ? 'text-secondary' : 'text-dark';
        $is_strong = $mail['is_read'] ? '' : '<strong>';
        $end_strong = $mail['is_read'] ? '' : '</strong>';
        $read_link = $url . 'mail_admin/index_read.php?id=' . $mail['id'];
    ?>

        <a href="<?= htmlspecialchars($read_link) ?>" class="list-group-item list-group-item-action py-3">
            <div class="d-flex w-100 justify-content-between">
                <div class="form-check me-3 d-flex align-items-center">
                    <input class="form-check-input" type="checkbox" value="<?= $mail['id'] ?>" id="mailCheck_<?= $mail['id'] ?>">
                    <label class="form-check-label ms-3" for="mailCheck_<?= $mail['id'] ?>">
                        <span class="<?= $text_class ?>"><?= $is_strong . htmlspecialchars($mail['sender']) . $end_strong ?></span>
                    </label>
                </div>
                <small class="text-muted text-end"><?= $mail['time'] ?></small>
            </div>

            <p class="mb-1 ps-4 <?= $text_class ?>">
                <?= $is_strong ?>
                Sujet : <?= htmlspecialchars($mail['subject']) ?>
                <?= $end_strong ?>
            </p>

            <small class="text-muted d-block ps-4 text-truncate">
                <?= htmlspecialchars($mail['preview']) ?>
            </small>
        </a>

    <?php endforeach; ?>

    </div>

    <?php if ($total_pages > 1): ?>

    <nav class="mt-4">
      <ul class="pagination justify-content-center">

        <li class="page-item <?= ($current_page <= 1) ? 'disabled' : '' ?>">
          <a class="page-link" href="<?= htmlspecialchars($base_pagination_url . ($current_page - 1)) ?>" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>

          <?php
            // Afficher seulement les pages proches
            if ($i >= $current_page - 2 && $i <= $current_page + 2):
          ?>

          <li class="page-item <?= ($i == $current_page) ? 'active' : '' ?>">
            <a class="page-link" href="<?= htmlspecialchars($base_pagination_url . $i) ?>">
              <?= $i ?>
            </a>
          </li>

          <?php endif; ?>
        <?php endfor; ?>

        <li class="page-item <?= ($current_page >= $total_pages) ? 'disabled' : '' ?>">
          <a class="page-link" href="<?= htmlspecialchars($base_pagination_url . ($current_page + 1)) ?>" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>

      </ul>
    </nav>

    <?php endif; ?>
