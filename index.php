<?php
session_start();

// Nombre maximum d'erreurs
$maxErreurs = 6;

// Liste des mots
$mots = file("mots.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Nouvelle partie
if (!isset($_SESSION['mot']) || isset($_POST['new'])) {
    $_SESSION['mot'] = strtoupper($mots[array_rand($mots)]);
    $_SESSION['lettres'] = [];
    $_SESSION['erreurs'] = 0;
}

$message = "";

// Traitement de la lettre
if (isset($_POST['lettre'])) {
    $lettre = strtoupper($_POST['lettre']);

    if (!in_array($lettre, $_SESSION['lettres'])) {
        $_SESSION['lettres'][] = $lettre;

        if (!str_contains($_SESSION['mot'], $lettre)) {
            $_SESSION['erreurs']++;
            $message = "❌ Mauvaise lettre, Sang-de-Bourbe !";
        } else {
            $message = "✨ Bien joué, la magie est en toi !";
        }
    }
}

// Mot affiché
$motAffiche = "";
$victoire = true;

foreach (str_split($_SESSION['mot']) as $l) {
    if (in_array($l, $_SESSION['lettres'])) {
        $motAffiche .= $l . " ";
    } else {
        $motAffiche .= "_ ";
        $victoire = false;
    }
}

// Défaite
$defaite = $_SESSION['erreurs'] >= $maxErreurs;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Le Pendu Magique</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>🪄 Le Pendu Magique</h1>

<div class="jeu">

    <!-- PENDU -->
    <div class="pendu">
        <?php
        // Décalage pour afficher la potence dès la 1ère erreur
        $nbErreurs = $_SESSION['erreurs'] + 1;
        $nbErreurs = min($nbErreurs, 7);
        $imgPendu = "images/pendu" . $nbErreurs . ".png";
        ?>
        <img src="<?= $imgPendu ?>" class="machine">
    </div>

    <!-- INFOS -->
    <div class="infos">

        <div class="mot"><?= $motAffiche ?></div>

        <p class="message"><?= $message ?></p>

        <p>Lettres utilisées : <?= implode(", ", $_SESSION['lettres']) ?></p>

        <?php if (!$victoire && !$defaite): ?>
            <!-- CLAVIER A–Z -->
            <form method="post" class="clavier">
                <?php
                foreach (range('A', 'Z') as $lettre) {
                    $desactive = in_array($lettre, $_SESSION['lettres']) ? "disabled" : "";
                    echo '<button type="submit" name="lettre" value="'.$lettre.'" '.$desactive.'>'
                        .$lettre.
                        '</button>';
                }
                ?>
            </form>
        <?php endif; ?>

        <?php if ($victoire): ?>
            <h2>🎉 Tu es un sorcier !</h2>
            <form method="post">
                <button name="new">Nouvelle partie</button>
            </form>
        <?php elseif ($defaite): ?>
            <h2>💀 Défaite, Moldu...</h2>
            <p>Le mot était : <?= $_SESSION['mot'] ?></p>
            <form method="post">
                <button name="new">Rejouer</button>
            </form>
        <?php endif; ?>

    </div>
</div>

<p><a href="admin.php">⚙️ Administration des mots</a></p>

</body>
</html>