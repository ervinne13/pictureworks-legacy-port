DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) DEFAULT NULL,
    `comments` text,
    PRIMARY KEY (`id`),
    UNIQUE KEY `id` (`id`)
) ENGINE = InnoDB AUTO_INCREMENT = 2 DEFAULT CHARSET = utf8;

INSERT INTO
    `users` (`id`, `name`, `comments`)
VALUES
    ('1', 'John Smith', 'Director');