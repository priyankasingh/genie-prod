-- phpMyAdmin SQL Dump
-- version 4.2.5
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Feb 12, 2015 at 01:52 PM
-- Server version: 5.5.40
-- PHP Version: 5.5.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `eugenie`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(45) NOT NULL,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `description` text,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` datetime DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- Table structure for table `categories_responses_statements`
--

CREATE TABLE IF NOT EXISTS `categories_responses_statements` (
`id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `responses_statement_id` int(10) unsigned NOT NULL,
  `weighting` tinyint(3) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=774 ;

-- --------------------------------------------------------

--
-- Table structure for table `categories_services`
--

CREATE TABLE IF NOT EXISTS `categories_services` (
`id` int(10) unsigned NOT NULL,
  `service_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1439 ;

-- --------------------------------------------------------

--
-- Table structure for table `categories_statements`
--

CREATE TABLE IF NOT EXISTS `categories_statements` (
`id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `statement_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Table structure for table `conditions`
--

CREATE TABLE IF NOT EXISTS `conditions` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(45) NOT NULL,
  `category_id` int(10) unsigned DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `conditions_responses`
--

CREATE TABLE IF NOT EXISTS `conditions_responses` (
`id` int(10) unsigned NOT NULL,
  `condition_id` int(10) unsigned NOT NULL,
  `response_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=219 ;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
`id` int(11) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `question` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `favourites`
--

CREATE TABLE IF NOT EXISTS `favourites` (
`id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `service_id` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- Table structure for table `i18n`
--

CREATE TABLE IF NOT EXISTS `i18n` (
`id` int(10) NOT NULL,
  `locale` varchar(6) NOT NULL,
  `model` varchar(255) NOT NULL,
  `foreign_key` int(10) NOT NULL,
  `field` varchar(255) NOT NULL,
  `content` text
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=772 ;

-- --------------------------------------------------------

--
-- Table structure for table `network_categories`
--

CREATE TABLE IF NOT EXISTS `network_categories` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` datetime DEFAULT NULL,
  `parent_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

--
-- Table structure for table `network_members`
--

CREATE TABLE IF NOT EXISTS `network_members` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `frequency` enum('daily','weekly','monthly','less') NOT NULL DEFAULT 'less',
  `diagram_x` smallint(5) unsigned DEFAULT '0',
  `diagram_y` smallint(5) unsigned DEFAULT '0',
  `other` varchar(200) DEFAULT NULL,
  `Interests` text,
  `network_category_id` int(10) unsigned NOT NULL,
  `response_id` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=659 ;

-- --------------------------------------------------------

--
-- Table structure for table `network_types`
--

CREATE TABLE IF NOT EXISTS `network_types` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `ruleset` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `content` text,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `responses`
--

CREATE TABLE IF NOT EXISTS `responses` (
`id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `title` char(5) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `age` char(5) DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `marital_status` char(1) DEFAULT NULL,
  `postcode` char(9) NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `telephone` char(15) DEFAULT NULL,
  `network_type` enum('diverse','family_friend_centered','friend_centered','family_centered','family_friend_supported','friend_supported','family_supported','isolated','highly_isolated') DEFAULT NULL,
  `network_type_id` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=210 ;

-- --------------------------------------------------------

--
-- Table structure for table `responses_statements`
--

CREATE TABLE IF NOT EXISTS `responses_statements` (
`id` int(10) unsigned NOT NULL,
  `response_id` int(10) unsigned NOT NULL,
  `statement_id` int(10) unsigned NOT NULL,
  `weighting` tinyint(3) unsigned NOT NULL DEFAULT '1'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1904 ;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE IF NOT EXISTS `services` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `contact_name` varchar(200) DEFAULT NULL,
  `address_1` varchar(200) DEFAULT NULL,
  `address_2` varchar(200) DEFAULT NULL,
  `address_3` varchar(200) DEFAULT NULL,
  `town` varchar(200) DEFAULT NULL,
  `postcode` char(9) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `url` varchar(200) DEFAULT NULL,
  `description` text,
  `time_details` varchar(200) DEFAULT NULL,
  `twitter` varchar(45) DEFAULT NULL,
  `facebook_url` varchar(200) DEFAULT NULL,
  `age_lower` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `age_upper` tinyint(3) unsigned NOT NULL DEFAULT '150',
  `gender_m` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `gender_f` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL,
  `lang` char(3) NOT NULL DEFAULT 'eng',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` datetime DEFAULT NULL,
  `slug` varchar(300) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=770 ;

-- --------------------------------------------------------

--
-- Table structure for table `statements`
--

CREATE TABLE IF NOT EXISTS `statements` (
`id` int(10) unsigned NOT NULL,
  `statement` varchar(200) NOT NULL,
  `description` text,
  `order` int(10) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`id` int(10) unsigned NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` char(40) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `forgot_password_key` char(20) DEFAULT NULL,
  `role` char(1) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=69 ;

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE IF NOT EXISTS `videos` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `service_id` int(10) unsigned NOT NULL,
  `url` varchar(200) NOT NULL,
  `embed_code` text NOT NULL,
  `thumb_url` varchar(200) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=296 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
 ADD PRIMARY KEY (`id`), ADD KEY `parent_idx` (`parent_id`);

--
-- Indexes for table `categories_responses_statements`
--
ALTER TABLE `categories_responses_statements`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_responses_categories_categories1_idx` (`category_id`), ADD KEY `fk_categories_responses_statements_responses_statements1_idx` (`responses_statement_id`);

--
-- Indexes for table `categories_services`
--
ALTER TABLE `categories_services`
 ADD PRIMARY KEY (`id`), ADD KEY `category_idx` (`category_id`), ADD KEY `service_idx` (`service_id`);

--
-- Indexes for table `categories_statements`
--
ALTER TABLE `categories_statements`
 ADD PRIMARY KEY (`id`), ADD KEY `category_idx` (`category_id`), ADD KEY `statement_idx` (`statement_id`);

--
-- Indexes for table `conditions`
--
ALTER TABLE `conditions`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_conditions_categories1_idx` (`category_id`);

--
-- Indexes for table `conditions_responses`
--
ALTER TABLE `conditions_responses`
 ADD PRIMARY KEY (`id`), ADD KEY `cr_condition_idx` (`condition_id`), ADD KEY `cr_response_idx` (`response_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favourites`
--
ALTER TABLE `favourites`
 ADD PRIMARY KEY (`id`), ADD KEY `user_idx` (`user_id`), ADD KEY `service_idx` (`service_id`);

--
-- Indexes for table `i18n`
--
ALTER TABLE `i18n`
 ADD PRIMARY KEY (`id`), ADD KEY `locale` (`locale`), ADD KEY `model` (`model`), ADD KEY `row_id` (`foreign_key`), ADD KEY `field` (`field`);

--
-- Indexes for table `network_categories`
--
ALTER TABLE `network_categories`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_network_categories_network_categories1_idx` (`parent_id`);

--
-- Indexes for table `network_members`
--
ALTER TABLE `network_members`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_contacts_contact_categories1_idx` (`network_category_id`), ADD KEY `fk_contacts_responses1_idx` (`response_id`);

--
-- Indexes for table `network_types`
--
ALTER TABLE `network_types`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `unique_ruleset` (`ruleset`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `responses`
--
ALTER TABLE `responses`
 ADD PRIMARY KEY (`id`), ADD KEY `user_idx` (`user_id`), ADD KEY `fk_responses_network_types1_idx` (`network_type_id`);

--
-- Indexes for table `responses_statements`
--
ALTER TABLE `responses_statements`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `no_mixtures` (`response_id`,`statement_id`), ADD KEY `statement_id_idx` (`statement_id`), ADD KEY `response_idx` (`response_id`), ADD KEY `order` (`weighting`,`response_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `statements`
--
ALTER TABLE `statements`
 ADD PRIMARY KEY (`id`), ADD KEY `ordering` (`order`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_videos_services1_idx` (`service_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `categories_responses_statements`
--
ALTER TABLE `categories_responses_statements`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=774;
--
-- AUTO_INCREMENT for table `categories_services`
--
ALTER TABLE `categories_services`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1439;
--
-- AUTO_INCREMENT for table `categories_statements`
--
ALTER TABLE `categories_statements`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `conditions`
--
ALTER TABLE `conditions`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `conditions_responses`
--
ALTER TABLE `conditions_responses`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=219;
--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `favourites`
--
ALTER TABLE `favourites`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `i18n`
--
ALTER TABLE `i18n`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=772;
--
-- AUTO_INCREMENT for table `network_categories`
--
ALTER TABLE `network_categories`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `network_members`
--
ALTER TABLE `network_members`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=659;
--
-- AUTO_INCREMENT for table `network_types`
--
ALTER TABLE `network_types`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `responses`
--
ALTER TABLE `responses`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=210;
--
-- AUTO_INCREMENT for table `responses_statements`
--
ALTER TABLE `responses_statements`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1904;
--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=770;
--
-- AUTO_INCREMENT for table `statements`
--
ALTER TABLE `statements`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=69;
--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=296;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
ADD CONSTRAINT `c_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `categories_responses_statements`
--
ALTER TABLE `categories_responses_statements`
ADD CONSTRAINT `fk_categories_responses_statements_responses_statements1` FOREIGN KEY (`responses_statement_id`) REFERENCES `responses_statements` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_responses_categories_categories1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `categories_services`
--
ALTER TABLE `categories_services`
ADD CONSTRAINT `cs_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `cs_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `categories_statements`
--
ALTER TABLE `categories_statements`
ADD CONSTRAINT `cst_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `cst_statement` FOREIGN KEY (`statement_id`) REFERENCES `statements` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `conditions`
--
ALTER TABLE `conditions`
ADD CONSTRAINT `fk_conditions_categories1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `conditions_responses`
--
ALTER TABLE `conditions_responses`
ADD CONSTRAINT `cr_condition` FOREIGN KEY (`condition_id`) REFERENCES `conditions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `cr_response` FOREIGN KEY (`response_id`) REFERENCES `responses` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `favourites`
--
ALTER TABLE `favourites`
ADD CONSTRAINT `f_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `f_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `network_categories`
--
ALTER TABLE `network_categories`
ADD CONSTRAINT `fk_network_categories_network_categories1` FOREIGN KEY (`parent_id`) REFERENCES `network_categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `network_members`
--
ALTER TABLE `network_members`
ADD CONSTRAINT `fk_nwm_contact_categories1` FOREIGN KEY (`network_category_id`) REFERENCES `network_categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_nwm_responses1` FOREIGN KEY (`response_id`) REFERENCES `responses` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `responses`
--
ALTER TABLE `responses`
ADD CONSTRAINT `fk_responses_network_types1` FOREIGN KEY (`network_type_id`) REFERENCES `network_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `r_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `responses_statements`
--
ALTER TABLE `responses_statements`
ADD CONSTRAINT `rs_response` FOREIGN KEY (`response_id`) REFERENCES `responses` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `rs_statement` FOREIGN KEY (`statement_id`) REFERENCES `statements` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `videos`
--
ALTER TABLE `videos`
ADD CONSTRAINT `fk_videos_services1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
