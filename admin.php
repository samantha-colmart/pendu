<?php
$mots = file("mots.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$message = "";

if (isset($_POST['add'])) {
    $mot = strtoupper(trim($_POST['mot']));
    if (ctype_alpha($mot) && !in_array($mot, $mots)) {
        $mots[] = $mot;
        file_put_contents("mots.txt", implode("\n", $mots));
        $message = "Mot ajouté ✔️";
    } else {
        $message = "Mot invalide ❌";
    }
}

if (isset($_GET['del']) && count($mots) > 1) {
    $mots = array_diff($mots, [$_GET['del']]);
    file_put_contents("mots.txt", implode("\n", $mots));
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin – Mots</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>⚙️ Administration des mots</h1>
<p><?= $message ?></p>

<ul class="admin">
<?php foreach ($mots as $mot): ?>
    <li><?= $mot ?> <a href="?del=<?= $mot ?>">❌</a></li>
<?php endforeach; ?>
</ul>

<form method="post">
    <input type="text" name="mot" required pattern="[A-Za-z]+">
    <button name="add">Ajouter</button>
</form>

<p><a href="index.php">⬅ Retour au jeu</a></p>

</body>
</html>