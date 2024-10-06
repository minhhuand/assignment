-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2024 at 12:48 AM
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
-- Database: `assignment2`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_09_30_145543_create_products_table', 2),
(6, '2024_09_30_145757_create_orders_table', 3),
(7, '2024_09_30_150917_create_order_detail_table', 4),
(10, '2024_09_30_085234_create_products_table', 5),
(11, '2024_09_30_085357_create_orders_table', 5),
(12, '2024_09_30_085837_create_order_detail_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `total` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `created_at`, `updated_at`, `status`) VALUES
(2, 3, 377368, '2024-10-06 07:08:20', '2024-10-06 08:03:32', 1),
(3, 3, 124456, '2024-10-06 08:32:00', '2024-10-06 15:25:30', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(2, 2, 2, 3, '2024-10-06 07:08:20', '2024-10-06 07:55:22'),
(3, 2, 3, 7, '2024-10-06 08:00:02', '2024-10-06 08:03:32'),
(4, 3, 2, 1, '2024-10-06 08:32:00', '2024-10-06 08:32:00'),
(5, 3, 3, 1, '2024-10-06 08:32:15', '2024-10-06 08:32:15');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 3, 'auth_token', '1a80d4d96e397603b93f5f8b100d32046f55d50f693420095d2698c133f9e12f', '[\"*\"]', NULL, NULL, '2024-09-30 06:06:05', '2024-09-30 06:06:05'),
(2, 'App\\Models\\User', 3, 'auth_token', '9eeb65d3ae5a8262be8eeb57af993d49ab8751f3143cc53ca36f20a603a6f491', '[\"*\"]', '2024-09-30 07:33:29', NULL, '2024-09-30 06:21:52', '2024-09-30 07:33:29'),
(3, 'App\\Models\\User', 6, 'auth_token', 'f95147a450d6c020f86908b3e8a118149c24096fe9859a36c07ab053bf2f74de', '[\"*\"]', '2024-09-30 07:49:51', NULL, '2024-09-30 07:34:18', '2024-09-30 07:49:51'),
(4, 'App\\Models\\User', 7, 'auth_token', 'e9eeb21c20dab9d5c6c824248836d57fe51665c05acbcc69af40ff6569a2ecd7', '[\"*\"]', '2024-10-05 20:11:40', NULL, '2024-09-30 08:27:03', '2024-10-05 20:11:40'),
(5, 'App\\Models\\User', 3, 'auth_token', '5ba94b4798e8e7dd4df7c317d411dc616a2aadbf2d307e6bb0432ee6eea2713f', '[\"*\"]', NULL, NULL, '2024-10-05 01:57:18', '2024-10-05 01:57:18'),
(6, 'App\\Models\\User', 3, 'auth_token', '2dd8c0a3155b873d4ea142a94a84f9770a9236a2d9170a3fedbde83139156793', '[\"*\"]', NULL, NULL, '2024-10-05 02:39:48', '2024-10-05 02:39:48'),
(7, 'App\\Models\\User', 3, 'auth_token', 'c15b9add1905f6547723f08bcca30a6225a650a322d2ba0d9a3af04a445f6ebc', '[\"*\"]', NULL, NULL, '2024-10-05 02:52:30', '2024-10-05 02:52:30'),
(8, 'App\\Models\\User', 3, 'auth_token', '82b12f328fbfda34ed4f9005344ed15eb9c460d1cd787787d1d91d7ae37e04fc', '[\"*\"]', NULL, NULL, '2024-10-05 02:56:53', '2024-10-05 02:56:53'),
(9, 'App\\Models\\User', 3, 'auth_token', 'f9060f98d4f1a36404d4ab7d319e7b972e08939aed0d1d9bca6238723e17470e', '[\"*\"]', NULL, NULL, '2024-10-05 02:59:07', '2024-10-05 02:59:07'),
(10, 'App\\Models\\User', 3, 'auth_token', '5350b67b6e25c49d3e8db4f8d4ad5d7ccb1d8def74b9e94510737f6c453e99c3', '[\"*\"]', NULL, NULL, '2024-10-05 02:59:44', '2024-10-05 02:59:44'),
(11, 'App\\Models\\User', 3, 'auth_token', '6449e982ca4e5f8e610c592903ca243a8ad7f4a7f1b0a464358814b68a2b4ab6', '[\"*\"]', NULL, NULL, '2024-10-05 02:59:55', '2024-10-05 02:59:55'),
(12, 'App\\Models\\User', 3, 'auth_token', 'f0eadf8b2ef6987bea6690fd4187bd3956959f3cb72d663a98f024e808dc815a', '[\"*\"]', NULL, NULL, '2024-10-05 03:01:12', '2024-10-05 03:01:12'),
(13, 'App\\Models\\User', 3, 'auth_token', 'f7278f847e4739b67a64b61e9b2e60fa158a2cde5adb04717eeeb68e4cb1654a', '[\"*\"]', NULL, NULL, '2024-10-05 03:05:32', '2024-10-05 03:05:32'),
(14, 'App\\Models\\User', 3, 'auth_token', '270b83f8c6db90b40d2b72f8496e2a6864f2c7abd0b854472315cd0677c57d26', '[\"*\"]', NULL, NULL, '2024-10-05 05:11:33', '2024-10-05 05:11:33'),
(15, 'App\\Models\\User', 3, 'auth_token', 'e574ebb8505d3b86bcd57ac8fd852303b9385e79b7a22c470571115d9d11283a', '[\"*\"]', NULL, NULL, '2024-10-05 05:54:45', '2024-10-05 05:54:45'),
(16, 'App\\Models\\User', 3, 'auth_token', 'c5020d01a747da5f9c8d9ea43bde846b1cc19eadb0b2a65c63deffe9700141fc', '[\"*\"]', NULL, NULL, '2024-10-05 05:55:09', '2024-10-05 05:55:09'),
(17, 'App\\Models\\User', 3, 'auth_token', 'a5b8df93b561438b3edc425a64d38f2537ed694a8183d0a3cc44033c1e56c7f7', '[\"*\"]', NULL, NULL, '2024-10-05 06:27:23', '2024-10-05 06:27:23'),
(18, 'App\\Models\\User', 3, 'auth_token', '771c6582bb2992a0671a1124d4eab7b871961975e566106d8811155ea6d45d30', '[\"*\"]', NULL, NULL, '2024-10-05 06:29:08', '2024-10-05 06:29:08'),
(19, 'App\\Models\\User', 3, 'auth_token', '1b549ddadf4127c2e8bec9b145b8fb99fd17e0cca286c209c01ab4273faf070e', '[\"*\"]', NULL, NULL, '2024-10-05 06:31:41', '2024-10-05 06:31:41'),
(20, 'App\\Models\\User', 3, 'auth_token', 'd2034978d89e6e9142db363b2a50ea1a29cef61a510136f6e948e2102fea6659', '[\"*\"]', NULL, NULL, '2024-10-05 06:47:01', '2024-10-05 06:47:01'),
(21, 'App\\Models\\User', 3, 'auth_token', '25db02636ff1ad498ba73de92f1c0489fd4ced595415f62cc1a54f19cc4e69e0', '[\"*\"]', NULL, NULL, '2024-10-05 06:47:24', '2024-10-05 06:47:24'),
(22, 'App\\Models\\User', 7, 'auth_token', '4f9bf04d273cb84a23dfe276caec12e680d6a721c346db90e4774140de8fd16f', '[\"*\"]', NULL, NULL, '2024-10-05 06:47:42', '2024-10-05 06:47:42'),
(23, 'App\\Models\\User', 7, 'auth_token', '1f9eac0b988bcd026c5c70e14ea63b626b2848e4361e1a748856f6e4343aba97', '[\"*\"]', NULL, NULL, '2024-10-05 06:48:55', '2024-10-05 06:48:55'),
(24, 'App\\Models\\User', 3, 'auth_token', 'f1485fbeaf3007542877d78fa267250ea1125286875629bd52ceb30bfd1a104e', '[\"*\"]', NULL, NULL, '2024-10-05 08:06:51', '2024-10-05 08:06:51'),
(25, 'App\\Models\\User', 3, 'auth_token', '93185460c0159f4fc2a8ecda384cb3f3831b17d76cc10bfc00752554a3417ceb', '[\"*\"]', NULL, NULL, '2024-10-05 08:30:44', '2024-10-05 08:30:44'),
(26, 'App\\Models\\User', 3, 'auth_token', 'dbd10513688a66401d423cf3cf6f96f95af835d03b17583c8eefdc2e188ec6f1', '[\"*\"]', NULL, NULL, '2024-10-05 08:48:30', '2024-10-05 08:48:30'),
(27, 'App\\Models\\User', 3, 'auth_token', 'ae10685c03ed847ec1f44b9c2367ad7ca3c1a3e8e0cc0b7d895161a323cee86c', '[\"*\"]', NULL, NULL, '2024-10-05 08:48:53', '2024-10-05 08:48:53'),
(28, 'App\\Models\\User', 3, 'auth_token', '17464407d47148b8add2ea50e0a3ba0d46405f461227ab3f28fcc577d351311a', '[\"*\"]', NULL, NULL, '2024-10-05 08:53:56', '2024-10-05 08:53:56'),
(29, 'App\\Models\\User', 3, 'auth_token', '5f8fb6d90db122bdb7f29c71904801d6605eded4fd4b7bcf8f9614edd1917777', '[\"*\"]', NULL, NULL, '2024-10-05 08:57:11', '2024-10-05 08:57:11'),
(30, 'App\\Models\\User', 3, 'auth_token', 'bd765f455dc147dedc9446c2632d0aaf325c148ac3cd8ba7eb63322f85f36749', '[\"*\"]', NULL, NULL, '2024-10-05 09:00:43', '2024-10-05 09:00:43'),
(31, 'App\\Models\\User', 3, 'auth_token', '60248028f4483401dce7fcb42ddb814cdba277389bb2c59512c80e239967ac70', '[\"*\"]', NULL, NULL, '2024-10-05 09:07:10', '2024-10-05 09:07:10'),
(32, 'App\\Models\\User', 3, 'auth_token', '1b36f133fa7dc48c2c84dc2eaa77664add00ad58bf9caf8e5f37fe5ea5e8e1ea', '[\"*\"]', NULL, NULL, '2024-10-05 09:08:27', '2024-10-05 09:08:27'),
(33, 'App\\Models\\User', 3, 'auth_token', '9f307754c5d593ef2cfbb4e8f397ed317709da77acae34c35344ba3c0d019374', '[\"*\"]', NULL, NULL, '2024-10-05 09:28:29', '2024-10-05 09:28:29'),
(34, 'App\\Models\\User', 3, 'auth_token', 'cbb80d0046eeb72e67f15023e2c491b87dd3f713ca2602489b2ce876404a7cda', '[\"*\"]', NULL, NULL, '2024-10-05 09:28:37', '2024-10-05 09:28:37'),
(35, 'App\\Models\\User', 3, 'auth_token', '824ba5aa36d274f4f4d65fc8d4441b3b6038980379636560e6929142ecef71db', '[\"*\"]', NULL, NULL, '2024-10-05 09:29:53', '2024-10-05 09:29:53'),
(36, 'App\\Models\\User', 3, 'auth_token', 'e6756be48e1992e91ff377cef3affc41da3d70c90cbbf6ca2214e34d2620c8dd', '[\"*\"]', NULL, NULL, '2024-10-05 09:32:32', '2024-10-05 09:32:32'),
(37, 'App\\Models\\User', 3, 'auth_token', '3ad7a49b4d9afd4450aa6529e98d95217b669517e67acac6e50cd30fdcb8c8ca', '[\"*\"]', NULL, NULL, '2024-10-05 09:33:26', '2024-10-05 09:33:26'),
(38, 'App\\Models\\User', 3, 'auth_token', 'e492db291e5356e111970ad2ea1a072bb16518198f6d4c01dfa0f65570c5f381', '[\"*\"]', NULL, NULL, '2024-10-05 09:34:41', '2024-10-05 09:34:41'),
(39, 'App\\Models\\User', 3, 'auth_token', 'f73dbb2a79ff22c88a77a18591de30f841093d4d2368c144e8a445b29f3539e0', '[\"*\"]', NULL, NULL, '2024-10-05 09:36:21', '2024-10-05 09:36:21'),
(40, 'App\\Models\\User', 3, 'auth_token', '4045fba2efbec9e5ca89b7afa87144bd913a885596ac4d70eb11708e0a554754', '[\"*\"]', NULL, NULL, '2024-10-05 09:36:55', '2024-10-05 09:36:55'),
(41, 'App\\Models\\User', 3, 'auth_token', '0e83050397060d82805fd3c62276cbe2efd7187de51ea2fd7dd45d1fe6d55669', '[\"*\"]', NULL, NULL, '2024-10-05 09:37:11', '2024-10-05 09:37:11'),
(42, 'App\\Models\\User', 3, 'auth_token', 'ea8acdcb87191fb70730387e012a3a6f692d57c3144daf6ade80b32bca72eab5', '[\"*\"]', NULL, NULL, '2024-10-05 09:37:59', '2024-10-05 09:37:59'),
(43, 'App\\Models\\User', 3, 'auth_token', 'a349c8dbd3806089ad5911d555d6ef45d62e76719103b20f56b0a92491241101', '[\"*\"]', NULL, NULL, '2024-10-05 09:39:39', '2024-10-05 09:39:39'),
(44, 'App\\Models\\User', 7, 'auth_token', '61a5bd9c438c03273a00de05399ace52ecf2b3081f4e9d3a924eada79f6f0fc1', '[\"*\"]', NULL, NULL, '2024-10-05 09:40:52', '2024-10-05 09:40:52'),
(45, 'App\\Models\\User', 7, 'auth_token', 'ea7061c0dda67c12276f2cdd502e407cff3f21ebe08c57429c74c30ba7e7df45', '[\"*\"]', NULL, NULL, '2024-10-05 09:41:27', '2024-10-05 09:41:27'),
(46, 'App\\Models\\User', 3, 'auth_token', '8acce67637afe40f2ba50b3d516cd846b3418892c13c72a935245f69af5805ea', '[\"*\"]', NULL, NULL, '2024-10-05 09:44:09', '2024-10-05 09:44:09'),
(47, 'App\\Models\\User', 3, 'auth_token', '3730792aad06bdf90c1554e988a4145cd93918398e296b450b246497e4819f30', '[\"*\"]', NULL, NULL, '2024-10-05 19:28:03', '2024-10-05 19:28:03'),
(48, 'App\\Models\\User', 3, 'auth_token', '499a86b8b3d9534231510eb8fe7767f3be604298c80503bd8a89a269471a336b', '[\"*\"]', NULL, NULL, '2024-10-05 19:35:24', '2024-10-05 19:35:24'),
(49, 'App\\Models\\User', 3, 'auth_token', '9ccf062305ea0c737e0741513b78681715043e3a13185e2727ed18148ee9b255', '[\"*\"]', NULL, NULL, '2024-10-05 19:37:25', '2024-10-05 19:37:25'),
(50, 'App\\Models\\User', 3, 'auth_token', 'ece9545107dc5101a51fa807614a04b073b24f7e9c7e47f4296a64ee238120e6', '[\"*\"]', NULL, NULL, '2024-10-05 19:38:10', '2024-10-05 19:38:10'),
(51, 'App\\Models\\User', 3, 'auth_token', '2d33d77c3092a2bff4610bfb5ab73c18b39866f4119d5c3609fda98841598be7', '[\"*\"]', NULL, NULL, '2024-10-05 19:39:03', '2024-10-05 19:39:03'),
(52, 'App\\Models\\User', 3, 'auth_token', 'f412fa424b6c7a9805257a5f7cdec79cc48b6645999fc3ad68b5c6749b8d754b', '[\"*\"]', NULL, NULL, '2024-10-05 19:40:02', '2024-10-05 19:40:02'),
(53, 'App\\Models\\User', 3, 'auth_token', '729e1bb87a71482c64d6c8766ead6177bc425f5b1ab3ccbff008ec028d282cba', '[\"*\"]', NULL, NULL, '2024-10-05 19:44:27', '2024-10-05 19:44:27'),
(54, 'App\\Models\\User', 3, 'auth_token', 'e71f3324badd7163bc19d7f6d9df0901a16a724161f308c8338f83fde2a2b6c4', '[\"*\"]', NULL, NULL, '2024-10-05 19:46:11', '2024-10-05 19:46:11'),
(55, 'App\\Models\\User', 3, 'auth_token', 'c4d204fccef87d64fdb893435e08e7f815341bec48ba1b0335895a39a1e34025', '[\"*\"]', NULL, NULL, '2024-10-05 19:48:47', '2024-10-05 19:48:47'),
(56, 'App\\Models\\User', 3, 'auth_token', 'e9acb54ebfb46add31ae827b2c724436eb3ba14c76544f743653ecf8876ad9fa', '[\"*\"]', NULL, NULL, '2024-10-05 19:51:28', '2024-10-05 19:51:28'),
(57, 'App\\Models\\User', 3, 'auth_token', '638aa2fdcbdf282f22ec72d56bdd5c8cc8e65da708b019b184e70b856786b1a2', '[\"*\"]', NULL, NULL, '2024-10-05 19:52:44', '2024-10-05 19:52:44'),
(58, 'App\\Models\\User', 3, 'auth_token', '486da827924d1af79b777d0ce1a82690956367ddc11c21a5ea3c91491f4e274c', '[\"*\"]', NULL, NULL, '2024-10-05 19:53:41', '2024-10-05 19:53:41'),
(59, 'App\\Models\\User', 3, 'auth_token', '582744b19d6edb06552f5c088232322903f4313094cb17a961aefc4c76b44771', '[\"*\"]', NULL, NULL, '2024-10-05 20:02:51', '2024-10-05 20:02:51'),
(60, 'App\\Models\\User', 3, 'auth_token', '428434c5b7716fa4a4cc530bd01cb6d365c31786597a643d545999d8a75617c0', '[\"*\"]', '2024-10-05 20:10:04', NULL, '2024-10-05 20:04:27', '2024-10-05 20:10:04'),
(61, 'App\\Models\\User', 3, 'auth_token', '027931caf4358c9ad02977a77b5b7a2cc5eb48b2338bd8e78bf669a21c385280', '[\"*\"]', '2024-10-06 01:13:15', NULL, '2024-10-05 20:11:48', '2024-10-06 01:13:15'),
(62, 'App\\Models\\User', 3, 'auth_token', '9646eea0de4a0247b4c5d68ab7aacf0285bac940a5f9699222f853eb34c27dc4', '[\"*\"]', '2024-10-05 20:17:06', NULL, '2024-10-05 20:15:43', '2024-10-05 20:17:06'),
(63, 'App\\Models\\User', 3, 'auth_token', 'b7f12ee50a71d641b59a926c0a4e36c94b42e901118138089e699f0633d3927f', '[\"*\"]', NULL, NULL, '2024-10-05 20:22:19', '2024-10-05 20:22:19'),
(64, 'App\\Models\\User', 3, 'auth_token', 'ec366de41e8727029c2374ddffc00aaefbd21d3a6c83ddaceab6a53cb2605960', '[\"*\"]', '2024-10-05 20:23:06', NULL, '2024-10-05 20:22:41', '2024-10-05 20:23:06'),
(65, 'App\\Models\\User', 3, 'auth_token', 'df4b9b8a4028a5139e8441258dc397fe7e0e7d6dc65715e7f79a9f41a8f51d2d', '[\"*\"]', '2024-10-05 20:26:47', NULL, '2024-10-05 20:26:30', '2024-10-05 20:26:47'),
(66, 'App\\Models\\User', 3, 'auth_token', 'fe7eb644135f7d04637e51593aec92c782fc7addba056c501563d423611f91d6', '[\"*\"]', '2024-10-05 20:37:09', NULL, '2024-10-05 20:29:13', '2024-10-05 20:37:09'),
(67, 'App\\Models\\User', 3, 'auth_token', '35891f4ac2f8a51d14a88de2a1d36827f6e07d62fd1787ca46d202e9b5d73dd9', '[\"*\"]', NULL, NULL, '2024-10-05 20:42:00', '2024-10-05 20:42:00'),
(68, 'App\\Models\\User', 3, 'auth_token', 'b94237587b8ae44fc763fafd163c50f611d3498c83177c1d76a000d26f461640', '[\"*\"]', '2024-10-05 20:43:51', NULL, '2024-10-05 20:43:34', '2024-10-05 20:43:51'),
(69, 'App\\Models\\User', 3, 'auth_token', '85a48afa648c2c482a1619d50d55962ef4dfb9cbbb6b318ccd4ee97f84f1c4f3', '[\"*\"]', '2024-10-06 05:33:20', NULL, '2024-10-05 20:49:03', '2024-10-06 05:33:20'),
(70, 'App\\Models\\User', 3, 'auth_token', 'c3c1ab04c2cd931b9ee74cd52dddd53fc0376bcc64001085daa902f78cc3a00a', '[\"*\"]', NULL, NULL, '2024-10-05 21:00:11', '2024-10-05 21:00:11'),
(71, 'App\\Models\\User', 3, 'auth_token', '91c193adb254d467e36bf2dabf2e69e0c33aa25fb68b25a32d4b8eadfda601d9', '[\"*\"]', NULL, NULL, '2024-10-05 21:01:23', '2024-10-05 21:01:23'),
(72, 'App\\Models\\User', 3, 'auth_token', '1ed6754e76bf9bf097831719710bd691d68ae1ec64ca2ea43fac7cdba9ca71dc', '[\"*\"]', '2024-10-05 22:05:06', NULL, '2024-10-05 21:10:09', '2024-10-05 22:05:06'),
(73, 'App\\Models\\User', 3, 'auth_token', '7e9dc536b78adfdd75e6d56b0830271c5d5fe069dbbec23bac6ee46214dc1f91', '[\"*\"]', '2024-10-06 00:22:31', NULL, '2024-10-05 21:10:54', '2024-10-06 00:22:31'),
(74, 'App\\Models\\User', 3, 'auth_token', 'd6fd4813174d4078e357a9ada86d6ffe7b46096479ced7da9defea11079cf087', '[\"*\"]', NULL, NULL, '2024-10-05 21:16:37', '2024-10-05 21:16:37'),
(75, 'App\\Models\\User', 3, 'auth_token', 'a623fdc95953b738e08e53096b5c2f36ed679fc052f77878e471f80f83c0a9cf', '[\"*\"]', '2024-10-06 15:38:51', NULL, '2024-10-05 22:05:55', '2024-10-06 15:38:51'),
(76, 'App\\Models\\User', 3, 'auth_token', 'd47c0d15135d612c59858ef438b64562c17dccab03977cc464e83873d6ee85cc', '[\"*\"]', '2024-10-06 01:47:24', NULL, '2024-10-06 01:08:58', '2024-10-06 01:47:24'),
(77, 'App\\Models\\User', 3, 'auth_token', 'a1a0f6e58eb093f3fb62231354a8e1453d657c203206dd02d84a333e4389bed6', '[\"*\"]', '2024-10-06 15:13:37', NULL, '2024-10-06 05:33:12', '2024-10-06 15:13:37'),
(78, 'App\\Models\\User', 3, 'auth_token', '69ddfa532d5b22e6c42fbb64f1919f19a9a4d3b24aa36ce13809a11639af799c', '[\"*\"]', '2024-10-06 15:01:17', NULL, '2024-10-06 07:03:09', '2024-10-06 15:01:17'),
(79, 'App\\Models\\User', 3, 'auth_token', 'cb68a9b811e729f250e759c2c623f779704f8277cd3a0ca650cc15901f1cef08', '[\"*\"]', '2024-10-06 15:29:19', NULL, '2024-10-06 15:17:44', '2024-10-06 15:29:19');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `created_at`, `updated_at`) VALUES
(2, 'áo thun 19', 'áo thun 2024', 123456.00, 'images/M3C68FLaVzvKSl9yI0eN6k68RAxbd4cOevjwiXBz.jpg', '2024-10-06 02:54:51', '2024-10-06 06:42:21'),
(3, 'áo thun 22', 'áo len 2024', 1000.00, 'images/elDY6Rkzwf9eVT79yhHUvZfsGIN1sfv6vjFoWIbF.png', '2024-10-06 03:23:16', '2024-10-06 06:39:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `email`, `email_verified_at`, `password`, `is_admin`, `remember_token`, `created_at`, `updated_at`) VALUES
(3, 'Huân', 'Minh', 'minhhuan', 'minhhuan190102@gmail.com', NULL, '$2y$10$11MQivNq0ma93FoA2CLb1e.zYvLM6rDpNvyhbPv.P/Wh5KnG/RcsO', 1, NULL, '2024-09-30 05:46:38', '2024-09-30 05:46:38'),
(7, 'Van CD', 'Nguyen CD', 'nguyenvanCD', 'nguyenvanc@gmail.com', NULL, '$2y$10$jKfQ1M6icEWLn7HnVih0kOX9Hb8pCajO/6XL2TnTkiBzBHSZU0cDi', 0, NULL, '2024-09-30 06:55:48', '2024-10-06 01:39:26'),
(8, 'Van d', 'Nguyen d', 'nguyenvand', 'nguyenvand@gmail.com', NULL, '$2y$10$7KPPByq6r2Mo7NcwZR9EmOOy4jFJdto/MIgfmGQGI5S/3uVa79fLO', 0, NULL, '2024-09-30 06:56:03', '2024-09-30 06:56:03'),
(9, 'Nguyễn', 'Huân', '20061511', 'minh@gmail.com', NULL, '$2y$12$m572yNGODZwLaDjKzChgpO9ijMFTqZFmEkvj7UExug0kxRSmRevpK', 0, NULL, '2024-10-05 20:10:05', '2024-10-05 20:10:05'),
(15, 'Nguyễn', 'Huân', 'minhhuan11', 'ntc11@gmail.com', NULL, '$2y$12$XWIGa3PbEqlMtAbEXKThx.ejZFaPPcAehAhcKNUznq0yuSllE3ow2', 0, NULL, '2024-10-05 20:29:30', '2024-10-05 20:29:30'),
(17, 'Nguyễn', 'Huân', 'minhhuan1', 'minhhuan19011102@gmail.com', NULL, '$2y$12$VIcrkL0PmvoM0MyvbTST4uHAYamHjeGxYRAKeNrpKIR3n0lEBSDRm', 0, NULL, '2024-10-05 20:32:10', '2024-10-05 20:32:10'),
(18, 'Nguyễn 99', 'Huân', 'huan1111', 'minhhuan0102@gmail.com', NULL, '$2y$12$cByemNGZoVLpG6Wg9ohYDONf58iALVu9CrBxXI8B9FTqO9tFf1FZG', 0, NULL, '2024-10-05 20:33:57', '2024-10-06 05:58:20'),
(21, 'Nguyễn hồ', 'Huân', 'minhhuan12', 'minhhuan11190102@gmail.com', NULL, '$2y$12$x/FXt3jklKxSOaGpv4qCJeb.1Yo2Dez4Mgdl4YnPMW4Y1cwi9pyy.', 0, NULL, '2024-10-05 22:07:13', '2024-10-06 01:27:49'),
(22, 'huanP', 'nguyenP', 'nhmh', 'minhhuan1@gmail.com', NULL, '$2y$12$9mubznkOsKnTM6JssuV2lunKGReawgdIhTIK4mx8U30u9LCMDqZb6', 0, NULL, '2024-10-06 01:13:16', '2024-10-06 01:48:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_detail_order_id_foreign` (`order_id`),
  ADD KEY `order_detail_product_id_foreign` (`product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_detail_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_detail_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
