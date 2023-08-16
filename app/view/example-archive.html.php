<?php require __DIR__ . '/header.html.php'; ?>

<main class="main main--archive">
    <header class="content-header">
        <h1>Your search returned <?= $count; ?> results</h1>
    </header>

    <ol class="results">
        <?php foreach($posts as $slug => $post): ?>
            <li class="results__item">
                <?php
                    $title = $post['title'];
                    $author = $post['author'];
                    $published = $post['published'];
                    $link = "/post/{$slug}";

                    require __DIR__ . '/components/card.html.php';
                ?>
            </li>
        <?php endforeach; ?>
    </ol>
</main>

<?php require __DIR__ . '/footer.html.php'; ?>
