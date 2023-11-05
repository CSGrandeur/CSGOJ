-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- 主机： db
-- 生成日期： 2023-03-22 03:04:08
-- 服务器版本： 8.0.30
-- PHP 版本： 8.0.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


-- --------------------------------------------------------

--
-- 表的结构 `compileinfo`
--

CREATE TABLE `compileinfo` (
  `solution_id` int NOT NULL DEFAULT '0',
  `error` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- 表的结构 `contest`
--

CREATE TABLE `contest` (
  `contest_id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `defunct` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'N',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `private` tinyint NOT NULL DEFAULT '0',
  `langmask` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'bits for LANG to mask',
  `password` char(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `attach` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '',
  `topteam` int NOT NULL DEFAULT '1',
  `award_ratio` int NOT NULL DEFAULT '20015010' COMMENT '获奖比例',
  `frozen_minute` int NOT NULL DEFAULT '-1' COMMENT '封榜分钟数',
  `frozen_after` int NOT NULL DEFAULT '-1' COMMENT '结束后持续封榜分钟数'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- 表的结构 `contest_balloon`
--

CREATE TABLE `contest_balloon` (
  `contest_id` int NOT NULL,
  `problem_id` int NOT NULL,
  `team_id` varchar(64) NOT NULL,
  `room` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ac_time` int NOT NULL,
  `pst` tinyint NOT NULL COMMENT 'problem status，2 ac、3 fb',
  `bst` tinyint NOT NULL COMMENT 'balloon status, 4分配,5已发',
  `balloon_sender` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='比赛的气球任务管理表';

-- --------------------------------------------------------

--
-- 表的结构 `contest_md`
--

CREATE TABLE `contest_md` (
  `contest_id` int NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- 表的结构 `contest_print`
--

CREATE TABLE `contest_print` (
  `print_id` int NOT NULL,
  `contest_id` int DEFAULT NULL,
  `team_id` char(48) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `source` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `print_status` tinyint DEFAULT '0',
  `in_date` datetime NOT NULL,
  `ip` char(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `code_length` int NOT NULL DEFAULT '0',
  `room` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- 表的结构 `contest_problem`
--

CREATE TABLE `contest_problem` (
  `problem_id` int NOT NULL DEFAULT '0',
  `contest_id` int DEFAULT NULL,
  `title` char(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `num` int NOT NULL DEFAULT '0',
  `pscore` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- 表的结构 `contest_topic`
--

CREATE TABLE `contest_topic` (
  `topic_id` int NOT NULL,
  `user_id` varchar(48) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `reply` int DEFAULT '0' COMMENT '正数回复的topic_id，负数被回复次数',
  `public_show` tinyint DEFAULT '0',
  `contest_id` int NOT NULL DEFAULT '-1',
  `in_date` datetime DEFAULT NULL,
  `problem_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- 表的结构 `cpc_team`
--

CREATE TABLE `cpc_team` (
  `team_id` varchar(48) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `contest_id` int NOT NULL,
  `defunct` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'N',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `tmember` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `tkind` tinyint NOT NULL DEFAULT 0 COMMENT '“常规”（0）、“女队”（1）、“打星”（2） ',
  `coach` varchar(48) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `school` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `room` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `privilege` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT '账号权限',
  `team_global_code` varchar(66) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'default'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- 表的结构 `custominput`
--

CREATE TABLE `custominput` (
  `solution_id` int NOT NULL DEFAULT '0',
  `input_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- 表的结构 `loginlog`
--

CREATE TABLE `loginlog` (
  `user_id` varchar(48) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ip` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- 表的结构 `mail`
--

CREATE TABLE `mail` (
  `mail_id` int NOT NULL,
  `to_user` varchar(48) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'user_id',
  `from_user` varchar(48) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'user_id',
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `new_mail` tinyint(1) NOT NULL DEFAULT '1',
  `reply` int DEFAULT '-1',
  `in_date` datetime DEFAULT NULL,
  `defunct` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- 表的结构 `news`
--

CREATE TABLE `news` (
  `news_id` int NOT NULL,
  `user_id` varchar(48) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'user_id',
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `content` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `time` datetime DEFAULT NULL,
  `importance` tinyint NOT NULL DEFAULT '0',
  `defunct` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'N',
  `tags` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `category` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `modify_user_id` varchar(48) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `attach` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- 表的结构 `news_md`
--

CREATE TABLE `news_md` (
  `news_id` int NOT NULL,
  `content` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- 表的结构 `news_tag`
--

CREATE TABLE `news_tag` (
  `news_id` int DEFAULT NULL,
  `tag` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- 表的结构 `privilege`
--

CREATE TABLE `privilege` (
  `privilege_id` int NOT NULL,
  `user_id` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `rightstr` char(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `defunct` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- 表的结构 `problem`
--

CREATE TABLE `problem` (
  `problem_id` int NOT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `input` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `output` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `sample_input` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `sample_output` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `spj` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `hint` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `source` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `in_date` datetime DEFAULT NULL,
  `time_limit` double NOT NULL DEFAULT '0',
  `memory_limit` int NOT NULL DEFAULT '0',
  `defunct` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'N',
  `accepted` int DEFAULT '0',
  `submit` int DEFAULT '0',
  `solved` int DEFAULT '0',
  `author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `attach` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- 表的结构 `problem_md`
--

CREATE TABLE `problem_md` (
  `problem_id` int NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `input` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `output` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `hint` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `source` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- 表的结构 `regcontest`
--

CREATE TABLE `regcontest` (
  `regcontest_id` int NOT NULL,
  `contest_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `contest_start` datetime DEFAULT NULL,
  `contest_end` datetime DEFAULT NULL,
  `contest_startreg` datetime DEFAULT NULL,
  `contest_endreg` datetime DEFAULT NULL,
  `contest_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `contest_description_md` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `defunct` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'N',
  `contest_kind` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `contest_pass` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT '比赛加密',
  `form_require` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- 表的结构 `reply`
--

CREATE TABLE `reply` (
  `rid` int NOT NULL,
  `author_id` varchar(48) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'user_id',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `topic_id` int NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `ip` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- 表的结构 `runtimeinfo`
--

CREATE TABLE `runtimeinfo` (
  `solution_id` int NOT NULL DEFAULT '0',
  `error` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- 表的结构 `sim`
--

CREATE TABLE `sim` (
  `s_id` int NOT NULL,
  `sim_s_id` int DEFAULT NULL,
  `sim` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- 表的结构 `solution`
--

CREATE TABLE `solution` (
  `solution_id` int NOT NULL,
  `problem_id` int NOT NULL DEFAULT '0',
  `user_id` char(48) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nick` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `time` int NOT NULL DEFAULT '0',
  `memory` int NOT NULL DEFAULT '0',
  `in_date` datetime NOT NULL DEFAULT '2016-05-13 19:24:00',
  `result` smallint NOT NULL DEFAULT '0',
  `language` int UNSIGNED NOT NULL DEFAULT '0',
  `ip` char(46) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `contest_id` int DEFAULT '0',
  `valid` tinyint NOT NULL DEFAULT '1',
  `num` tinyint NOT NULL DEFAULT '-1',
  `code_length` int NOT NULL DEFAULT '0',
  `judgetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `pass_rate` decimal(3,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `lint_error` int UNSIGNED NOT NULL DEFAULT '0',
  `judger` char(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'LOCAL'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- 表的结构 `source_code`
--

CREATE TABLE `source_code` (
  `solution_id` int NOT NULL,
  `source` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- 表的结构 `source_code_user`
--

CREATE TABLE `source_code_user` (
  `solution_id` int NOT NULL,
  `source` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- 表的结构 `topic`
--

CREATE TABLE `topic` (
  `tid` int NOT NULL,
  `title` varbinary(60) NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `top_level` int NOT NULL DEFAULT '0',
  `cid` int DEFAULT NULL,
  `pid` int NOT NULL,
  `author_id` varchar(48) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'user_id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE `users` (
  `user_id` varchar(48) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'user_id',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `submit` int DEFAULT '0',
  `solved` int DEFAULT '0',
  `defunct` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'N',
  `ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `accesstime` datetime DEFAULT NULL,
  `volume` int NOT NULL DEFAULT '1',
  `language` int NOT NULL DEFAULT '1',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `reg_time` datetime DEFAULT NULL,
  `nick` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `school` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 表的索引 `compileinfo`
--
ALTER TABLE `compileinfo`
  ADD PRIMARY KEY (`solution_id`);

--
-- 表的索引 `contest`
--
ALTER TABLE `contest`
  ADD PRIMARY KEY (`contest_id`);
--
-- 表的索引 `contest_balloon`
--
ALTER TABLE `contest_balloon`
  ADD PRIMARY KEY (`contest_id`,`problem_id`,`team_id`);
--
-- 表的索引 `contest_md`
--
ALTER TABLE `contest_md`
  ADD PRIMARY KEY (`contest_id`);

--
-- 表的索引 `contest_print`
--
ALTER TABLE `contest_print`
  ADD PRIMARY KEY (`print_id`);

--
-- 表的索引 `contest_problem`
--
ALTER TABLE `contest_problem`
  ADD KEY `Index_contest_id` (`contest_id`);

--
-- 表的索引 `contest_topic`
--
ALTER TABLE `contest_topic`
  ADD PRIMARY KEY (`topic_id`);

--
-- 表的索引 `cpc_team`
--
ALTER TABLE `cpc_team`
  ADD PRIMARY KEY (`team_id`,`contest_id`);

--
-- 表的索引 `custominput`
--
ALTER TABLE `custominput`
  ADD PRIMARY KEY (`solution_id`);

--
-- 表的索引 `loginlog`
--
ALTER TABLE `loginlog`
  ADD KEY `user_time_index` (`user_id`,`time`);

--
-- 表的索引 `mail`
--
ALTER TABLE `mail`
  ADD PRIMARY KEY (`mail_id`),
  ADD KEY `uid` (`to_user`);

--
-- 表的索引 `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`news_id`);

--
-- 表的索引 `news_md`
--
ALTER TABLE `news_md`
  ADD PRIMARY KEY (`news_id`);

--
-- 表的索引 `privilege`
--
ALTER TABLE `privilege`
  ADD PRIMARY KEY (`privilege_id`);

--
-- 表的索引 `problem`
--
ALTER TABLE `problem`
  ADD PRIMARY KEY (`problem_id`);

--
-- 表的索引 `problem_md`
--
ALTER TABLE `problem_md`
  ADD PRIMARY KEY (`problem_id`);

--
-- 表的索引 `regcontest`
--
ALTER TABLE `regcontest`
  ADD PRIMARY KEY (`regcontest_id`);

--
-- 表的索引 `reply`
--
ALTER TABLE `reply`
  ADD PRIMARY KEY (`rid`),
  ADD KEY `author_id` (`author_id`);

--
-- 表的索引 `runtimeinfo`
--
ALTER TABLE `runtimeinfo`
  ADD PRIMARY KEY (`solution_id`);

--
-- 表的索引 `sim`
--
ALTER TABLE `sim`
  ADD PRIMARY KEY (`s_id`),
  ADD KEY `Index_sim_id` (`sim_s_id`);

--
-- 表的索引 `solution`
--
ALTER TABLE `solution`
  ADD PRIMARY KEY (`solution_id`),
  ADD KEY `uid` (`user_id`),
  ADD KEY `pid` (`problem_id`),
  ADD KEY `res` (`result`),
  ADD KEY `cid` (`contest_id`);

--
-- 表的索引 `source_code`
--
ALTER TABLE `source_code`
  ADD PRIMARY KEY (`solution_id`);

--
-- 表的索引 `source_code_user`
--
ALTER TABLE `source_code_user`
  ADD PRIMARY KEY (`solution_id`);

--
-- 表的索引 `topic`
--
ALTER TABLE `topic`
  ADD PRIMARY KEY (`tid`),
  ADD KEY `cid` (`cid`,`pid`);

--
-- 表的索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `contest`
--
ALTER TABLE `contest`
  MODIFY `contest_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- 使用表AUTO_INCREMENT `contest_md`
--
ALTER TABLE `contest_md`
  MODIFY `contest_id` int NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `contest_print`
--
ALTER TABLE `contest_print`
  MODIFY `print_id` int NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `contest_topic`
--
ALTER TABLE `contest_topic`
  MODIFY `topic_id` int NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `mail`
--
ALTER TABLE `mail`
  MODIFY `mail_id` int NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `news`
--
ALTER TABLE `news`
  MODIFY `news_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- 使用表AUTO_INCREMENT `privilege`
--
ALTER TABLE `privilege`
  MODIFY `privilege_id` int NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `problem`
--
ALTER TABLE `problem`
  MODIFY `problem_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- 使用表AUTO_INCREMENT `regcontest`
--
ALTER TABLE `regcontest`
  MODIFY `regcontest_id` int NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `reply`
--
ALTER TABLE `reply`
  MODIFY `rid` int NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `solution`
--
ALTER TABLE `solution`
  MODIFY `solution_id` int NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `topic`
--
ALTER TABLE `topic`
  MODIFY `tid` int NOT NULL AUTO_INCREMENT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

COMMIT;