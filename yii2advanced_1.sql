-- phpMyAdmin SQL Dump
-- version 4.4.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2015-07-10 17:13:18
-- 服务器版本： 5.5.43-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `yii2advanced`
--

-- --------------------------------------------------------

--
-- 表的结构 `node_ad`
--

CREATE TABLE IF NOT EXISTS `node_ad` (
  `id` int(11) NOT NULL COMMENT 'id',
  `node_id` int(11) NOT NULL COMMENT '节点',
  `content` text NOT NULL COMMENT '广告内容',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '启用'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `node_link`
--

CREATE TABLE IF NOT EXISTS `node_link` (
  `id` int(11) NOT NULL COMMENT 'id',
  `node_id` int(11) NOT NULL COMMENT '节点',
  `content` text NOT NULL COMMENT '链接正文',
  `status` tinyint(1) DEFAULT '0' COMMENT '启用'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `tab_ad`
--

CREATE TABLE IF NOT EXISTS `tab_ad` (
  `id` int(11) NOT NULL COMMENT 'id',
  `tab_id` int(11) NOT NULL COMMENT 'tab',
  `content` text NOT NULL COMMENT '广告内容',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '启用'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `node_ad`
--
ALTER TABLE `node_ad`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `node_link`
--
ALTER TABLE `node_link`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tab_ad`
--
ALTER TABLE `tab_ad`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `node_ad`
--
ALTER TABLE `node_ad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';
--
-- AUTO_INCREMENT for table `node_link`
--
ALTER TABLE `node_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';
--
-- AUTO_INCREMENT for table `tab_ad`
--
ALTER TABLE `tab_ad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
