-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2024 at 01:51 PM
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
-- Database: `triang14_newinvoice`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `log_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `event` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `causer_type` varchar(255) DEFAULT NULL,
  `causer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `batch_uuid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `backend_menus`
--

CREATE TABLE `backend_menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `priority` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(3) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `backend_menus`
--

INSERT INTO `backend_menus` (`id`, `name`, `link`, `icon`, `priority`, `parent_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Dashboard', 'dashboard', 'fa fa-tachometer-alt', 1000, 0, 1, NULL, NULL),
(2, 'Invoice', 'invoice', 'fas fa-file-invoice-dollar', 1000, 0, 1, NULL, NULL),
(3, 'Quotation', 'quotation', 'fas fa-file-invoice', 1000, 0, 1, NULL, NULL),
(4, 'Product', 'product', 'fas fa-cart-plus', 1000, 0, 1, NULL, NULL),
(5, 'Barcode', 'barcode', 'fas fa-barcode', 1000, 0, 1, NULL, NULL),
(6, 'Account', '#', 'fas fa-university', 1000, 0, 1, NULL, NULL),
(7, 'Income', 'income', 'fas fa-university', 1000, 6, 1, NULL, NULL),
(8, 'Expense', 'expense', 'fas fa-file-invoice-dollar', 1000, 6, 1, NULL, NULL),
(9, 'Report', '#', 'fas fa-file', 1000, 0, 1, NULL, NULL),
(10, 'Invoice Overview Report', 'invoice-overview-report', 'fas fa-file-invoice-dollar', 1000, 9, 1, NULL, NULL),
(11, 'Administrator', '#', 'fas fa-tools', 1000, 0, 1, NULL, NULL),
(12, 'User', 'user', 'fas fa-users', 1000, 11, 1, NULL, NULL),
(13, 'Role', 'role', 'fas fa-sliders-h', 1000, 11, 1, NULL, NULL),
(14, 'Unit', 'unit', 'fas fa-calculator', 1000, 11, 1, NULL, NULL),
(15, 'Tax Rate', 'tax-rate', 'fas fa-user-secret', 1000, 11, 1, NULL, NULL),
(16, 'Setting', 'setting', 'fas fa-cogs', 1000, 0, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `amount` double NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) DEFAULT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incomes`
--

CREATE TABLE `incomes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `amount` double NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `due_date` datetime NOT NULL DEFAULT current_timestamp(),
  `reference_no` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `discount_amount` double UNSIGNED NOT NULL DEFAULT 0,
  `delivery_charge` double UNSIGNED NOT NULL DEFAULT 0,
  `tax_amount` double UNSIGNED NOT NULL DEFAULT 0,
  `subtotal_amount` double UNSIGNED NOT NULL DEFAULT 0,
  `total_amount` double UNSIGNED NOT NULL DEFAULT 0,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '5=Cancel, 10=Deleted',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT 5 COMMENT '5=Invoice, 10=Quotation',
  `payment_status` tinyint(3) UNSIGNED NOT NULL DEFAULT 5 COMMENT '5=Unpaid, 10=Partial, 15=Full Paid',
  `discount_status` tinyint(3) UNSIGNED NOT NULL DEFAULT 5 COMMENT '5=Fixed, 10=Percentage',
  `meta` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `uuid` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `user_id`, `date`, `due_date`, `reference_no`, `note`, `discount_amount`, `delivery_charge`, `tax_amount`, `subtotal_amount`, `total_amount`, `status`, `type`, `payment_status`, `discount_status`, `meta`, `created_at`, `updated_at`, `uuid`) VALUES
(1, 1, '2024-10-03 04:47:18', '2024-10-17 04:47:18', NULL, NULL, 0, 0, 0, 200, 200, 0, 5, 5, 5, NULL, '2024-10-03 04:47:26', '2024-10-03 04:49:02', NULL),
(2, 1, '2024-10-03 04:49:44', '2024-10-16 04:49:44', NULL, NULL, 0, 0, 0, 200, 200, 0, 10, 5, 5, NULL, '2024-10-03 04:49:54', '2024-10-03 04:49:54', NULL),
(3, 2, '2024-11-06 22:18:26', '2024-11-06 22:18:26', '1', 'note', 0, 0, 0, 200, 200, 0, 5, 5, 5, NULL, '2024-11-06 20:18:59', '2024-11-06 20:19:02', 'd512876c-2844-4291-a573-0096db577080');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_products`
--

CREATE TABLE `invoice_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `unit_price` double UNSIGNED NOT NULL DEFAULT 0,
  `subtotal_amount` double UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_products`
--

INSERT INTO `invoice_products` (`id`, `invoice_id`, `product_id`, `quantity`, `unit_price`, `subtotal_amount`, `created_at`, `updated_at`) VALUES
(3, 1, 1, 1, 200, 200, NULL, NULL),
(4, 2, 1, 1, 200, 200, NULL, NULL),
(5, 3, 1, 1, 200, 200, NULL, NULL),
(6, 4, 1, 1, 200, 200, NULL, NULL),
(7, 5, 1, 1, 200, 200, NULL, NULL),
(8, 6, 1, 1, 200, 200, NULL, NULL),
(9, 7, 1, 1, 200, 200, NULL, NULL),
(10, 8, 1, 1, 200, 200, NULL, NULL),
(11, 9, 1, 1, 200, 200, NULL, NULL),
(12, 10, 1, 1, 200, 200, NULL, NULL),
(13, 11, 1, 1, 200, 200, NULL, NULL),
(14, 12, 1, 1, 200, 200, NULL, NULL),
(15, 14, 1, 1, 200, 200, NULL, NULL),
(16, 15, 1, 1, 200, 200, NULL, NULL),
(17, 16, 1, 1, 200, 200, NULL, NULL),
(18, 17, 1, 1, 200, 200, NULL, NULL),
(19, 18, 1, 1, 200, 200, NULL, NULL),
(20, 19, 1, 1, 200, 200, NULL, NULL),
(21, 20, 1, 1, 200, 200, NULL, NULL),
(22, 21, 1, 1, 200, 200, NULL, NULL),
(23, 22, 1, 1, 200, 200, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_taxes`
--

CREATE TABLE `invoice_taxes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` int(10) UNSIGNED NOT NULL,
  `tax_rate_id` int(10) UNSIGNED NOT NULL,
  `amount` double UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `collection_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `disk` varchar(255) NOT NULL,
  `conversions_disk` varchar(255) DEFAULT NULL,
  `size` bigint(20) UNSIGNED NOT NULL,
  `manipulations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`manipulations`)),
  `custom_properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`custom_properties`)),
  `generated_conversions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`generated_conversions`)),
  `responsive_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`responsive_images`)),
  `order_column` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `model_type`, `model_id`, `uuid`, `collection_name`, `name`, `file_name`, `mime_type`, `disk`, `conversions_disk`, `size`, `manipulations`, `custom_properties`, `generated_conversions`, `responsive_images`, `order_column`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\Invoice', 3, 'a1d21f89-a090-4f80-b0dc-2a24494c2719', 'invoice', '1715553041421', '1715553041421.jpg', 'image/jpeg', 'public', 'public', 19742, '[]', '[]', '[]', '[]', 1, '2024-11-06 20:19:00', '2024-11-06 20:19:00');

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
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2017_08_24_000000_create_settings_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2020_08_01_192740_create_permission_tables', 1),
(6, '2020_09_11_174743_create_expenses_table', 1),
(7, '2020_09_27_153404_create_incomes_table', 1),
(8, '2020_09_27_193249_create_products_table', 1),
(9, '2020_09_27_194128_create_units_table', 1),
(10, '2020_09_28_034158_create_tax_rates_table', 1),
(11, '2020_09_28_173408_create_notification_tags_table', 1),
(12, '2020_09_28_185736_create_invoices_table', 1),
(13, '2020_10_01_170927_create_backend_menus_table', 1),
(14, '2020_10_04_022832_create_invoice_products_table', 1),
(15, '2020_10_04_022842_create_invoice_taxes_table', 1),
(16, '2020_10_08_172421_create_payments_table', 1),
(17, '2021_11_25_052019_create_activity_log_table', 1),
(18, '2023_04_07_190445_add_event_column_to_activity_log_table', 1),
(19, '2023_04_07_190446_add_batch_uuid_column_to_activity_log_table', 1),
(20, '2023_04_07_191118_create_media_table', 1),
(21, '2023_04_12_201911_create_notifications_table', 1),
(22, '2024_11_06_212138_add_uuid_to_invoices_table', 2),
(23, '2024_11_08_183438_create_payment_logs_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `creator_type` varchar(255) NOT NULL,
  `creator_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_tags`
--

CREATE TABLE `notification_tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_tags`
--

INSERT INTO `notification_tags` (`id`, `name`, `type`, `created_at`, `updated_at`) VALUES
(1, '[name]', 0, NULL, NULL),
(2, '[designation]', 0, NULL, NULL),
(3, '[email]', 0, NULL, NULL),
(4, '[phone]', 0, NULL, NULL),
(5, '[date_of_birth]', 0, NULL, NULL),
(6, '[joining_date]', 0, NULL, NULL),
(7, '[photo]', 0, NULL, NULL),
(8, '[address]', 0, NULL, NULL),
(9, '[role]', 0, NULL, NULL),
(10, '[username]', 0, NULL, NULL),
(11, '[date]', 0, NULL, NULL),
(12, '[amount]', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `invoice_id` int(10) UNSIGNED NOT NULL,
  `payment_amount` double UNSIGNED NOT NULL DEFAULT 0,
  `payment_method` tinyint(3) UNSIGNED NOT NULL DEFAULT 5 COMMENT '5=Cash On Delivery, 10=Stripe',
  `payment_date` datetime NOT NULL DEFAULT current_timestamp(),
  `create_user_id` int(10) UNSIGNED NOT NULL,
  `meta` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_logs`
--

CREATE TABLE `payment_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transactionId` text NOT NULL,
  `amount` double NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `currency` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'dashboard', 'web', NULL, NULL),
(2, 'invoice', 'web', NULL, NULL),
(3, 'invoice_create', 'web', NULL, NULL),
(4, 'invoice_edit', 'web', NULL, NULL),
(5, 'invoice_show', 'web', NULL, NULL),
(6, 'invoice_destroy', 'web', NULL, NULL),
(7, 'quotation', 'web', NULL, NULL),
(8, 'quotation_create', 'web', NULL, NULL),
(9, 'quotation_edit', 'web', NULL, NULL),
(10, 'quotation_show', 'web', NULL, NULL),
(11, 'quotation_destroy', 'web', NULL, NULL),
(12, 'user', 'web', NULL, NULL),
(13, 'user_create', 'web', NULL, NULL),
(14, 'user_edit', 'web', NULL, NULL),
(15, 'user_show', 'web', NULL, NULL),
(16, 'user_destroy', 'web', NULL, NULL),
(17, 'product', 'web', NULL, NULL),
(18, 'product_create', 'web', NULL, NULL),
(19, 'product_edit', 'web', NULL, NULL),
(20, 'product_destroy', 'web', NULL, NULL),
(21, 'barcode', 'web', NULL, NULL),
(22, 'unit', 'web', NULL, NULL),
(23, 'unit_create', 'web', NULL, NULL),
(24, 'unit_edit', 'web', NULL, NULL),
(25, 'unit_destroy', 'web', NULL, NULL),
(26, 'income', 'web', NULL, NULL),
(27, 'income_create', 'web', NULL, NULL),
(28, 'income_edit', 'web', NULL, NULL),
(29, 'income_destroy', 'web', NULL, NULL),
(30, 'expense', 'web', NULL, NULL),
(31, 'expense_create', 'web', NULL, NULL),
(32, 'expense_edit', 'web', NULL, NULL),
(33, 'expense_destroy', 'web', NULL, NULL),
(34, 'invoice-overview-report', 'web', NULL, NULL),
(35, 'role', 'web', NULL, NULL),
(36, 'role_create', 'web', NULL, NULL),
(37, 'role_edit', 'web', NULL, NULL),
(38, 'role_destroy', 'web', NULL, NULL),
(39, 'permission', 'web', NULL, NULL),
(40, 'tax-rate', 'web', NULL, NULL),
(41, 'tax-rate_create', 'web', NULL, NULL),
(42, 'tax-rate_edit', 'web', NULL, NULL),
(43, 'tax-rate_destroy', 'web', NULL, NULL),
(44, 'activity-log', 'web', NULL, NULL),
(45, 'setting', 'web', NULL, NULL),
(46, 'notification', 'web', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `price` double NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `unit_id`, `price`, `code`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Bag', 1, 200, '1222', NULL, 5, '2024-10-03 04:47:12', '2024-10-03 04:47:12');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', '2024-05-18 03:26:10', '2024-05-18 03:26:10'),
(2, 'Moderator', 'web', '2024-05-18 03:26:10', '2024-05-18 03:26:10'),
(3, 'Customer', 'web', '2024-05-18 03:26:10', '2024-05-18 03:26:10');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 1),
(2, 2),
(2, 3),
(3, 1),
(3, 2),
(4, 1),
(4, 2),
(5, 1),
(5, 2),
(5, 3),
(6, 1),
(6, 2),
(7, 1),
(7, 2),
(7, 3),
(8, 1),
(8, 2),
(9, 1),
(9, 2),
(10, 1),
(10, 2),
(10, 3),
(11, 1),
(11, 2),
(12, 1),
(12, 2),
(12, 3),
(13, 1),
(13, 2),
(14, 1),
(14, 2),
(15, 1),
(15, 2),
(15, 3),
(16, 1),
(16, 2),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(26, 2),
(27, 1),
(27, 2),
(28, 1),
(28, 2),
(29, 1),
(29, 2),
(30, 1),
(30, 2),
(31, 1),
(31, 2),
(32, 1),
(32, 2),
(33, 1),
(33, 2),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(40, 2),
(40, 3),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(46, 2),
(46, 3);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`) VALUES
(1, 'site_name', 'Green Inventory'),
(2, 'email', 'ahmednassag@gmail.com'),
(3, 'phone', '00458716574'),
(4, 'copyright_by', 'This site was developed  Green Soft BD'),
(5, 'site_logo', 'logo.png'),
(6, 'address', 'Mirpur, Dhaka'),
(7, 'timezone', 'Asia/Dhaka'),
(8, 'currency_symbol', '$'),
(9, 'currency_code', 'EGP'),
(10, 'site_sidebar', '1'),
(11, 'settingtype', 'emailsetting'),
(12, 'invoicetheme', 'invoice1'),
(13, 'stripe_key', 'test'),
(14, 'stripe_secret', 'test'),
(15, 'MERCHANT_ID', 'TESTGAT_25'),
(16, 'API_PASSWORD', 'Mm147258'),
(17, 'mail_host', 'smtp.gmail.com'),
(18, 'mail_port', '587'),
(19, 'mail_username', 'ahmednassag@gmail.com'),
(20, 'mail_password', 'ukyumnqeuamqizle'),
(21, 'mail_encryption', 'tls'),
(22, 'mail_from_address', 'ahmednassag@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `tax_rates`
--

CREATE TABLE `tax_rates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `percent` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `symbol` varchar(255) DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `symbol`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Kg', NULL, 5, '2024-10-03 04:46:56', '2024-10-03 04:46:56');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_birth` datetime DEFAULT NULL,
  `joining_date` datetime DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `designation`, `email`, `username`, `password`, `phone`, `address`, `date_of_birth`, `joining_date`, `last_login_at`, `email_verified_at`, `deleted_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Mr Admin', 'Admin', 'admin@example.com', 'admin', '$2y$10$6DmEE5Axey58Tl.kEZAerOoq3dLxKLsY3Hv6qSDHGE8ooZ8VBwtE.', '+15005550006', 'Mirpur 1, Dhaka, Bangladesh', '2024-05-18 09:26:11', '2024-05-18 09:26:11', '2024-05-18 03:26:11', NULL, NULL, 'Cel7Xp87qgtuc8G784Mbi4ILVNzXmiwYItM0aUtefuZT2ak0sob1mqJdM1od', '2024-05-18 03:26:11', '2024-05-18 03:26:11'),
(2, 'Ahmed Nabil Esmail', NULL, 'ahmednassag@gmail.com', 'ahmed_nabil_esmail', '$2y$10$6DmEE5Axey58Tl.kEZAerOoq3dLxKLsY3Hv6qSDHGE8ooZ8VBwtE.', '01016856433', 'Shorafa', NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-06 20:15:24', '2024-11-06 20:15:24'),
(3, 'user', NULL, 'user@gmail.com', 'user', '$2y$10$E4d8IJ.DW.gFBhxcZIsAGO4lUCakRUw20p.uFri12UxuHB.KmaW2W', '0123456789', 'ay kalam', NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-08 17:06:52', '2024-11-08 17:06:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject` (`subject_type`,`subject_id`),
  ADD KEY `causer` (`causer_type`,`causer_id`),
  ADD KEY `activity_log_log_name_index` (`log_name`);

--
-- Indexes for table `backend_menus`
--
ALTER TABLE `backend_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `incomes`
--
ALTER TABLE `incomes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_uuid_unique` (`uuid`);

--
-- Indexes for table `invoice_products`
--
ALTER TABLE `invoice_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_taxes`
--
ALTER TABLE `invoice_taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `media_uuid_unique` (`uuid`),
  ADD KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  ADD KEY `media_order_column_index` (`order_column`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`),
  ADD KEY `notifications_creator_type_creator_id_index` (`creator_type`,`creator_id`);

--
-- Indexes for table `notification_tags`
--
ALTER TABLE `notification_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_logs`
--
ALTER TABLE `payment_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `settings_key_index` (`key`);

--
-- Indexes for table `tax_rates`
--
ALTER TABLE `tax_rates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `backend_menus`
--
ALTER TABLE `backend_menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incomes`
--
ALTER TABLE `incomes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `invoice_products`
--
ALTER TABLE `invoice_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `invoice_taxes`
--
ALTER TABLE `invoice_taxes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `notification_tags`
--
ALTER TABLE `notification_tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_logs`
--
ALTER TABLE `payment_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tax_rates`
--
ALTER TABLE `tax_rates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
