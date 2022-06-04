<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Titre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <style>
        hr {
            opacity: 0.9;
        }
    </style>
</head>

<body>
    <!--Si Session user existe et Session user à le rôle Admiin ,  navbar primary -->
    <?php if (isset($_SESSION['user']['roles']) && in_array('ROLE_ADMIN', $_SESSION['user']['roles'])) : ?>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <!--si non navbar par default-->
        <?php else : ?>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <?php endif ?>

            <div class="container-fluid">
                <a class="navbar-brand" href="/">Ma Banque</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <!-- <a class="nav-link" href="/">Acceuil</a> -->
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <!--le liens Admin ne s'affichera que si l'utilisateur est connecté et en même temps Admin-->
                        <?php if (isset($_SESSION['user']) && !empty($_SESSION['user']['id'])) : ?>
                            <?php if (isset($_SESSION['user']['roles']) && in_array('ROLE_ADMIN', $_SESSION['user']['roles'])) : ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="/admin">Menu-Admin</a>
                                </li>
                            <?php endif; ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/users/logout">Déconnexion</a>
                            </li>
                            <!-- si non connection -->
                        <?php else : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/users/login">Connection</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            </nav>

            <div class="container-fluid">
                <?php if (!empty($_SESSION['erreur'])) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_SESSION['erreur'];
                        unset($_SESSION['erreur']); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($_SESSION['message'])) : ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $_SESSION['message'];
                        unset($_SESSION['message']); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($_SESSION['user'])) : ?>
                    <div class="alert alert-success" role="alert">
                        <?php foreach ($_SESSION['user'] as $key => $value) {
                            $email = $_SESSION['user']['email'];
                        }
                        echo "Bonjour $email";
                        unset($_SESSION['message']);
                       unset($_SESSION['user']);
                        ?>

                    </div>
                <?php endif; ?>

                <?= $contenu ?>

            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
</body>

</html>