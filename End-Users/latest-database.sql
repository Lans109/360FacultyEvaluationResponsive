-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2024 at 08:41 PM
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
-- Database: `evalsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `password_hash`, `email`, `created_at`) VALUES
(1, 'admin1', '$2y$10$H1zBzwt8PYnhAV9heYzAJep236obJTC2VqyIzmTBA.PY.anEifMJC', 'admin1@example.com', '2024-10-13 06:52:28'),
(2, 'admin2', '$2y$10$1ZzjDCd5w5Caa1o1Mcz0J.S7Uq/fv./mrIJaMJvrX6kBz1SO9/2um', 'admin2@example.com', '2024-10-13 06:52:28'),
(3, 'admin3', '$2y$10$wcP5Xyq/DlUaHLfYbvFuaOjMrU2IaC9zRfLMEz7tDfG23SeOB1UNi', 'admmin@gmail', '2024-10-20 08:18:25'),
(4, 'admin4', '12345', 'admin4@example.com', '2024-10-20 14:06:35'),
(5, 'admin5', '12345', 'admin5@example.com', '2024-10-20 14:06:35');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `course_description` varchar(220) DEFAULT NULL,
  `course_code` varchar(220) DEFAULT NULL,
  `department_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_name`, `created_at`, `course_description`, `course_code`, `department_id`) VALUES
(1, 'Software Engineering', '2024-10-13 06:55:01', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa autem voluptatibus excepturi veniam, fuga rerum vitae vero optio cum nam amet labore illo dolores ab ipsam maxime veritatis, perspiciatis blanditiis.', 'CSCN10C', 1),
(2, 'Information Assurance and Security', '2024-10-13 06:55:01', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa autem voluptatibus excepturi veniam, fuga rerum vitae vero optio cum nam amet labore illo dolores ab ipsam maxime veritatis, perspiciatis blanditiis.', 'ITEN07C', 1),
(3, 'Automata and Language', '2024-10-20 14:09:50', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa autem voluptatibus excepturi veniam, fuga rerum vitae vero optio cum nam amet labore illo dolores ab ipsam maxime veritatis, perspiciatis blanditiis.', 'CSCN05C', 1),
(4, 'Quantitative Methods', '2024-10-20 14:09:50', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa autem voluptatibus excepturi veniam, fuga rerum vitae vero optio cum nam amet labore illo dolores ab ipsam maxime veritatis, perspiciatis blanditiis.', 'ITEN04C', 1),
(5, 'Architechture and Organization', '2024-10-20 14:09:50', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa autem voluptatibus excepturi veniam, fuga rerum vitae vero optio cum nam amet labore illo dolores ab ipsam maxime veritatis, perspiciatis blanditiis.', 'CSCN07C', 1),
(31, 'Application Development and Emerging Technologies', '2024-11-08 17:31:47', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa autem voluptatibus excepturi veniam, fuga rerum vitae vero optio cum nam amet labore illo dolores ab ipsam maxime veritatis, perspiciatis blanditiis.', 'DCSN06C', 1),
(33, 'Fine Arts', '2024-11-12 13:02:38', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa autem voluptatibus excepturi veniam, fuga rerum vitae vero optio cum nam amet labore illo dolores ab ipsam maxime veritatis, perspiciatis blanditiis.', 'FASN01C', 2);

-- --------------------------------------------------------

--
-- Table structure for table `course_sections`
--

CREATE TABLE `course_sections` (
  `course_section_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `section` varchar(220) NOT NULL,
  `period_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_sections`
--

INSERT INTO `course_sections` (`course_section_id`, `course_id`, `section`, `period_id`) VALUES
(1, 1, 'CS301', 1),
(2, 2, 'CS302', 1),
(19, 4, 'CS301', 1),
(20, 4, 'CS302', 1),
(21, 3, 'IT204', 1),
(24, 31, 'CS301', 1),
(32, 1, 'CS302', 1),
(33, 33, 'IT404', 1);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `department_description` varchar(500) DEFAULT NULL,
  `department_code` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`, `created_at`, `department_description`, `department_code`) VALUES
(1, 'College of Engineering, Computer Studies and Architecture', '2024-10-13 06:49:09', 'Combining technical skills with creative design, this college prepares students for careers in engineering, IT, and architecture. It emphasizes innovation, problem-solving, and practical applications.', 'COECSA'),
(2, 'College of Fine Arts and Design', '2024-10-13 06:49:09', 'This college nurtures artistic talent and creativity, offering programs in visual arts, graphic design, and multimedia. Students develop a solid foundation in artistic expression and technical skills.', 'CFAD'),
(3, 'College of International Tourism and Hospitality Management', '2024-10-13 06:49:09', 'Focused on global hospitality and tourism, this college equips students with the skills to manage hotels, resorts, and travel services. Programs emphasize cultural sensitivity, customer service, and industry trends.', 'CITHM'),
(4, 'College of Nursing', '2024-10-12 22:49:09', 'Dedicated to training compassionate and skilled nurses, this college emphasizes patient care, clinical skills, and ethical practice. Graduates are prepared to excel in various healthcare settings and provide high-quality nursing care.', 'CON'),
(6, 'College of Allied Medical Sciences', '2024-10-12 22:49:09', 'This college provides students with a strong foundation in medical and health sciences, preparing them for careers in healthcare. Programs are designed to develop essential skills for diagnostic, therapeutic, and preventive services.', 'CAMS'),
(14, 'College of Liberal Arts and Education', '2024-10-26 19:06:06', 'Focused on cultivating well-rounded educators and thinkers, this college emphasizes critical thinking and communication skills. It prepares students for careers in teaching, social sciences, and humanities.', 'CLAE'),
(18, 'College of Business Administration', '2024-10-26 23:22:12', 'Dedicated to developing future business leaders, this college offers programs in management, marketing, and finance. Students gain practical skills and strategic knowledge to excel in dynamic business environments.', 'CBA');

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

CREATE TABLE `evaluations` (
  `evaluation_id` int(11) NOT NULL,
  `course_section_id` int(20) DEFAULT NULL,
  `survey_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `period_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluations`
