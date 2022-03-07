<?php
// Created by Bennito254 (https://www.bennito254.com)
if(file_exists('../env.php')){
    header("Location: ..");
}
error_reporting(0);
session_start();
function generateRandomString($length = 32) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!-+=@#$%^&*.,:';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if (!isset($_SESSION['database'])) {
    header("Location: database.php");
}
if (!isset($_SESSION['user'])) {
    header("Location: user.php");
}
//$password = 'somepass';
//$password = password_hash($password, PASSWORD_BCRYPT, ['cost'=>12]);

$conn = mysqli_connect($_SESSION['database']['server'], $_SESSION['database']['username'], $_SESSION['database']['password'], $_SESSION['database']['database']);
if (mysqli_connect_errno()) {
    header("Location: database.php");
    exit;
}
$password = password_hash($_SESSION['user']['password'], PASSWORD_BCRYPT, ['cost' => 12]);
$time = time();
$prefix = $_SESSION['database']['prefix'] ? $_SESSION['database']['prefix'] : 'prefix_';
$sql = <<<EOL
-- Adminer 4.7.7 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `{$prefix}asp_schedule`;
CREATE TABLE `{$prefix}asp_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` int(11) NOT NULL,
  `section` int(11) NOT NULL,
  `day` varchar(40) NOT NULL,
  `time` varchar(40) NOT NULL,
  `subject` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `section` (`section`),
  KEY `class` (`class`),
  KEY `subject` (`subject`),
  CONSTRAINT `{$prefix}asp_schedule_ibfk_1` FOREIGN KEY (`section`) REFERENCES `{$prefix}sections` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}asp_schedule_ibfk_2` FOREIGN KEY (`class`) REFERENCES `{$prefix}classes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}asp_schedule_ibfk_3` FOREIGN KEY (`subject`) REFERENCES `{$prefix}class_subjects` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}assignments`;
CREATE TABLE `{$prefix}assignments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` int(11) NOT NULL,
  `section` int(11) DEFAULT NULL,
  `subject` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `books` text DEFAULT NULL,
  `file` varchar(200) DEFAULT NULL,
  `deadline` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `class` (`class`),
  KEY `section` (`section`),
  KEY `subject` (`subject`),
  CONSTRAINT `{$prefix}assignments_ibfk_1` FOREIGN KEY (`class`) REFERENCES `{$prefix}classes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}assignments_ibfk_3` FOREIGN KEY (`section`) REFERENCES `{$prefix}sections` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}assignments_ibfk_4` FOREIGN KEY (`subject`) REFERENCES `{$prefix}class_subjects` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}assignment_submissions`;
CREATE TABLE `{$prefix}assignment_submissions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `file` varchar(200) DEFAULT NULL,
  `submitted_at` varchar(30) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `marks_awarded` int(10) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `assignment_id` (`assignment_id`),
  KEY `student_id` (`student_id`),
  KEY `subject_id` (`subject_id`),
  CONSTRAINT `{$prefix}assignment_submissions_ibfk_2` FOREIGN KEY (`assignment_id`) REFERENCES `{$prefix}assignments` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}assignment_submissions_ibfk_4` FOREIGN KEY (`student_id`) REFERENCES `{$prefix}students` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}assignment_submissions_ibfk_5` FOREIGN KEY (`subject_id`) REFERENCES `{$prefix}class_subjects` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}attendance`;
CREATE TABLE `{$prefix}attendance` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `timestamp` bigint(20) NOT NULL,
  `student` bigint(20) DEFAULT NULL,
  `class` int(11) DEFAULT NULL,
  `section` int(11) DEFAULT NULL,
  `teacher` int(11) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `teacher` (`teacher`),
  KEY `class` (`class`),
  KEY `section` (`section`),
  KEY `student` (`student`),
  CONSTRAINT `{$prefix}attendance_ibfk_2` FOREIGN KEY (`teacher`) REFERENCES `{$prefix}teachers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}attendance_ibfk_3` FOREIGN KEY (`class`) REFERENCES `{$prefix}classes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}attendance_ibfk_4` FOREIGN KEY (`section`) REFERENCES `{$prefix}sections` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}attendance_ibfk_5` FOREIGN KEY (`student`) REFERENCES `{$prefix}students` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}classes`;
