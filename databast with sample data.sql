-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2024 at 03:38 PM
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
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `password`, `email`, `created_at`, `first_name`, `last_name`, `updated_at`) VALUES
(1, 'appleAdmin', '$2y$10$0/5rDDgPGnDL0dinuyuIBeR6D2rN0vYhisR/IE9NMkGfkv1e43Oye', 'admin1@example.com', '2024-10-13 06:52:28', 'Apple', 'Tree', '2024-12-02 11:21:09'),
(2, 'admin2', '$2y$10$1ZzjDCd5w5Caa1o1Mcz0J.S7Uq/fv./mrIJaMJvrX6kBz1SO9/2um', 'admin2@example.com', '2024-10-13 06:52:28', 'Jesus', 'Christ', '2024-12-02 11:21:09'),
(3, 'admin3', '$2y$10$wcP5Xyq/DlUaHLfYbvFuaOjMrU2IaC9zRfLMEz7tDfG23SeOB1UNi', 'admmin@gmail', '2024-10-20 08:18:25', 'Banana', 'Monke', '2024-12-02 11:21:09'),
(4, 'admin4', '12345', 'admin4@example.com', '2024-10-20 14:06:35', 'Glee', 'Moya', '2024-12-02 11:21:09'),
(5, 'admin5', '12345', 'admin5@example.com', '2024-10-20 14:06:35', 'Sisha', 'Rojo', '2024-12-02 11:21:09');

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
  `department_id` int(10) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_name`, `created_at`, `course_description`, `course_code`, `department_id`, `updated_at`) VALUES
(1, 'Introduction to Computing', '2024-12-02 12:22:29', 'An introductory course to computing, covering basic computer systems and applications.', 'DCSN01C', 1, '2024-12-02 12:22:29'),
(2, 'Computer Programming 1', '2024-12-02 12:22:29', 'This course introduces programming concepts and techniques in a structured programming environment.', 'DCSN02C', 1, '2024-12-02 12:22:29'),
(3, 'Mathematics In the Modern World', '2024-12-02 12:22:29', 'Focus on mathematical principles that are used in everyday life and in various modern fields.', 'MATH01G', 5, '2024-12-02 12:24:18'),
(4, 'Understanding the Self', '2024-12-02 12:22:29', 'A course designed to explore personal identity, self-awareness, and the human experience.', 'UTSN0IG', 3, '2024-12-02 12:24:24'),
(5, 'Ethics', '2024-12-02 12:22:29', 'Introduction to the principles of ethics, moral philosophy, and contemporary ethical issues.', 'ESTN01G', 6, '2024-12-02 12:24:31'),
(6, 'JPL Life and His Works', '2024-12-02 12:22:29', 'Study of the life and works of J.P. Lang, focusing on his contributions to science and literature.', 'JPLN01G', 5, '2024-12-02 12:24:37'),
(7, 'Physical Activities Toward Health and Fitness 1', '2024-12-02 12:22:29', 'A physical education course designed to promote health and fitness through physical activities.', 'PATHFit1', 4, '2024-12-02 12:55:55'),
(8, 'National Service Training Program 1', '2024-12-02 12:22:29', 'A course aimed at fostering civic consciousness and defense preparedness through community service and military training.', 'NSTPN01G', 7, '2024-12-02 12:50:40'),
(9, 'Quality Consciousness, Processes and Habits', '2024-12-02 12:22:29', 'A course that explores quality assurance, process improvement, and establishing good work habits.', 'CPHN0IC', 7, '2024-12-02 12:55:47'),
(10, 'Computer Programming 2', '2024-12-02 12:23:36', 'This course continues the study of programming with an emphasis on algorithms, data structures, and advanced programming concepts.', 'DCSN03C', 1, '2024-12-02 12:23:36'),
(11, 'Discrete Structures 1', '2024-12-02 12:23:36', 'Introduction to discrete mathematical structures used in computer science, such as sets, graphs, and combinatorics.', 'CSCN01C', 1, '2024-12-02 12:23:36'),
(12, 'Social Issues and Professional Practice', '2024-12-02 12:23:36', 'A course that explores the social, ethical, and legal issues related to the practice of computing and technology professions.', 'DCSN07C', 1, '2024-12-02 12:23:36'),
(13, 'The Contemporary World', '2024-12-02 12:23:36', 'Study of global issues and how they affect modern society, politics, and economics.', 'TCWN01G', 5, '2024-12-02 12:25:41'),
(14, 'Purposive Communication', '2024-12-02 12:23:36', 'Course focused on effective communication skills across various media, emphasizing purpose-driven communication in professional and personal contexts.', 'ENGL0IG', 3, '2024-12-02 12:25:34'),
(15, 'Pre-Calculus and Functional Mathematics', '2024-12-02 12:23:36', 'A preparatory mathematics course that covers functions, algebra, and the basic concepts necessary for calculus.', 'MATN07G', 5, '2024-12-02 12:23:36'),
(16, 'Living in the IT Era', '2024-12-02 12:23:36', 'Course that focuses on the role of information technology in everyday life and the workplace, including its impact on society and individuals.', 'LVTN01C', 1, '2024-12-02 12:23:36'),
(17, 'Physical Activities Toward Health and Fitness 2', '2024-12-02 12:23:36', 'Continued physical education focusing on advanced activities aimed at improving fitness and health.', 'PATHFit2', 4, '2024-12-02 12:26:21'),
(18, 'National Service Training Program 2', '2024-12-02 12:23:36', 'Continuation of the National Service Training Program (NSTP) that emphasizes civic duty and community involvement.', 'NSTPN02G', 7, '2024-12-02 12:26:08'),
(19, 'Drawing 1: Basic Form and Shape', '2024-12-02 12:53:07', 'An introductory course focusing on the basic principles of drawing, including form, shape, and perspective.', 'DRWM01F', 2, '2024-12-02 12:53:07'),
(20, 'Introduction to Multimedia Arts', '2024-12-02 12:53:07', 'An introductory course on multimedia arts, exploring graphic design, animation, and video production techniques.', 'BMMA01F', 2, '2024-12-02 12:53:07'),
(21, 'History of Graphic Design', '2024-12-02 12:53:07', 'A course covering the history and evolution of graphic design, from its origins to modern developments.', 'BMMA02F', 2, '2024-12-02 12:53:07'),
(22, 'Science, Technology and Society', '2024-12-02 12:53:07', 'A course exploring the interaction between science, technology, and society, with a focus on their impact on modern life.', 'STSN11G', 5, '2024-12-02 12:53:07'),
(23, 'Kontekstwalisadong Komunikasyon Sa Filipino', '2024-12-02 12:53:07', 'A Filipino course focusing on contextualized communication and the use of Filipino in various real-world settings.', 'FLIN01G', 2, '2024-12-02 12:57:46'),
(24, 'Data Structures and Algorithms', '2024-12-02 13:20:05', 'A course that covers the essential concepts of data structures and algorithms, essential for efficient programming.', 'DCSN04C', 1, '2024-12-02 13:20:05'),
(25, 'Object Oriented Programming', '2024-12-02 13:20:05', 'An introductory course to Object Oriented Programming (OOP), including classes, objects, inheritance, and polymorphism.', 'CSCN02C', 1, '2024-12-02 13:20:05'),
(26, 'Discrete Structures 2', '2024-12-02 13:20:05', 'A continuation of Discrete Structures 1, focusing on more advanced topics in mathematics and logic for computer science.', 'CSCN03C', 1, '2024-12-02 13:20:05'),
(27, 'CS Elective 1', '2024-12-02 13:20:05', 'A Computer Science elective course offering students an opportunity to dive into specialized topics of their interest in CS.', 'CSELCO1C', 1, '2024-12-02 13:20:05'),
(28, 'Differential and Integral Calculus', '2024-12-02 13:20:05', 'A math course covering the fundamentals of differential and integral calculus, essential for many fields of engineering and computer science.', 'MATH23E', 5, '2024-12-02 13:20:05'),
(29, 'Art Appreciation', '2024-12-02 13:20:05', 'A course that introduces students to the appreciation of visual arts and their cultural significance.', 'HUMNO2G', 5, '2024-12-02 13:20:05'),
(30, 'Life and Works of Rizal', '2024-12-02 13:20:05', 'A course examining the life and works of Dr. Jose Rizal, the national hero of the Philippines.', 'LWRNDIG', 4, '2024-12-02 13:20:05'),
(31, 'Physical Activities Toward Health and Fitness 3', '2024-12-02 13:20:05', 'A physical education course focused on advanced fitness activities to promote health and well-being.', 'PATHFit3', 4, '2024-12-02 13:23:33');

-- --------------------------------------------------------

--
-- Table structure for table `course_sections`
--

CREATE TABLE `course_sections` (
  `course_section_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `section` varchar(220) NOT NULL,
  `period_id` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_sections`
--

INSERT INTO `course_sections` (`course_section_id`, `course_id`, `section`, `period_id`, `updated_at`) VALUES
(46, 18, 'CS101', 1, '2024-12-02 12:30:36'),
(47, 17, 'CS101', 1, '2024-12-02 12:30:40'),
(48, 16, 'CS101', 1, '2024-12-02 12:30:46'),
(50, 15, 'CS101', 1, '2024-12-02 12:31:07'),
(51, 14, 'CS101', 1, '2024-12-02 12:31:12'),
(52, 13, 'CS101', 1, '2024-12-02 12:31:17'),
(53, 12, 'CS101', 1, '2024-12-02 12:31:41'),
(54, 11, 'CS101', 1, '2024-12-02 12:32:11'),
(55, 10, 'CS101', 1, '2024-12-02 12:32:19'),
(56, 18, 'CS102', 1, '2024-12-02 12:34:45'),
(57, 17, 'CS102', 1, '2024-12-02 12:34:53'),
(58, 16, 'CS102', 1, '2024-12-02 12:34:57'),
(59, 15, 'CS102', 1, '2024-12-02 12:35:02'),
(60, 14, 'CS102', 1, '2024-12-02 12:35:07'),
(61, 13, 'CS102', 1, '2024-12-02 12:35:14'),
(62, 12, 'CS102', 1, '2024-12-02 12:35:20'),
(63, 11, 'CS102', 1, '2024-12-02 12:35:25'),
(64, 10, 'CS102', 1, '2024-12-02 12:35:30'),
(68, 19, 'MMA101', 1, '2024-12-02 13:02:58'),
(69, 23, 'MMA101', 1, '2024-12-02 13:03:36'),
(70, 22, 'MMA101', 1, '2024-12-02 13:03:43'),
(71, 20, 'MMA101', 1, '2024-12-02 13:04:05'),
(72, 21, 'MMA101', 1, '2024-12-02 13:04:15'),
(73, 3, 'MMA101', 1, '2024-12-02 13:04:43'),
(74, 8, 'MMA101', 1, '2024-12-02 13:04:59'),
(75, 7, 'MMA101', 1, '2024-12-02 13:05:11'),
(76, 4, 'MMA101', 1, '2024-12-02 13:05:26'),
(77, 23, 'MMA102', 1, '2024-12-02 13:06:08'),
(78, 22, 'MMA102', 1, '2024-12-02 13:06:15'),
(79, 21, 'MMA102', 1, '2024-12-02 13:06:20'),
(81, 19, 'MMA102', 1, '2024-12-02 13:06:32'),
(82, 7, 'MMA102', 1, '2024-12-02 13:06:37'),
(83, 3, 'MMA102', 1, '2024-12-02 13:06:45'),
(84, 20, 'MMA102', 1, '2024-12-02 13:07:01'),
(85, 4, 'MMA102', 1, '2024-12-02 13:07:58'),
(86, 8, 'MMA102', 1, '2024-12-02 13:08:54'),
(87, 19, 'MMA103', 1, '2024-12-02 13:09:38'),
(88, 23, 'MMA103', 1, '2024-12-02 13:09:48'),
(89, 3, 'MMA103', 1, '2024-12-02 13:09:56'),
(90, 20, 'MMA103', 1, '2024-12-02 13:10:15'),
(91, 21, 'MMA103', 1, '2024-12-02 13:10:23'),
(92, 8, 'MMA103', 1, '2024-12-02 13:10:38'),
(93, 7, 'MMA103', 1, '2024-12-02 13:10:47'),
(94, 22, 'MMA103', 1, '2024-12-02 13:10:56'),
(95, 4, 'MMA103', 1, '2024-12-02 13:11:03'),
(96, 24, 'CS201', 1, '2024-12-02 13:27:16'),
(97, 25, 'CS201', 1, '2024-12-02 13:27:39'),
(98, 26, 'CS201', 1, '2024-12-02 13:27:56'),
(99, 27, 'CS201', 1, '2024-12-02 13:28:07'),
(100, 28, 'CS201', 1, '2024-12-02 13:28:17'),
(101, 29, 'CS201', 1, '2024-12-02 13:28:28'),
(102, 30, 'CS201', 1, '2024-12-02 13:28:38'),
(103, 31, 'CS201', 1, '2024-12-02 13:28:48');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `department_description` varchar(500) DEFAULT NULL,
  `department_code` varchar(10) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`, `created_at`, `department_description`, `department_code`, `updated_at`) VALUES
