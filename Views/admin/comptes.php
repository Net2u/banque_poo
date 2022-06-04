<table class="table table-striped">
    <thead>
        <th>ID</th>
        <th>Nom</th>
        <th>Prenom</th>
        <th>Actif-Admin</th>
        <th>Action</th>
    </thead>
    <tbody>
        <?php foreach ($compte as $comptes) : ?>
            <tr>
                <td><?= $comptes->id ?></td>
                <td><?= $comptes->nom ?></td>
                <td><?= $comptes->prenom ?></td>
                <td>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault
                        <?= $comptes->id ?>" <?= $comptes->actif ? 'checked' : '' ?> data-id="<?= $comptes->id ?>">
                        <label class="form-check-label" for="flexSwitchCheckDefault"><?= $comptes->id ?></label>
                    </div>
                </td>
                <td>
                    <a href="/annonces/modifier/<?= $comptes->id ?>" class="btn btn-warning">Modifier</a>
                    <a href="/admin/supprimeCompte/<?= $comptes->id ?>" class=" btn btn-danger">Supprimer</a>
                </td>
            </tr>

        <?php endforeach; ?>
    </tbody>
</table>