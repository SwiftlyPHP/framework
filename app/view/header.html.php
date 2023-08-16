<?php
/**
 * @var string $title Document title
 */
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $title; ?></title>
        <link rel="stylesheet" href="/assets/style.css" />
    </head>
    <body>
        <header class="site-header">
            <span class="site-header__title" aria-hidden="true">
                <span>Swiftly</span>PHP
            </span>
            <nav class="site-header__nav navigation" aria-label="Primary">
                <ul class="navigation__list">
                    <li class="navigation__item">
                        <a class="navigation__link" href="/">Home</a>
                    </li>
                    <li class="navigation__item">
                        <a class="navigation__link" href="/posts">Posts</a>
                    </li>
                    <li class="navigation__item">
                        <a class="navigation__link" href="/examples">Examples</a>
                    </li>
                </ul>
            </nav>
        </header>

