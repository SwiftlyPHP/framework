<?= $this->render(__DIR__ . '/header.html.php', ['title' => 'Search Results']); ?>

<main class="main main--archive">
    <header class="content-header">
        <h1>Welcome to <span>Swiftly</span>PHP</h1>
        <p>You are currently running in mode: <strong>development</strong></p>
    </header>

    <ul class="homepage-links">
        <li class="homepage-links__item">
            <a href="#" class="homepage-links__link">
                Getting started
            </a>
        </li>
        <li class="homepage-links__item">
            <a href="#" class="homepage-links__link">
                Read the docs
            </a>
        </li>
    </ul>
</main>

<?= $this->render(__DIR__ . '/footer.html.php'); ?>