(1, 'College of Engineering, Computer Studies and Architecture', '2024-10-13 06:49:09', 'Combining technical skills with creative design, this college prepares students for careers in engineering, IT, and architecture. It emphasizes innovation, problem-solving, and practical applications.', 'COECSA', '2024-12-02 11:12:49'),
(2, 'College of Fine Arts and Design', '2024-10-13 06:49:09', 'This college nurtures artistic talent and creativity, offering programs in visual arts, graphic design, and multimedia. Students develop a solid foundation in artistic expression and technical skills.', 'CFAD', '2024-12-02 11:05:08'),
(3, 'College of International Tourism and Hospitality Management', '2024-10-13 06:49:09', 'Focused on global hospitality and tourism, this college equips students with the skills to manage hotels, resorts, and travel services. Programs emphasize cultural sensitivity, customer service, and industry trends.', 'CITHM', '2024-12-02 11:05:08'),
(4, 'College of Nursing', '2024-10-12 22:49:09', 'Dedicated to training compassionate and skilled nurses, this college emphasizes patient care, clinical skills, and ethical practice. Graduates are prepared to excel in various healthcare settings and provide high-quality nursing care.', 'CON', '2024-12-02 11:05:08'),
(5, 'College of Allied Medical Sciences', '2024-12-02 11:43:40', 'The College of Allied Medical Sciences (CAMS) provides education and training in various healthcare fields, preparing students for careers in medical technology, physical therapy, nursing, and other allied health professions. It emphasizes hands-on learning and healthcare excellence.', 'CAMS', '2024-12-02 11:53:13'),
(6, 'College of Liberal Arts and Education', '2024-10-12 22:49:09', 'Offers a diverse range of programs focused on critical thinking, creativity, and communication. It prepares students for careers in education, social sciences, humanities, and the arts, fostering a well-rounded, socially responsible approach to learning.', 'CLAE', '2024-12-02 11:49:57'),
(7, 'College of Business Administration', '2024-12-02 11:33:39', 'Department dedicated to the design, construction, and operation of machinery and mechanical systems.The College of Business Administration (CBA) equips students with essential business skills in management, finance, and marketing, preparing them for leadership roles in the global business world.', 'CBA', '2024-12-02 11:49:34');

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
  `status` enum('active','closed') DEFAULT 'closed',
  `student_scoring` decimal(5,0) NOT NULL DEFAULT 50,
  `self_scoring` decimal(5,0) NOT NULL DEFAULT 5,
  `peer_scoring` decimal(5,0) NOT NULL DEFAULT 5,
  `chair_scoring` decimal(5,0) NOT NULL DEFAULT 40,
  `disseminated` tinyint(1) NOT NULL DEFAULT 0,
  `is_completed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluation_periods`