CREATE TABLE `{$prefix}classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `session` (`session`),
  CONSTRAINT `{$prefix}classes_ibfk_1` FOREIGN KEY (`session`) REFERENCES `{$prefix}sessions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}class_groups`;
CREATE TABLE `{$prefix}class_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `section` (`section`),
  CONSTRAINT `{$prefix}class_groups_ibfk_1` FOREIGN KEY (`section`) REFERENCES `{$prefix}sections` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}class_subjects`;
CREATE TABLE `{$prefix}class_subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` int(11) NOT NULL,
  `subject` int(11) NOT NULL,
  `optional` int(1) NOT NULL DEFAULT 0,
  `pass_mark` int(11) NOT NULL DEFAULT 40,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `class` (`class`),
  KEY `subject` (`subject`),
  CONSTRAINT `{$prefix}class_subjects_ibfk_1` FOREIGN KEY (`subject`) REFERENCES `{$prefix}subjects` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}continuous_assessment`;
CREATE TABLE `{$prefix}continuous_assessment` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) NOT NULL,
  `class` int(11) NOT NULL,
  `section` int(11) NOT NULL,
  `subject` int(11) NOT NULL,
  `session` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `week` int(11) NOT NULL,
  `worksheet` varchar(11) DEFAULT NULL,
  `classwork_1` varchar(11) DEFAULT NULL,
  `classwork_2` varchar(11) DEFAULT NULL,
  `homework_1` varchar(11) DEFAULT NULL,
  `homework_2` varchar(11) DEFAULT NULL,
  `quiz_1` varchar(11) DEFAULT NULL,
  `ex_book` varchar(11) DEFAULT NULL,
  `conduct` varchar(11) DEFAULT NULL,
  `bonus` varchar(11) DEFAULT NULL,
  `assignment` varchar(11) DEFAULT NULL,
  `total` varchar(11) DEFAULT NULL,
  `quiz_of_10` varchar(11) DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `session` (`session`),
  KEY `subject` (`subject`),
  CONSTRAINT `{$prefix}continuous_assessment_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `{$prefix}students` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}continuous_assessment_ibfk_2` FOREIGN KEY (`session`) REFERENCES `{$prefix}sessions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}continuous_assessment_ibfk_3` FOREIGN KEY (`subject`) REFERENCES `{$prefix}class_subjects` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}departments`;
CREATE TABLE `{$prefix}departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `head` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}emergency_contacts`;
CREATE TABLE `{$prefix}emergency_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `surname` varchar(60) DEFAULT NULL,
  `first_name` varchar(60) DEFAULT NULL,
  `last_name` varchar(60) DEFAULT NULL,
  `phone_mobile` varchar(69) DEFAULT NULL,
  `phone_work` varchar(60) DEFAULT NULL,
  `subcity` varchar(60) DEFAULT NULL,
  `woreda` varchar(60) DEFAULT NULL,
  `house_number` varchar(60) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}events`;
CREATE TABLE `{$prefix}events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `starting_date` varchar(30) NOT NULL,
  `ending_date` varchar(30) DEFAULT NULL,
  `session` int(11) DEFAULT NULL,
  `class` int(11) DEFAULT NULL,
  `section` int(11) DEFAULT NULL,
  `public` int(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}exams`;
CREATE TABLE `{$prefix}exams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(254) NOT NULL,
  `starting_date` varchar(30) NOT NULL,
  `ending_date` varchar(30) NOT NULL,
  `session` int(11) NOT NULL,
  `semester` int(11) DEFAULT NULL,
  `class` int(11) DEFAULT NULL,
  `section` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `session` (`session`),
  KEY `class` (`class`),
  KEY `semester` (`semester`),
  KEY `section` (`section`),
  CONSTRAINT `{$prefix}exams_ibfk_1` FOREIGN KEY (`session`) REFERENCES `{$prefix}sessions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}exams_ibfk_2` FOREIGN KEY (`class`) REFERENCES `{$prefix}classes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}exams_ibfk_3` FOREIGN KEY (`semester`) REFERENCES `{$prefix}semesters` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}exams_ibfk_4` FOREIGN KEY (`section`) REFERENCES `{$prefix}sections` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}exams_timetable`;
