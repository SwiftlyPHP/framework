<?php
/**
 * @var string $title                Page title
 * 
 */
?>
<?= $this->render(__DIR__ . '/header.html.php', ['title' => $title]); ?>

<main class="main main--archive">
    <header class="content-header">
        <h1><?= $title; ?></h1>
    </header>

    <section class="post-content">
        No post found
    </section>

    <footer
        aria-label="Article information"
        class="post-footer"
    >
        <ul class="homepage-links">
            <li class="homepage-links__item">
                <a
                    href="/posts"
                    class="homepage-links__meta"
                >Back to posts</a>
            </li>
        </ul>
    </footer>
</main>

<?= $this->render(__DIR__ . '/footer.html.php'); ?>
