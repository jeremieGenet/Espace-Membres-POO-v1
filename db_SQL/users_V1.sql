-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  lun. 21 jan. 2019 à 11:32
-- Version du serveur :  10.1.32-MariaDB
-- Version de PHP :  7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `member_area`
--

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `confirmation_token` varchar(60) DEFAULT NULL,
  `confirmed_at` datetime DEFAULT NULL,
  `reset_token` varchar(60) DEFAULT NULL,
  `reset_at` datetime DEFAULT NULL,
  `remember_token` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `mdp`, `confirmation_token`, `confirmed_at`, `reset_token`, `reset_at`, `remember_token`) VALUES
(13, 'tingle', 'tingle@tingle.fr', '$2y$10$09hDn/YuOrtINnhUj19qhOSZImTzRaYTjUXHCrse9KjYKpc8VLI0e', 'P2mZuDDkcYnoBG6bvABmg1xE95vEmg5jYCJMSSgHk3H6lMW5nXAcZSlqaCdy', NULL, NULL, NULL, NULL),
(15, 'zelda', 'zelda@zelda.fr', '$2y$10$09hDn/YuOrtINnhUj19qhOSZImTzRaYTjUXHCrse9KjYKpc8VLI0e', 'iX8nxIMy6criRJSU4Yy7qvae9ruBwYyOXFDq5gzcdRQrUqjpkKCuLsfM7h50', NULL, NULL, NULL, NULL),
(16, 'link', 'link@link.fr', '$2y$10$09hDn/YuOrtINnhUj19qhOSZImTzRaYTjUXHCrse9KjYKpc8VLI0e', 'tZidX7iAujE17nfbzeic46ym6FrTyJteJr3uPwuF45I5ky1GVzNTIzCnblAD', NULL, NULL, NULL, NULL),
(19, 'nouveau', 'nouveau@nouveau.fr', '$2y$10$09hDn/YuOrtINnhUj19qhOSZImTzRaYTjUXHCrse9KjYKpc8VLI0e', 'i3LUAPqjfn3mzUw4S0FSV2tgQqLrrAmT5judzmSDmLT2wo6OHafc61uuitFF', NULL, NULL, NULL, NULL),
(23, 'jeremie', 'jeremiesamuel.genet@gmail.com', '$2y$10$2QAb6LP.AWn6cmH23T4EsOniOQRPUAWDtqZdEfyY7Lo6rssskfKMu', NULL, '2019-01-17 06:51:51', 'FCXLfrOstVclKW0wJ9LtnHOGWyklYQDNBsitrIS5fnznkUHMxzHeu21IXuEJ', '2019-01-17 07:11:22', 'cdBtKJKH1XA7eQTqo5LjcWHEEbzJTNIPjF9quwzUpPgnsPtY6qKcnZ6si4DECiXa9TmE2jrm6zT4kf7AKP9dBus06ICu6nscH29FMgkQbbxr5tbCWmgUvOPa6uWAp9tYflCA3MU4w81LalvCG9SsIvWYWVD7dlTKGQap9oFCKT28WVbDTrH0lKRSSE3d5EvmsuN7Ruy63VfzOkhgwaa2RSeecChGWvttRjouakDlJ6znlt3Fus7NwNYD99'),
(24, 'essai', 'essai@essai.fr', '$2y$10$v1GhVGewIlM6xLVemf9J6Oul7/Lli7BzSkbmiWcU6FZ9d2Da5ybNe', 'hDsnc2qAxdUkvdrTLZ1Tm5XikGGfSvvNs8aqXxcXPLGgCqylwEUyXswQo0eQ', NULL, NULL, NULL, NULL),
(31, 'ganon', 'ganon@ganon.fr', '$2y$10$28ysrS4GQChhvfeGYp9AkOwzzzGLJV2F.Cv31nP.6bDXBxRENKBoW', '8pVIl4uyDiT9apXocFeDYYd3Uzg7vJ05pnIY6rULWYFcS9KGLGlpHxMHo7H2', NULL, NULL, NULL, NULL),
(44, 'test', 'test@test.fr', '$2y$10$3CLm0TjqZ3Zc9s831ZL2tehQEs/H/qmJ1Nm8Kc2cdr26cDlJLItwK', NULL, '2019-01-20 13:46:46', 'EHN03yKh6h2DOXUyYoyajsKcQGxqKfJzldcwjsaTwyEZ9NHXIrDM9We3I3yI', '2019-01-21 10:45:15', '8Mtc9HT9EJDm7Ro868wvLpYKhjygMuVOZhzdq1ZAFJs19AcVe70BaTJMESKFKfG3KcupRThmH0QR1KO6RarRAJVHYBpBTiM4iodp4SbTyFfuK9XTFwGgBK2iy4xQicouQXA8TcoRiY7w8wsyDqkFWOghGoKGV2HbMFbKPZb6O6AEU8xucfHAPPshU5Il1Wl4CzIRjtMRKGrBRw3Fnsf5RWlaO7HXwIYMm2DHrO74ppZJFqaaPOzebrNi9J');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
