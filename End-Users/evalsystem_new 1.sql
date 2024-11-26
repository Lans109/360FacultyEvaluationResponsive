-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2024 at 11:28 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `evalsystem_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `password`, `email`, `created_at`) VALUES
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
(1, 'Software Engineering', '2024-10-13 06:55:01', 'software Making', 'CSCN10C', 1),
(2, 'Information Assurance and Security', '2024-10-13 06:55:01', 'about cybersecurity.', 'ITEN07C', 1),
(3, 'Automata and Language', '2024-10-20 14:09:50', 'Automata Logics.', 'CSCN05C', 1),
(4, 'Quantitative Methods', '2024-10-20 14:09:50', 'Linear Programming.', 'ITEN04C', 1),
(5, 'Architechture and Organization', '2024-10-20 14:09:50', 'Bread Board.', 'CSCN07C', 1);

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
(20, 4, 'CS302', 1);

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
(61, 1, 1, '2024-10-28 00:11:56', 1),
(62, 2, 1, '2024-10-28 00:12:15', 1),
(63, 2, 1, '2024-10-28 00:12:27', 1),
(64, 19, 1, '2024-11-20 01:54:08', 1);

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
(2, '2nd', '2024-2025', '2025-01-01', '2025-05-31', 'upcoming');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `faculty_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `profile_image` varchar(220) DEFAULT 'uploads/default_profile.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`faculty_id`, `username`, `password`, `email`, `created_at`, `updated_at`, `first_name`, `last_name`, `profile_image`) VALUES
(1, 'professor1', 'abcde', 'Testing@gmail.com', '2024-10-13 06:56:21', '2024-11-20 12:24:39', 'Testing', 'Testing', 'uploads/673dd5078e0ef1.04892625.jpg'),
(2, 'professor2', 'fghij', 'Vincent@lpunetwork.edu.ph', '2024-10-13 06:56:21', '2024-10-13 06:56:21', 'John', 'Vincent', 'uploads/default_profile.jpg'),
(3, 'professor3', '12345', 'Sylas@lpunetwork.edu.ph', '2024-10-20 14:49:59', '2024-11-18 11:50:01', 'Sylas', 'Mage', 'uploads/673b29e9418ab0.25225770.jpg'),
(4, 'professor4', '12345', 'Talo@lpunetwork.edu.ph', '2024-10-20 14:49:59', '2024-10-20 14:49:59', 'Talon', 'Blade', 'uploads/default_profile.jpg'),
(5, 'professor5', '12345', 'Katarina@lpunetwork.edu.ph', '2024-10-20 14:49:59', '2024-10-20 14:49:59', 'Katarina', 'Blade', 'uploads/default_profile.jpg'),
(16, 'professor6', '12345', 'Samsung@lpunetwork.edu.ph', '2024-10-21 02:15:29', '2024-10-21 02:15:29', 'Sam', 'Sung', 'uploads/default_profile.jpg'),
(17, 'professor7', '12345', 'Myth@lpunetwork.edu.ph', '2024-10-21 02:15:29', '2024-10-21 02:15:29', 'Wukong', 'Myth', 'uploads/default_profile.jpg'),
(18, 'professor8', '12345', 'Albert@lpunetwork.edu.ph', '2024-10-21 02:15:29', '2024-10-21 02:15:29', 'Albert', 'Einstein', 'uploads/default_profile.jpg'),
(19, 'professor9', '12345', 'Jin@lpunetwork.edu.ph', '2024-10-21 02:15:29', '2024-10-21 02:15:29', 'Jin', 'Kazama', 'uploads/default_profile.jpg'),
(20, 'professor10', '12345', 'Emilie@lpunetwork.edu.ph', '2024-10-21 02:15:29', '2024-10-21 02:15:29', 'Emilie', 'Rochefort', 'uploads/default_profile.jpg'),
(21, '', '$2y$10$uAcSX922nekspp31At/bz.nQYlvKniKroLWU2ME0hVyiTiZVs7lV6', 'sadsa@gmail.com', '2024-10-27 00:08:12', '2024-10-27 00:08:12', 'Testsetsetsetsetsest', 'dasd', 'uploads/default_profile.jpg');

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
(2, 1),
(2, 2),
(3, 19),
(3, 20);