--

INSERT INTO `evaluations` (`evaluation_id`, `course_section_id`, `survey_id`, `created_at`, `period_id`) VALUES
(190, 20, 1, '2024-11-24 15:46:23', 1),
(191, 24, 1, '2024-11-24 15:46:50', 1),
(192, 20, 2, '2024-11-24 15:47:55', 1),
(193, 24, 2, '2024-11-24 15:47:58', 1),
(194, 20, 3, '2024-11-24 15:48:37', 1),
(195, 24, 3, '2024-11-24 15:48:40', 1),
(196, 20, 4, '2024-11-24 15:49:05', 1),
(197, 24, 4, '2024-11-24 15:49:08', 1),
(198, 24, 4, '2024-11-24 15:49:33', 1);

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_periods`
--

CREATE TABLE `evaluation_periods` (
  `period_id` int(11) NOT NULL,
  `semester` varchar(10) NOT NULL,
  `academic_year` varchar(9) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('active','completed','upcoming') DEFAULT 'upcoming'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluation_periods`
--

INSERT INTO `evaluation_periods` (`period_id`, `semester`, `academic_year`, `start_date`, `end_date`, `status`) VALUES
(1, '1st', '2024-2025', '2024-08-01', '2024-12-31', 'active'),
(2, '2nd', '2024-2025', '2025-01-01', '2025-05-31', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `faculty_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `profile_image` varchar(220) DEFAULT 'uploads/default_image.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`faculty_id`, `username`, `password_hash`, `email`, `created_at`, `updated_at`, `first_name`, `last_name`, `department_id`, `phone_number`, `profile_image`) VALUES
(1, 'professor1', 'abcde', 'john.doe@gmail.com', '2024-10-13 06:56:21', '2024-11-23 20:24:58', 'John', 'Doe', 1, '0917-632-5894', 'uploads/default_image.jpg'),
(2, 'professor2', 'fghij', 'halford.bermudez@lpunetwork.edu.ph', '2024-10-13 06:56:21', '2024-11-18 18:10:20', 'Halford', 'Bermudez', 1, '0921-354-7203', 'uploads/default_image.jpg'),
(3, 'professor3', '12345', 'Sylas@lpunetwork.edu.ph', '2024-10-20 14:49:59', '2024-11-18 18:10:27', 'Sylas', 'Mage', 2, '0947-530-2894', 'uploads/default_image.jpg'),
(4, 'professor4', '12345', 'Talo@lpunetwork.edu.ph', '2024-10-20 14:49:59', '2024-11-18 18:10:32', 'Talon', 'Blade', 1, '0919-634-5183', 'uploads/default_image.jpg'),
(5, 'professor5', '12345', 'Katarina@lpunetwork.edu.ph', '2024-10-20 14:49:59', '2024-11-18 18:10:37', 'Katarina', 'Blade', 4, '0925-459-7302', 'uploads/default_image.jpg'),
(6, 'professor6', '12345', 'Samsung@lpunetwork.edu.ph', '2024-10-21 02:15:29', '2024-11-18 18:10:42', 'Sam', 'Sung', 2, '0938-670-8294', 'uploads/default_image.jpg'),
(7, 'professor7', '12345', 'Myth@lpunetwork.edu.ph', '2024-10-21 02:15:29', '2024-11-18 18:10:48', 'Wukong', 'Myth', 1, '0916-482-3501', 'uploads/default_image.jpg'),
(8, 'professor8', '12345', 'Albert@lpunetwork.edu.ph', '2024-10-21 02:15:29', '2024-11-18 18:10:54', 'Albert', 'Einstein', 3, '0943-748-5906', 'uploads/default_image.jpg'),
(9, 'professor9', '12345', 'Jin@lpunetwork.edu.ph', '2024-10-21 02:15:29', '2024-11-18 18:10:58', 'Jin', 'Kazama', 2, '0927-516-4380', 'uploads/default_image.jpg'),
(10, 'professor10', '12345', 'Emilie@lpunetwork.edu.ph', '2024-10-21 02:15:29', '2024-11-18 18:11:04', 'Emilie', 'Rochefort', 1, '0918-536-0497', 'uploads/default_image.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `faculty_courses`
--

CREATE TABLE `faculty_courses` (
  `faculty_id` int(11) NOT NULL,
  `course_section_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_courses`
--

INSERT INTO `faculty_courses` (`faculty_id`, `course_section_id`) VALUES
(1, 20),
(1, 24),
(2, 1),
(2, 2),
(2, 19),
(2, 32),
(3, 33),
(6, 21);

-- --------------------------------------------------------

--
-- Table structure for table `faculty_evaluations`
--

CREATE TABLE `faculty_evaluations` (
  `evaluation_id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `program_id` int(11) NOT NULL,
  `program_code` varchar(10) NOT NULL,
  `program_name` varchar(255) NOT NULL,
  `program_description` text DEFAULT NULL,
  `department_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`program_id`, `program_code`, `program_name`, `program_description`, `department_id`) VALUES
(1, 'BSMT', 'BS Medical Technology', 'Focuses on laboratory sciences, training students in clinical procedures and diagnostics used to detect, diagnose, and treat diseases.', 6),
(2, 'BS-PHAR', 'BS Pharmacy', 'Prepares students for careers in the pharmaceutical industry, emphasizing drug therapy, patient care, and medication management.', 6),
(3, 'BS-RADTECH', 'BS Radiologic Technology', 'Trains students in medical imaging techniques, including X-ray, MRI, and CT scans, to assist in patient diagnosis.', 6),
(4, 'BS-BIO', 'BS Biology', 'Provides a broad understanding of biological sciences, covering genetics, ecology, and microbiology as foundations for careers in research, healthcare, and biotechnology.', 6),
(5, 'B-COM', 'Bachelor of Arts in Communication', 'Focuses on communication theory, media studies, and public relations, preparing students for careers in journalism, media, and corporate communication.', 14),
(6, 'AB-FS', 'AB Foreign Service', 'Prepares students for diplomatic careers with training in international relations, global politics, and cultural studies.', 14),
(7, 'AB-LS', 'AB Legal Studies', 'Offers foundational legal knowledge and critical thinking skills, ideal for students aiming for law school or legal assistant roles.', 14),
(8, 'BECE', 'Bachelor of Early Childhood Education', 'Equips students with teaching methods and psychology for educating young children, particularly in preschool and elementary levels.', 14),
(9, 'BSE', 'Bachelor in Secondary Education ', 'Prepares future educators to teach at the secondary level, specializing in pedagogy and adolescent development.', 14),
(30, 'BS-PSYCH', 'BS Psychology', 'Covers psychological theories and practices, preparing students for roles in counseling, human resources, or further studies in psychology.', 14),
(31, 'BSA', 'BS Accountancy', 'Provides expertise in financial accounting, taxation, and auditing, essential for roles as accountants or financial analysts.', 18),
(32, 'BA-HRM', 'BS Business Administration major in Human Resource Development Management', 'Trains students in managing employee relations, talent acquisition, and organizational behavior.', 18),
(33, 'BA-MA', 'BS Business Administration major in Management Accounting', 'Equips students with skills in budgeting, cost management, and financial planning within organizations.', 18),
(34, 'BA-MM', 'BS Business Administration major in Marketing Management', 'Focuses on marketing strategies, consumer behavior, and brand management, preparing students for marketing careers.', 18),
(35, 'BA-OM', 'BS Business Administration major in Operations Management', 'Teaches principles of production, logistics, and supply chain management to streamline business processes.', 18),
(36, 'BSCA', 'BS Customs Administration', 'Prepares students for careers in customs, trade compliance, and logistics within global supply chains.', 18),
(37, 'BS-ENTR-AI', 'BS Entrepreneurship with specialization in Aesthetics Industry Management', 'Focuses on business startup processes and managing ventures specifically within the aesthetics and wellness industry.', 18),
(38, 'BSREM', 'BS Real Estate Management', 'Provides knowledge in real estate laws, property management, and market analysis, preparing students for roles in real estate.', 18),
(39, 'BS-ARCH', 'Bachelor of Science in Architecture', 'Teaches design principles, architectural theory, and construction techniques for careers in architecture.', 1),
(40, 'BSCS', 'BS Computer Science', 'Computer Science will be able to learn about other subjects such as machine learning, blockchain, social hacking and data analytics.', 1),
(41, 'BSIT', 'BS Information Technology', 'Equips students with the basic ability to conceptualize, design and implement software applications.', 1),
(42, 'LIS', 'Bachelor of Library and Information Science', 'Trains students in library management, information retrieval, and cataloging, preparing them for roles in library sciences.', 1),
(43, 'BSAE', 'Bachelor of Science in Aeronautical Engineering', 'Prepares students for the aviation industry with training in aircraft design, systems, and safety.', 1),
(44, 'BSCE', 'BS Civil Engineering', 'Designed to prepare graduates to apply knowledge of mathematics, calculus-based physics, chemistry, and at least one additional area of basic science.', 1),
(45, 'BSCPE', 'Bachelor of Science in Computer Engineering', 'Combines computer science and electrical engineering for computing technology development.', 1),
(46, 'BET-CTM', 'Bachelor of Engineering Technology', 'Teaches construction practices and project management in engineering settings.', 1),
(47, 'BSECE', 'BS in Electronics Engineering', 'Focuses on electronic devices, telecommunications, and signal processing.', 1),
(48, 'BSIE', 'Bachelor of Science in Industrial Engineering', 'Teaches process optimization, production planning, and quality control in various industries.', 1),
(49, 'BSME', 'BS Mechanical Engineering', 'Covers thermodynamics, mechanical design, and manufacturing processes for engineering roles.', 1),
(50, 'BFA', 'Bachelor of Fine Arts', 'Offers creative training in painting, sculpture, and visual arts for careers in the arts sector.', 2),
(51, 'BMMA', 'Bachelor of Multimedia Arts', 'Combines digital media, graphic design, and animation, preparing students for multimedia industries.', 2),
(52, 'B-PHOTO', 'Bachelor in Photography', 'Teaches photography techniques and visual storytelling for careers in professional photography.', 1),
(53, 'BS-ITTM', 'BS International Travel and Tourism Management', 'Prepares students for roles in tourism management, including travel planning and tour operations.', 3),
(54, 'BS-ITTM-HW', 'BS International Travel and Tourism Management (Health and Wellness)', 'Specializes in health and wellness tourism, focusing on wellness programs and spa management.', 3),
(55, 'BSHM', 'BS International Hospitality Management', 'Prepares students to become effective leaders, managers, and/or entrepreneurs in the global hospitality industry. ', 3),
(56, 'BSND', 'BS Nutrition and Dietetics', 'Prepares students to become dietitians or nutritionists, focusing on meal planning and health management.', 3),
(57, 'BSN', 'BS Nursing', 'Provides clinical and theoretical training in patient care, preparing students to become registered nurses.', 4);

-- --------------------------------------------------------

--
-- Table structure for table `program_chairs`
--

CREATE TABLE `program_chairs` (
  `chair_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `department_id` int(10) DEFAULT NULL,
  `profiile_image` varchar(220) DEFAULT 'uploads/default_image.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program_chairs`
--

INSERT INTO `program_chairs` (`chair_id`, `username`, `password_hash`, `email`, `created_at`, `first_name`, `last_name`, `department_id`, `profiile_image`) VALUES
(1, 'chair1', '$2y$10$2h2z0cH05zaPPCifFWoPqecCAybejdIxTE.KbAopfyULnTJh27hfe', 'chair1dsadsdd@example.com', '2024-10-12 22:52:28', 'Cj', 'Moya', 14, 'uploads/default_image.jpg'),
(23, 'asmith', '$2y$10$E7qZ6L7..0m4RlnD5QH1QO53zHcG/dA9kvlqIHXlW8AF0t3F5mU6e', 'asmith@example.com', '2024-10-27 04:00:00', 'Alice', 'Smith', 18, 'uploads/default_image.jpg'),
(31, 'mjones', '$2y$10$E7qZ6L7..0m4RlnD5QH1QO53zHcG/dA9kvlqIHXlW8AF0t3F5mU6e', 'mjones@example.com', '2024-10-27 04:00:00', 'Michael', 'Jones', 6, 'uploads/default_image.jpg'),
(65, 'jdoe', '$2y$10$E7qZ6L7..0m4RlnD5QH1QO53zHcG/dA9kvlqIHXlW8AF0t3F5mU6e', 'jdoe@example.com', '2024-10-27 04:00:00', 'John', 'Doe', 1, 'uploads/default_image.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `program_chair_evaluations`
--

CREATE TABLE `program_chair_evaluations` (
  `chair_id` int(11) NOT NULL,
  `evaluation_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `program_courses`
--

CREATE TABLE `program_courses` (
  `program_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program_courses`
--

INSERT INTO `program_courses` (`program_id`, `course_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(39, 1),
(50, 33);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `question_code` varchar(50) DEFAULT NULL,
  `criteria_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `question_text`, `created_at`, `question_code`, `criteria_id`) VALUES
(1, 'States the objectives of the lesson/activities before the start of the class.', '2024-11-01 08:38:12', 'TC1', 1),
(2, 'Orients the students on the planned activities for the day.', '2024-11-01 08:38:12', 'TC2', 1),
(3, 'Adheres to school regulations on proper conduct/behavior.', '2024-11-01 08:38:12', 'TC3', 1),
(4, 'Creates/Modifies appropriate activities as necessary.', '2024-11-01 08:38:12', 'TC4', 1),
(5, 'Communicates ideas clearly and correctly.', '2024-11-01 08:38:12', 'TC5', 1),
(6, 'Demonstrates teaching/laboratory skills with ease.', '2024-11-01 08:38:12', 'TC6', 1),
(7, 'Uses teaching methods appropriate to the lesson/activity.', '2024-11-01 08:38:12', 'TC7', 1),
(8, 'Gives appropriate comments or relevant feedbacks.', '2024-11-01 08:38:12', 'TC8', 1),
(9, 'Provides opportunities for student participation.', '2024-11-01 08:38:12', 'TC9', 1),
(10, 'Presents lesson/activities at a pace appropriate to student capability.', '2024-11-01 08:38:12', 'TC10', 1),
(11, 'Provides examples that show application of concept.', '2024-11-01 08:38:12', 'TC11', 1),
(12, 'Relates lesson/activities to other fields of knowledge.', '2024-11-01 08:38:12', 'TC12', 1),
(13, 'Uses the medium of instruction as required by the course.', '2024-11-01 08:38:12', 'TC13', 1),
(14, 'Practices Ss at all times. (Sort, Systematize, Sweep, Sanitize, Self Discipline)', '2024-11-01 08:38:12', 'CM1', 2),
(15, 'Informs students of their class performance.', '2024-11-01 08:38:12', 'CM2', 2),
(16, 'Attends class regularly and punctually.', '2024-11-01 08:38:12', 'CM3', 2),
(17, 'Uses time efficiently and effectively.', '2024-11-01 08:38:12', 'CM4', 2),
(18, 'Checks attendance regularly.', '2024-11-01 08:38:12', 'CM5', 2),
(19, 'Follows schedule for examination.', '2024-11-01 08:38:12', 'CM6', 2),
(20, 'Creates an environment conducive for learning.', '2024-11-01 08:38:12', 'CM7', 2),
(21, 'Follows a syllabus/course outline as a guide for the lessons.', '2024-11-01 08:44:31', 'KM1', 3),
(22, 'Delivers the lessons confidently and with mastery.', '2024-11-01 08:44:31', 'KM2', 3),
(23, 'Relates subject matter to life situations and the world of work.', '2024-11-01 08:44:31', 'KM3', 3),
(24, 'Integrates Lycean values in teaching whenever relevant.', '2024-11-01 08:44:31', 'TP1', 4),
(25, 'Communicates clearly and correctly.', '2024-11-01 08:44:31', 'TP2', 4),
(26, 'Presents lessons in a clear and well-organized manner.', '2024-11-01 08:44:31', 'TP3', 4),
(27, 'In laboratory or clinical classes/on-the-job training, provides clear and well-organized pre-lab/pre-conference and post-lab/post-conference discussions.', '2024-11-01 08:44:31', 'TP4', 4),
(28, 'Uses varied teaching methods/strategies to effect learning.', '2024-11-01 08:44:31', 'TP5', 4),
(29, 'Shows effectiveness in the use of teaching strategies.', '2024-11-01 08:44:31', 'TP6', 4),
(30, 'Provides appropriate learning activities/practical applications to suit individual/group interests and capabilities, enhancing their academic and personal development.', '2024-11-01 08:44:31', 'TP7', 4),
(31, 'In laboratory or clinical classes/on-the-job training, encourages leadership skills toward independent practice.', '2024-11-01 08:44:31', 'TP8', 4),
(32, 'Uses alternative teaching aids such as films, illustrations, modules, AIMS, and internet information, when applicable.', '2024-11-01 08:44:31', 'TP9', 4),
(33, 'Assigns research/library work whenever relevant.', '2024-11-01 08:44:31', 'TP10', 4),
(34, 'Uses classroom and instructional resources effectively.', '2024-11-01 08:44:31', 'TP11', 4),
(35, 'Asks questions that promote critical and creative thinking skills.', '2024-11-01 08:44:31', 'TP12', 4),
(36, 'Encourages maximum student participation in the learning activities.', '2024-11-01 08:44:31', 'TP13', 4),
(37, 'Maintains a receptive and disciplined classroom/laboratory atmosphere.', '2024-11-01 08:44:31', 'TP14', 4),
(38, 'Provides adequate feedback mechanisms and applications to enhance learning.', '2024-11-01 08:44:31', 'TP15', 4),
(39, 'Evaluates students\' progress regularly and fairly (using valid and reliable tests and grading system).', '2024-11-01 08:44:31', 'TP16', 4),
(40, 'Optimizes the use of classroom time.', '2024-11-01 08:44:31', 'TP17', 4),
(41, 'Shows genuine concern towards students.', '2024-11-01 08:44:31', 'PD1', 5),
(42, 'Manifests openness to suggestions and criticisms.', '2024-11-01 08:44:31', 'PD2', 5),
(43, 'Exhibits fairness and impartiality in dealing with students.', '2024-11-01 08:44:31', 'PD3', 5),
(44, 'Observes proper teaching attire and grooming.', '2024-11-01 08:44:31', 'PD4', 5),
(45, 'Cooperates with and supports the goals, objectives, policies, programs, and activities of the College/Faculty/Department.', '2024-11-01 08:44:31', 'PD5', 5),
(46, 'Cooperates with and supports the goals, objectives, policies, programs, and activities of the University.', '2024-11-01 08:44:31', 'PD6', 5),
(47, 'Is committed to academic advancement and scholarly (research/creative) pursuits.', '2024-11-01 08:44:31', 'PD7', 5),
(48, 'Shows behavior consistent with the Code of Ethics, the University\'s norms of discipline, and sound moral standards.', '2024-11-01 08:44:31', 'PD8', 5),
(49, 'Relates professionally and harmoniously with Administrators.', '2024-11-01 08:44:31', 'PD9', 5),
(50, 'Relates professionally and harmoniously with Colleagues.', '2024-11-01 08:44:31', 'PD10', 5),
(51, 'Relates professionally and harmoniously with Students.', '2024-11-01 08:44:31', 'PD11', 5),
(52, 'Relates professionally and harmoniously with Support Staff.', '2024-11-01 08:44:31', 'PD12', 5),
(53, 'How well did your peer demonstrate effective communication skills?', '2024-11-01 10:15:20', 'CT1', 6),
(54, 'Did your peer actively listen and respond to others during discussions?', '2024-11-01 10:15:20', 'CT2', 6),
(55, 'How effectively did your peer convey their ideas to the group?', '2024-11-01 10:15:20', 'CT3', 6),
(56, 'Did your peer use appropriate language and tone when communicating?', '2024-11-01 10:15:20', 'CT4', 6),
(57, 'How well did your peer facilitate discussions to ensure everyone participated?', '2024-11-01 10:15:20', 'CT5', 6),
(58, 'How well did your peer manage their time during group projects?', '2024-11-01 10:15:20', 'CS1', 7),
(59, 'Was your peer effective in prioritizing tasks within the group?', '2024-11-01 10:15:20', 'CS2', 7),
(60, 'How consistently did your peer meet deadlines for their contributions?', '2024-11-01 10:15:20', 'CS3', 7),
(61, 'Did your peer proactively identify and address potential obstacles?', '2024-11-01 10:15:20', 'CS4', 7),
(62, 'How effectively did your peer balance workload among group members?', '2024-11-01 10:15:20', 'CS5', 7),
(63, 'How well do you understand your personal strengths?', '2024-11-01 18:15:26', 'SA1', 8),
(64, 'How often do you reflect on your weaknesses?', '2024-11-01 18:15:26', 'SA2', 8),
(65, 'To what extent do you recognize your impact on team dynamics?', '2024-11-01 18:15:26', 'SA3', 8),
(66, 'How do you evaluate your values and how they align with your actions?', '2024-11-01 18:15:26', 'SA4', 8),
(67, 'How effectively do you set and pursue personal goals?', '2024-11-01 18:15:26', 'SA5', 8),
(68, 'How regularly do you assess your progress towards your professional goals?', '2024-11-01 18:15:26', 'GA1', 9),
(69, 'How well do you identify obstacles that hinder your goal achievement?', '2024-11-01 18:15:26', 'GA2', 9),
(70, 'To what degree do you celebrate your successes?', '2024-11-01 18:15:26', 'GA3', 9),
(71, 'How often do you seek feedback to improve your performance?', '2024-11-01 18:15:26', 'GA4', 9),
(72, 'How committed are you to lifelong learning and personal development?', '2024-11-01 18:15:26', 'GA5', 9);

-- --------------------------------------------------------

--
-- Table structure for table `questions_criteria`
--

CREATE TABLE `questions_criteria` (
  `criteria_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `survey_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions_criteria`
--

INSERT INTO `questions_criteria` (`criteria_id`, `description`, `survey_id`) VALUES
(1, 'Teaching Competence', 1),
(2, 'Classroom Management', 1),
(3, 'Knowledge on the Subject Matter', 3),
(4, 'Teaching Performance (Methods/Strategies, Classroom Management and Evaluation)', 3),
(5, 'Performance of Duties', 3),
(6, 'Collaboration and Teamwork', 2),
(7, 'Communication Skills', 2),
(8, 'Self-Awareness', 4),
(9, 'Goal Achievement', 4);

-- --------------------------------------------------------

--
-- Table structure for table `responses`
--

CREATE TABLE `responses` (
  `response_id` int(11) NOT NULL,
  `evaluation_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `responses`
--

INSERT INTO `responses` (`response_id`, `evaluation_id`, `question_id`, `rating`) VALUES
(1, 190, 1, 5),
(2, 190, 2, 5),
(3, 190, 3, 5),
(4, 190, 4, 5),
(5, 190, 5, 5),
(6, 190, 6, 5),
(7, 190, 7, 5),
(8, 190, 8, 5),
(9, 190, 9, 5),
(10, 190, 10, 5),
(11, 190, 11, 5),
(12, 190, 12, 5),
(13, 190, 13, 5),
(14, 190, 14, 5),
(15, 190, 15, 5),
(16, 190, 16, 5),
(17, 190, 17, 5),
(18, 190, 18, 5),
(19, 190, 19, 5),
(20, 190, 20, 5),
(21, 191, 1, 5),
(22, 191, 2, 5),
(23, 191, 3, 5),
(24, 191, 4, 5),
(25, 191, 5, 5),
(26, 191, 6, 5),
(27, 191, 7, 5),
(28, 191, 8, 5),
(29, 191, 9, 5),
(30, 191, 10, 5),
(31, 191, 11, 5),
(32, 191, 12, 5),
(33, 191, 13, 5),
(34, 191, 14, 5),
(35, 191, 15, 5),
(36, 191, 16, 5),
(37, 191, 17, 5),
(38, 191, 18, 5),
(39, 191, 19, 5),
(40, 191, 20, 5),
(41, 192, 21, 5),
(42, 192, 22, 5),
(43, 192, 23, 5),
(44, 192, 24, 5),
(45, 192, 25, 5),
(46, 192, 26, 5),
(47, 192, 27, 5),
(48, 192, 28, 5),
(49, 192, 29, 5),
(50, 192, 30, 5),
(51, 192, 31, 5),
(52, 192, 32, 5),
(53, 192, 33, 5),
(54, 192, 34, 5),
(55, 192, 35, 5),
(56, 192, 36, 5),
(57, 192, 37, 5),
(58, 192, 38, 5),
(59, 192, 39, 5),
(60, 192, 40, 5),
(61, 193, 21, 5),
(62, 193, 22, 5),
(63, 193, 23, 5),
(64, 193, 24, 5),
(65, 193, 25, 5),
(66, 193, 26, 5),
(67, 193, 27, 5),
(68, 193, 28, 5),
(69, 193, 29, 5),
(70, 193, 30, 5),
(71, 193, 31, 5),
(72, 193, 32, 5),
(73, 193, 33, 5),
(74, 193, 34, 5),
(75, 193, 35, 5),
(76, 193, 36, 5),
(77, 193, 37, 5),
(78, 193, 38, 5),
(79, 193, 39, 5),
(80, 193, 40, 5),
(81, 194, 41, 5),
(82, 194, 42, 5),
(83, 194, 43, 5),
(84, 194, 44, 5),
(85, 194, 45, 5),
(86, 194, 46, 5),
(87, 194, 47, 5),
(88, 194, 48, 5),
(89, 194, 49, 5),
(90, 194, 50, 5),
(91, 194, 51, 5),
(92, 194, 52, 5),
(93, 194, 53, 5),
(94, 194, 54, 5),
(95, 194, 55, 5),
(96, 194, 56, 5),
(97, 194, 57, 5),
(98, 195, 41, 5),
(99, 195, 42, 5),
(100, 195, 43, 5),
(101, 195, 44, 5),
(102, 195, 45, 5),
(103, 195, 46, 5),
(104, 195, 47, 5),
(105, 195, 48, 5),
(106, 195, 49, 5),
(107, 195, 50, 5),
(108, 195, 51, 5),
(109, 195, 52, 5),
(110, 195, 53, 5),
(111, 195, 54, 5),
(112, 195, 55, 5),
(113, 195, 56, 5),
(114, 195, 57, 5),
(115, 196, 58, 5),
(116, 196, 59, 5),
(117, 196, 60, 5),
(118, 196, 61, 5),
(119, 196, 62, 5),
(120, 196, 63, 5),
(121, 196, 64, 5),
(122, 196, 65, 5),
(123, 196, 66, 5),
(124, 196, 67, 5),
(125, 196, 68, 5),
(126, 196, 69, 5),
(127, 196, 70, 5),
(128, 196, 71, 5),
(129, 196, 72, 5),
(130, 197, 58, 5),
(131, 197, 59, 5),
(132, 197, 60, 5),
(133, 197, 61, 5),
(134, 197, 62, 5),
(135, 197, 63, 5),
(136, 197, 64, 5),
(137, 197, 65, 5),
(138, 197, 66, 5),
(139, 197, 67, 5),
(140, 197, 68, 5),
(141, 197, 69, 5),
(142, 197, 70, 5),
(143, 197, 71, 5),
(144, 197, 72, 5),
(145, 198, 58, 1),
(146, 198, 59, 2),
(147, 198, 60, 3),
(148, 198, 61, 2),
(149, 198, 62, 1),
(150, 198, 63, 2),
(151, 198, 64, 2),
(152, 198, 65, 1),
(153, 198, 66, 2),
(154, 198, 67, 2),
(155, 198, 68, 1),
(156, 198, 69, 1),
(157, 198, 70, 1),
(158, 198, 71, 1),
(159, 198, 72, 1);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `program_id` int(5) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `profile_image` varchar(220) DEFAULT 'uploads/default_image.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `username`, `password_hash`, `email`, `created_at`, `updated_at`, `first_name`, `last_name`, `program_id`, `phone_number`, `profile_image`) VALUES
(3, 'student1', '$2y$10$nvZhBk7tk4RoXHBuM0tqpO9b/5MHFnkUXP4BDUB.Nyq876qyDk3Uu', '2023-2-03361@lpunetwork.edu.ph', '2024-10-21 02:18:42', '2024-11-21 17:11:00', 'Cj', 'Rojo', 1, '09156578280', 'uploads/default_image.jpg'),
(5, 'student3', '12345', 'Akali@lpunetwork.edu.ph', '2024-10-21 02:18:42', '2024-11-19 19:25:58', 'Akali', 'Tethi', 40, '0917-123-4567', 'uploads/default_image.jpg'),
(6, 'student4', '12345', 'Fiora@lpunetwork.edu.ph', '2024-10-21 02:18:42', '2024-11-19 19:26:06', 'Fiora', 'Laurent', 1, '(02) 8412-5678', 'uploads/default_image.jpg'),
(7, 'student5', '12345', 'Lux@lpunetwork.edu.ph', '2024-10-21 02:18:42', '2024-11-19 19:26:21', 'Lux', 'Crownguard', 3, '0919 876 5432', 'uploads/default_image.jpg'),
(8, 'student6', '12345', 'Alisa@lpunetwork.edu.ph', '2024-10-21 02:18:42', '2024-11-19 19:26:26', 'Alisa', 'Bosconovitch', 3, '(02) 713 9876', 'uploads/default_image.jpg'),
(9, 'student7', '12345', 'Anna@lpunetwork.edu.ph', '2024-10-21 02:18:42', '2024-11-19 19:26:38', 'Anna', 'Williams', 5, '0945 876 1234', 'uploads/default_image.jpg'),
(10, 'student8', '12345', 'Ling@lpunetwork.edu.ph', '2024-10-21 02:18:42', '2024-11-19 19:26:44', 'Ling', 'Xiaoyu', 3, '02 321 4567', 'uploads/default_image.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `students_evaluations`
--

CREATE TABLE `students_evaluations` (
  `evaluation_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `comments` text DEFAULT NULL,
  `date_evaluated` date DEFAULT NULL,
  `time_evaluated` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students_evaluations`
--

INSERT INTO `students_evaluations` (`evaluation_id`, `student_id`, `comments`, `date_evaluated`, `time_evaluated`) VALUES
(190, 3, NULL, '2024-11-24', '23:45:00'),
(191, 3, NULL, '2024-11-24', '23:45:00'),
(192, 3, NULL, '2024-11-24', '23:45:00'),
(193, 3, NULL, '2024-11-24', '23:45:00'),
(194, 3, NULL, '2024-11-24', '23:45:00'),
(195, 3, NULL, '2024-11-24', '23:45:00'),
(196, 3, NULL, '2024-11-24', '23:45:00'),
(197, 3, NULL, '2024-11-24', '23:45:00'),
(198, 3, NULL, '2024-11-24', '23:45:00');

-- --------------------------------------------------------

--
-- Table structure for table `student_courses`
--

CREATE TABLE `student_courses` (
  `student_id` int(11) NOT NULL,
  `course_section_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_courses`
--

INSERT INTO `student_courses` (`student_id`, `course_section_id`) VALUES
(3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `surveys`
--

CREATE TABLE `surveys` (
  `survey_id` int(11) NOT NULL,
  `survey_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `target_role` enum('Student','Faculty','Program_chair','Self') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `surveys`
--

INSERT INTO `surveys` (`survey_id`, `survey_name`, `created_at`, `target_role`) VALUES
(1, 'Student Evaluation', '2024-10-13 06:56:45', 'Student'),
(2, 'Faculty Evaluation', '2024-10-13 06:56:45', 'Faculty'),
(3, 'Chair Feedback', '2024-10-27 23:56:38', 'Program_chair'),
(4, 'Self Evaluation', '2024-11-01 18:10:16', 'Self');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `course_sections`
--
ALTER TABLE `course_sections`
  ADD PRIMARY KEY (`course_section_id`),
  ADD KEY `course_sections_ibfk_1` (`course_id`),
  ADD KEY `fk_period` (`period_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`evaluation_id`),
  ADD KEY `evaluations_ibfk_2` (`survey_id`),
  ADD KEY `evaluations_ibfk_1` (`course_section_id`),
  ADD KEY `period_id` (`period_id`);

--
-- Indexes for table `evaluation_periods`
--
ALTER TABLE `evaluation_periods`
  ADD PRIMARY KEY (`period_id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`faculty_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_department` (`department_id`);

--
-- Indexes for table `faculty_courses`
--
ALTER TABLE `faculty_courses`
  ADD PRIMARY KEY (`faculty_id`,`course_section_id`),
  ADD UNIQUE KEY `course_section_id` (`course_section_id`);

--
-- Indexes for table `faculty_evaluations`
--
ALTER TABLE `faculty_evaluations`
  ADD PRIMARY KEY (`evaluation_id`,`faculty_id`),
  ADD KEY `evaluation_faculty_ibfk_2` (`faculty_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`program_id`),
  ADD UNIQUE KEY `program_code` (`program_code`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `program_chairs`
--
ALTER TABLE `program_chairs`
  ADD PRIMARY KEY (`chair_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `program_chair_evaluations`
--
ALTER TABLE `program_chair_evaluations`
  ADD PRIMARY KEY (`chair_id`,`evaluation_id`),
  ADD KEY `evaluation_id` (`evaluation_id`);

--
-- Indexes for table `program_courses`
--
ALTER TABLE `program_courses`
  ADD PRIMARY KEY (`program_id`,`course_id`),
  ADD KEY `fk_course` (`course_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `fk_criteria` (`criteria_id`);

--
-- Indexes for table `questions_criteria`
--
ALTER TABLE `questions_criteria`
  ADD PRIMARY KEY (`criteria_id`),
  ADD KEY `fk_survey_id` (`survey_id`);

--
-- Indexes for table `responses`
--
ALTER TABLE `responses`
  ADD PRIMARY KEY (`response_id`),
  ADD KEY `responses_ibfk_1` (`evaluation_id`),
  ADD KEY `responses_ibfk_2` (`question_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `students_evaluations`
--
ALTER TABLE `students_evaluations`
  ADD PRIMARY KEY (`evaluation_id`,`student_id`),
  ADD KEY `evaluation_students_ibfk_2` (`student_id`);

--
-- Indexes for table `student_courses`
--
ALTER TABLE `student_courses`
  ADD PRIMARY KEY (`student_id`,`course_section_id`),
  ADD KEY `student_courses_ibfk_2` (`course_section_id`);

--
-- Indexes for table `surveys`
--
ALTER TABLE `surveys`
  ADD PRIMARY KEY (`survey_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `course_sections`
--
ALTER TABLE `course_sections`
  MODIFY `course_section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `evaluation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=199;

--
-- AUTO_INCREMENT for table `evaluation_periods`
--
ALTER TABLE `evaluation_periods`
  MODIFY `period_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `faculty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `program_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT for table `questions_criteria`
--
ALTER TABLE `questions_criteria`
  MODIFY `criteria_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `responses`
--
ALTER TABLE `responses`
  MODIFY `response_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `surveys`
--
ALTER TABLE `surveys`
  MODIFY `survey_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`);

--
-- Constraints for table `course_sections`
--
ALTER TABLE `course_sections`
  ADD CONSTRAINT `course_sections_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`),
  ADD CONSTRAINT `fk_period` FOREIGN KEY (`period_id`) REFERENCES `evaluation_periods` (`period_id`);

--
-- Constraints for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_ibfk_1` FOREIGN KEY (`course_section_id`) REFERENCES `course_sections` (`course_section_id`),
  ADD CONSTRAINT `evaluations_ibfk_2` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`survey_id`),
  ADD CONSTRAINT `evaluations_ibfk_3` FOREIGN KEY (`period_id`) REFERENCES `evaluation_periods` (`period_id`);

--
-- Constraints for table `faculty`
--
ALTER TABLE `faculty`
  ADD CONSTRAINT `fk_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`);

--
-- Constraints for table `faculty_courses`
--
ALTER TABLE `faculty_courses`
  ADD CONSTRAINT `faculty_courses_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`),
  ADD CONSTRAINT `faculty_courses_ibfk_2` FOREIGN KEY (`course_section_id`) REFERENCES `course_sections` (`course_section_id`);

--
-- Constraints for table `faculty_evaluations`
--
ALTER TABLE `faculty_evaluations`
  ADD CONSTRAINT `faculty_evaluations_ibfk_1` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluations` (`evaluation_id`),
  ADD CONSTRAINT `faculty_evaluations_ibfk_2` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`);

--
-- Constraints for table `programs`
--
ALTER TABLE `programs`
  ADD CONSTRAINT `programs_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`);

--
-- Constraints for table `program_chairs`
--
ALTER TABLE `program_chairs`
  ADD CONSTRAINT `program_chairs_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`);

--
-- Constraints for table `program_chair_evaluations`
--
ALTER TABLE `program_chair_evaluations`
  ADD CONSTRAINT `program_chair_evaluations_ibfk_1` FOREIGN KEY (`chair_id`) REFERENCES `program_chairs` (`chair_id`),
  ADD CONSTRAINT `program_chair_evaluations_ibfk_2` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluations` (`evaluation_id`);

--
-- Constraints for table `program_courses`
--
ALTER TABLE `program_courses`
  ADD CONSTRAINT `fk_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`),
  ADD CONSTRAINT `fk_program_course` FOREIGN KEY (`program_id`) REFERENCES `programs` (`program_id`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `fk_criteria` FOREIGN KEY (`criteria_id`) REFERENCES `questions_criteria` (`criteria_id`);

--
-- Constraints for table `questions_criteria`
--
ALTER TABLE `questions_criteria`
  ADD CONSTRAINT `fk_survey_id` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`survey_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `responses`
--
ALTER TABLE `responses`
  ADD CONSTRAINT `responses_ibfk_1` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluations` (`evaluation_id`),
  ADD CONSTRAINT `responses_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programs` (`program_id`);

--
-- Constraints for table `students_evaluations`
--
ALTER TABLE `students_evaluations`
  ADD CONSTRAINT `students_evaluations_ibfk_1` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluations` (`evaluation_id`),
  ADD CONSTRAINT `students_evaluations_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `student_courses`
--
ALTER TABLE `student_courses`
  ADD CONSTRAINT `student_courses_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `student_courses_ibfk_2` FOREIGN KEY (`course_section_id`) REFERENCES `course_sections` (`course_section_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
