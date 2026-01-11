<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultats du vote</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="bg-light">

<div class="container my-5">

    <h1 class="mb-4 text-center">Résultats du vote</h1>

    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5 class="card-title">Électeurs inscrits</h5>
                    <p class="display-6 mb-0">
                        <?= htmlspecialchars((string)$nbElecteurs) ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5 class="card-title">Votants</h5>
                    <p class="display-6 mb-0">
                        <?= htmlspecialchars((string)$nbVotesExprimes) ?>
                    </p>
                    <small class="text-muted">
                        <?= number_format($pctVotants, 2) ?> %
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5 class="card-title">Abstentions</h5>
                    <p class="display-6 mb-0">
                        <?= htmlspecialchars((string)$nbAbstention) ?>
                    </p>
                    <small class="text-muted">
                        <?= number_format($pctAbstention, 2) ?> %
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5 class="card-title">Bulletins invalides</h5>
                    <p class="display-6 mb-0">
                        <?= htmlspecialchars((string)$invalid) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Détail des votes exprimés
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                <tr>
                    <th>Option</th>
                    <th>Voix</th>
                    <th>% des votes exprimés</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($detailsVotes)): ?>
                    <tr>
                        <td colspan="3" class="text-center py-3">
                            Aucun vote exprimé.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($detailsVotes as $ligne): ?>
                        <tr>
                            <td><?= htmlspecialchars((string)$ligne['option']) ?></td>
                            <td><?= htmlspecialchars((string)$ligne['nombre']) ?></td>
                            <td><?= number_format($ligne['pourcent'], 2) ?> %</td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        <a href="index.php?p=vote/index" class="btn btn-secondary">
            Retour au formulaire de vote
        </a>
    </div>

</div>

</body>
</html>
