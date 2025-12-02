<form class="d-flex flex-grow-1 gap-2" method="GET" action="index.php">
    <input type="hidden" name="page" value="<?= $current_page ?>">
    <input class="form-control flex-grow-1"
           type="text"
           name="search_term"
           placeholder="Searach data..."
           value="<?= htmlspecialchars($search_term); ?>"
           aria-label="Search">

    <button class="btn btn-primary flex-shrink-0" type="submit">
        <i class="bi bi-search"></i>
        <span class="d-none d-md-inline ms-1">Search</span>
    </button>
    <?php if (!empty($search_term)): ?>
        <a href="<?=$url?>mail_admin/index.php" class="btn btn-outline-secondary flex-shrink-0">  <i class="bi bi-x-circle"></i>  <span class="d-none d-md-inline ms-1">Reset</span></a>
    <?php endif; ?>
</form>
