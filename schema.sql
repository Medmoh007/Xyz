-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 13 fév. 2026 à 03:33
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `hyip_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `symbol` varchar(20) NOT NULL,
  `type` enum('market','limit') NOT NULL,
  `side` enum('buy','sell') NOT NULL,
  `quantity` decimal(18,8) NOT NULL,
  `filled_quantity` decimal(18,8) DEFAULT 0.00000000,
  `price` decimal(18,2) DEFAULT NULL,
  `status` enum('open','partial','filled','cancelled','expired') DEFAULT 'open',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `symbol`, `type`, `side`, `quantity`, `filled_quantity`, `price`, `status`, `created_at`) VALUES
(43, 1, 'BTCUSDT', 'limit', 'buy', 0.10000000, 0.00000000, 45000.00, '', '2026-02-13 00:40:00'),
(44, 1, 'ETHUSDT', 'market', 'sell', 2.00000000, 0.00000000, 3200.00, '', '2026-02-13 00:40:00'),
(45, 1, 'XRPUSDT', 'limit', 'buy', 500.00000000, 0.00000000, 0.55, '', '2026-02-13 00:40:00'),
(46, 1, 'ADAUSDT', 'limit', 'sell', 1000.00000000, 0.00000000, 1.20, '', '2026-02-13 00:40:00'),
(47, 1, 'BNBUSDT', 'market', 'buy', 5.00000000, 0.00000000, 380.00, '', '2026-02-13 00:40:00'),
(48, 1, 'SOLUSDT', 'limit', 'buy', 10.00000000, 0.00000000, 95.00, '', '2026-02-13 00:40:00'),
(49, 1, 'DOGEUSDT', 'limit', 'sell', 2000.00000000, 0.00000000, 0.08, '', '2026-02-13 00:40:00'),
(50, 1, 'DOTUSDT', 'market', 'buy', 50.00000000, 0.00000000, 7.50, '', '2026-02-13 00:40:00'),
(51, 1, 'MATICUSDT', 'limit', 'buy', 300.00000000, 0.00000000, 1.10, '', '2026-02-13 00:40:00'),
(52, 1, 'LTCUSDT', 'limit', 'sell', 20.00000000, 0.00000000, 150.00, '', '2026-02-13 00:40:00'),
(53, 1, 'AVAXUSDT', 'market', 'buy', 15.00000000, 0.00000000, 30.00, '', '2026-02-13 00:40:00'),
(54, 1, 'TRXUSDT', 'limit', 'buy', 1000.00000000, 0.00000000, 0.09, '', '2026-02-13 00:40:00'),
(55, 1, 'SHIBUSDT', 'limit', 'sell', 1000000.00000000, 0.00000000, 0.00, '', '2026-02-13 00:40:00'),
(56, 1, 'NEARUSDT', 'market', 'buy', 40.00000000, 0.00000000, 5.00, '', '2026-02-13 00:40:00'),
(57, 1, 'ATOMUSDT', 'limit', 'buy', 25.00000000, 0.00000000, 12.00, '', '2026-02-13 00:40:00'),
(58, 1, 'FILUSDT', 'limit', 'sell', 10.00000000, 0.00000000, 8.50, '', '2026-02-13 00:40:00'),
(59, 1, 'APEUSDT', 'market', 'buy', 100.00000000, 0.00000000, 1.80, '', '2026-02-13 00:40:00'),
(60, 1, 'SANDUSDT', 'limit', 'buy', 200.00000000, 0.00000000, 0.70, '', '2026-02-13 00:40:00'),
(61, 1, 'UNIUSDT', 'limit', 'sell', 30.00000000, 0.00000000, 6.00, '', '2026-02-13 00:40:00'),
(62, 1, 'LINKUSDT', 'market', 'buy', 15.00000000, 0.00000000, 20.00, '', '2026-02-13 00:40:00');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `symbol` (`symbol`),
  ADD KEY `status` (`status`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`symbol`) REFERENCES `trading_pairs` (`symbol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
