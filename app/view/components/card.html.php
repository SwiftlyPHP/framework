<?php
/**
 * @var string $title                Card title
 * @var string $author               Article author
 * @var DateTimeImmutable $published Date published
 * @var string $link                 Article link
 */
?>
<article class="card">
    <h2 class="card__title">
        <a href="<?= $link; ?>"><?= $title; ?></a>
    </h2>
    <small class="card__author"><?= $author; ?></small>
    <time 
        class="card__date"
        datetime="<?= $published->format("c"); ?>"
    ><?= $published->format("jS F"); ?></time>
</article>
