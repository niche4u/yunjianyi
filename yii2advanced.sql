-- phpMyAdmin SQL Dump
-- version 4.4.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2015-08-04 18:30:47
-- 服务器版本： 5.5.43-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yunjianyi`
--

-- --------------------------------------------------------

--
-- 表的结构 `follow`
--

CREATE TABLE IF NOT EXISTS `follow` (
  `user_id` int(11) NOT NULL COMMENT '用户',
  `follow_id` int(11) NOT NULL COMMENT '关注了',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1用户;2节点;3主题'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL COMMENT '菜单名称',
  `route` varchar(256) DEFAULT NULL COMMENT '访问路由',
  `order` int(11) DEFAULT NULL COMMENT '排序',
  `data` text COMMENT '图标'
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `menu`
--

INSERT INTO `menu` (`id`, `name`, `route`, `order`, `data`) VALUES
(3, '菜单管理', '/menu/index', 20, '<i class="fa fa-tags"></i>'),
(4, '建议管理', '/topic/index', 1, '<i class="fa fa-pencil"></i>'),
(5, '回复管理', '/reply/index', 4, '<i class="fa fa-reply"></i>'),
(6, '用户管理', '/user/index', 3, '<i class="fa fa-user"></i>'),
(7, '节点管理', '/node/index', 4, '<i class="fa fa-tags"></i>'),
(8, 'TAB管理', '/tab/index', 5, '<i class="fa fa-navicon"></i>'),
(10, '清除缓存', '/site/clean-cache', 100, '<i class="fa fa-trash"></i>'),
(12, 'TAB右侧菜单', '/tab-nav/index', 19, '<i class="fa fa-server"></i>'),
(13, '建议正文管理', '/topic-content/index', 2, '<i class="fa fa-pencil"></i>'),
(14, '节点广告管理', '/node-ad/index', 5, '<i class="fa fa-buysellads"></i>'),
(15, 'tab广告管理', '/tab-ad/index', 5, '<i class="fa fa-buysellads"></i>'),
(16, '节点推荐链接管理', '/node-link/index', 5, '<i class="fa fa-buysellads"></i>'),
(17, '后台首页', '/site/index', 0, '<i class="fa fa-dashboard"></i>');

-- --------------------------------------------------------

--
-- 表的结构 `node`
--

CREATE TABLE IF NOT EXISTS `node` (
  `id` int(11) NOT NULL COMMENT '节点ID',
  `tab_id` int(11) DEFAULT NULL COMMENT '所属tab',
  `name` varchar(50) NOT NULL COMMENT '节点名称',
  `enname` varchar(45) NOT NULL COMMENT '英文名称',
  `parent_id` int(11) DEFAULT NULL COMMENT '父节点',
  `desc` varchar(150) NOT NULL COMMENT '节点描述',
  `logo` varchar(30) NOT NULL COMMENT 'logo',
  `is_hidden` tinyint(1) DEFAULT '0' COMMENT '是否隐藏节点',
  `need_login` tinyint(1) NOT NULL DEFAULT '0' COMMENT '需要登陆',
  `bg` varchar(30) DEFAULT NULL COMMENT '背景图',
  `use_bg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '启用背景图片',
  `bg_color` varchar(20) DEFAULT NULL COMMENT '背景颜色',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  `created` int(11) NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
-- 表的结构 `notice`
--

