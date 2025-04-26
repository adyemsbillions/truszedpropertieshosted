-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2025 at 04:36 AM
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
-- Database: `truszed`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'Billions001', '$2y$10$Ir2P2Be874sd8W8JVuBotu/uBnqZ.cm.f7499gPTxj/jGaIda9qqC');

-- --------------------------------------------------------

--
-- Table structure for table `agent`
--

CREATE TABLE `agent` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `birth_date` date NOT NULL,
  `gender` enum('male','female','prefer not to say') NOT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) NOT NULL,
  `id_type` enum('NIN','Passport','Drivers Licence','Voter''s card') NOT NULL,
  `id_number` varchar(50) NOT NULL,
  `id_front_image` varchar(255) NOT NULL,
  `id_back_image` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected','suspended') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agent`
--

INSERT INTO `agent` (`id`, `full_name`, `email`, `phone_number`, `birth_date`, `gender`, `address_line1`, `address_line2`, `id_type`, `id_number`, `id_front_image`, `id_back_image`, `password`, `created_at`, `status`) VALUES
(2, 'Zena Klein', 'tonava@mailinator.com', '487', '1991-07-02', 'female', '933 Milton Boulevard', 'Aut necessitatibus q', 'NIN', '413', 'images.jpg', 'images (1).jpg', '$2y$10$YKnmJTVeuj/yegZKjIEJjuRlkKALF810enI6b1U/PIbQg9H1lSHDm', '2025-01-17 14:41:33', 'approved'),
(3, 'fater ', 'joponuza@mailinator.com', '539', '2024-09-06', 'male', '49 Old Lane', 'Omnis quidem quia ve', 'Drivers Licence', '931', 'hello - Copy.jpg', 'images (1).jpg', '$2y$10$OgJbd6fHn9zT1OzcdxbqHeAx1rErrYoiSMREINCDg5SqOZmCkYowa', '2025-01-17 15:36:15', 'approved'),
(4, 'Linda Gregory', 'macisirici@mailinator.com', '81', '2008-09-22', 'prefer not to say', '214 White Cowley Drive', 'Eum ullam adipisicin', 'Passport', '495', 'hello - Copy - Copy.jpg', 'images (1).jpg', '$2y$10$YzsWhBpxBEGKTRgzzV45tO30aCpiKfTHbqQ7FADVV823DSoFAw.Ze', '2025-01-17 19:02:05', 'approved'),
(5, 'Dalton Schneider', 'dihyhepo@mailinator.com', '7', '2023-08-26', 'female', '95 East White Hague Street', 'Consectetur id cons', 'Passport', '24', 'hello.jpg', 'hello - Copy - Copy.jpg', '$2y$10$xhQ0tGSKsLkvMF6Y12arzuafqZ3MCZDtKNPZKOITfU9lZez9WcDA.', '2025-01-17 19:11:18', 'pending'),
(6, 'Rhiannon Reed', 'saviourwerey@gmail.com', '715', '1990-04-20', 'male', '103 Oak Street', 'Quis similique cumqu', 'Voter\'s card', '276', 'download (4).jpeg', 'download (4).jpeg', '$2y$10$1rLiGMfWJcZxrxtdWY.i..qKVrM2UZDDD4Sg1RVq31BauOB8sCCnq', '2025-02-10 16:42:59', 'approved'),
(7, 'Tatiana Atkins', 'dyxopopexe@mailinator.com', '500', '2021-03-09', 'female', '777 Hague Lane', 'Voluptatibus earum d', 'Passport', '290', 'Screenshot_1744528521.png', 'Screenshot_1744525921.png', '$2y$10$/bJEKmSPHdUKxA492oE1rOpqK2x.K2kBIKKoMh4pAZBB.etZIzWLq', '2025-04-25 17:30:38', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `agent_properties`
--

CREATE TABLE `agent_properties` (
  `id` int(11) NOT NULL,
  `property_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `address` text NOT NULL,
  `dimensions` varchar(255) DEFAULT NULL,
  `property_type` enum('rent','sell') NOT NULL,
  `bedrooms` int(11) NOT NULL,
  `bathrooms` int(11) NOT NULL,
  `toilets` int(11) NOT NULL,
  `parking_space` int(11) NOT NULL,
  `post_image` varchar(255) NOT NULL,
  `other_images` text DEFAULT NULL,
  `market_status` enum('available','unavailable') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `state` varchar(100) DEFAULT NULL,
  `lga` varchar(255) DEFAULT NULL,
  `property_details` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `stars` int(11) DEFAULT 0,
  `agent_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agent_properties`
--

INSERT INTO `agent_properties` (`id`, `property_name`, `price`, `address`, `dimensions`, `property_type`, `bedrooms`, `bathrooms`, `toilets`, `parking_space`, `post_image`, `other_images`, `market_status`, `created_at`, `state`, `lga`, `property_details`, `status`, `stars`, `agent_id`) VALUES
(1, 'Ginger Wilder', 45.00, 'Eveniet eum eos qui', 'Nisi sint nobis temp', 'rent', 10, 36, 3, 74, 'hello.jpg', 'hello - Copy.jpg', 'available', '2025-01-17 11:12:34', 'Adamawa', 'Girei', 'Deleniti provident ', 'approved', 0, NULL),
(2, 'Jasper Strong', 484.00, 'Totam iste id ullam ', 'Elit doloremque eos', 'sell', 3, 38, 2, 28, 'hello - Copy.jpg', '', 'available', '2025-01-17 11:12:48', 'Niger', 'Wushishi', 'Porro consequat Aut', 'pending', 0, NULL),
(3, 'Jasper Strong', 484.00, 'Totam iste id ullam ', 'Elit doloremque eos', 'sell', 3, 38, 2, 28, 'hello - Copy.jpg', '', 'available', '2025-01-17 11:13:53', 'Niger', 'Wushishi', 'Porro consequat Aut', 'pending', 0, NULL),
(4, 'Jasper Strong', 484.00, 'Totam iste id ullam ', 'Elit doloremque eos', 'sell', 3, 38, 2, 28, 'hello - Copy.jpg', '', 'available', '2025-01-17 11:14:17', 'Niger', 'Wushishi', 'Porro consequat Aut', 'pending', 0, NULL),
(5, 'Jasper Strong', 484.00, 'Totam iste id ullam ', 'Elit doloremque eos', 'sell', 3, 38, 2, 28, 'hello - Copy.jpg', '', 'available', '2025-01-17 11:14:25', 'Niger', 'Wushishi', 'Porro consequat Aut', 'pending', 0, NULL),
(6, 'Jasper Strong', 484.00, 'Totam iste id ullam ', 'Elit doloremque eos', 'sell', 3, 38, 2, 28, 'hello - Copy.jpg', '', 'available', '2025-01-17 11:14:32', 'Niger', 'Wushishi', 'Porro consequat Aut', 'pending', 0, NULL),
(7, 'Jasper Strong', 484.00, 'Totam iste id ullam ', 'Elit doloremque eos', 'sell', 3, 38, 2, 28, 'hello - Copy.jpg', '', 'available', '2025-01-17 11:14:59', 'Niger', 'Wushishi', 'Porro consequat Aut', 'pending', 0, NULL),
(8, 'Jasper Strong', 484.00, 'Totam iste id ullam ', 'Elit doloremque eos', 'sell', 3, 38, 2, 28, 'hello - Copy.jpg', '', 'available', '2025-01-17 11:15:03', 'Niger', 'Wushishi', 'Porro consequat Aut', 'pending', 0, NULL),
(9, 'Jasper Strong', 484.00, 'Totam iste id ullam ', 'Elit doloremque eos', 'sell', 3, 38, 2, 28, 'hello - Copy.jpg', '', 'available', '2025-01-17 11:16:22', 'Niger', 'Wushishi', 'Porro consequat Aut', 'pending', 0, NULL),
(10, 'Jasper Strong', 484.00, 'Totam iste id ullam ', 'Elit doloremque eos', 'sell', 3, 38, 2, 28, 'hello - Copy.jpg', '', 'available', '2025-01-17 11:16:40', 'Niger', 'Wushishi', 'Porro consequat Aut', 'pending', 0, NULL),
(11, 'Jasper Strong', 484.00, 'Totam iste id ullam ', 'Elit doloremque eos', 'sell', 3, 38, 2, 28, 'hello - Copy.jpg', '', 'available', '2025-01-17 11:16:53', 'Niger', 'Wushishi', 'Porro consequat Aut', 'pending', 0, NULL),
(12, 'Galvin Boyle', 633.00, 'Nisi autem fugiat ul', 'Saepe officia Nam re', 'rent', 61, 14, 52, 70, 'images.jpg', '', 'available', '2025-01-17 11:39:47', 'Adamawa', 'Ganye', 'Adipisci aliquid ame', 'approved', 0, NULL),
(13, 'adyems chech again', 966.00, 'Doloribus rem dolori', 'Enim rem eaque repel', 'sell', 50, 50, 53, 91, 'l2.jpg', 'images (1) - Copy.jpg,images (1).jpg,images.jpg,l2.jpg', 'unavailable', '2025-01-17 12:05:51', 'Osun', 'Ede South', 'Tempora ut dolores r', 'approved', 0, NULL),
(14, 'last check2', 685.00, 'Et aliquid reiciendi', 'Incididunt id natus ', 'sell', 37, 17, 34, 37, 'office-buildings-with-modern-architecture_107420-95734.jpg', '', 'unavailable', '2025-01-17 12:08:45', 'Bauchi', 'Toro', 'Enim tempore est m', 'approved', 0, NULL),
(15, 'last check2', 685.00, 'Et aliquid reiciendi', 'Incididunt id natus ', 'sell', 37, 17, 34, 37, 'office-buildings-with-modern-architecture_107420-95734.jpg', '', 'unavailable', '2025-01-17 14:02:55', 'Bauchi', 'Toro', 'Enim tempore est m', 'pending', 0, NULL),
(16, 'Claudia Owen', 724.00, 'Quia deserunt nostru', 'Distinctio Vero sin', 'rent', 36, 7, 88, 6, 'hello - Copy.jpg', '', 'unavailable', '2025-01-17 14:21:04', 'Imo', 'Nkwerre', 'Ducimus sequi ut re', 'pending', 0, NULL),
(17, 'fater duplic', 2345679.00, 'Incididunt nisi at e', 'Ut expedita in maxim', 'sell', 15, 51, 50, 83, 'images (1) - Copy.jpg', 'office-buildings-with-modern-architecture_107420-95734.jpg,papa.jfif,prop1.jpg,ru4.jpg,l2 - Copy.jpg,hello - Copy - Copy.jpg', 'available', '2025-01-17 15:40:15', 'Edo', 'Esan North-East', 'Dolorem odit aliquid', 'approved', 3, NULL),
(18, 'adyems chris duplex', 563.00, 'Obcaecati omnis do c', 'Aperiam anim est pos', 'sell', 99, 27, 62, 47, 'l2 - Copy.jpg', 'hello - Copy - Copy.jpg,hello - Copy (2).jpg,hello.jpg,images (1) - Copy.jpg,images (1).jpg,papa.jfif', 'available', '2025-01-17 18:19:26', 'Benue', 'Ukum', 'Libero alias itaque ', 'approved', 2, NULL),
(19, 'Alvin Sandoval', 113.00, 'Recusandae Quidem o', 'Non ut cillum accusa', 'rent', 65, 99, 95, 83, 'hello - Copy.jpg', 'images (1).jpg', 'unavailable', '2025-01-17 19:20:15', 'Sokoto', 'Tambuwal', 'Aliquip similique be', 'pending', 0, NULL),
(20, 'Alvin Sandoval', 113.00, 'Recusandae Quidem o', 'Non ut cillum accusa', 'rent', 65, 99, 95, 83, 'hello - Copy.jpg', 'images (1).jpg', 'unavailable', '2025-01-18 06:24:19', 'Sokoto', 'Tambuwal', 'Aliquip similique be', 'pending', 0, NULL),
(21, 'Alvin Sandoval', 113.00, 'Recusandae Quidem o', 'Non ut cillum accusa', 'rent', 65, 99, 95, 83, 'hello - Copy.jpg', 'images (1).jpg', 'unavailable', '2025-01-18 06:33:09', 'Sokoto', 'Tambuwal', 'Aliquip similique be', 'pending', 0, ''),
(22, 'Minerva Herman', 211.00, 'Libero et aut molest', 'Temporibus aliquid d', 'rent', 19, 52, 51, 72, 'images (1) - Copy.jpg', 'images (1).jpg', 'available', '2025-01-18 06:34:05', 'Edo', 'Akoko-Edo', 'Et labore rerum et c', 'approved', 0, '4'),
(23, 'Steel Mcintyre', 172.00, 'Quo voluptate molest', 'Qui pariatur Velit ', 'rent', 1, 24, 35, 14, 'hello - Copy.jpg', 'hello.jpg', 'unavailable', '2025-01-28 19:27:02', 'Lagos', 'Ikorodu', 'Dolor quis sed in ma', 'pending', 0, 'Ullamco sunt consequ'),
(24, 'Mallory Morton', 346.00, 'In quas aliqua Illo', 'Iure ullamco facilis', 'sell', 24, 84, 84, 82, 'IMG_20241222_101120_203.jpg', 'IMG_20241222_101224_864.jpg,IMG_20241225_111843_788.jpg', 'available', '2025-01-28 19:29:01', 'Ogun', 'Ikenne', 'Eaque dignissimos sa', 'pending', 0, '4'),
(25, 'Mallory Morton', 346.00, 'In quas aliqua Illo', 'Iure ullamco facilis', 'sell', 24, 84, 84, 82, 'IMG_20241222_101120_203.jpg', 'IMG_20241222_101224_864.jpg,IMG_20241225_111843_788.jpg', 'available', '2025-01-28 19:32:28', 'Ogun', 'Ikenne', 'Eaque dignissimos sa', 'pending', 0, '4'),
(26, 'adyems dup', 2000000.00, 'Ullam et quia in et ', 'Est similique non of', 'sell', 100, 17, 49, 48, 'IMG_20241222_101120_203.jpg', 'hello - Copy.jpg,hello.jpg,images (1) - Copy.jpg', 'available', '2025-01-28 19:33:07', 'Nasarawa', 'Nasarawa', 'Irure aute accusamus', 'approved', 0, '4'),
(27, 'saviour threplex', 845.00, 'In ipsum dolor qui q', 'Asperiores Nam dolor', 'sell', 45, 23, 72, 12, 'download (6).jpeg', 'download (6).jpeg,download (5).jpeg,download (4).jpeg,download (3).jpeg,download (2).jpeg', 'available', '2025-02-10 16:47:20', 'Benue', 'Ukum', 'Qui ipsam ut debitis', 'approved', 0, '6'),
(28, 'Farrah Frye', 499.00, 'Provident et amet ', 'Nulla harum lorem vo', 'rent', 17, 50, 84, 82, 'images (1) - Copy.jpg', 'hello.jpg,images (1) - Copy.jpg,images (1).jpg', 'available', '2025-02-19 15:17:32', 'Edo', 'Ovia North-East', 'Quis amet laboriosa', 'approved', 0, 'Ut explicabo Laboru'),
(29, 'Mollie Carlson', 227.00, 'Qui quia eiusmod aut', 'Id explicabo Debiti', 'rent', 53, 80, 24, 49, 'papa.jfif', 'hello - Copy - Copy.jpg,images (1) - Copy.jpg', 'available', '2025-02-19 15:23:05', 'Kano', 'Karaye', 'Suscipit et perferen', 'approved', 0, 'this one now oo'),
(30, 'Sydney Todd', 717.00, 'Sint sit excepturi ', 'Excepturi quae asper', 'sell', 27, 8, 33, 37, 'hello.jpg', 'images (1) - Copy.jpg', 'unavailable', '2025-02-19 15:25:38', 'Lagos', 'Oshodi-Isolo', 'Excepteur doloremque', 'pending', 0, 'Nihil sit amet maio'),
(31, 'Lyle Ayers', 73.00, 'Ut quo veniam volup', 'Magni voluptate accu', 'sell', 99, 52, 51, 86, 'hello.jpg', 'images (1) - Copy.jpg', 'available', '2025-02-19 15:26:24', 'Jigawa', 'Birnin Kudu', 'Sunt incidunt expli', 'approved', 0, '4'),
(32, 'Brianna Robinson', 538.00, 'Quos duis non verita', 'Dolore dolore labori', 'rent', 44, 82, 100, 95, 'images (1) - Copy.jpg', 'hello - Copy (2).jpg', 'unavailable', '2025-02-19 15:27:49', 'Ebonyi', 'Ezza South', 'Est ullamco a quaer', 'approved', 0, '4'),
(33, 'this is the new check i am making', 117.00, 'Mollitia saepe vel u', 'Incididunt quisquam ', 'rent', 12, 5, 60, 41, '59a0e3a89bc0a55bc184e2d9f695034191c9a3ec (2).png', '59a0e3a89bc0a55bc184e2d9f695034191c9a3ec.png,Educational-School-Admission-Banner-Template-2048x1152.jpg', 'available', '2025-04-25 18:00:15', 'Benue', 'Ogbadibo', 'Earum facilis nobis ', 'approved', 0, '7'),
(34, 'Guy Stokes', 629.00, 'Eligendi in eligendi', 'Ipsum officia blandi', 'rent', 60, 86, 57, 94, 'l2 - Copy.jpg', 'waa1.jpg,WhatsApp Image 2025-03-20 at 20.39.26_e7981f98.jpg', 'unavailable', '2025-04-25 18:10:17', 'Ogun', 'Ipokia', 'Sit soluta qui ad co', 'approved', 0, '1'),
(35, 'Brett Buck', 223.00, 'Asperiores neque quo', 'Ut occaecat non labo', 'sell', 74, 22, 50, 63, 'WhatsApp Image 2025-03-20 at 20.39.26_e7981f98.jpg', 'kate.jpg', 'available', '2025-04-25 18:30:53', 'Adamawa', 'Numan', 'Enim iure eaque lore', 'approved', 0, '1');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_user_sender` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `property_id`, `sender_id`, `recipient_id`, `message`, `timestamp`, `is_user_sender`) VALUES
(1, 13, 1, 0, 'i want it', '2025-01-28 17:08:46', 1),
(2, 0, 1, 0, '', '2025-01-28 17:09:45', 1),
(3, 0, 1, 0, '', '2025-01-28 17:09:50', 1),
(4, 0, 1, 0, '', '2025-01-28 17:10:32', 1),
(5, 0, 1, 0, '', '2025-01-28 17:11:09', 1),
(6, 0, 1, 0, '', '2025-01-28 17:11:39', 1),
(7, 0, 1, 0, '', '2025-01-28 17:11:41', 1),
(8, 0, 1, 0, '', '2025-01-28 17:11:44', 1),
(9, 26, 1, 4, 'i want tis', '2025-01-28 19:34:45', 1),
(10, 26, 1, 4, 'i want to buy', '2025-01-28 19:42:38', 1),
(11, 26, 1, 4, 'right now', '2025-01-28 19:43:06', 1),
(12, 0, 1, 4, 'okay', '2025-01-28 19:52:58', 0),
(13, 26, 1, 4, 'i said', '2025-01-28 19:53:23', 1),
(14, 26, 6, 4, 'i said this one', '2025-01-28 19:59:24', 1),
(15, 0, 1, 4, 'okay', '2025-01-28 20:00:31', 0),
(16, 26, 4, 0, 'okay', '2025-01-28 20:23:09', 0),
(17, 26, 6, 4, 'i will pay 2M', '2025-01-28 20:27:16', 1),
(18, 26, 6, 4, 'i just have 2million', '2025-01-28 20:32:02', 1),
(19, 27, 6, 6, 'hello i am saviour i want to buy your property but i will pay 200 dollar', '2025-02-10 16:52:04', 1),
(20, 27, 6, 1, 'oga pay 400 dolls', '2025-02-10 16:52:35', 0),
(21, 26, 13, 4, 'thats good', '2025-02-19 14:23:48', 1),
(22, 26, 13, 4, 'serr', '2025-02-19 15:07:57', 1),
(23, 22, 13, 4, 'helll', '2025-02-19 15:08:28', 1),
(24, 22, 13, 4, 'hello', '2025-02-19 15:09:15', 1),
(25, 22, 4, 1, 'welcome sir', '2025-02-19 15:11:31', 0),
(26, 22, 13, 4, 'thank you', '2025-02-19 15:11:58', 1),
(27, 28, 13, 0, 'hello', '2025-02-19 15:18:31', 1),
(28, 28, 13, 0, 'now see', '2025-02-19 15:20:14', 1),
(29, 28, 13, 0, 'cant you reply', '2025-02-19 15:22:05', 1),
(30, 29, 13, 0, 'i want this one', '2025-02-19 15:24:46', 1),
(31, 31, 13, 4, 'helooooooooooo', '2025-02-19 15:26:56', 1),
(32, 32, 13, 4, 'i wanna buy', '2025-02-19 15:28:19', 1),
(33, 32, 4, 1, 'hello', '2025-02-19 15:31:00', 0),
(34, 32, 6, 4, 'i want to buy this', '2025-02-22 10:52:11', 1),
(35, 32, 26, 4, 'i need it', '2025-04-25 17:27:33', 1),
(36, 33, 26, 7, 'i want it', '2025-04-25 18:00:44', 1),
(37, 34, 26, 1, 'i want this', '2025-04-25 18:12:28', 1);

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 'Leila Gilbert', 'gadamiwo@mailinator.com', 'Aut voluptatem animi', 'Obcaecati vel illo q', '2025-02-21 18:09:46'),
(2, 'Leila Gilbert', 'gadamiwo@mailinator.com', 'Aut voluptatem animi', 'Obcaecati vel illo q', '2025-02-21 18:12:01'),
(3, 'Leila Gilbert', 'gadamiwo@mailinator.com', 'Aut voluptatem animi', 'Obcaecati vel illo q', '2025-02-21 18:22:04'),
(4, 'Leila Gilbert', 'gadamiwo@mailinator.com', 'Aut voluptatem animi', 'Obcaecati vel illo q', '2025-02-21 18:22:45'),
(5, 'Leila Gilbert', 'gadamiwo@mailinator.com', 'Aut voluptatem animi', 'Obcaecati vel illo q', '2025-02-21 18:25:30'),
(6, 'Leila Gilbert', 'gadamiwo@mailinator.com', 'Aut voluptatem animi', 'Obcaecati vel illo q', '2025-02-21 18:25:42'),
(7, 'Leila Gilbert', 'gadamiwo@mailinator.com', 'Aut voluptatem animi', 'Obcaecati vel illo q', '2025-02-21 18:26:32');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `recipient_id`, `message`, `link`, `sent_at`, `read`) VALUES
(1, 1, 4, 'Cillum fugit minima', NULL, '2025-01-14 21:33:07', 0),
(2, 1, 4, 'Cillum fugit minima', NULL, '2025-01-14 21:34:06', 0),
(3, 1, 4, 'Cillum fugit minima', NULL, '2025-01-14 21:37:46', 0),
(4, 1, 6, 'check', NULL, '2025-01-14 21:37:52', 1),
(5, 1, 4, 'check', NULL, '2025-01-14 21:37:52', 0),
(6, 1, 3, 'check', NULL, '2025-01-14 21:37:52', 0),
(7, 1, 1, 'check', NULL, '2025-01-14 21:37:52', 0),
(8, 1, 5, 'check', NULL, '2025-01-14 21:37:52', 0),
(9, 1, 6, 'check', NULL, '2025-01-14 21:58:30', 1),
(10, 1, 4, 'check', NULL, '2025-01-14 21:58:30', 0),
(11, 1, 3, 'check', NULL, '2025-01-14 21:58:30', 0),
(12, 1, 1, 'check', NULL, '2025-01-14 21:58:30', 0),
(13, 1, 5, 'check', NULL, '2025-01-14 21:58:30', 0),
(14, 1, 6, 'Et asperiores non do\n\nLink: Mollit voluptas reru', NULL, '2025-01-14 22:00:27', 1),
(15, 1, 4, 'Et asperiores non do\n\nLink: Mollit voluptas reru', NULL, '2025-01-14 22:00:27', 0),
(16, 1, 3, 'Et asperiores non do\n\nLink: Mollit voluptas reru', NULL, '2025-01-14 22:00:27', 0),
(17, 1, 1, 'Et asperiores non do\n\nLink: Mollit voluptas reru', NULL, '2025-01-14 22:00:27', 0),
(18, 1, 5, 'Et asperiores non do\n\nLink: Mollit voluptas reru', NULL, '2025-01-14 22:00:27', 0),
(19, 1, 6, 'Omnis at quia lorem \n\nLink: amazon.com', NULL, '2025-01-14 22:00:52', 1),
(20, 1, 6, 'Omnis at quia lorem ', 'amazon.com', '2025-01-14 22:02:06', 1),
(21, 1, 3, 'bbb', '', '2025-01-14 22:37:36', 0),
(22, 1, 6, 'now', '', '2025-01-14 22:37:50', 1),
(23, 1, 6, 'this is a message to welcome all users on this platform\r\n\r\nyou can click the link below to visit our rent page too', 'truszedproperties.com/rent', '2025-01-16 20:20:40', 1),
(24, 1, 4, 'this is a message to welcome all users on this platform\r\n\r\nyou can click the link below to visit our rent page too', 'truszedproperties.com/rent', '2025-01-16 20:20:40', 0),
(25, 1, 3, 'this is a message to welcome all users on this platform\r\n\r\nyou can click the link below to visit our rent page too', 'truszedproperties.com/rent', '2025-01-16 20:20:40', 0),
(26, 1, 1, 'this is a message to welcome all users on this platform\r\n\r\nyou can click the link below to visit our rent page too', 'truszedproperties.com/rent', '2025-01-16 20:20:40', 0),
(27, 1, 5, 'this is a message to welcome all users on this platform\r\n\r\nyou can click the link below to visit our rent page too', 'truszedproperties.com/rent', '2025-01-16 20:20:41', 0),
(28, 1, 6, 'sentt', '', '2025-02-19 11:34:41', 0),
(29, 1, 6, 'Rerum adipisci et ci', 'Hic sed fuga Consec', '2025-04-25 17:45:16', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `task` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `task`, `created_at`) VALUES
(2, 'this is what i am going to do next', '2025-02-23 14:15:22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `alt_phone_number` varchar(15) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT 'Other',
  `home_address` text DEFAULT NULL,
  `residential_address` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `verification_token` varchar(255) NOT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `activation_token` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone_number`, `alt_phone_number`, `gender`, `home_address`, `residential_address`, `password`, `created_at`, `verification_token`, `is_verified`, `activation_token`, `is_active`) VALUES
(1, 'Leigh Fitzpatrick', 'hygy@mailinator.com', '1234567890', NULL, 'Other', NULL, NULL, '$2y$10$sJGnfe/NlMVFfoVfK2Zda.uK6eK.oRdrZB0WIiL5jvVZRSWf3H2t6', '2025-01-14 16:58:45', '', 0, NULL, 0),
(3, 'Ava Maxwell', 'fiquxoxopy@mailinator.com', '1234567888', NULL, 'Other', NULL, NULL, '$2y$10$Gh68HCkvevSG31JvNAca1OH/Z8AArpgbcRj1z7sOlzCcHa2OVk0rm', '2025-01-14 17:04:48', '', 0, NULL, 0),
(4, 'Cairo Dean', 'dyxoze@mailinator.com', '1234567885', NULL, 'Other', NULL, NULL, '$2y$10$0Et.9yTIoA/Xb0qTtmn81u7cE30jOfZXQUGmSKZwVeVAfZH0.zJWu', '2025-01-14 17:06:17', '', 0, NULL, 0),
(5, 'Bethany Ray', 'reqeh@mailinator.com', '+1 (268) 281-89', '+1 (908) 807-76', 'Male', 'Voluptatum nostrum i', 'Deserunt velit accus', '$2y$10$SMWWciLOp7qIJy962tGH9ePW8reTtyFRs82gRy0lvpdPLXeCJ2wTK', '2025-01-14 17:07:24', '', 0, NULL, 0),
(6, 'Adyems godlove aondosoo', 'billions@gmail.com', '09061512740', '+1 (771) 553-52', 'Other', 'Recusandae Lorem te', 'Quo commodo sequi su', '$2y$10$laeVQ2JVK42YUd1fm1gz.uwT7K2Hx7oIl3ItlTphVS6UgIS394vza', '2025-01-14 17:14:40', '', 0, NULL, 0),
(7, '', 'your-email@domain.com', '', NULL, 'Other', NULL, NULL, '', '2025-01-18 08:40:24', '39411277d3a6f5082aacf739803fa506', 0, NULL, 0),
(8, '', 'adyemsgodlov@gmail.com', '', NULL, 'Other', NULL, NULL, '', '2025-01-18 08:50:41', 'd7a2e69889d395f2f8678f2a93f947f3', 0, NULL, 0),
(13, 'Zoe Cruz', 'dizyzafy@mailinator.com', '09061512741', NULL, 'Other', NULL, NULL, '$2y$10$BNEzaQTlCrrNtNYxx1uLD.wDg07eCy18ioTb5e1Qp/RxzaY9jVEyG', '2025-02-19 13:22:41', '', 0, NULL, 0),
(15, 'Adyems godlove aondosoo', 'adyemsgodlove5@gmail.com', '09061512744', NULL, 'Other', NULL, NULL, '$2y$10$OeAgKUTfJ.E4Vqo8Dp0GG.NmgeNyZMeYuhC2rOzmKaedOL5sMOxcW', '2025-02-22 18:46:33', '', 0, 'f12f369375b4505061f328a5868a5d4aa42e4e2b227146bd38886f15ec1c89fa473a54921a7a8e9122d46e4ebf8eeb364578', 0),
(16, 'Adyems godlove aondosoo', 'adyemsgove6@gmail.com', '09061512745', NULL, 'Other', NULL, NULL, '$2y$10$ICjXu5ZoAWuxMapXry4ngeaiW//ByoFPm6nHRGeTC8EMlJegYNfvS', '2025-02-22 19:08:27', '', 0, '796814f6bfadd1cc9568a3e5757652db925e5ae2e85c04a602b0ce5e20d67f09c5eaa86c19edf344c6ced41736131b26a301', 0),
(17, 'Adyems godlove aondosoo', 'adyemsgodlve@gmail.com', '09061512747', NULL, 'Other', NULL, NULL, '$2y$10$pvxY.1ITMxm1W/Lvjlqnxuzt8HjLlvbgomi1fsZnIhJ.ZVDtXGLhK', '2025-02-22 19:14:30', '', 0, NULL, 1),
(18, 'Adyems godlove aondosoo', 'adyemsgodlove6@gmail.com', '09061512749', NULL, 'Other', NULL, NULL, '$2y$10$43HktLL5rSJJaLMunQOZeugda.mXGVrBhxp7rSR0juh9uIzlFf5ZS', '2025-02-22 19:19:48', '', 0, '6720fa5480786371184f11f4aeac92aeb8dbc864c4e9d3dcccdb7a86fe05e663c657561a0b46f93947ba2d654a38fb932b82', 0),
(19, 'Adyems godlove aondosoo', 'adyemodlove@gmail.com', '09061512723', NULL, 'Other', NULL, NULL, '$2y$10$Ngj.WQGKh7zpBgVpqL5RWulE0phkJgYT5rpKcZw7zuoiGhFTKLwyS', '2025-02-22 19:20:48', '', 0, NULL, 1),
(20, 'bar femi', 'femiadegboa2020@gmail.com', '08039841738', NULL, 'Other', NULL, NULL, '$2y$10$.BQwua/spWcX8SzZ9hDjw.KmCz5SRbeBxvJZe7rz/6XUulTw1qPOe', '2025-02-22 19:58:11', '', 0, 'fa3ae08a400f7601c0d52cb29723d5e38c09446177bac01eb3fbf58ce098deda155ddc51597f618986c2350c12b45bce2f25', 0),
(21, 'bar femi', 'femiadegla2020@gmail.com', '080398117', NULL, 'Other', NULL, NULL, '$2y$10$NIK/N8C142S/yHwT2njaN.QBEm9CjRODZGR427dHLnPvg8eYL9xwW', '2025-02-22 20:00:30', '', 0, '6941983654bc6c5462a386a8c64518a19f57255556dd2684ed914f60cc0aaf26de55f8dad89ea857aaa365ad64f51a0cf54d', 0),
(22, 'bar femi', 'femiagbola2020@gmail.com', '039811738', NULL, 'Other', NULL, NULL, '$2y$10$uyG9UDgFCU5tLliKGLX1meOwCpCAMpWzpGzPrOAtQDNrixRAg3fia', '2025-02-22 20:01:23', '', 0, 'b1f79c9d20cb08231c1f25580b5e35684f503311b8182c4bcb62c0dafb924f8a97931d914169970c05c58ed9c0c08e463d3a', 0),
(23, 'bar femi', 'femiadegbola2020@gmail.c', '080811738', NULL, 'Other', NULL, NULL, '$2y$10$S1Fhl7mkZdgG2yGIhXF32e5iP0SeJV98vdApkyiCuEeUtoi0djg8y', '2025-02-22 20:02:43', '', 0, '61fae0b8c4e5f3ac44f12195f68b89250226cf5634d431dd7e342128f1a894d0ed496ca64c55548c521d54872560b7afb48e', 0),
(24, 'bar femi', 'femiadegbola2020@gmail.com', '08039811738', NULL, 'Other', NULL, NULL, '$2y$10$6WdhWVC0pRnUQJpwA9EcFe0bGGzIjl4Kz/vqCvMm2XK72gNBQAf2e', '2025-02-22 20:07:01', '', 0, '61a9e3524b008c8f3c58a42d02a2a6a75e108dca0ed846db5c2dc4de7fd668680022de9048e3b3fc9f93f7ad8864b6df7bdc', 0),
(25, 'adyems aondosoo', 'adyemsglove@gmail.com', '119352963', NULL, 'Other', NULL, NULL, '$2y$10$z37UK5oIH76jc5B.QtAMwO8TMXGkiBBvyPkCfwvF/w75fsplGbpzS', '2025-02-22 20:08:48', '', 0, NULL, 1),
(26, 'Adyems godlove aondosoo', 'adyemsgodlove@gmail.com', '09119352963', NULL, 'Other', NULL, NULL, '$2y$10$TWHkv2McSz8PQBqG4S7jV.y..Dpxl5/B1FuVsVd4DJd3twqDEzTTq', '2025-02-22 20:09:37', '', 0, '5194acc0f9f810dad30889a817fbe08c794030979cbe801a15c888945fdddfb37017be624eb44b59c7a86a509ec4505352ab', 0),
(27, 'Adyems godlove aondosoo', 'spotifyadyems@gmail.com', '09133293270', NULL, 'Other', NULL, NULL, '$2y$10$34jKMiRIKxRJ7bzE7y3DTOv7Qta/0MI.XuoKdCBthYxWxk/Wn0RdO', '2025-03-20 10:10:22', '', 0, 'f6875b2cf9245adfb365b11306d6d593364a87d66c109a31cdcc49826eeb4ecbb3c6e4b457f8824f879d52eef93419a8bf4e', 0),
(28, 'Quinn Ward', 'zoni@mailinator.com', '09139245575', NULL, 'Other', NULL, NULL, '$2y$10$vl8qE1j1dWqrOWpJ3I.XGesg.INIYE4.uRniLoNXGVyZge/goMZ5C', '2025-03-20 10:10:43', '', 0, '5d74237e314373ec06a5aca6212fe4072fc32d74fcabbd699fe7970ce308230467efd6ec4be25ef6a9d7584d77ba9a5cec30', 0),
(29, 'Molly Powell', 'cuvaqo@mailinator.com', '09061512773', NULL, 'Other', NULL, NULL, '$2y$10$nZTrPlWxpLmnjydDZ6zokuvzljIJD6.bKyVuIoQazEPD/BsOB0/SK', '2025-04-25 17:26:06', '', 0, '59a3e633ba050cd752531da5121ed24e55853a7691d01b0b2c6c1a6e54a5673c15b04a824aa48b85ffcdf493f0fa153f6c64', 0),
(30, 'Victoria Rodriguez', 'projectadyems@gmail.com', '08165738383', NULL, 'Other', NULL, NULL, '$2y$10$9WvyzfJZEzBi13k7OfDDPeavcywsb.tNSQ/6Pa.fmSDHUBvh1wRIO', '2025-04-25 18:47:20', '', 0, '2fc1ed812e8f8caf145dd132287932879abd37f47bcc9a3e27036b9703fd3af810f8c94c93154aeb6d4239eb2a8f715eaec0', 0),
(31, 'Dane Bass', 'hugewob@mailinator.com', '09186476433', NULL, 'Other', NULL, NULL, '$2y$10$VbgWuPnbBP.j2Jacf/ZMkOj.dupU3CkjxXtMTYWSmIlbJICkbTIoO', '2025-04-25 18:49:36', '', 0, '3656b4cc554bf74c2efa354ca357503545604938a5afec8f738129d6bd5e622c903f4deadc0427acc80bc7dbfc10c3038db7', 0),
(32, 'Sloane Nguyen', 'miqybezafy@mailinator.com', '08263526262', NULL, 'Other', NULL, NULL, '$2y$10$CJ6ZgIsVYGhYOWWDYIYfPerAUvXfi4ibnMnLK1zL2MxnEW8yDtwYi', '2025-04-25 19:40:10', '', 0, 'c745d6e2fbfdab36fafdeb881358423faf4230379be9d52aa27e105125c9cdbec41223ec0b0def3207ab12d2fe85aa24d1df', 0),
(33, 'Sharon Graham', 'huqyjywe@mailinator.com', '02836473627', NULL, 'Other', NULL, NULL, '$2y$10$anR4f4LVlyiWETYDduLBH.QqFTpz4D4dJ8reSdT/QcdTogk5jt8bO', '2025-04-25 22:45:14', '', 0, '534b607f9deb2879c7cdaa2557cf92c933aa28ebd036eb6d82317b3fa52b264a350029c23a26d91042c043e3b680fbfef18c', 0),
(34, 'Alika Dyer', 'zirzufekna@gufum.com', '08373626278', NULL, 'Other', NULL, NULL, '$2y$10$aYbTz.ZMxM0ZxG4tJOJxrOPdvGEuQkmxeDbvlHJrHu9Qo8K.pxnVC', '2025-04-25 22:46:34', '', 0, NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `agent`
--
ALTER TABLE `agent`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agent_properties`
--
ALTER TABLE `agent_properties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `agent`
--
ALTER TABLE `agent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `agent_properties`
--
ALTER TABLE `agent_properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `admin` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