CREATE TABLE `{$prefix}exams_timetable` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `exam` int(11) NOT NULL,
  `class` int(11) NOT NULL,
  `day` varchar(20) NOT NULL,
  `time` varchar(30) NOT NULL,
  `subject` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}exam_results`;
CREATE TABLE `{$prefix}exam_results` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `exam` int(11) NOT NULL,
  `student` bigint(20) NOT NULL,
  `class` int(11) NOT NULL,
  `subject` int(11) NOT NULL,
  `subject_name` varchar(40) DEFAULT NULL,
  `mark` int(11) NOT NULL,
  `grade` varchar(3) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `exam` (`exam`),
  KEY `class` (`class`),
  KEY `student` (`student`),
  KEY `subject` (`subject`),
  CONSTRAINT `{$prefix}exam_results_ibfk_1` FOREIGN KEY (`exam`) REFERENCES `{$prefix}exams` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}exam_results_ibfk_2` FOREIGN KEY (`class`) REFERENCES `{$prefix}classes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}exam_results_ibfk_3` FOREIGN KEY (`student`) REFERENCES `{$prefix}students` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}exam_results_ibfk_4` FOREIGN KEY (`subject`) REFERENCES `{$prefix}class_subjects` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}fees`;
CREATE TABLE `{$prefix}fees` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `session` int(11) NOT NULL,
  `semester` int(11) DEFAULT NULL,
  `class` int(11) DEFAULT NULL,
  `section` int(11) DEFAULT NULL,
  `student` bigint(20) DEFAULT NULL,
  `description` text NOT NULL,
  `amount` int(20) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `session` (`session`),
  KEY `student` (`student`),
  KEY `class` (`class`),
  KEY `section` (`section`),
  KEY `semester` (`semester`),
  CONSTRAINT `{$prefix}fees_ibfk_1` FOREIGN KEY (`session`) REFERENCES `{$prefix}sessions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}fees_ibfk_2` FOREIGN KEY (`student`) REFERENCES `{$prefix}students` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}fees_ibfk_3` FOREIGN KEY (`class`) REFERENCES `{$prefix}classes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}fees_ibfk_4` FOREIGN KEY (`section`) REFERENCES `{$prefix}sections` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}fees_ibfk_5` FOREIGN KEY (`semester`) REFERENCES `{$prefix}semesters` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}fee_payment`;
CREATE TABLE `{$prefix}fee_payment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `session` int(11) NOT NULL,
  `student` bigint(20) NOT NULL,
  `amount` int(30) unsigned NOT NULL,
  `date` varchar(30) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `session` (`session`),
  KEY `student` (`student`),
  CONSTRAINT `{$prefix}fee_payment_ibfk_1` FOREIGN KEY (`session`) REFERENCES `{$prefix}sessions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}fee_payment_ibfk_2` FOREIGN KEY (`student`) REFERENCES `{$prefix}students` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}files`;
CREATE TABLE `{$prefix}files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL,
  `uid` int(11) NOT NULL,
  `file` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}groups`;
CREATE TABLE `{$prefix}groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  `capabilities` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `{$prefix}groups` (`id`, `name`, `description`, `capabilities`) VALUES
(1,	'Administrator',	'Administrator',	NULL),
(2,	'Teachers',	'Teachers',	'{\"update_user\":\"1\",\"create_exam\":\"1\",\"create_exam_timetable\":\"1\",\"create_exam_results\":\"1\",\"record_cats\":\"1\"}'),
(3,	'Student',	'Students roles',	NULL),
(4,	'Parents',	'Parents',	NULL);

DROP TABLE IF EXISTS `{$prefix}lesson_plans`;
CREATE TABLE `{$prefix}lesson_plans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session` int(11) NOT NULL,
  `class` int(11) NOT NULL,
  `section` int(10) NOT NULL,
  `subject` int(11) NOT NULL,
  `month` int(3) DEFAULT NULL,
  `week` int(3) DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `duration` varchar(254) DEFAULT NULL,
  `day` text NOT NULL,
  `objectives` text NOT NULL,
  `intro` text DEFAULT NULL,
  `presentation` text DEFAULT NULL,
  `stabilization` text DEFAULT NULL,
  `evaluation` text DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `class` (`class`),
  KEY `section` (`section`),
  KEY `subject` (`subject`),
  KEY `session` (`session`),
  CONSTRAINT `{$prefix}lesson_plans_ibfk_1` FOREIGN KEY (`class`) REFERENCES `{$prefix}classes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}lesson_plans_ibfk_2` FOREIGN KEY (`section`) REFERENCES `{$prefix}sections` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}lesson_plans_ibfk_3` FOREIGN KEY (`subject`) REFERENCES `{$prefix}class_subjects` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}lesson_plans_ibfk_4` FOREIGN KEY (`session`) REFERENCES `{$prefix}sessions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}login_attempts`;
