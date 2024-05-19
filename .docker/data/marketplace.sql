-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: mysql:3306
-- Tiempo de generación: 19-05-2024 a las 18:07:54
-- Versión del servidor: 8.0.37
-- Versión de PHP: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `marketplace`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart`
--

CREATE TABLE `cart` (
  `id` int NOT NULL,
  `customer_id_id` int DEFAULT NULL,
  `status` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customer`
--

CREATE TABLE `customer` (
  `id` int NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `customer`
--

INSERT INTO `customer` (`id`, `name`, `email`, `password`) VALUES
(1, 'Daniel', 'dmaggio@dmaggio.com', 'abc123'),
(2, 'gumersindo', 'admin@example.com', '$2y$13$iUoSZLHbXgGPC/k353iAjemoG2IB16E9P0ycuHdwV5zsf7A0idYzW'),
(3, 'perico', 'perico@example.com', '$2y$13$PaI96bPux.v13a/rmAeN5u1ixqrwuBBwlUBQkfdZg1aH2ak8wh5tO'),
(4, 'nuevo', 'nuevo@example.com', '$2y$13$OQdpYj2PWeqcz8RnND0jTeGS8f.wu/vib70MbPnl4J1DmQhz9E1CO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customer_address`
--

CREATE TABLE `customer_address` (
  `id` int NOT NULL,
  `customer_id` int DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_address` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `customer_address`
--

INSERT INTO `customer_address` (`id`, `customer_id`, `address`, `default_address`) VALUES
(47, 3, 'calle 123', 1),
(50, 2, 'calle 123', 1),
(51, 1, 'direccion 123 1ºb', 1),
(52, 4, 'calle nueva', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order`
--

CREATE TABLE `order` (
  `id` int NOT NULL,
  `customer_id_id` int NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_line`
--

CREATE TABLE `order_line` (
  `id` int NOT NULL,
  `product_id_id` int NOT NULL,
  `order_id_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product`
--

CREATE TABLE `product` (
  `id` int NOT NULL,
  `supplier_id_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock_quantity` int NOT NULL,
  `version` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `product`
--

INSERT INTO `product` (`id`, `supplier_id_id`, `name`, `price`, `description`, `stock_quantity`, `version`) VALUES
(1, 1, 'producto1', 1000, 'descripcion producto 1', 1, 6),
(2, 1, 'producto2', 1000, 'descripcion producto 2', 1, 2),
(3, 1, 'producto3', 1000, 'descripcion producto 3', 3, 1),
(4, 1, 'producto4', 1000, 'descripcion producto 4', 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_cart`
--

CREATE TABLE `product_cart` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `cart_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `supplier`
--

CREATE TABLE `supplier` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cif` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `supplier`
--

INSERT INTO `supplier` (`id`, `name`, `cif`, `password`) VALUES
(1, 'proveedor1', 'id-23849', 'abc123');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_DD6A6BAEB171EB6C` (`customer_id_id`);

--
-- Indices de la tabla `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `customer_address`
--
ALTER TABLE `customer_address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_DD42C42C9395C3F3` (`customer_id`);

--
-- Indices de la tabla `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F5299398B171EB6C` (`customer_id_id`);

--
-- Indices de la tabla `order_line`
--
ALTER TABLE `order_line`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9CE58EE1DE18E50B` (`product_id_id`),
  ADD KEY `IDX_9CE58EE1FCDAEAAA` (`order_id_id`);

--
-- Indices de la tabla `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D34A04ADA65F9C7D` (`supplier_id_id`);

--
-- Indices de la tabla `product_cart`
--
ALTER TABLE `product_cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_864BAA164584665A` (`product_id`),
  ADD KEY `IDX_864BAA161AD5CDBF` (`cart_id`);

--
-- Indices de la tabla `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `customer_address`
--
ALTER TABLE `customer_address`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de la tabla `order`
--
ALTER TABLE `order`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT de la tabla `order_line`
--
ALTER TABLE `order_line`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT de la tabla `product`
--
ALTER TABLE `product`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `product_cart`
--
ALTER TABLE `product_cart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=448;

--
-- AUTO_INCREMENT de la tabla `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `FK_DD6A6BAEB171EB6C` FOREIGN KEY (`customer_id_id`) REFERENCES `customer` (`id`);

--
-- Filtros para la tabla `customer_address`
--
ALTER TABLE `customer_address`
  ADD CONSTRAINT `FK_DD42C42C9395C3F3` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`);

--
-- Filtros para la tabla `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `FK_F5299398B171EB6C` FOREIGN KEY (`customer_id_id`) REFERENCES `customer` (`id`);

--
-- Filtros para la tabla `order_line`
--
ALTER TABLE `order_line`
  ADD CONSTRAINT `FK_9CE58EE1DE18E50B` FOREIGN KEY (`product_id_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `FK_9CE58EE1FCDAEAAA` FOREIGN KEY (`order_id_id`) REFERENCES `order` (`id`);

--
-- Filtros para la tabla `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_D34A04ADA65F9C7D` FOREIGN KEY (`supplier_id_id`) REFERENCES `supplier` (`id`);

--
-- Filtros para la tabla `product_cart`
--
ALTER TABLE `product_cart`
  ADD CONSTRAINT `FK_864BAA161AD5CDBF` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`),
  ADD CONSTRAINT `FK_864BAA164584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
