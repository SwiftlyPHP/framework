<?php
/**
 * @var string $title                Post title
 * @var string $author               Author name
 * @var string $author_slug          Author slug
 * @var DateTimeImmutable $published Post publication date
 * @var string $content              Post HTML content
 * 
 */
?>
<?= $this->render(__DIR__ . '/header.html.php', ['title' => $title]); ?>

<main class="main main--archive">
    <header class="content-header">
        <h1><?= $title; ?></h1>
    </header>

    <section class="post-content">
        <?= $content; ?>
    </section>

    <footer
        aria-label="Article information"
        class="post-footer"
    >
        <ul class="homepage-links">
            <li class="homepage-links__item">
                <a
                    href="/author/<?= $author_slug; ?>"
                    class="homepage-links__meta"
                ><?= $author; ?></a>
            </li>
            <li class="homepage-links__item">
                <time
                    datetime="<?= $published->format("c"); ?>"
                    class="homepage-links__meta"
                ><?= $published->format("jS F"); ?></time>
            </li>
        </ul>
    </footer>
</main>

<?= $this->render(__DIR__ . '/footer.html.php'); ?>