--

INSERT INTO `evaluation_periods` (`period_id`, `semester`, `academic_year`, `start_date`, `end_date`, `status`, `student_scoring`, `self_scoring`, `peer_scoring`, `chair_scoring`, `disseminated`, `is_completed`) VALUES
(1, '1st', '2024-2025', '2024-11-19', '2024-12-20', 'active', 50, 5, 5, 40, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `faculty_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
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

INSERT INTO `faculty` (`faculty_id`, `username`, `password`, `email`, `created_at`, `updated_at`, `first_name`, `last_name`, `department_id`, `phone_number`, `profile_image`) VALUES
(20001, 'hbermudez', 'password123', 'hbermudez@example.com', '2024-12-02 11:54:31', '2024-12-02 11:54:31', 'Halford', 'Bermudez', 1, '09171234567', 'uploads/default_image.jpg'),
(20002, 'jperen', 'password123', 'jperen@example.com', '2024-12-02 11:54:31', '2024-12-02 12:39:32', 'Jerian', 'Peren', 1, '09172345678', 'uploads/default_image.jpg'),
(20003, 'ppocaan', 'password123', 'ppocaan@example.com', '2024-12-02 11:54:31', '2024-12-02 11:54:31', 'Paola', 'Pocaan', 3, '09173456789', 'uploads/default_image.jpg'),
(20004, 'mbrown', 'password123', 'mbrown@example.com', '2024-12-02 11:54:31', '2024-12-02 11:54:31', 'Michael', 'Brown', 4, '09174567890', 'uploads/default_image.jpg'),
(20005, 'dgarcia', 'password123', 'dgarcia@example.com', '2024-12-02 11:54:31', '2024-12-02 12:40:43', 'Diana', 'Garcia', 4, '09175678901', 'uploads/default_image.jpg'),
(20006, 'jwhite', 'password123', 'jwhite@example.com', '2024-12-02 11:54:31', '2024-12-02 12:42:19', 'James', 'White', 7, '09176789012', 'uploads/default_image.jpg'),
(20007, 'sallen', 'password123', 'sallen@example.com', '2024-12-02 11:54:31', '2024-12-02 11:54:31', 'Sandra', 'Allen', 7, '09177890123', 'uploads/default_image.jpg'),
(20008, 'kwilson', 'password123', 'kwilson@example.com', '2024-12-02 11:54:31', '2024-12-02 11:54:31', 'Kevin', 'Wilson', 1, '09178901234', 'uploads/default_image.jpg'),
(20009, 'lreyes', 'password123', 'lreyes@example.com', '2024-12-02 11:54:31', '2024-12-02 12:44:21', 'Lina', 'Reyes', 5, '09180123456', 'uploads/default_image.jpg'),
(20010, 'tlopez', 'password123', 'tlopez@example.com', '2024-12-02 11:54:31', '2024-12-02 12:44:28', 'Tony', 'Lopez', 5, '09181234567', 'uploads/default_image.jpg'),
(20011, 'cbishop', 'password123', 'cbishop@example.com', '2024-12-02 11:54:31', '2024-12-02 12:59:57', 'Cathy', 'Bishop', 1, '09182345678', 'uploads/default_image.jpg'),
(20012, 'rmoore', 'password123', 'rmoore@example.com', '2024-12-02 11:54:31', '2024-12-02 13:00:06', 'Rita', 'Moore', 1, '09183456789', 'uploads/default_image.jpg'),
(20013, 'kwilliams', 'password123', 'kwilliams@example.com', '2024-12-02 11:54:31', '2024-12-02 13:12:20', 'Kendall', 'Williams', 3, '09184567890', 'uploads/default_image.jpg'),
(20014, 'ssmith', 'password123', 'ssmith@example.com', '2024-12-02 11:54:31', '2024-12-02 11:54:31', 'Steven', 'Smith', 7, '09185678901', 'uploads/default_image.jpg'),
(20015, 'fthomas', 'password123', 'fthomas@example.com', '2024-12-02 11:54:31', '2024-12-02 13:13:06', 'Felicia', 'Thomas', 7, '09186789012', 'uploads/default_image.jpg'),
(20016, 'jclark', 'password123', 'jclark@example.com', '2024-12-02 11:54:31', '2024-12-02 11:54:31', 'John', 'Clark', 2, '09187890123', 'uploads/default_image.jpg'),
(20017, 'sramirez', 'password123', 'sramirez@example.com', '2024-12-02 11:54:31', '2024-12-02 13:14:09', 'Sandra', 'Ramirez', 2, '09188901234', 'uploads/default_image.jpg'),
(20018, 'tparker', 'password123', 'tparker@example.com', '2024-12-02 11:54:31', '2024-12-02 13:14:30', 'Timothy', 'Parker', 2, '09189012345', 'uploads/default_image.jpg'),
(20019, 'jhernandez', 'password123', 'jhernandez@example.com', '2024-12-02 11:54:31', '2024-12-02 13:14:44', 'Juan', 'Hernandez', 2, '09190123456', 'uploads/default_image.jpg'),
(20020, 'arodriguez', 'password123', 'arodriguez@example.com', '2024-12-02 11:54:31', '2024-12-02 13:15:15', 'Ana', 'Rodriguez', 2, '09191234567', 'uploads/default_image.jpg'),
(20021, 'lgonzalez', 'password123', 'lgonzalez@example.com', '2024-12-02 11:54:31', '2024-12-02 13:23:43', 'Luis', 'Gonzalez', 4, '09192345678', 'uploads/default_image.jpg'),
(20022, 'efoster', 'password123', 'efoster@example.com', '2024-12-02 11:54:31', '2024-12-02 13:24:02', 'Emily', 'Foster', 4, '09193456789', 'uploads/default_image.jpg'),
(20023, 'bmartinez', 'password123', 'bmartinez@example.com', '2024-12-02 11:54:31', '2024-12-02 13:24:41', 'Benjamin', 'Martinez', 5, '09194567890', 'uploads/default_image.jpg'),
(20024, 'mallen', 'password123', 'mallen@example.com', '2024-12-02 11:54:31', '2024-12-02 13:26:05', 'Michelle', 'Allen', 5, '09195678901', 'uploads/default_image.jpg'),
(20025, 'jprice', 'password123', 'jprice@example.com', '2024-12-02 11:54:31', '2024-12-02 12:48:05', 'Jack', 'Price', 3, '09196789012', 'uploads/default_image.jpg'),
(20026, 'rmorris', 'password123', 'rmorris@example.com', '2024-12-02 11:54:31', '2024-12-02 11:54:31', 'Rachel', 'Morris', 5, '09197890123', 'uploads/default_image.jpg'),
(20027, 'kroger', 'password123', 'kroger@example.com', '2024-12-02 11:54:31', '2024-12-02 11:54:31', 'Karen', 'Rogers', 6, '09198901234', 'uploads/default_image.jpg'),
(20028, 'dsmith', 'password123', 'dsmith@example.com', '2024-12-02 11:54:31', '2024-12-02 13:33:12', 'David', 'Smith', 6, '09199012345', 'uploads/default_image.jpg'),
(20029, 'mbrooks', 'password123', 'mbrooks@example.com', '2024-12-02 11:54:31', '2024-12-02 13:32:19', 'Mark', 'Brooks', 3, '09199123456', 'uploads/default_image.jpg'),
(20030, 'tmartin', 'password123', 'tmartin@example.com', '2024-12-02 11:54:31', '2024-12-02 13:32:57', 'Tina', 'Martin', 6, '09199234567', 'uploads/default_image.jpg');

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
(20001, 54),
(20001, 63),
(20001, 98),
(20001, 99),
(20002, 55),
(20002, 64),
(20002, 96),
(20003, 51),
(20003, 85),
(20004, 47),
(20004, 102),
(20004, 103),
(20005, 57),
(20006, 46),
(20007, 56),
(20008, 53),
(20008, 62),
(20009, 52),
(20009, 61),
(20009, 100),
(20009, 101),
(20010, 50),
(20010, 59),
(20011, 48),
(20011, 97),
(20012, 58),
(20013, 95),
(20014, 74),
(20015, 86),
(20015, 92),
(20016, 68),
(20016, 81),
(20016, 87),
(20017, 71),
(20017, 90),
(20018, 84),
(20019, 72),
(20019, 79),
(20019, 91),
(20020, 69),
(20020, 77),
(20020, 88),
(20021, 75),
(20021, 82),
(20022, 93),
(20023, 70),
(20023, 78),
(20023, 94),
(20024, 83),
(20024, 89),
(20025, 60),
(20026, 73),
(20029, 76);

-- --------------------------------------------------------

--
-- Table structure for table `faculty_evaluations`
--

CREATE TABLE `faculty_evaluations` (
  `evaluation_id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `is_completed` tinyint(1) DEFAULT 0,
  `date_evaluated` datetime DEFAULT NULL,
  `time_evaluated` time DEFAULT NULL
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
  `department_id` int(10) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`program_id`, `program_code`, `program_name`, `program_description`, `department_id`, `updated_at`) VALUES
