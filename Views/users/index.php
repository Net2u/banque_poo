<h1 class="text-center">Bienvenue dans votre compte</h1>

<article>
    <div class="row">
        <div class="col">
            <div>Nom: <?= $compte->nom ?></div>
            <div>Prénom: <?= $compte->prenom ?></div>
            <div>Ville: <?= $compte->ville ?></div>
            <div>Solde: <?= $solde->solde ?></div>
        </div>
    </div>
</article>
<div class="row text-center ">
    <div class="col-4 mx-auto">
        <a href="/compte/deposer/<?= $compte->id ?>" class="btn btn-primary btn-lg ">Déposer</a>
    </div>
    <div class="col-4 mx-auto">
        <a href="/compte/retirer/<?= $compte->id ?>" class="btn btn-secondary btn-lg ">Retirer</a>
    </div>
</div>
<div class="row d-grid gap-3">
    <img class="text-center p-3 img-fluid" src="/images/fond_banque.jpg" alt="banque">
</div>

</article>