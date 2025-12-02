
<div class="card" style="width: 18rem;">
    <img src="chemin/vers/votre/image.jpg" class="card-img-top" alt="Description de l'image">
    <div class="card-body">
        <h5 class="card-title">Card avec Image</h5>
        <p class="card-text">
            Ce texte est lié à l'image ci-dessus. Idéal pour les galeries ou les fiches produits.
        </p>
        <a href="#" class="btn btn-primary">Voir plus</a>
    </div>
</div>

<div class="card" style="width: 18rem;">
    <img src="chemin/vers/votre/image.jpg" class="card-img-top" alt="Description de l'image">
    <div class="card-body">
        <h5 class="card-title">Card avec Image</h5>
        <p class="card-text">
            Ce texte est lié à l'image ci-dessus. Idéal pour les galeries ou les fiches produits.
        </p>
        <a href="#" class="btn btn-primary">Voir plus</a>
    </div>
</div>


<?php
foreach ($users as $user) {
    ?>



    <div class="col-12 col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm user-card">

            <div class="card-body d-flex align-items-center">

                <div class="avatar">
                    <?php echo $user['initial']; ?>
                </div>

                <div class="flex-grow-1">
                    <h5 class="card-title mb-0"><?php echo $user['firstName'] . ' ' . $user['lastName']; ?></h5>
                    <p class="card-text text-muted small mb-1">
                        <?php echo $user['email']; ?>
                    </p>

                    <span class="badge <?php echo $user['statusClass']; ?>">
                                    <?php echo $user['statusIcon']; ?> <?php echo $user['statusText']; ?>
                                </span>
                    <span class="text-muted small ms-2">(<?php echo $user['lastSeen']; ?>)</span>
                </div>
            </div>
        </div>
    </div>



    <?php
}
?>
