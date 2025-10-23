<?php
require_once  "db.php";
/*
getDb()->exec("CREATE TABLE IF NOT EXISTS `sounds` (
	`id` integer primary key NOT NULL UNIQUE,
	`title` TEXT NOT NULL,
	`text` TEXT NOT NULL,
    `likes` INTEGER DEFAULT 0
)");

//создание необходимой таблицы и поля
$stmt = getDb()->prepare("SELECT count() cnt FROM sqlite_master WHERE type='table' AND name='categories'");
$stmt->execute();
if ($stmt->fetch()["cnt"]==0) {
    getDb()->exec("CREATE TABLE IF NOT EXISTS `categories` (`id` integer primary key NOT NULL UNIQUE,`name` TEXT NOT NULL)");
    getDb()->exec("ALTER TABLE 'sounds' ADD COLUMN Category integer");
}//
*/

