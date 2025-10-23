<?php

function isAuthorized(): bool
{
    return isset($_SESSION['login']);
}

function getUserName(): string
{
    return $_SESSION['login'] ?? 'guest';
}

function isAdmin(): bool
{
    return getUserName() === 'admin';
}