/*
 Navicat Premium Data Transfer

 Source Server         : 本地
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : 127.0.0.1:3306
 Source Schema         : admin

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 17/07/2020 09:59:10
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for seek_admin
-- ----------------------------
DROP TABLE IF EXISTS `seek_admin`;
CREATE TABLE `seek_admin`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '密码',
  `login_num` int(11) NOT NULL DEFAULT 0 COMMENT '登陆次数',
  `last_login_ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '127.0.0.1' COMMENT '最后登录IP',
  `last_login_time` int(11) NOT NULL DEFAULT 0 COMMENT '最后登录时间',
  `realname` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '真实姓名',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '状态【1为启用 -1为停用】',
  `role_id` int(11) NOT NULL DEFAULT 1 COMMENT '用户角色id',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  `updated_at` int(11) NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '后台管理-后台用户' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of seek_admin
-- ----------------------------
INSERT INTO `seek_admin` VALUES (1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 10, '127.0.0.1', 1594601976, 'admin', 1, 1, 1594601976, 1594601976);
INSERT INTO `seek_admin` VALUES (2, 'seek', 'e10adc3949ba59abbe56e057f20f883e\r\ne10adc3949ba59ab', 0, '127.0.0.1', 0, 'seek', 1, 2, 1594601976, 1594601976);

-- ----------------------------
-- Table structure for seek_node
-- ----------------------------
DROP TABLE IF EXISTS `seek_node`;
CREATE TABLE `seek_node`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node_name` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '节点名称',
  `control_name` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '控制器名',
  `action_name` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '方法名',
  `route_name` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '路由名称',
  `is_menu` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否是菜单项 1不是 2是',
  `type_id` int(11) NOT NULL COMMENT '父级节点id',
  `style` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '菜单样式',
  `sort` tinyint(4) NOT NULL DEFAULT 0 COMMENT '排序',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  `updated_at` int(11) NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '后台管理-后台节点' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of seek_node
-- ----------------------------
INSERT INTO `seek_node` VALUES (1, '后台权限管理', '#', '#', NULL, 2, 0, 'fa fa-desktop', 99, 0, 0);
INSERT INTO `seek_node` VALUES (2, '管理员管理', 'admin', 'lists', 'admin_lists', 2, 1, '', 0, 0, 0);
INSERT INTO `seek_node` VALUES (3, '添加管理员', 'admin', 'add', 'admin_add', 1, 2, '', 0, 0, 0);
INSERT INTO `seek_node` VALUES (4, '编辑管理员', 'admin', 'edit', 'admin_edit', 1, 2, '', 0, 0, 0);
INSERT INTO `seek_node` VALUES (5, '删除管理员', 'admin', 'del', 'admin_del', 1, 2, '', 0, 0, 0);
INSERT INTO `seek_node` VALUES (6, '角色管理', 'role', 'lists', 'role_lists', 2, 1, '', 0, 0, 0);
INSERT INTO `seek_node` VALUES (7, '添加角色', 'role', 'add', 'role_add', 1, 6, '', 0, 0, 0);
INSERT INTO `seek_node` VALUES (8, '编辑角色', 'role', 'edit', 'role_edit', 1, 6, '', 0, 0, 0);
INSERT INTO `seek_node` VALUES (9, '删除角色', 'role', 'del', 'role_del', 1, 6, '', 0, 0, 0);
INSERT INTO `seek_node` VALUES (10, '分配权限', 'role', 'give_access', 'role_give_access', 1, 6, '', 0, 0, 0);
INSERT INTO `seek_node` VALUES (11, '系统管理', '#', '#', NULL, 2, 0, 'fa fa-desktop', 98, 0, 0);
INSERT INTO `seek_node` VALUES (12, '数据备份/还原', 'data', 'lists', 'data_lists', 2, 11, '', 0, 0, 0);
INSERT INTO `seek_node` VALUES (13, '备份数据', 'data', 'import_data', 'data_import', 1, 12, '', 0, 0, 0);
INSERT INTO `seek_node` VALUES (14, '还原数据', 'data', 'back_data', 'data_back', 1, 12, '', 0, 0, 0);
INSERT INTO `seek_node` VALUES (15, '节点管理', 'node', 'lists', 'node_lists', 2, 1, '', 0, 0, 0);
INSERT INTO `seek_node` VALUES (16, '添加节点', 'node', 'add', 'node_add', 1, 15, '', 0, 0, 0);
INSERT INTO `seek_node` VALUES (17, '编辑节点', 'node', 'edit', 'node_edit', 1, 15, '', 0, 0, 0);
INSERT INTO `seek_node` VALUES (18, '删除节点', 'node', 'del', 'node_del', 1, 15, '', 0, 0, 0);
INSERT INTO `seek_node` VALUES (19, '后台框架', 'home', 'index', 'home', 1, 0, 'fa fa-desktop', 0, 0, 0);
INSERT INTO `seek_node` VALUES (20, '后台主页', 'home', 'home', 'home_index', 1, 19, '', 0, 0, 0);

-- ----------------------------
-- Table structure for seek_role
-- ----------------------------
DROP TABLE IF EXISTS `seek_role`;
CREATE TABLE `seek_role`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `role_name` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '角色名称',
  `rule` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '权限节点数据',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  `updated_at` int(11) NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '后台管理-后台角色' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of seek_role
-- ----------------------------
INSERT INTO `seek_role` VALUES (1, '超级管理员', '*', 0, 0);
INSERT INTO `seek_role` VALUES (2, '系统维护员', '1,2,3,4,5,6,7,8,9,10,15,16,17,18,19,20,22', 0, 0);

SET FOREIGN_KEY_CHECKS = 1;
