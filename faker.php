<?php
require 'vendor/autoload.php';
require 'config.php';

$faker = Faker\Factory::create();

try {
    $stmt = $pdo->prepare("INSERT INTO produit (name, description, price, image_url) VALUES (:name, :description, :price, :image_url)");

    $nbProduits = 500;

    for ($i = 0; $i < $nbProduits; $i++) {
        $stmt->execute([
            ':name' => $faker->words(2, true),
            ':description' => $faker->sentence(6),
            ':price' => $faker->randomFloat(2, 1.0, 500.0),
            ':image_url' => $faker->imageUrl(200, 200, 'business', true, 'Produit')
        ]);
    }

    echo "$nbProduits produits ont été ajoutés.";
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}