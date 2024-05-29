<?php

$host = 'localhost';
$dbname = 'mglsi_news';
$username = 'mglsi_user';
$password = 'P@ss@ger@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "La connexion à la base de données a échoué : " . $e->getMessage();
    exit();
}

$dataArticles = [];
$dataCategories = [];

$stmtCategories = $pdo->query('SELECT id, libelle FROM Categorie');
$dataCategories = $stmtCategories->fetchAll(PDO::FETCH_ASSOC);

$stmtArticles = $pdo->query('SELECT id, titre, contenu, categorie FROM Article');
$dataArticles = $stmtArticles->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ActuNet</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="header">
        <ul id='list-item'></ul>
    </div>
    <div id="content"></div> 

    
    <script>
        
        const displayCategories = () => {
            let dataCategories = <?php echo json_encode($dataCategories); ?>;
            dataCategories.unshift({ 'id': 0, "libelle": "Accueil" });
            let listContent = document.getElementById("list-item");
            dataCategories.forEach(category => {
                let item = document.createElement("li");
                item.setAttribute("class", "item");
                item.textContent = category.libelle;
                listContent.appendChild(item);
            });
        }

        const displayArticlesPerCategory = () => {
            let articles = <?php echo json_encode($dataArticles); ?>;
            let content = document.getElementById("content");
            let category = document.querySelectorAll(".item");

            // Display all articles by default
            displayArticles(articles);

            category.forEach((item, index) => {
                item.addEventListener("click", () => {
                    content.innerHTML = "";
                    let categoryId = index == 0 ? null : index;
                    let filteredArticles = articles.filter(article => {
                        return categoryId === null || article.categorie == categoryId;
                    });
                    displayArticles(filteredArticles);
                });
            });
        }

        const displayArticles = (articles) => {
            let content = document.getElementById("content");
            content.innerHTML = "";
            if (articles.length === 0) {
                content.innerHTML = "<h1>Aucun article trouvé</h1>";
            } else {
                articles.forEach(article => {
                    let div = document.createElement("div");
                    let h1 = document.createElement("h1");
                    let p = document.createElement("p");
                    h1.textContent = article.titre;
                    p.textContent = article.contenu;
                    div.appendChild(h1);
                    div.appendChild(p);
                    content.appendChild(div);
                });
            }
        }

        displayCategories();
        displayArticlesPerCategory();
    </script>
</body>

</html>