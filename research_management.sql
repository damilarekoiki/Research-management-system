-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 04, 2020 at 05:58 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.0.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `research_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `surname` varchar(20) NOT NULL,
  `other_names` varchar(35) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(55) NOT NULL,
  `profile_pix` text NOT NULL,
  `date_registered` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `admin_id`, `surname`, `other_names`, `email`, `password`, `profile_pix`, `date_registered`) VALUES
(1, 1, 'Koiki', 'Damilare Solomon', 'koikidamilare@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'app/assets/avatar.png', '2018-09-13 07:32:51');

-- --------------------------------------------------------

--
-- Table structure for table `references_available`
--

CREATE TABLE `references_available` (
  `id` int(11) NOT NULL,
  `reference_id` int(11) NOT NULL,
  `reference` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `references_available`
--

INSERT INTO `references_available` (`id`, `reference_id`, `reference`) VALUES
(1, 1, '2002 – Martin Fowler – Patterns of Enterprise Application Architecture.'),
(2, 2, '2003 – Eric Evans – Domain-driven Design: Tackling complexity in the heart of software.'),
(3, 3, '2011 – Chris Ostrowski – Understanding Oracle SOA – Part 1 – Architecture.'),
(4, 4, '2012 – Ian Sommerville  –  Software Engineering (9th edition). '),
(5, 5, 'Eickelman, D.F. and A. Salvatore, 2002. The Public\r\norganises Prayer fairs that are well patronised in such a Sphere and Muslim Identities. European Journal of\r\nsystematic manner that the recitation of Prayers can be Sociology, 43(1): 92-115');

-- --------------------------------------------------------

--
-- Table structure for table `research`
--

CREATE TABLE `research` (
  `id` int(11) NOT NULL,
  `researcher_id` int(11) NOT NULL,
  `research_id` int(11) NOT NULL,
  `research_title` varchar(100) NOT NULL,
  `research_description` text NOT NULL,
  `is_shared_to_public` int(11) NOT NULL DEFAULT '0',
  `is_approved` int(11) NOT NULL DEFAULT '2',
  `is_seen` int(11) NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `research`
--

INSERT INTO `research` (`id`, `researcher_id`, `research_id`, `research_title`, `research_description`, `is_shared_to_public`, `is_approved`, `is_seen`, `date_created`) VALUES
(2, 3, 2, 'Effect of Weather on Farm Produce', 'The Research work aims at studying the impact of climatic factors on Farm produces', 0, 1, 1, '2018-09-13 13:02:28'),
(3, 6, 3, 'Project Management System', 'Manages research', 0, 1, 1, '2018-09-20 13:44:22'),
(4, 7, 4, 'Design and implementation of an automated billing system', 'Case study is the Power holding company of Nigeria, Ibadan branch', 0, 1, 1, '2018-09-24 12:08:13');

-- --------------------------------------------------------

--
-- Table structure for table `researcher_report`
--

CREATE TABLE `researcher_report` (
  `id` int(11) NOT NULL,
  `report` text NOT NULL,
  `report_id` int(11) NOT NULL,
  `researcher_id` int(11) NOT NULL,
  `reporter_id` int(11) NOT NULL,
  `date_reported` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `research_collaborators`
--

CREATE TABLE `research_collaborators` (
  `id` int(11) NOT NULL,
  `research_id` int(11) NOT NULL,
  `collaborator` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `research_collaborators`
--

INSERT INTO `research_collaborators` (`id`, `research_id`, `collaborator`) VALUES
(2, 2, 2),
(3, 2, 3),
(4, 3, 3),
(5, 3, 4),
(6, 3, 5),
(7, 4, 4),
(8, 4, 6);

-- --------------------------------------------------------

--
-- Table structure for table `research_comments`
--

CREATE TABLE `research_comments` (
  `id` int(11) NOT NULL,
  `research_id` int(11) NOT NULL,
  `researcher_id` int(11) NOT NULL,
  `comment_by` int(11) NOT NULL,
  `comment` text NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `research_contribution_files`
--

CREATE TABLE `research_contribution_files` (
  `id` int(11) NOT NULL,
  `research_id` int(11) NOT NULL,
  `contributor` int(11) NOT NULL,
  `researcher` int(11) NOT NULL,
  `file_directory` text NOT NULL,
  `file_name` varchar(150) NOT NULL,
  `file_id` int(11) NOT NULL,
  `date_uploaded` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `research_contribution_files_references`
--

CREATE TABLE `research_contribution_files_references` (
  `id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `reference_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `research_contribution_text`
--

CREATE TABLE `research_contribution_text` (
  `id` int(11) NOT NULL,
  `text_id` int(11) NOT NULL,
  `research_id` int(11) NOT NULL,
  `contributor` int(11) NOT NULL,
  `researcher` int(11) NOT NULL,
  `text` text NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `research_contribution_text_references`
--

CREATE TABLE `research_contribution_text_references` (
  `id` int(11) NOT NULL,
  `text_id` int(11) NOT NULL,
  `reference_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `research_files`
