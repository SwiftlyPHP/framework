<?php
/**
 * @var string $title                Page title
 * 
 */
?>
<?= $this->render(__DIR__ . '/header.html.php', ['title' => $title ?? 'Not Found']); ?>

<main class="main main--archive">
    <header class="content-header">
        <h1><?= $title ?? '404 - Not Found'; ?></h1>
        <p>Resource not found, please try another URL</p>
    </header>

    <ul class="homepage-links">
        <li class="homepage-links__item">
            <a
                href="/posts"
                class="homepage-links__link"
            >Back to posts</a>
        </li>
    </ul>
</main>

<?= $this->render(__DIR__ . '/footer.html.php'); ?>
