<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Nouvel Utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">👤 Ajouter un Nouvel Utilisateur</h1>
                </div>
                <div class="card-body">
                    <p class="text-muted">Veuillez remplir le formulaire ci-dessous pour créer un nouveau compte.</p>

                    <form action="/api/users" method="POST">

                        <div class="mb-3">
                            <label for="firstName" class="form-label">Prénom :</label>
                            <input type="text" id="firstName" name="firstName" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="lastName" class="form-label">Nom :</label>
                            <input type="text" id="lastName" name="lastName" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email :</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de Passe :</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>

                        <hr>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="reset" class="btn btn-outline-secondary">Effacer les champs</button>
                            <button type="submit" class="btn btn-success">Créer l'utilisateur</button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>