--

CREATE TABLE `research_files` (
  `id` int(11) NOT NULL,
  `research_id` int(11) NOT NULL,
  `researcher_id` int(11) NOT NULL,
  `file_name` varchar(150) NOT NULL,
  `file_directory` text NOT NULL,
  `file_id` int(11) NOT NULL,
  `date_uploaded` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `research_files_references`
--

CREATE TABLE `research_files_references` (
  `id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `reference_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `research_follow`
--

CREATE TABLE `research_follow` (
  `id` int(11) NOT NULL,
  `research_id` int(11) NOT NULL,
  `researcher_id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL,
  `is_approved` int(11) NOT NULL DEFAULT '2',
  `is_seen` int(11) NOT NULL DEFAULT '0',
  `date_followed` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `research_report`
--

CREATE TABLE `research_report` (
  `id` int(11) NOT NULL,
  `report` text NOT NULL,
  `report_id` int(11) NOT NULL,
  `research_id` int(11) NOT NULL,
  `reporter_id` int(11) NOT NULL,
  `date_reported` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `research_share`
--

CREATE TABLE `research_share` (
  `id` int(11) NOT NULL,
  `research_id` int(11) NOT NULL,
  `researcher_id` int(11) NOT NULL,
  `shared_to` int(11) NOT NULL,
  `date_shared` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `research_share`
--

INSERT INTO `research_share` (`id`, `research_id`, `researcher_id`, `shared_to`, `date_shared`) VALUES
(1, 3, 0, 2, '2018-09-20 13:47:01'),
(2, 3, 0, 4, '2018-09-20 13:47:03');

-- --------------------------------------------------------

--
-- Table structure for table `research_text`
--

CREATE TABLE `research_text` (
  `id` int(11) NOT NULL,
  `research_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `text_id` int(11) NOT NULL,
  `researcher_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `research_text_references`
--

CREATE TABLE `research_text_references` (
  `id` int(11) NOT NULL,
  `text_id` int(11) NOT NULL,
  `reference_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `surname` varchar(20) NOT NULL,
  `other_names` varchar(35) NOT NULL,
  `user_role` int(11) NOT NULL DEFAULT '0' COMMENT '0 for resercher, 1 for coordinator',
  `email` varchar(50) NOT NULL,
  `password` varchar(55) NOT NULL,
  `profile_pix` text NOT NULL,
  `activation_code` text NOT NULL,
  `activation_code_validity` varchar(50) NOT NULL,
  `account_status` int(11) NOT NULL,
  `request_for_coordination_role_is_seen` int(11) DEFAULT '0',
  `is_approved_as_coordinator` int(11) NOT NULL DEFAULT '2',
  `date_registered` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `user_id`, `surname`, `other_names`, `user_role`, `email`, `password`, `profile_pix`, `activation_code`, `activation_code_validity`, `account_status`, `request_for_coordination_role_is_seen`, `is_approved_as_coordinator`, `date_registered`) VALUES
(1, 1, 'IFEDAPO ', 'ANUOLUWAPO', 0, 'mercydapo@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'app/assets/avatar.png', '6553584555b9a3d67cf72a', '1536921319', 1, 0, 2, '2018-09-13 12:35:19'),
(2, 2, 'OBAKUNLE', 'OLUSESAN', 1, 'obakunleolusesan@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'app/assets/avatar.png', '18585306235b9a3e181f47c', '1536921496', 1, 0, 1, '2018-09-13 12:38:16'),
(3, 3, 'obagbemiro', 'ibraheem tunji', 0, 'obagbemiroibraheem@gmail.com', 'aa9916957a0ffbcb8c3c1b1a31085976', 'app/assets/avatar.png', '7069308485b9a41f3d095f', '1536922483', 1, 0, 2, '2018-09-13 12:54:43'),
(4, 4, 'Nduka', 'Irene', 0, 'irenenduka@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'app/assets/avatar.png', '9315599795b9a459721f0b', '1536923415', 1, 0, 2, '2018-09-13 13:10:15'),
(5, 5, 'Obalalu', 'Babatunde Sunday', 1, 'inioluwa@rwms.org', '81dc9bdb52d04dc20036dbd8313ed055', 'app/assets/avatar.png', '18421120405b9a484af0979', '1536924106', 1, 0, 1, '2018-09-13 13:21:46'),
(6, 6, 'Jegede', 'Olamide', 0, 'jegedeolamide99@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'app/assets/avatar.png', '10990146485ba387bf6fa28', '1537530175', 1, 0, 2, '2018-09-20 13:42:55'),
(7, 7, 'Koiki', 'Damilare Goke', 0, 'koikidamilare@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'app/assets/avatar.png', '6777396385ba84305b3b57', '1537840261', 1, 0, 2, '2018-09-24 03:51:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`,`admin_id`,`email`);

--
-- Indexes for table `references_available`
--
ALTER TABLE `references_available`
  ADD PRIMARY KEY (`id`,`reference_id`);

--
-- Indexes for table `research`
--
ALTER TABLE `research`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `researcher_report`
--
ALTER TABLE `researcher_report`
  ADD PRIMARY KEY (`id`,`report_id`);

--
-- Indexes for table `research_collaborators`
--
ALTER TABLE `research_collaborators`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `research_comments`
--
ALTER TABLE `research_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `research_contribution_files`
--
ALTER TABLE `research_contribution_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `research_contribution_files_references`
--
ALTER TABLE `research_contribution_files_references`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `research_contribution_text`
--
ALTER TABLE `research_contribution_text`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `research_contribution_text_references`
--
ALTER TABLE `research_contribution_text_references`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `research_files`
--
ALTER TABLE `research_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `research_files_references`
--
ALTER TABLE `research_files_references`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `research_follow`
--
ALTER TABLE `research_follow`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `research_report`
--
ALTER TABLE `research_report`
  ADD PRIMARY KEY (`id`,`report_id`);

--
-- Indexes for table `research_share`
--
ALTER TABLE `research_share`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `research_text`
--
ALTER TABLE `research_text`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `research_text_references`
--
ALTER TABLE `research_text_references`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`,`user_id`,`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `references_available`
--
ALTER TABLE `references_available`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `research`
--
ALTER TABLE `research`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `researcher_report`
--
ALTER TABLE `researcher_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `research_collaborators`
--
ALTER TABLE `research_collaborators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `research_comments`
--
ALTER TABLE `research_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `research_contribution_files`
--
ALTER TABLE `research_contribution_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `research_contribution_files_references`
--
ALTER TABLE `research_contribution_files_references`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `research_contribution_text`
--
ALTER TABLE `research_contribution_text`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `research_contribution_text_references`
--
ALTER TABLE `research_contribution_text_references`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `research_files`
--
ALTER TABLE `research_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `research_files_references`
--
ALTER TABLE `research_files_references`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `research_follow`
--
ALTER TABLE `research_follow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `research_report`
--
ALTER TABLE `research_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `research_share`
--
ALTER TABLE `research_share`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `research_text`
--
ALTER TABLE `research_text`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `research_text_references`
--
ALTER TABLE `research_text_references`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