CREATE TABLE `{$prefix}login_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `{$prefix}messages`;
CREATE TABLE `{$prefix}messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `teacher` int(11) NOT NULL,
  `parent` bigint(20) unsigned DEFAULT NULL,
  `student` bigint(20) DEFAULT NULL,
  `for_student` bigint(20) NOT NULL,
  `direction` varchar(1) NOT NULL DEFAULT 'r' COMMENT 'in respect to teacher, r for receive, s for send',
  `message` text NOT NULL,
  `attachment` varchar(254) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `student` (`student`),
  KEY `teacher` (`teacher`),
  CONSTRAINT `{$prefix}messages_ibfk_1` FOREIGN KEY (`student`) REFERENCES `{$prefix}students` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}messages_ibfk_2` FOREIGN KEY (`teacher`) REFERENCES `{$prefix}teachers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}migrations`;
CREATE TABLE `{$prefix}migrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` text NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `{$prefix}notes`;
CREATE TABLE `{$prefix}notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` int(11) NOT NULL,
  `section` int(11) DEFAULT NULL,
  `subject` int(11) DEFAULT NULL,
  `name` varchar(254) NOT NULL,
  `description` text DEFAULT NULL,
  `file` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `class` (`class`),
  KEY `section` (`section`),
  KEY `subject` (`subject`),
  CONSTRAINT `{$prefix}notes_ibfk_1` FOREIGN KEY (`class`) REFERENCES `{$prefix}classes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}notes_ibfk_2` FOREIGN KEY (`section`) REFERENCES `{$prefix}sections` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}notes_ibfk_3` FOREIGN KEY (`subject`) REFERENCES `{$prefix}class_subjects` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}options`;
CREATE TABLE `{$prefix}options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meta_parent` varchar(254) DEFAULT NULL,
  `meta_key` varchar(254) NOT NULL,
  `meta_value` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `meta_key` (`meta_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `{$prefix}options` (`id`, `meta_parent`, `meta_key`, `meta_value`) VALUES
(1,	'system',	'_active_plugins',	'[]'),
(2,	'system',	'site_title',	'AYA School'),
(3,	NULL,	'timetable_framework',	'[{\"time\":\"08:00 - 09:00\",\"break\":false,\"label\":\"\"},{\"time\":\"09:00 - 10:00\",\"break\":false,\"label\":\"\"},{\"time\":\"10:00 - 10:30\",\"break\":true,\"label\":\"Mini Break\"},{\"time\":\"10:30 - 11:30\",\"break\":false,\"label\":\"\"},{\"time\":\"11:30 - 12:30\",\"break\":false,\"label\":\"\"},{\"time\":\"12:30 - 02:00\",\"break\":true,\"label\":\"Lunch Break\"}]'),
(4,	NULL,	'asp_timetable_framework',	'[{\"time\":\"08:00 - 09:00\",\"break\":false,\"label\":\"\"},{\"time\":\"09:00 - 10:00\",\"break\":true,\"label\":\"Refreshment\"},{\"time\":\"09:00 - 10:00\",\"break\":false,\"label\":\"\"}]');

DROP TABLE IF EXISTS `{$prefix}payments`;
CREATE TABLE `{$prefix}payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session` int(11) NOT NULL,
  `class` int(11) DEFAULT NULL,
  `section` int(11) DEFAULT NULL,
  `amount` bigint(20) NOT NULL,
  `description` text NOT NULL,
  `deadline` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `session` (`session`),
  KEY `class` (`class`),
  KEY `section` (`section`),
  CONSTRAINT `{$prefix}payments_ibfk_1` FOREIGN KEY (`session`) REFERENCES `{$prefix}sessions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}payments_ibfk_2` FOREIGN KEY (`class`) REFERENCES `{$prefix}classes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}payments_ibfk_3` FOREIGN KEY (`section`) REFERENCES `{$prefix}sections` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}requirements`;
CREATE TABLE `{$prefix}requirements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session` int(11) NOT NULL,
  `class` int(11) DEFAULT NULL,
  `section` int(11) DEFAULT NULL,
  `item` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `deadline` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `session` (`session`),
  KEY `class` (`class`),
  KEY `section` (`section`),
  CONSTRAINT `{$prefix}requirements_ibfk_1` FOREIGN KEY (`session`) REFERENCES `{$prefix}sessions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}requirements_ibfk_2` FOREIGN KEY (`class`) REFERENCES `{$prefix}classes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}requirements_ibfk_3` FOREIGN KEY (`section`) REFERENCES `{$prefix}sections` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}sections`;
