--Cria o banco
CREATE DATABASE `ecommerce_poo`
--Cria a tabela usuario com senha, email e id
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
);
--Cria produto
CREATE TABLE `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `price` DECIMAL(10, 2) NOT NULL,
  `stock` INT NOT NULL
);
--Produtos pré-inseridos
INSERT INTO `products` (`name`, `price`, `stock`) VALUES
('Galaxy S23 Ultra', 4500.00, 10),
('iPhone 16', 7500.00, 5),
('Dell Inspiron 15', 3500.00, 8);