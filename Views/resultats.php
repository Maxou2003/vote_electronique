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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

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

        <?php if (!empty($history)): ?>
            <button
                type="button"
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#historyModal"
            >
                Voir le détail des votes
            </button>
        <?php endif; ?>
    </div>
   

    <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historyModalLabel">Détail des votes (n2, option)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <?php if (empty($history)): ?>
                        <p class="text-muted mb-0">Aucun détail disponible.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Code n2</th>
                                    <th scope="col">Vote</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($history as $index => $item): ?>
                                    <?php
                                        $vote = $item[0] ?? '';
                                        $n2   = $item[1] ?? '';
                                    ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars((string)$n2, ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars((string)$vote, ENT_QUOTES, 'UTF-8') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>