CREATE TABLE `{$prefix}sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `maximum_students` int(10) NOT NULL DEFAULT 45,
  `advisor` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `class_id` (`class`),
  KEY `advisor` (`advisor`),
  CONSTRAINT `{$prefix}sections_ibfk_1` FOREIGN KEY (`class`) REFERENCES `{$prefix}classes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}sections_ibfk_2` FOREIGN KEY (`advisor`) REFERENCES `{$prefix}teachers` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}section_groups`;
CREATE TABLE `{$prefix}section_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` int(11) NOT NULL,
  `student` bigint(20) NOT NULL,
  `section` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `group` (`group`),
  KEY `student` (`student`),
  KEY `section` (`section`),
  CONSTRAINT `{$prefix}section_groups_ibfk_1` FOREIGN KEY (`group`) REFERENCES `{$prefix}class_groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}section_groups_ibfk_3` FOREIGN KEY (`student`) REFERENCES `{$prefix}students` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}section_groups_ibfk_4` FOREIGN KEY (`section`) REFERENCES `{$prefix}sections` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}semesters`;
CREATE TABLE `{$prefix}semesters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `opening_date` varchar(30) DEFAULT NULL,
  `closing_date` varchar(30) DEFAULT NULL,
  `active` int(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  KEY `id` (`id`),
  KEY `session` (`session`),
  CONSTRAINT `{$prefix}semesters_ibfk_3` FOREIGN KEY (`session`) REFERENCES `{$prefix}sessions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}sessions`;
CREATE TABLE `{$prefix}sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `active` int(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}students`;
CREATE TABLE `{$prefix}students` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `admission_number` varchar(30) NOT NULL,
  `session` int(11) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `class` int(11) DEFAULT NULL,
  `section` int(11) DEFAULT NULL,
  `parent` int(11) unsigned DEFAULT NULL,
  `contact` int(11) unsigned DEFAULT NULL,
  `active` int(1) DEFAULT 1,
  `admission_date` varchar(30) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `session` (`session`),
  KEY `user_id` (`user_id`),
  KEY `class` (`class`),
  KEY `section` (`section`),
  KEY `parent` (`parent`),
  KEY `contact` (`contact`),
  CONSTRAINT `{$prefix}students_ibfk_1` FOREIGN KEY (`session`) REFERENCES `{$prefix}sessions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}students_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `{$prefix}users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}students_ibfk_3` FOREIGN KEY (`class`) REFERENCES `{$prefix}classes` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}students_ibfk_4` FOREIGN KEY (`section`) REFERENCES `{$prefix}sections` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}students_ibfk_5` FOREIGN KEY (`parent`) REFERENCES `{$prefix}users` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}students_archives`;
CREATE TABLE `{$prefix}students_archives` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `admission_number` varchar(30) NOT NULL,
  `session` int(11) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `class` int(11) DEFAULT NULL,
  `section` int(11) DEFAULT NULL,
  `parent` int(11) unsigned DEFAULT NULL,
  `contact` int(11) unsigned DEFAULT NULL,
  `active` int(1) DEFAULT 1,
  `admission_date` varchar(30) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `session` (`session`),
  KEY `user_id` (`user_id`),
  KEY `class` (`class`),
  KEY `section` (`section`),
  KEY `parent` (`parent`),
  KEY `contact` (`contact`),
  CONSTRAINT `{$prefix}students_archives_ibfk_1` FOREIGN KEY (`session`) REFERENCES `{$prefix}sessions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}students_archives_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `{$prefix}users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}students_archives_ibfk_3` FOREIGN KEY (`class`) REFERENCES `{$prefix}classes` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}students_archives_ibfk_4` FOREIGN KEY (`section`) REFERENCES `{$prefix}sections` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}students_archives_ibfk_5` FOREIGN KEY (`parent`) REFERENCES `{$prefix}users` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}students_archives_ibfk_6` FOREIGN KEY (`contact`) REFERENCES `{$prefix}users` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}subjects`;
