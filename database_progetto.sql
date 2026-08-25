-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2025 at 01:40 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `database_progetto`
--

-- --------------------------------------------------------

--
-- Table structure for table `carrello`
--

CREATE TABLE `carrello` (
  `Cart_id` int(11) NOT NULL,
  `fan` varchar(20) NOT NULL,
  `prodotto` int(10) DEFAULT NULL,
  `quantita` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carrello`
--

INSERT INTO `carrello` (`Cart_id`, `fan`, `prodotto`, `quantita`) VALUES
(242, 'marco', 90, 1),
(243, 'marco', 96, 1);

-- --------------------------------------------------------

--
-- Table structure for table `commenti`
--

CREATE TABLE `commenti` (
  `descrizione` varchar(500) DEFAULT NULL,
  `voto` enum('1','2','3','4','5','0') NOT NULL,
  `fan` varchar(20) NOT NULL,
  `prodotto` int(11) NOT NULL,
  `data_commento` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commenti`
--

INSERT INTO `commenti` (`descrizione`, `voto`, `fan`, `prodotto`, `data_commento`) VALUES
('descrizione fuorviante :(', '0', 'Chiara', 83, '2025-05-05 13:24:40'),
('bello', '4', 'marco', 90, '2025-05-07 09:47:26');

-- --------------------------------------------------------

--
-- Table structure for table `dettagli_ordini`
--

CREATE TABLE `dettagli_ordini` (
  `id_dettaglio` int(11) NOT NULL,
  `prodotto` int(11) NOT NULL,
  `ordine` int(11) NOT NULL,
  `quantita` int(11) NOT NULL,
  `prezzo_unitario` float(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dettagli_ordini`
--

INSERT INTO `dettagli_ordini` (`id_dettaglio`, `prodotto`, `ordine`, `quantita`, `prezzo_unitario`) VALUES
(100, 83, 69, 1, 15.00),
(102, 82, 71, 1, 25.00),
(105, 88, 73, 1, 20.00),
(129, 93, 91, 1, 10.00),
(130, 90, 91, 2, 15.00),
(131, 93, 92, 1, 10.00),
(133, 93, 94, 1, 10.00),
(135, 85, 96, 1, 10.00),
(136, 93, 97, 1, 10.00),
(137, 82, 98, 1, 25.00),
(138, 81, 99, 1, 15.00),
(139, 89, 99, 1, 40.00),
(140, 83, 100, 1, 15.00),
(141, 84, 101, 1, 22.00),
(142, 85, 101, 1, 10.00),
(143, 94, 102, 1, 15.00),
(144, 95, 102, 1, 25.00),
(148, 90, 105, 1, 15.00),
(149, 94, 106, 1, 15.00);

-- --------------------------------------------------------

--
-- Table structure for table `ordine`
--

CREATE TABLE `ordine` (
  `id_ordine` int(11) NOT NULL,
  `totale` float NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `stato` enum('in attesa di conferma','confermato','rifiutato') NOT NULL,
  `fan` varchar(20) NOT NULL,
  `quantita` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ordine`
--

INSERT INTO `ordine` (`id_ordine`, `totale`, `data`, `stato`, `fan`, `quantita`) VALUES
(68, 37, '2025-05-05 08:00:50', 'confermato', 'Chiara', 3),
(69, 14.25, '2025-05-05 08:02:03', 'rifiutato', 'Chiara', 1),
(71, 25, '2025-05-05 07:50:59', 'rifiutato', 'Chiara', 1),
(72, 33.5, '2025-05-05 07:44:13', 'rifiutato', 'Chiara', 2),
(73, 20, '2025-05-05 08:01:58', 'confermato', 'Chiara', 1),
(76, 54, '2025-05-05 08:32:34', 'confermato', 'Chiara', 3),
(80, 137.5, '2025-05-05 10:13:48', 'in attesa di conferma', 'Chiara', 10),
(82, 13.5, '2025-05-05 14:03:56', 'rifiutato', 'Chiara', 1),
(87, 27, '2025-05-05 14:03:52', 'confermato', 'Chiara', 2),
(91, 35, '2025-05-06 10:03:47', 'confermato', 'Chiara', 3),
(92, 9.5, '2025-05-06 10:03:43', 'confermato', 'Chiara', 1),
(94, 9.5, '2025-05-06 10:03:35', 'rifiutato', 'Chiara', 1),
(96, 10, '2025-05-06 09:28:16', 'in attesa di conferma', 'Chiara', 1),
(97, 9.5, '2025-05-06 10:03:25', 'rifiutato', 'Chiara', 1),
(98, 25, '2025-05-06 09:51:58', 'rifiutato', 'Chiara', 1),
(99, 55, '2025-05-06 09:51:55', 'confermato', 'Chiara', 2),
(100, 14.25, '2025-05-06 09:47:53', 'in attesa di conferma', 'Chiara', 1),
(101, 32, '2025-05-06 09:48:08', 'in attesa di conferma', 'Chiara', 2),
(102, 38.5, '2025-05-07 09:56:43', 'confermato', 'marco', 2),
(105, 12.75, '2025-05-07 09:58:05', 'in attesa di conferma', 'marco', 1),
(106, 13.5, '2025-05-07 09:58:05', 'in attesa di conferma', 'marco', 1);

-- --------------------------------------------------------

--
-- Table structure for table `prodotto`
--

CREATE TABLE `prodotto` (
  `id_prodotto` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `prezzo` float NOT NULL,
  `categoria` enum('CD','Vinile','T-shirt','Calze','Cappello','Felpa') NOT NULL,
  `artista` varchar(20) NOT NULL,
  `immagine` varchar(100) NOT NULL,
  `descrizione` varchar(150) DEFAULT NULL,
  `sconto` int(100) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prodotto`
--

INSERT INTO `prodotto` (`id_prodotto`, `nome`, `prezzo`, `categoria`, `artista`, `immagine`, `descrizione`, `sconto`) VALUES
(80, 'felpa deftones', 35, 'Felpa', 'deftones', 'felpa deftones.jpg', 'taglia S\r\n', 0),
(81, 'cappello deftones', 15, 'Cappello', 'deftones', 'cappello deftones.jpg', '', 0),
(82, 'around the fur', 25, 'Vinile', 'deftones', 'around the fur.jpg', '', 0),
(83, 'meteora', 15, 'CD', 'link(in) park', 'meteora.jpg', '', 5),
(84, 'maglietta linkin park', 22, 'T-shirt', 'link(in) park', 'maglietta linkin park.jpg', 'taglia M', 0),
(85, 'calze linkin park ', 10, 'Calze', 'link(in) park', 'calze linkin park.jpg', 'taglia unica', 0),
(86, 'vinile tarot', 20, 'Vinile', 'DearLunacy', 'vinile tarot.jpg', '', 0),
(87, 'cd tarot', 15, 'CD', 'DearLunacy', 'cd tarot.jpg', '', 0),
(88, 'maglietta linkin park', 20, 'T-shirt', 'link(in) park', 'maglietta linkin park.jpg', 'taglia S', 0),
(89, 'felpa deftones', 40, 'Felpa', 'deftones', 'felpa deftones.jpg', 'taglia L', 0),
(90, 'diamond eyes', 15, 'CD', 'deftones', 'diamond eyes.jpg', '', 15),
(91, 'diamond eyes', 25, 'Vinile', 'deftones', 'diamond eyes.jpg', '', 0),
(92, 'maglietta', 15, 'T-shirt', 'deftones', 'maglietta.jpg', 'taglia unica', 0),
(93, 'white pony calze', 10, 'Calze', 'deftones', 'white pony calze.jpg', 'taglia unica', 5),
(94, 'in rainbows', 15, 'CD', 'sara', 'in rainbows.jpg', '', 10),
(95, 'maglietta in rainbows', 25, 'T-shirt', 'sara', 'maglietta in rainbows.jpg', 'taglia XL', 0),
(96, 'cappello radiohead', 15, 'Cappello', 'sara', 'cappello radiohead.jpg', 'taglia unica', 13);

-- --------------------------------------------------------

--
-- Table structure for table `utente`
--

CREATE TABLE `utente` (
  `username` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `tipologia` enum('admin','fan','artista','') NOT NULL,
  `approvazione` enum('approvato','pending','rifiutato','') NOT NULL,
  `descrizione` varchar(500) DEFAULT NULL,
  `immagine_profilo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utente`
--

INSERT INTO `utente` (`username`, `email`, `password`, `tipologia`, `approvazione`, `descrizione`, `immagine_profilo`) VALUES
('admin', 'admin@gmail.com', '123', 'admin', 'approvato', NULL, '../uploads/profile_pictures/admin.jpg'),
('Carlo', 'carlo@gmail.com', 'password', 'admin', 'approvato', NULL, '../uploads/profile_pictures/Carlo.jpg'),
('Caterina', 'caterina@gmail.com', 'password', 'admin', 'rifiutato', NULL, '../uploads/profile_pictures/Caterina.jpg'),
('Chiara', 'chiara@gmail.com', 'password', 'fan', 'approvato', NULL, '../uploads/profile_pictures/Chiara.jpg'),
('DearLunacy', 'dearlunacy@gmail.com', 'password', 'artista', 'approvato', 'band punk', '../uploads/profile_pictures/DearLunacy.jpg'),
('deftones', 'deftones@gmail.com', 'password', 'artista', 'approvato', 'shoegaze', '../uploads/profile_pictures/deftones.jpg'),
('Giova', 'giovanni@gmail.com', 'password', 'fan', 'pending', NULL, '../uploads/profile_pictures/Giova.jpg'),
('link(in) park', 'linkinpark@gmail.com', 'password', 'artista', 'approvato', 'nu metal', '../uploads/profile_pictures/link(in) park.png'),
('marco', 'marco@gmail.com', '123', 'fan', 'approvato', NULL, '../uploads/profile_pictures/marco.jpg'),
('sara', 'sara@gmail.com', '123', 'artista', 'approvato', NULL, '../uploads/profile_pictures/sara.jpg'),
('Stellario', 'Stellario@gmail.com', 'password', 'fan', 'approvato', NULL, '../uploads/profile_pictures/Stellario.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carrello`
--
ALTER TABLE `carrello`
  ADD PRIMARY KEY (`Cart_id`),
  ADD KEY `fan` (`fan`,`prodotto`),
  ADD KEY `cart_ibfk_2` (`prodotto`);

--
-- Indexes for table `commenti`
--
ALTER TABLE `commenti`
  ADD PRIMARY KEY (`fan`,`prodotto`),
  ADD KEY `fan` (`fan`),
  ADD KEY `fan_2` (`fan`),
  ADD KEY `prodotto` (`prodotto`);

--
-- Indexes for table `dettagli_ordini`
--
ALTER TABLE `dettagli_ordini`
  ADD PRIMARY KEY (`id_dettaglio`),
  ADD KEY `id_prodotto` (`prodotto`),
  ADD KEY `id_ordine` (`ordine`);

--
-- Indexes for table `ordine`
--
ALTER TABLE `ordine`
  ADD PRIMARY KEY (`id_ordine`),
  ADD KEY `fan` (`fan`);

--
-- Indexes for table `prodotto`
--
ALTER TABLE `prodotto`
  ADD PRIMARY KEY (`id_prodotto`),
  ADD KEY `artista` (`artista`);

--
-- Indexes for table `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carrello`
--
ALTER TABLE `carrello`
  MODIFY `Cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=244;

--
-- AUTO_INCREMENT for table `dettagli_ordini`
--
ALTER TABLE `dettagli_ordini`
  MODIFY `id_dettaglio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- AUTO_INCREMENT for table `ordine`
--
ALTER TABLE `ordine`
  MODIFY `id_ordine` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `prodotto`
--
ALTER TABLE `prodotto`
  MODIFY `id_prodotto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carrello`
--
ALTER TABLE `carrello`
  ADD CONSTRAINT `carrello_ibfk_2` FOREIGN KEY (`prodotto`) REFERENCES `prodotto` (`id_prodotto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `carrello_ibfk_3` FOREIGN KEY (`fan`) REFERENCES `utente` (`username`);

--
-- Constraints for table `commenti`
--
ALTER TABLE `commenti`
  ADD CONSTRAINT `commenti_ibfk_1` FOREIGN KEY (`prodotto`) REFERENCES `prodotto` (`id_prodotto`) ON DELETE CASCADE,
  ADD CONSTRAINT `commenti_ibfk_2` FOREIGN KEY (`fan`) REFERENCES `utente` (`username`) ON DELETE CASCADE;

--
-- Constraints for table `dettagli_ordini`
--
ALTER TABLE `dettagli_ordini`
  ADD CONSTRAINT `dettagli_ordini_ibfk_1` FOREIGN KEY (`prodotto`) REFERENCES `prodotto` (`id_prodotto`) ON DELETE CASCADE,
  ADD CONSTRAINT `dettagli_ordini_ibfk_2` FOREIGN KEY (`ordine`) REFERENCES `ordine` (`id_ordine`) ON DELETE CASCADE;

--
-- Constraints for table `ordine`
--
ALTER TABLE `ordine`
  ADD CONSTRAINT `ordine_ibfk_1` FOREIGN KEY (`fan`) REFERENCES `utente` (`username`) ON DELETE CASCADE;

--
-- Constraints for table `prodotto`
--
ALTER TABLE `prodotto`
  ADD CONSTRAINT `prodotto_ibfk_1` FOREIGN KEY (`artista`) REFERENCES `utente` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
