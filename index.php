<?php
require 'config.php';

try {

    $produitsParPage = 10;
    $totalProduits = $pdo->query("SELECT COUNT(*) FROM produit")->fetchColumn();
    $totalPages = ceil($totalProduits / $produitsParPage);
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;


    if ($page < 1) {
        $page = 1;
    } elseif ($page > $totalPages) {
        $page = $totalPages;
    }

    $offset = ($page - 1) * $produitsParPage;
    if ($offset < 0) {
        $offset = 0;
    }

    $stmt = $pdo->prepare("SELECT * FROM produit ORDER BY id ASC LIMIT :offset, :limit");
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', (int)$produitsParPage, PDO::PARAM_INT);
    $stmt->execute();
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Produits</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h1>Produits</h1>

<?php foreach ($produits as $produit): ?>
    <div class="produit">
        <img src="<?= htmlspecialchars($produit['image_url']) ?>" alt="Image">
        <div class="details">
            <h2><?= htmlspecialchars($produit['name']) ?></h2>
            <p><strong>Prix :</strong> <?= number_format($produit['price'], 2) ?> €</p>
            <p><?= htmlspecialchars($produit['description']) ?></p>
        </div>
    </div>
<?php endforeach; ?>


<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>">&lt; Précédent</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page + 1 ?>">Suivant &gt;</a>
    <?php endif; ?>
</div>

</body>
</html>