<h1 class="text-center">Comptes clients</h1>

<article>
    <?php foreach ($compte as $comptes) : ?>
        <div class="row">
            <div>
                <div><?= $comptes->nom ?></div>
                <div><?= $comptes->prenom ?></div>
                <div><?= $comptes->ville ?></div>
                <div class="row">
                    <div class="col">
                        <a href="/compte/lire/<?= $comptes->id ?>" class="btn btn-primary"> Voir Compte</a>
                    </div>
                    <div class="col">
                        <a href="/compte/delete/<?= $comptes->id ?>" class="btn btn-warning"> Supprimer Compte</a>
                    </div>

                </div>
                <hr class="bg-dark border-3 border-top border-dark">
            </div>
        </div>
    <?php endforeach; ?>

</article>