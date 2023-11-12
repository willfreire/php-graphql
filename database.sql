CREATE TABLE IF NOT EXISTS `users` (
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(250) NULL,
    `email` VARCHAR(100) NULL,
    `active` TINYINT NULL
);

CREATE TABLE IF NOT EXISTS `posts` (
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT,
    `title` VARCHAR(100) NULL,
    `body` TEXT NULL,
    `author_id` INT NULL
);

INSERT INTO `users` (`name`, `email`, `active`) VALUES 
('Erick', 'erick@erick.com', 1), 
('João', 'joao@erick.com', 1), 
('Manuel', 'manuel@erick.com', 0);

INSERT INTO `posts` (`title`, `body`, `author_id`) VALUES 
('Primeiro Artigo', 'Conteúdo 1', 1), 
('Segundo Artigo', 'Conteúdo 2', 1), 
('Terceiro Artigo', 'Conteúdo 3', 1),
('Quarto Artigo', 'Conteúdo 4', 2);