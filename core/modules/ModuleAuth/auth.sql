CREATE TABLE `users`(
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `login` VARCHAR (60) NOT NULL UNIQUE ,
  `pass` VARCHAR (250) NOT NULL,
  `email` VARCHAR (100)
);

CREATE TABLE `user_tokens`(
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `token` VARCHAR (250) NOT NULL UNIQUE ,
  `user_ip` VARCHAR (250) NOT NULL ,
  `user_agent` VARCHAR (250) NOT NULL ,
  `user_id` INT UNSIGNED NOT NULL ,
  `expires` BIGINT UNSIGNED NOT NULL ,
  `created` BIGINT UNSIGNED NOT NULL ,
  CONSTRAINT `user_token_fk_users` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT
);

CREATE INDEX `token_index` ON `user_tokens`(`token`);

CREATE TABLE `roles`(
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `name` VARCHAR (250) NOT NULL UNIQUE
);

CREATE TABLE `user_roles`(
  `user_id` INT UNSIGNED NOT NULL,
  `role_id` INT UNSIGNED NOT NULL,
   CONSTRAINT `user_roles_fk_users` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
   CONSTRAINT `user_roles_fk_roles` FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  PRIMARY KEY (`user_id`,`role_id`)
);



CREATE TABLE `images` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR (255) NOT NULL,
  `url` VARCHAR (255) NOT NULL
);

CREATE TABLE `categories` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR (100) NOT NULL UNIQUE ,
  `image_id` INT UNSIGNED NOT NULL,
  FOREIGN KEY (`image_id`) REFERENCES `images`(`id`)
);

CREATE TABLE `posts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR (250) NOT NULL,
  `text` TEXT NOT NULL,
  `likes` INT,
  `views` INT,
  `time` BIGINT,
  `user_id` INT UNSIGNED NOT NULL,
  `image_id` INT UNSIGNED NOT NULL,
  `category_id` INT UNSIGNED NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`image_id`) REFERENCES `images`(`id`),
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`)
);

CREATE TABLE `likes`(
  `user_id` INT UNSIGNED NOT NULL,
  `post_id` INT UNSIGNED NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`),
  PRIMARY KEY(`user_id`,`post_id`)
);

CREATE TRIGGER `add_like` AFTER INSERT ON `likes` FOR EACH ROW
BEGIN
  UPDATE `posts` SET `likes`=`likes`+1 WHERE `id`=NEW.`post_id`;
end;

CREATE TRIGGER `remove_like` BEFORE DELETE ON `likes` FOR EACH ROW
BEGIN
  UPDATE `posts` SET `likes`=`likes`-1 WHERE `id`=OLD.`post_id`;
end;