CREATE TABLE `{$prefix}subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `class` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}subject_departments`;
CREATE TABLE `{$prefix}subject_departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dept_id` int(11) NOT NULL,
  `subject` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `subject` (`subject`),
  KEY `dept_id` (`dept_id`),
  CONSTRAINT `{$prefix}subject_departments_ibfk_1` FOREIGN KEY (`subject`) REFERENCES `{$prefix}subjects` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}subject_departments_ibfk_2` FOREIGN KEY (`dept_id`) REFERENCES `{$prefix}departments` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}subject_teacher`;
CREATE TABLE `{$prefix}subject_teacher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `class_id` (`class_id`),
  KEY `section_id` (`section_id`),
  KEY `teacher_id` (`teacher_id`),
  KEY `subject_id` (`subject_id`),
  CONSTRAINT `{$prefix}subject_teacher_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `{$prefix}classes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}subject_teacher_ibfk_2` FOREIGN KEY (`section_id`) REFERENCES `{$prefix}sections` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}subject_teacher_ibfk_4` FOREIGN KEY (`teacher_id`) REFERENCES `{$prefix}teachers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}subject_teacher_ibfk_5` FOREIGN KEY (`subject_id`) REFERENCES `{$prefix}class_subjects` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}teachers`;
CREATE TABLE `{$prefix}teachers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `teacher_number` varchar(30) DEFAULT NULL,
  `admission_date` varchar(30) DEFAULT NULL,
  `contact` int(11) DEFAULT NULL,
  `active` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `contact` (`contact`),
  CONSTRAINT `{$prefix}teachers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `{$prefix}users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}teachers_ibfk_2` FOREIGN KEY (`contact`) REFERENCES `{$prefix}emergency_contacts` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}timetable`;
CREATE TABLE `{$prefix}timetable` (
  `id` bigint(30) NOT NULL AUTO_INCREMENT,
  `class` int(11) NOT NULL,
  `section` int(11) NOT NULL,
  `day` varchar(20) NOT NULL,
  `time` varchar(40) NOT NULL,
  `subject` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `class` (`class`),
  KEY `section` (`section`),
  KEY `subject` (`subject`),
  CONSTRAINT `{$prefix}timetable_ibfk_1` FOREIGN KEY (`class`) REFERENCES `{$prefix}classes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}timetable_ibfk_2` FOREIGN KEY (`section`) REFERENCES `{$prefix}sections` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}timetable_ibfk_3` FOREIGN KEY (`subject`) REFERENCES `{$prefix}class_subjects` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}transport_routes`;
CREATE TABLE `{$prefix}transport_routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `driver_name` varchar(254) NOT NULL,
  `driver_phone` varchar(254) NOT NULL,
  `licence_plate` varchar(60) DEFAULT NULL,
  `route` text NOT NULL,
  `price` int(10) NOT NULL,
  `active` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}users`;
CREATE TABLE `{$prefix}users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(254) NOT NULL,
  `activation_selector` varchar(255) DEFAULT NULL,
  `activation_code` varchar(255) DEFAULT NULL,
  `forgotten_password_selector` varchar(255) DEFAULT NULL,
  `forgotten_password_code` varchar(255) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_selector` varchar(255) DEFAULT NULL,
  `remember_code` varchar(255) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `surname` varchar(50) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_email` (`email`),
  UNIQUE KEY `uc_activation_selector` (`activation_selector`),
  UNIQUE KEY `uc_forgotten_password_selector` (`forgotten_password_selector`),
  UNIQUE KEY `uc_remember_selector` (`remember_selector`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `{$prefix}usersmeta`;
CREATE TABLE `{$prefix}usersmeta` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `meta_key` varchar(60) NOT NULL,
  `meta_value` text NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  CONSTRAINT `{$prefix}usersmeta_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `{$prefix}users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `{$prefix}users_groups`;
CREATE TABLE `{$prefix}users_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  KEY `fk_users_groups_users1_idx` (`user_id`),
  KEY `fk_users_groups_groups1_idx` (`group_id`),
  CONSTRAINT `{$prefix}users_groups_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `{$prefix}users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `{$prefix}users_groups_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `{$prefix}groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2020-05-21 09:28:54
EOL;
//$sql = str_replace(array("\r", "\n"), '', $sql);
//echo $sql;
//exit;
$SUCCESS = TRUE;
$X = mysqli_multi_query($conn, $sql);
do {
    if ($result = mysqli_store_result($conn)) {
        $result->free();
    }

} while (mysqli_more_results($conn) && mysqli_next_result($conn));

if (!$X) {
    $SUCCESS = FALSE;
    $ERROR = "Failed to set up database: " . mysqli_error($conn);
} else {
    //Create the user
    mysqli_close($conn);
    $conn = mysqli_connect($_SESSION['database']['server'], $_SESSION['database']['username'], $_SESSION['database']['password'], $_SESSION['database']['database']);
    $sql = "INSERT INTO `{$prefix}users` (ip_address, username, password, email, created_on, first_name, last_name) VALUES ('127.0.0.1', '".mysqli_escape_string($conn, $_SESSION['user']['email'])."', '".mysqli_escape_string($conn, $password)."', '".mysqli_escape_string($conn, $_SESSION['user']['email'])."', '{$time}', '".mysqli_escape_string($conn, $_SESSION['user']['fname'])."', '".mysqli_escape_string($conn, $_SESSION['user']['lname'])."');";
    $sql .= "INSERT INTO `{$prefix}users_groups` (user_id, group_id) VALUES (1, 1);";
    $X = TRUE;
    $X = mysqli_multi_query($conn, $sql);
    do {
        if ($result = mysqli_store_result($conn)) {
            $result->free();
        }

    } while (mysqli_more_results($conn) && mysqli_next_result($conn));
    if(!$X) {
        $SUCCESS = FALSE;
        $ERROR = "Failed to set up Admin Account: " . mysqli_error($conn);
    } else {
        $url = $_SESSION['user']['url'];
        $contents = "<?php if (!defined('BASEPATH')) exit('No direct access'); ?>
#Modify this file IF you know what you are doing

CI_ENVIRONMENT = production
app.baseURL = ".$url."
app.indexPage =
encryption.key = ".generateRandomString(32)."

database.default.hostname = ".$_SESSION['database']['server']."
database.default.database = ".$_SESSION['database']['database']."
database.default.username = ".$_SESSION['database']['username']."
database.default.password = ".$_SESSION['database']['password']."
database.default.DBDriver = MySQLi
database.default.DBPrefix = ".$prefix;
        if(file_put_contents('../env.php', $contents, LOCK_EX)) {
            $WRITE = TRUE;
        } else {
            $WRITE = FALSE;
        }
    }
}

include "header.php";

if ($SUCCESS) {
    session_destroy();
    ?>
    <div class="login">
        <div class="auth-heading mt-15">
            <h2 class="text-center">Setup Complete</h2>
        </div>
        <div class="auth-form">
            <h3>Congratulations! Your dashboard is now installed!</h3>
            <?php
            if(!$WRITE) {
                ?>
                <div class="alert alert-warning">Failed to write to configuration file. Please copy the following and paste it in <code><?php echo dirname(dirname(__FILE__)).'/env.php'; ?></code></div>
                <textarea class="form-control" readonly="readonly" rows="15"><?php echo $contents; ?></textarea>
                <?php
            }
            ?>
            <p>
                Click the button below to login using the details you have set up.
            </p>
            <div>
                <a class="btn btn-lg btn-primary" href="<?php echo $url; ?>">Proceed to Login</a>
            </div>
        </div>
    </div>
    <?php
} else {
    ?>
    <div class="login">
        <div class="auth-heading mt-15">
            <h2 class="text-center">Setup Failed</h2>
        </div>
        <div class="auth-form">
            <p>
                A problem occured
            </p>
            <div class="alert alert-danger">
                <?php echo $ERROR; ?>
            </div>
            <a class="btn btn-lg btn-danger" href="">Retry</a>
            <a class="btn btn-lg btn-success" href="database.php">Start Over</a>
        </div>
    </div>
    <?php
}

include 'footer.php';