(1, 'BSMT', 'BS Medical Technology', 'Focuses on laboratory sciences, training students in clinical procedures and diagnostics used to detect, diagnose, and treat diseases.', 5, '2024-12-02 11:44:06'),
(2, 'BS-PHAR', 'BS Pharmacy', 'Prepares students for careers in the pharmaceutical industry, emphasizing drug therapy, patient care, and medication management.', 5, '2024-12-02 11:44:07'),
(3, 'BS-RADTECH', 'BS Radiologic Technology', 'Trains students in medical imaging techniques, including X-ray, MRI, and CT scans, to assist in patient diagnosis.', 5, '2024-12-02 11:44:11'),
(4, 'BS-BIO', 'BS Biology', 'Provides a broad understanding of biological sciences, covering genetics, ecology, and microbiology as foundations for careers in research, healthcare, and biotechnology.', 5, '2024-12-02 11:44:14'),
(5, 'B-COM', 'Bachelor of Arts in Communication', 'Focuses on communication theory, media studies, and public relations, preparing students for careers in journalism, media, and corporate communication.', 6, '2024-12-02 11:44:16'),
(6, 'AB-FS', 'AB Foreign Service', 'Prepares students for diplomatic careers with training in international relations, global politics, and cultural studies.', 6, '2024-12-02 11:44:17'),
(7, 'AB-LS', 'AB Legal Studies', 'Offers foundational legal knowledge and critical thinking skills, ideal for students aiming for law school or legal assistant roles.', 6, '2024-12-02 11:44:20'),
(8, 'BECE', 'Bachelor of Early Childhood Education', 'Equips students with teaching methods and psychology for educating young children, particularly in preschool and elementary levels.', 6, '2024-12-02 11:44:22'),
(9, 'BSE', 'Bachelor in Secondary Education ', 'Prepares future educators to teach at the secondary level, specializing in pedagogy and adolescent development.', 6, '2024-12-02 11:44:23'),
(10, 'BS-PSYCH', 'BS Psychology', 'Covers psychological theories and practices, preparing students for roles in counseling, human resources, or further studies in psychology.', 6, '2024-12-02 11:44:25'),
(11, 'BSA', 'BS Accountancy', 'Provides expertise in financial accounting, taxation, and auditing, essential for roles as accountants or financial analysts.', 7, '2024-12-02 11:44:30'),
(12, 'BA-HRM', 'BS Business Administration major in Human Resource Development Management', 'Trains students in managing employee relations, talent acquisition, and organizational behavior.', 7, '2024-12-02 11:44:32'),
(13, 'BA-MA', 'BS Business Administration major in Management Accounting', 'Equips students with skills in budgeting, cost management, and financial planning within organizations.', 7, '2024-12-02 11:44:34'),
(14, 'BA-MM', 'BS Business Administration major in Marketing Management', 'Focuses on marketing strategies, consumer behavior, and brand management, preparing students for marketing careers.', 7, '2024-12-02 11:44:36'),
(15, 'BA-OM', 'BS Business Administration major in Operations Management', 'Teaches principles of production, logistics, and supply chain management to streamline business processes.', 7, '2024-12-02 11:44:37'),
(16, 'BSCA', 'BS Customs Administration', 'Prepares students for careers in customs, trade compliance, and logistics within global supply chains.', 7, '2024-12-02 11:44:39'),
(17, 'BS-ENTR-AI', 'BS Entrepreneurship with specialization in Aesthetics Industry Management', 'Focuses on business startup processes and managing ventures specifically within the aesthetics and wellness industry.', 7, '2024-12-02 11:44:41'),
(18, 'BSREM', 'BS Real Estate Management', 'Provides knowledge in real estate laws, property management, and market analysis, preparing students for roles in real estate.', 7, '2024-12-02 11:44:43'),
(19, 'BS-ARCH', 'Bachelor of Science in Architecture', 'Teaches design principles, architectural theory, and construction techniques for careers in architecture.', 1, '2024-12-02 11:05:08'),
(20, 'BSCS', 'BS Computer Science', 'Computer Science will be able to learn about other subjects such as machine learning, blockchain, social hacking and data analytics.', 1, '2024-12-02 11:05:08'),
(21, 'BSIT', 'BS Information Technology', 'Equips students with the basic ability to conceptualize, design and implement software applications.', 1, '2024-12-02 11:05:08'),
(22, 'LIS', 'Bachelor of Library and Information Science', 'Trains students in library management, information retrieval, and cataloging, preparing them for roles in library sciences.', 1, '2024-12-02 11:05:08'),
(23, 'BSAE', 'Bachelor of Science in Aeronautical Engineering', 'Prepares students for the aviation industry with training in aircraft design, systems, and safety.', 1, '2024-12-02 11:05:08'),
(24, 'BSCE', 'BS Civil Engineering', 'Designed to prepare graduates to apply knowledge of mathematics, calculus-based physics, chemistry, and at least one additional area of basic science.', 1, '2024-12-02 11:05:08'),
(25, 'BSCPE', 'Bachelor of Science in Computer Engineering', 'Combines computer science and electrical engineering for computing technology development.', 1, '2024-12-02 11:05:08'),
(26, 'BET-CTM', 'Bachelor of Engineering Technology', 'Teaches construction practices and project management in engineering settings.', 1, '2024-12-02 11:05:08'),
(27, 'BSECE', 'BS in Electronics Engineering', 'Focuses on electronic devices, telecommunications, and signal processing.', 1, '2024-12-02 11:05:08'),
(28, 'BSIE', 'Bachelor of Science in Industrial Engineering', 'Teaches process optimization, production planning, and quality control in various industries.', 1, '2024-12-02 11:05:08'),
(29, 'BSME', 'BS Mechanical Engineering', 'Covers thermodynamics, mechanical design, and manufacturing processes for engineering roles.', 1, '2024-12-02 11:05:08'),
(30, 'BFA', 'Bachelor of Fine Arts', 'Offers creative training in painting, sculpture, and visual arts for careers in the arts sector.', 2, '2024-12-02 11:05:08'),
(31, 'BMMA', 'Bachelor of Multimedia Arts', 'Combines digital media, graphic design, and animation, preparing students for multimedia industries.', 2, '2024-12-02 11:05:08'),
(32, 'B-PHOTO', 'Bachelor in Photography', 'Teaches photography techniques and visual storytelling for careers in professional photography.', 1, '2024-12-02 11:05:08'),
(33, 'BS-ITTM', 'BS International Travel and Tourism Management', 'Prepares students for roles in tourism management, including travel planning and tour operations.', 3, '2024-12-02 11:05:08'),
(34, 'BS-ITTM-HW', 'BS International Travel and Tourism Management (Health and Wellness)', 'Specializes in health and wellness tourism, focusing on wellness programs and spa management.', 3, '2024-12-02 11:05:08'),
(35, 'BSHM', 'BS International Hospitality Management', 'Prepares students to become effective leaders, managers, and/or entrepreneurs in the global hospitality industry. ', 3, '2024-12-02 11:05:08'),
(36, 'BSND', 'BS Nutrition and Dietetics', 'Prepares students to become dietitians or nutritionists, focusing on meal planning and health management.', 3, '2024-12-02 11:05:08'),
(37, 'BSN', 'BS Nursing', 'Provides clinical and theoretical training in patient care, preparing students to become registered nurses.', 4, '2024-12-02 11:05:08');