CREATE TABLE IF NOT EXISTS `notice` (
  `id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL COMMENT '主题id',
  `from_user_id` int(11) NOT NULL COMMENT '来自用户',
  `to_user_id` int(11) NOT NULL COMMENT '传至用户',
  `msg` text COMMENT '消息内容',
  `is_read` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已读',
  `type` tinyint(1) DEFAULT NULL COMMENT '1:评论;2:评论@;3:关注了主题',
  `created` int(11) NOT NULL COMMENT '创建时间'
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `reply`
--

CREATE TABLE IF NOT EXISTS `reply` (
  `id` int(11) NOT NULL COMMENT '评论ID',
  `user_id` int(11) NOT NULL COMMENT '评论者',
  `topic_id` int(11) NOT NULL COMMENT '主题',
  `content` text NOT NULL COMMENT '评论内容',
  `created` int(11) NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `tab`
--

CREATE TABLE IF NOT EXISTS `tab` (
  `id` int(11) NOT NULL COMMENT 'tab id',
  `name` varchar(20) NOT NULL COMMENT 'tab名称',
  `enname` varchar(20) NOT NULL COMMENT '英文名称',
  `bg` varchar(30) DEFAULT NULL COMMENT '背景图',
  `bg_color` varchar(20) DEFAULT NULL COMMENT '背景颜色',
  `use_bg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '启用背景图',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态'
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

-- --------------------------------------------------------

--
-- 表的结构 `tab_nav`
--

CREATE TABLE IF NOT EXISTS `tab_nav` (
  `tab_id` int(11) NOT NULL COMMENT 'tab id',
  `name` varchar(25) NOT NULL COMMENT '名称',
  `link` varchar(20) NOT NULL COMMENT '链接',
  `sort` int(11) NOT NULL COMMENT '排序'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `topic`
--

CREATE TABLE IF NOT EXISTS `topic` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `title` varchar(300) NOT NULL COMMENT '主题标题',
  `user_id` int(11) NOT NULL COMMENT '作者',
  `node_id` int(11) NOT NULL COMMENT '节点',
  `need_login` tinyint(1) NOT NULL DEFAULT '0' COMMENT '需要登录',
  `click` int(11) NOT NULL DEFAULT '0' COMMENT '点击数',
  `follow` int(11) NOT NULL DEFAULT '0' COMMENT '收藏人数',
  `reply` int(11) NOT NULL DEFAULT '0' COMMENT '回复数',
  `last_reply_user` varchar(255) DEFAULT NULL COMMENT '最后回复',
  `last_reply_time` int(11) DEFAULT NULL COMMENT '最后回复时间',
  `updated_at` int(11) NOT NULL COMMENT '最后更新',
  `created` int(11) NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `topic_content`
--

CREATE TABLE IF NOT EXISTS `topic_content` (
  `id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL COMMENT '主题ID',
  `content` text NOT NULL COMMENT '主题正文',
  `is_append` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否追加',
  `created` int(11) NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL COMMENT '用户名',
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(255) NOT NULL COMMENT '密码',
  `password_reset_token` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL COMMENT '邮箱',
  `email_verify_token` varchar(256) DEFAULT NULL COMMENT '邮箱激活token',
  `avatar` varchar(200) NOT NULL DEFAULT 'default.png' COMMENT '头像',
  `role` smallint(6) NOT NULL DEFAULT '10',
  `status` smallint(6) NOT NULL DEFAULT '10',
  `email_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '邮箱激活',
  `desc` varchar(200) DEFAULT NULL COMMENT '一句话介绍',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `email_verify_token`, `avatar`, `role`, `status`, `email_status`, `desc`, `created_at`, `updated_at`) VALUES
(1, 'admin', '85e7ahcThR1Btqu-wqfouYVIBfHwtd3a', '$2y$13$F9n7tFBSOy35PjDZe7hHYOS/h4omRZlGxYpUGPrdANV5fB1CC0qL.', NULL, 'admin@yourdomain.com', 'h_DDbTznUkIkjqO8JRRTszUrkHtzwvwC_1435751017', 'default.png', 20, 1, 1, 'yunjianyi', 1435749537, 1436710272);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `follow`
--
ALTER TABLE `follow`
  ADD PRIMARY KEY (`user_id`,`follow_id`,`type`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `node`
--
ALTER TABLE `node`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `tab_id` (`tab_id`);

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
-- Indexes for table `notice`
--
ALTER TABLE `notice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `to_user_id` (`to_user_id`,`is_read`);

--
-- Indexes for table `reply`
--
ALTER TABLE `reply`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic_id` (`topic_id`);

--
-- Indexes for table `tab`
--
ALTER TABLE `tab`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tab_ad`
--
ALTER TABLE `tab_ad`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tab_nav`
--
ALTER TABLE `tab_nav`
  ADD PRIMARY KEY (`tab_id`);

--
-- Indexes for table `topic`
--
ALTER TABLE `topic`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `topic_content`
--
ALTER TABLE `topic_content`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic_id` (`topic_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `node`
--
ALTER TABLE `node`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '节点ID';
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
-- AUTO_INCREMENT for table `notice`
--
ALTER TABLE `notice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `reply`
--
ALTER TABLE `reply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '评论ID';
--
-- AUTO_INCREMENT for table `tab`
--
ALTER TABLE `tab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'tab id';
--
-- AUTO_INCREMENT for table `tab_ad`
--
ALTER TABLE `tab_ad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';
--
-- AUTO_INCREMENT for table `topic`
--
ALTER TABLE `topic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID';
--
-- AUTO_INCREMENT for table `topic_content`
--
ALTER TABLE `topic_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- 限制导出的表
--

--
-- 限制表 `node`
--
ALTER TABLE `node`
  ADD CONSTRAINT `node_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `node` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `node_ibfk_2` FOREIGN KEY (`tab_id`) REFERENCES `tab` (`id`);

--
-- 限制表 `reply`
--
ALTER TABLE `reply`
  ADD CONSTRAINT `reply_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`id`) ON DELETE CASCADE;

--
-- 限制表 `topic_content`
--
ALTER TABLE `topic_content`
  ADD CONSTRAINT `topic_content_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
