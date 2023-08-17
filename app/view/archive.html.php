<?= $this->render(__DIR__ . '/header.html.php', ['title' => $title ?? 'Search Results']); ?>

<main class="main main--archive">
    <header class="content-header">
        <h1>Your search returned <?= $count; ?> results</h1>
    </header>

    <ol class="results">
        <?php foreach($posts as $slug => $post): ?>
            <li class="results__item">
                <?= $this->render(__DIR__ . '/components/card.html.php', [
                    'title'     => $post->title,
                    'author'    => $post->author,
                    'published' => $post->published,
                    'link'      => $post->link
                ]); ?>
            </li>
        <?php endforeach; ?>
    </ol>
</main>

<?= $this->render(__DIR__ . '/footer.html.php'); ?>