-- --------------------------------------------------------

--
-- Table structure for table `faculty_departments`
--

CREATE TABLE `faculty_departments` (
  `faculty_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_departments`
--

INSERT INTO `faculty_departments` (`faculty_id`, `department_id`) VALUES
(1, 18),
(2, 1),
(3, 1),
(4, 1),
(5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `faculty_evaluations`
--

CREATE TABLE `faculty_evaluations` (
  `evaluation_id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `is_completed` tinyint(1) DEFAULT 0,
  `comments` varchar(220) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_evaluations`
--

INSERT INTO `faculty_evaluations` (`evaluation_id`, `faculty_id`, `is_completed`, `comments`) VALUES
(61, 1, 1, 'a'),
(62, 1, 1, 'is it working now?'),
(63, 1, 0, 'E'),
(64, 1, 0, 'pleaseeeee');

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
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `department_id` int(10) DEFAULT NULL,
  `profile_image` varchar(220) DEFAULT 'uploads/default_profile.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program_chairs`
--

INSERT INTO `program_chairs` (`chair_id`, `username`, `password`, `email`, `created_at`, `first_name`, `last_name`, `department_id`, `profile_image`) VALUES
(1, 'chair1', '$2y$10$JrkueOfL0UA65qJ9OHtnMuUlEnqOmuPWtWbeJ6RpYxfRhzdKS3DZi', 'chair1dsadsdd2@example.com', '2024-10-12 22:52:28', 'Cj', 'Moya', 14, 'uploads/default_profile.jpg'),
(23, 'asmith', '$2y$10$E7qZ6L7..0m4RlnD5QH1QO53zHcG/dA9kvlqIHXlW8AF0t3F5mU6e', 'asmith@example.com', '2024-10-27 04:00:00', 'Alice', 'Smith', 18, 'uploads/default_profile.jpg'),
(31, 'mjones', '$2y$10$E7qZ6L7..0m4RlnD5QH1QO53zHcG/dA9kvlqIHXlW8AF0t3F5mU6e', 'mjones@example.com', '2024-10-27 04:00:00', 'Michael', 'Jones', 6, 'uploads/default_profile.jpg'),
(65, 'jdoe', '$2y$10$E7qZ6L7..0m4RlnD5QH1QO53zHcG/dA9kvlqIHXlW8AF0t3F5mU6e', 'jdoe@example.com', '2024-10-27 04:00:00', 'John', 'Doe', 1, 'uploads/673b066912b5b5.19847356.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `program_chair_evaluations`
--

CREATE TABLE `program_chair_evaluations` (
  `chair_id` int(11) NOT NULL,
  `evaluation_id` int(11) NOT NULL,
  `is_completed` tinyint(1) DEFAULT 0,
  `comments` varchar(220) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program_chair_evaluations`
--

INSERT INTO `program_chair_evaluations` (`chair_id`, `evaluation_id`, `is_completed`, `comments`) VALUES
(1, 61, 1, 'apple'),
(1, 62, 0, 'IDK what to say'),
(1, 63, 0, NULL);

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
(1, 3),
(1, 4),
(2, 2),
(2, 4),
(2, 5),
(3, 1),
(3, 2),
(3, 5),
(4, 3),
(4, 5),
(5, 2),
(5, 3),
(6, 1),
(6, 2),
(7, 4),
(7, 5),
(8, 1),
(8, 5),
(9, 1),
(9, 2),
(40, 1);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `question_code` varchar(50) DEFAULT NULL,
  `criteria_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `survey_id`, `question_text`, `created_at`, `question_code`, `criteria_id`) VALUES
(1, 1, 'States the objectives of the lesson/activities before the start of the class.', '2024-10-13 06:56:46', 'TC1', 1),
(2, 1, 'Orients the student on the planned activities for the day.', '2024-10-13 06:56:46', 'TC2', 1),
(3, 1, 'Practices 5s at all times. (Sort, Systematize, Sweep, Sanitize, Self Descipline)', '2024-10-13 06:56:46', 'CM1', 2),
(4, 1, 'Informs students of their class performance.', '2024-10-12 22:56:46', 'CM2', 2),
(5, 1, 'Attends class regularly and punctually.', '2024-10-12 22:56:46', 'CM3', 2),
(6, 2, 'Follows a syllabus/course outline as guide for the lessons.', '2024-10-21 10:22:14', 'KS1', 3),
(7, 2, 'Delivers the lesson confidently and with mastery.', '2024-10-21 10:22:14', 'KS2', 3),
(8, 2, 'Integrates Lycean values in teaching whenever relevant.', '2024-10-21 10:22:14', 'TP1', 4),
(9, 2, 'Communicates clearly and correctly.', '2024-10-21 10:22:14', 'TP2', 4),
(10, 2, 'Shows genuine concern towards students.', '2024-10-21 10:22:14', 'PD1', 5),
(11, 2, 'Manifests openness to suggestions and criticisms.', '2024-10-27 23:58:29', 'PD2', 5),
(12, 3, 'How effectively does the peer contribute to team discussions and activities?', '2024-10-27 23:58:29', 'CT1', 6),
(13, 3, 'How would you rate the instructor’s teaching methods?How willing is the peer to assist team members when needed?', '2024-10-27 23:58:29', 'CT2', 6),
(14, 3, 'How clear and concise is the peers communication within the team?', '2024-10-27 23:58:29', 'CS1', 7),
(15, 3, 'Do you feel the program has prepared you for your career?Does the peer provide constructive feedback to help the team improve?', '2024-10-27 23:58:29', 'CS2', 7);

-- --------------------------------------------------------

--
-- Table structure for table `questions_criteria`
--

CREATE TABLE `questions_criteria` (
  `criteria_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions_criteria`
--

INSERT INTO `questions_criteria` (`criteria_id`, `description`) VALUES
(1, 'Teaching Competence'),
(2, 'Classroom Management'),
(3, 'Knowledge on the Subject Matter'),
(4, 'Teaching Performance (Methods/Strategies, Classroom Management and Evaluation)'),
(5, 'Performance of Duties'),
(6, 'Collaboration and Teamwork'),
(7, 'Communication Skills');

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
(166, 61, 5, 4),
(167, 61, 1, 4),
(168, 61, 2, 4),
(169, 61, 3, 4),
(170, 61, 4, 4),
(171, 62, 5, 5),
(172, 62, 1, 5),
(173, 62, 2, 5),
(174, 62, 3, 5),
(175, 62, 4, 5),
(176, 61, 5, 5),
(177, 61, 1, 1),
(178, 61, 2, 2),
(179, 61, 3, 3),
(180, 61, 4, 4),
(181, 61, 5, 2),
(182, 61, 1, 2),
(183, 61, 2, 2),
(184, 61, 3, 2),
(185, 61, 4, 2),
(186, 61, 5, 4),
(187, 61, 1, 4),
(188, 61, 2, 4),
(189, 61, 3, 4),
(190, 61, 4, 4);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `program_id` int(5) DEFAULT NULL,
  `profile_image` varchar(220) DEFAULT 'uploads/default_profile.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `username`, `password`, `email`, `created_at`, `updated_at`, `first_name`, `last_name`, `program_id`, `profile_image`) VALUES
(3, 'student1', '$2y$10$nvZhBk7tk4RoXHBuM0tqpO9b/5MHFnkUXP4BDUB.Nyq876qyDk3Uu', '2023-2-03361@lpunetwork.edu.ph', '2024-10-21 02:18:42', '2024-10-27 02:06:02', 'Cj', 'Rojo', 40, 'uploads/default_profile.jpg'),
(5, 'student3', '12345', 'Akali@lpunetwork.edu.ph', '2024-10-21 02:18:42', '2024-11-20 09:24:36', 'Akali', 'Tethi', 2, 'uploads/673daad4cab244.75486649.jpg'),
(6, 'student4', '12345', 'Fiora@lpunetwork.edu.ph', '2024-10-21 02:18:42', '2024-10-26 22:43:40', 'Fiora', 'Laurent', 1, 'uploads/default_profile.jpg'),
(7, 'student5', '12345', 'Lux@lpunetwork.edu.ph', '2024-10-21 02:18:42', '2024-10-26 22:43:43', 'Lux', 'Crownguard', 3, 'uploads/default_profile.jpg'),
(8, 'student6', '12345', 'Alisa@lpunetwork.edu.ph', '2024-10-21 02:18:42', '2024-10-26 22:43:46', 'Alisa', 'Bosconovitch', 3, 'uploads/default_profile.jpg'),
(9, 'student7', '12345', 'Anna@lpunetwork.edu.ph', '2024-10-21 02:18:42', '2024-10-26 22:43:48', 'Anna', 'Williams', 5, 'uploads/default_profile.jpg'),
(10, 'student8', '12345', 'Ling@lpunetwork.edu.ph', '2024-10-21 02:18:42', '2024-10-26 22:43:51', 'Ling', 'Xiaoyu', 3, 'uploads/default_profile.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `students_evaluations`
--

CREATE TABLE `students_evaluations` (
  `evaluation_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `is_completed` tinyint(1) DEFAULT 0,
  `comments` varchar(220) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students_evaluations`
--

INSERT INTO `students_evaluations` (`evaluation_id`, `student_id`, `is_completed`, `comments`) VALUES
(61, 3, 0, NULL),
(61, 5, 1, 'IDK'),
(61, 6, 0, 'NA'),
(62, 3, 0, NULL),
(62, 5, 1, 'idk'),
(62, 6, 0, 'NA'),
(63, 3, 0, NULL),
(63, 5, 0, 'hatdog'),
(63, 6, 0, 'speed'),
(64, 3, 0, ''),
(64, 6, 0, NULL);

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
(3, 1),
(5, 1),
(5, 2),
(5, 19),
(5, 20);

-- --------------------------------------------------------

--
-- Table structure for table `surveys`
--

CREATE TABLE `surveys` (
  `survey_id` int(11) NOT NULL,
  `survey_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `target_role` enum('Student','Faculty','Program_chair') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `surveys`
--

INSERT INTO `surveys` (`survey_id`, `survey_name`, `created_at`, `target_role`) VALUES
(1, 'Student Evaluation', '2024-10-13 06:56:45', 'Student'),
(2, 'Faculty Evaluation', '2024-10-13 06:56:45', 'Faculty'),
(3, 'Chair Feedback', '2024-10-27 23:56:38', 'Program_chair');

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
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `faculty_courses`
--
ALTER TABLE `faculty_courses`
  ADD PRIMARY KEY (`faculty_id`,`course_section_id`),
  ADD UNIQUE KEY `course_section_id` (`course_section_id`);

--
-- Indexes for table `faculty_departments`
--
ALTER TABLE `faculty_departments`
  ADD PRIMARY KEY (`faculty_id`,`department_id`),
  ADD KEY `faculty_departments_ibfk_2` (`department_id`);

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
  ADD KEY `questions_ibfk_1` (`survey_id`),
  ADD KEY `fk_criteria` (`criteria_id`);

--
-- Indexes for table `questions_criteria`
--
ALTER TABLE `questions_criteria`
  ADD PRIMARY KEY (`criteria_id`);

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
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `course_sections`
--
ALTER TABLE `course_sections`
  MODIFY `course_section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `evaluation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `evaluation_periods`
--
ALTER TABLE `evaluation_periods`
  MODIFY `period_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `faculty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `program_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `questions_criteria`
--
ALTER TABLE `questions_criteria`
  MODIFY `criteria_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `responses`
--
ALTER TABLE `responses`
  MODIFY `response_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=191;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `surveys`
--
ALTER TABLE `surveys`
  MODIFY `survey_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

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
-- Constraints for table `faculty_courses`
--
ALTER TABLE `faculty_courses`
  ADD CONSTRAINT `faculty_courses_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`),
  ADD CONSTRAINT `faculty_courses_ibfk_2` FOREIGN KEY (`course_section_id`) REFERENCES `course_sections` (`course_section_id`);

--
-- Constraints for table `faculty_departments`
--
ALTER TABLE `faculty_departments`
  ADD CONSTRAINT `faculty_departments_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`),
  ADD CONSTRAINT `faculty_departments_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`);

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
  ADD CONSTRAINT `fk_criteria` FOREIGN KEY (`criteria_id`) REFERENCES `questions_criteria` (`criteria_id`),
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`survey_id`);

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
