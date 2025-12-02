<?php
/********************************
 * 1. CONNEXION DATABASE
 ********************************/
function db()
{
    return new PDO("mysql:host=localhost;dbname=records_db;charset=utf8", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
}


/********************************
 * 2. CLASS PAGINATION (Google Style)
 ********************************/
class Pagination {

    public $page;
    public $limit;
    public $total;
    public $pages;

    public function __construct($total, $limit = 10)
    {
        $this->limit = $limit;
        $this->total = $total;
        $this->pages = max(1, ceil($total / $limit));

        $this->page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        if ($this->page > $this->pages) $this->page = $this->pages;
    }

    public function offset()
    {
        return ($this->page - 1) * $this->limit;
    }

    public function bsLinks($extra = "")
    {
        if ($this->pages <= 1) return "";

        $html = '<nav><ul class="pagination justify-content-center">';

        // PREVIOUS
        $prev = max(1, $this->page - 1);
        $disabled = ($this->page == 1) ? "disabled" : "";
        $html .= "<li class='page-item $disabled'>
                    <a class='page-link' href='?page=$prev$extra'>&laquo;</a>
                  </li>";

        // Page 1
        if ($this->page == 1) {
            $html .= "<li class='page-item active'><span class='page-link'>1</span></li>";
        } else {
            $html .= "<li class='page-item'><a class='page-link' href='?page=1$extra'>1</a></li>";
        }

        // Ellipsis
        if ($this->page > 4) {
            $html .= "<li class='page-item disabled'><span class='page-link'>…</span></li>";
        }

        // Window around current page
        $start = max(2, $this->page - 2);
        $end   = min($this->pages - 1, $this->page + 2);

        for ($i = $start; $i <= $end; $i++) {
            $active = ($i == $this->page) ? "active" : "";
            $html .= "<li class='page-item $active'>
                        <a class='page-link' href='?page=$i$extra'>$i</a>
                      </li>";
        }

        // Ellipsis before last page
        if ($this->page < $this->pages - 3) {
            $html .= "<li class='page-item disabled'><span class='page-link'>…</span></li>";
        }

        // Last page
        if ($this->page == $this->pages) {
            $html .= "<li class='page-item active'><span class='page-link'>{$this->pages}</span></li>";
        } else {
            $html .= "<li class='page-item'><a class='page-link' href='?page={$this->pages}$extra'>{$this->pages}</a></li>";
        }

        // NEXT
        $next = min($this->pages, $this->page + 1);
        $disabled = ($this->page == $this->pages) ? "disabled" : "";
        $html .= "<li class='page-item $disabled'>
                    <a class='page-link' href='?page=$next$extra'>&raquo;</a>
                  </li>";

        $html .= "</ul></nav>";

        return $html;
    }
}


/********************************
 * 3. RESET VIEWS + LIKES
 ********************************/
if (isset($_GET['reset'])) {
    $id = (int)$_GET['reset'];
    $sql = db()->prepare("UPDATE posts SET views = 0, likes = 0 WHERE id = ?");
    $sql->execute([$id]);
    header("Location: index.php");
    exit;
}


/********************************
 * 4. RECHERCHE
 ********************************/
$search = $_GET['search'] ?? "";
$where = "";
$params = [];

if (!empty($search)) {
    $where = "WHERE title LIKE :s OR author LIKE :s OR status LIKE :s OR date LIKE :s";
    $params[':s'] = "%$search%";
}


/********************************
 * 5. TOTAL + PAGINATION
 ********************************/
$sqlCount = db()->prepare("SELECT COUNT(*) FROM posts $where");
$sqlCount->execute($params);
$total = $sqlCount->fetchColumn();

$pagination = new Pagination($total, 10);


/********************************
 * 6. RÉCUPÉRER LES POSTS
 ********************************/
$sql = db()->prepare("SELECT * FROM posts $where ORDER BY id DESC LIMIT :offset, :limit");

foreach ($params as $k => $v) {
    $sql->bindValue($k, $v);
}

$sql->bindValue(':offset', $pagination->offset(), PDO::PARAM_INT);
$sql->bindValue(':limit', $pagination->limit, PDO::PARAM_INT);
$sql->execute();

$posts = $sql->fetchAll(PDO::FETCH_ASSOC);

$extra = "";
if ($search) $extra .= "&search=" . urlencode($search);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">

<div class="container">

    <h2 class="mb-4">Liste des Posts</h2>

    <!-- SEARCH -->
    <form method="get" class="mb-4 d-flex">
        <input type="text" name="search"
               value="<?= htmlspecialchars($search) ?>"
               class="form-control me-2"
               placeholder="Rechercher (title, author, status)">
        <button class="btn btn-primary">Rechercher</button>
    </form>

    <!-- RESET GLOBAL -->
    <?php if(!empty($search) && $total>0): ?>
        <a href="index.php" class="btn btn-danger btn-sm mb-3">
            Reset tous les résultats
        </a>
    <?php endif; ?>

    <!-- TABLE -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Vues</th>
            <th>Likes</th>
            <th>Status</th>
            <th>Auteur</th>
            <th>Date</th>
            <th>Reset</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($posts as $p): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['title']) ?></td>
                <td><?= $p['views'] ?></td>
                <td><?= $p['likes'] ?></td>
                <td><?= htmlspecialchars($p['status']) ?></td>
                <td><?= htmlspecialchars($p['author']) ?></td>
                <td><?= $p['date'] ?></td>
                <td>
                    <a href="?reset=<?= $p['id'] ?>"
                       class="btn btn-warning btn-sm"
                       onclick="return confirm('Reset vues + likes ?');">
                        Reset
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- PAGINATION -->
    <?= $pagination->bsLinks($extra); ?>

</div>

</body>
</html>