-- --------------------------------------------------------

--
-- Table structure for table `program_chairs`
--

CREATE TABLE `program_chairs` (
  `chair_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `department_id` int(10) DEFAULT NULL,
  `profile_image` varchar(220) DEFAULT 'uploads/default_image.jpg',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program_chairs`
--

INSERT INTO `program_chairs` (`chair_id`, `username`, `password`, `email`, `created_at`, `first_name`, `last_name`, `department_id`, `profile_image`, `updated_at`) VALUES
(1, 'chair', '$2y$10$W.748FkPLcEn4x3AmlG0uuu2ijCUQ5ghUMBzeEqunr0D0J5WCvLpG', 'chair1dsadsdd@example.com', '2024-10-12 22:52:28', 'Cj', 'Moya', 6, 'uploads/default_image.jpg', '2024-12-02 11:45:04'),
(23, 'asmith', '$2y$10$JYg8BStfJosYXKZYlqiL.uQUu2cq2ri4ZfQ0cnb.LFJ.BhCi739pS', 'asmith@example.com', '2024-10-27 04:00:00', 'Alice', 'Smith', 7, 'uploads/default_image.jpg', '2024-12-02 11:45:09'),
(31, 'mjones', '$2y$10$E7qZ6L7..0m4RlnD5QH1QO53zHcG/dA9kvlqIHXlW8AF0t3F5mU6e', 'mjones@example.com', '2024-10-27 04:00:00', 'Michael', 'Jones', 5, 'uploads/default_image.jpg', '2024-12-02 11:53:13'),
(65, 'jdoe', '$2y$10$E7qZ6L7..0m4RlnD5QH1QO53zHcG/dA9kvlqIHXlW8AF0t3F5mU6e', 'jdoe@example.com', '2024-10-27 04:00:00', 'John', 'Doe', 1, 'uploads/default_image.jpg', '2024-12-02 11:06:21'),
(66, 'jsdoe', 'hashed_password', 'jdoe@university.com', '2024-01-15 00:00:00', 'John', 'Doe 2', NULL, 'uploads/default_image.jpg', '2024-12-02 11:06:21'),
(67, 'asmdith', 'hashed_password', 'asmith@university.com', '2024-01-16 01:30:00', 'Alice', 'Smith', NULL, 'uploads/default_image.jpg', '2024-12-02 11:06:21'),
(68, 'bwhsite', 'hashed_password', 'bwhite@university.com', '2024-02-01 02:00:00', 'Bob', 'White', NULL, 'uploads/default_image.jpg', '2024-12-02 11:06:21'),
(69, 'mjonses', 'hashed_password', 'mjones@university.com', '2024-02-20 03:30:00', 'Mary', 'Jones', NULL, 'uploads/default_image.jpg', '2024-12-02 11:06:21'),
(70, 'kblasck', 'hashed_password', 'kblack@university.com', '2024-03-10 04:15:00', 'Kevin', 'Black', 3, 'uploads/default_image.jpg', '2024-12-02 11:06:21'),
(71, 'ljacdkson', 'hashed_password', 'ljackson@university.com', '2024-04-05 05:00:00', 'Laura', 'Jackson', NULL, 'uploads/default_image.jpg', '2024-12-02 11:06:21'),
(72, 'rwhiste', 'hashed_password', 'rwhite@university.com', '2024-05-10 06:00:00', 'Richard', 'White', NULL, 'uploads/default_image.jpg', '2024-12-02 11:06:21'),
(73, 'jclaark', 'hashed_password', 'jclark@university.com', '2024-06-22 07:30:00', 'James', 'Clark', 2, 'uploads/default_image.jpg', '2024-12-02 11:06:21'),
(74, 'sbrodwn', 'hashed_password', 'sbrown@university.com', '2024-07-18 08:45:00', 'Sarah', 'Brown', NULL, 'uploads/default_image.jpg', '2024-12-02 11:53:13'),
(75, 'pgreden', 'hashed_password', 'pgreen@university.com', '2024-08-25 09:00:00', 'Paul', 'Green', 4, 'uploads/default_image.jpg', '2024-12-02 11:06:21');

-- --------------------------------------------------------

--
-- Table structure for table `program_chair_evaluations`
--

CREATE TABLE `program_chair_evaluations` (
  `chair_id` int(11) NOT NULL,
  `evaluation_id` int(11) NOT NULL,
  `is_completed` tinyint(1) DEFAULT 0,
  `date_evaluated` datetime DEFAULT NULL,
  `time_evaluated` time DEFAULT NULL
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
(20, 1),
(20, 2),
(20, 3),
(20, 4),
(20, 5),
(20, 6),
(20, 7),
(20, 8),
(20, 9),
(20, 10),
(20, 11),
(20, 12),
(20, 13),
(20, 14),
(20, 15),
(20, 16),
(20, 17),
(20, 18),
(20, 24),
(20, 25),
(20, 26),
(20, 27),
(20, 28),
(20, 29),
(20, 30),
(20, 31),
(31, 3),
(31, 4),
(31, 7),
(31, 8),
(31, 9),
(31, 19),
(31, 20),
(31, 21),
(31, 22),
(31, 23);

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

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
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

INSERT INTO `students` (`student_id`, `username`, `password`, `email`, `created_at`, `updated_at`, `first_name`, `last_name`, `program_id`, `phone_number`, `profile_image`) VALUES
(10001, 'loganprice', 'password1', 'cj.rojo@lpunetwork.edu.ph', '2024-12-02 10:43:45', '2024-12-02 11:28:05', 'Cj', 'Rojo', 20, '09156578280', 'uploads/default_image.jpg'),
(10002, 'emilywarren', 'password2', 'patricianicole.mendoza@lpunetwork.edu.ph', '2024-12-02 10:43:45', '2024-12-02 11:27:04', 'Patricia', 'Mendoza', 20, '(202) 555-0102', 'uploads/default_image.jpg'),
(10003, 'alexhunter', 'password3', 'lance.romero@lpunetwork.edu.ph', '2024-12-02 10:43:45', '2024-12-02 12:38:02', 'Lance', 'Romero', 20, '(203) 555-0103', 'uploads/default_image.jpg'),
(10004, 'sophiabrooks', 'password4', 'sophia.brooks@gmail.com', '2024-12-02 10:43:45', '2024-12-02 12:53:57', 'Sophia', 'Brooks', 31, '(204) 555-0104', 'uploads/default_image.jpg'),
(10005, 'liamgriffin', 'password5', 'liam.griffin@gmail.com', '2024-12-02 10:43:45', '2024-12-02 13:39:35', 'Liam', 'Griffin', 31, '(205) 555-0105', 'uploads/default_image.jpg'),
(10006, 'avafoster', 'password6', 'ava.foster@gmail.com', '2024-12-02 10:43:45', '2024-12-02 13:39:41', 'Ava', 'Foster', 31, '(206) 555-0106', 'uploads/default_image.jpg'),
(10007, 'ethanreid', 'password7', 'ethan.reid@gmail.com', '2024-12-02 10:43:45', '2024-12-02 13:39:57', 'Ethan', 'Reid', 31, '(207) 555-0107', 'uploads/default_image.jpg'),
(10008, 'oliviaclark', 'password8', 'olivia.clark@gmail.com', '2024-12-02 10:43:45', '2024-12-02 14:32:36', 'Olivia', 'Clark', 20, '(208) 555-0108', 'uploads/default_image.jpg'),
(10009, 'masonroberts', 'password9', 'mason.roberts@gmail.com', '2024-12-02 10:43:45', '2024-12-02 14:32:10', 'Mason', 'Roberts', 20, '(209) 555-0109', 'uploads/default_image.jpg'),
(10010, 'lucysmith', 'password10', 'lucy.smith@gmail.com', '2024-12-02 10:43:45', '2024-12-02 10:43:45', 'Lucy', 'Smith', 33, '(210) 555-0110', 'uploads/default_image.jpg'),
(10011, 'jackjohnson', 'password11', 'jack.johnson@gmail.com', '2024-12-02 10:43:45', '2024-12-02 10:43:45', 'Jack', 'Johnson', 22, '(211) 555-0111', 'uploads/default_image.jpg'),
(10012, 'miaanderson', 'password12', 'mia.anderson@gmail.com', '2024-12-02 10:43:45', '2024-12-02 10:43:45', 'Mia', 'Anderson', 12, '(212) 555-0112', 'uploads/default_image.jpg'),
(10013, 'benjaminmoore', 'password13', 'benjamin.moore@gmail.com', '2024-12-02 10:43:45', '2024-12-02 10:43:45', 'Benjamin', 'Moore', 29, '(213) 555-0113', 'uploads/default_image.jpg'),
(10014, 'emilyjames', 'password14', 'emily.james@gmail.com', '2024-12-02 10:43:45', '2024-12-02 10:43:45', 'Emily', 'James', 35, '(214) 555-0114', 'uploads/default_image.jpg'),
(10015, 'noahlewis', 'password15', 'noah.lewis@gmail.com', '2024-12-02 10:43:45', '2024-12-02 10:43:45', 'Noah', 'Lewis', 14, '(215) 555-0115', 'uploads/default_image.jpg'),
(10016, 'charlottemiller', 'password16', 'charlotte.miller@gmail.com', '2024-12-02 10:43:45', '2024-12-02 10:43:45', 'Charlotte', 'Miller', 2, '(216) 555-0116', 'uploads/default_image.jpg'),
(10017, 'danielmartin', 'password17', 'daniel.martin@gmail.com', '2024-12-02 10:43:45', '2024-12-02 10:43:45', 'Daniel', 'Martin', 6, '(217) 555-0117', 'uploads/default_image.jpg'),
(10018, 'harperdavies', 'password18', 'harper.davies@gmail.com', '2024-12-02 10:43:45', '2024-12-02 10:43:45', 'Harper', 'Davies', 21, '(218) 555-0118', 'uploads/default_image.jpg'),
(10019, 'scarlettrodriguez', 'password19', 'scarlett.rodriguez@gmail.com', '2024-12-02 10:43:45', '2024-12-02 10:43:45', 'Scarlett', 'Rodriguez', 15, '(219) 555-0119', 'uploads/default_image.jpg'),
(10020, 'jacksonlewis', 'password20', 'jackson.lewis@gmail.com', '2024-12-02 10:43:45', '2024-12-02 10:43:45', 'Jackson', 'Lewis', 9, '(220) 555-0120', 'uploads/default_image.jpg'),
(10021, 'student01', 'password123', 'student01@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Oliver', 'Kingston', 20, '09123456789', 'uploads/default_image.jpg'),
(10022, 'student02', 'password123', 'student02@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Sophia', 'Martinez', 20, '09123456788', 'uploads/default_image.jpg'),
(10023, 'student03', 'password123', 'student03@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Liam', 'Anderson', 20, '09123456787', 'uploads/default_image.jpg'),
(10024, 'student04', 'password123', 'student04@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Mia', 'Roberts', 20, '09123456786', 'uploads/default_image.jpg'),
(10025, 'student05', 'password123', 'student05@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Aiden', 'Harris', 20, '09123456785', 'uploads/default_image.jpg'),
(10026, 'student06', 'password123', 'student06@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Isabella', 'Lopez', 20, '09123456784', 'uploads/default_image.jpg'),
(10027, 'student07', 'password123', 'student07@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Ethan', 'Clark', 20, '09123456783', 'uploads/default_image.jpg'),
(10028, 'student08', 'password123', 'student08@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Olivia', 'Walker', 20, '09123456782', 'uploads/default_image.jpg'),
(10029, 'student09', 'password123', 'student09@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Jackson', 'Taylor', 20, '09123456781', 'uploads/default_image.jpg'),
(10030, 'student10', 'password123', 'student10@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Chloe', 'Parker', 20, '09123456780', 'uploads/default_image.jpg'),
(10031, 'student11', 'password123', 'student11@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Liam', 'Robinson', 20, '09123456779', 'uploads/default_image.jpg'),
(10032, 'student12', 'password123', 'student12@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Harper', 'Moore', 20, '09123456778', 'uploads/default_image.jpg'),
(10033, 'student13', 'password123', 'student13@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Gabriel', 'Morris', 20, '09123456777', 'uploads/default_image.jpg'),
(10034, 'student14', 'password123', 'student14@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Amelia', 'Scott', 20, '09123456776', 'uploads/default_image.jpg'),
(10035, 'student15', 'password123', 'student15@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'James', 'Evans', 20, '09123456775', 'uploads/default_image.jpg'),
(10036, 'student16', 'password123', 'student16@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Ava', 'Baker', 20, '09123456774', 'uploads/default_image.jpg'),
(10037, 'student17', 'password123', 'student17@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Lucas', 'Gonzalez', 20, '09123456773', 'uploads/default_image.jpg'),
(10038, 'student18', 'password123', 'student18@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Charlotte', 'Nelson', 20, '09123456772', 'uploads/default_image.jpg'),
(10039, 'student19', 'password123', 'student19@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Benjamin', 'Carter', 20, '09123456771', 'uploads/default_image.jpg'),
(10040, 'student20', 'password123', 'student20@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Evelyn', 'Adams', 20, '09123456770', 'uploads/default_image.jpg'),
(10041, 'student21', 'password123', 'student21@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Nathan', 'Bennett', 20, '09123456769', 'uploads/default_image.jpg'),
(10042, 'student22', 'password123', 'student22@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Ella', 'Gonzalez', 20, '09123456768', 'uploads/default_image.jpg'),
(10043, 'student23', 'password123', 'student23@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Oliver', 'Cunningham', 20, '09123456767', 'uploads/default_image.jpg'),
(10044, 'student24', 'password123', 'student24@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Grace', 'Stewart', 20, '09123456766', 'uploads/default_image.jpg'),
(10045, 'student25', 'password123', 'student25@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Sebastian', 'Perry', 20, '09123456765', 'uploads/default_image.jpg'),
(10046, 'student26', 'password123', 'student26@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Victoria', 'Mills', 20, '09123456764', 'uploads/default_image.jpg'),
(10047, 'student27', 'password123', 'student27@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Carter', 'James', 20, '09123456763', 'uploads/default_image.jpg'),
(10048, 'student28', 'password123', 'student28@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Madison', 'Peters', 20, '09123456762', 'uploads/default_image.jpg'),
(10049, 'student29', 'password123', 'student29@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Lucas', 'Russell', 20, '09123456761', 'uploads/default_image.jpg'),
(10050, 'student30', 'password123', 'student30@example.com', '2024-12-02 14:24:26', '2024-12-02 14:24:26', 'Chloe', 'Morgan', 20, '09123456760', 'uploads/default_image.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `students_evaluations`
--

CREATE TABLE `students_evaluations` (
  `evaluation_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `comments` text DEFAULT NULL,
  `date_evaluated` date DEFAULT NULL,
  `time_evaluated` time DEFAULT NULL,
  `is_completed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(10001, 46),
(10001, 47),
(10001, 48),
(10001, 50),
(10001, 51),
(10001, 52),
(10001, 53),
(10001, 54),
(10001, 55),
(10002, 46),
(10002, 47),
(10002, 48),
(10002, 50),
(10002, 51),
(10002, 52),
(10002, 53),
(10002, 54),
(10002, 55),
(10003, 56),
(10003, 57),
(10003, 58),
(10003, 59),
(10003, 60),
(10003, 61),
(10003, 62),
(10003, 63),
(10003, 64),
(10004, 68),
(10004, 69),
(10004, 70),
(10004, 71),
(10004, 72),
(10004, 73),
(10004, 74),
(10004, 75),
(10004, 76),
(10005, 69),
(10005, 72),
(10005, 74),
(10005, 78),
(10005, 82),
(10005, 83),
(10005, 87),
(10005, 90),
(10005, 95),
(10006, 87),
(10006, 88),
(10006, 89),
(10006, 90),
(10006, 91),
(10006, 92),
(10006, 93),
(10006, 94),
(10006, 95),
(10007, 77),
(10007, 78),
(10007, 79),
(10007, 81),
(10007, 82),
(10007, 83),
(10007, 84),
(10007, 85),
(10007, 86),
(10008, 96),
(10008, 97),
(10008, 98),
(10008, 99),
(10008, 100),
(10008, 101),
(10008, 102),
(10008, 103),
(10009, 56),
(10009, 57),
(10009, 58),
(10009, 59),
(10009, 60),
(10009, 61),
(10009, 62),
(10009, 63),
(10009, 64),
(10021, 96),
(10021, 97),
(10021, 98),
(10021, 99),
(10021, 100),
(10021, 101),
(10021, 102),
(10021, 103),
(10022, 56),
(10022, 57),
(10022, 58),
(10022, 59),
(10022, 60),
(10022, 61),
(10022, 62),
(10022, 63),
(10022, 64),
(10023, 96),
(10023, 97),
(10023, 98),
(10023, 99),
(10023, 100),
(10023, 101),
(10023, 102),
(10023, 103),
(10024, 96),
(10024, 97),
(10024, 98),
(10024, 99),
(10024, 100),
(10024, 101),
(10024, 102),
(10024, 103),
(10025, 96),
(10025, 97),
(10025, 98),
(10025, 99),
(10025, 100),
(10025, 101),
(10025, 102),
(10025, 103),
(10026, 96),
(10026, 97),
(10026, 98),
(10026, 99),
(10026, 100),
(10026, 101),
(10026, 102),
(10026, 103),
(10027, 56),
(10027, 57),
(10027, 58),
(10027, 59),
(10027, 60),
(10027, 61),
(10027, 62),
(10027, 63),
(10027, 64),
(10028, 56),
(10028, 57),
(10028, 58),
(10028, 59),
(10028, 60),
(10028, 61),
(10028, 62),
(10028, 63),
(10028, 64),
(10029, 56),
(10029, 57),
(10029, 58),
(10029, 59),
(10029, 60),
(10029, 61),
(10029, 62),
(10029, 63),
(10029, 64),
(10030, 46),
(10030, 47),
(10030, 48),
(10030, 50),
(10030, 51),
(10030, 52),
(10030, 53),
(10030, 54),
(10030, 55),
(10031, 46),
(10031, 47),
(10031, 48),
(10031, 50),
(10031, 51),
(10031, 52),
(10031, 53),
(10031, 54),
(10031, 55),
(10032, 46),
(10032, 47),
(10032, 48),
(10032, 50),
(10032, 51),
(10032, 52),
(10032, 53),
(10032, 54),
(10032, 55),
(10033, 46),
(10033, 48),
(10033, 50),
(10033, 51),
(10033, 52),
(10033, 53),
(10033, 54),
(10033, 55),
(10034, 46),
(10034, 47),
(10034, 48),
(10034, 50),
(10034, 51),
(10034, 52),
(10034, 53),
(10034, 54),
(10034, 55);

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
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `course_sections`
--
ALTER TABLE `course_sections`
  MODIFY `course_section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `evaluation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `evaluation_periods`
--
ALTER TABLE `evaluation_periods`
  MODIFY `period_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `faculty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20031;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `program_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3214;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT for table `questions_criteria`
--
ALTER TABLE `questions_criteria`
  MODIFY `criteria_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `responses`
--
ALTER TABLE `responses`
  MODIFY `response_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10051;

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
