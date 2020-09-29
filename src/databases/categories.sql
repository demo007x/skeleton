drop table if exists categories;
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pid` int NOT NULL DEFAULT (0),
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT (_utf8mb4''),
  `sort` tinyint unsigned NOT NULL DEFAULT (1),
  `description` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT (_utf8mb4''),
  `img` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分类图片',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid_index` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='分类';