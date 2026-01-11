<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire de vote</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</head>
<body class="bg-light">


<?php if (!empty($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['success_message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['error_message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Vote électronique</h3>

                    <form method="post" action="index.php?p=vote/addVote">
                        <div class="mb-3">
                            <label for="codeN1" class="form-label">Code N1</label>
                            <input type="text" class="form-control" id="codeN1" name="codeN1" required>
                        </div>

                        <div class="mb-3">
                            <label for="codeN2" class="form-label">Code N2</label>
                            <input type="text" class="form-control" id="codeN2" name="codeN2" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Choix du vote</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="voteOption" id="optionA" value="A" required>
                                <label class="form-check-label" for="optionA">
                                    Option A
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="voteOption" id="optionB" value="B">
                                <label class="form-check-label" for="optionB">
                                    Option B
                                </label>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Valider mon vote
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="d-flex w-100 gap-2 mt-3">
                <form method="post" action="index.php?p=vote/finaliser" class="flex-fill">
                    <button class="btn btn-primary w-100" type="submit">
                        Finaliser le vote
                    </button>
                </form>

                <form method="post" action="index.php?p=vote/reset" class="flex-fill">
                    <button class="btn btn-secondary w-100" type="submit">
                        Réinitialiser le vote
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

</body>
</html>
