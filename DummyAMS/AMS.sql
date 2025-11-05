-- Create the new database.
CREATE DATABASE externalUniversitydb;

-- Switch to the new database.
USE externalUniversitydb;

--
-- Table structure for table `students`
--
CREATE TABLE `students` (
  `id` int(11) NOT NULL PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `photo_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students`
--
INSERT INTO `students` (`id`, `name`, `email`, `password`, `photo_url`) VALUES
(183523, 'Karanei Kimutai', 'kimutai.karanei@strathmore.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'https://randomuser.me/api/portraits/women/1.jpg'),
(190004, 'Witness Mukundi', 'mukundi.chingwena@strathmore.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'https://randomuser.me/api/portraits/men/2.jpg'),
(130103, 'Fatima Yusuf', 'fatima.yusuf@university.ac.ke', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'https://randomuser.me/api/portraits/women/3.jpg'),
(130104, 'David Kariuki', 'david.kariuki@university.ac.ke', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'https://randomuser.me/api/portraits/men/4.jpg'),
(130105, 'Chloe Wangari', 'chloe.wangari@university.ac.ke', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'https://randomuser.me/api/portraits/women/5.jpg'),
(130106, 'Samuel Mwangi', 'samuel.mwangi@university.ac.ke', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'https://randomuser.me/api/portraits/men/6.jpg'),
(189984, 'Alvin Murithi', 'alvin.muriuki@strathmore.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'https://randomuser.me/api/portraits/men/18.jpg');

