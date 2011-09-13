/*
 Navicat Premium Data Transfer

 Source Server         : sbc-mysql
 Source Server Type    : MySQL
 Source Server Version : 50151
 Source Host           : localhost
 Source Database       : sbc

 Target Server Type    : MySQL
 Target Server Version : 50151
 File Encoding         : utf-8

 Date: 10/31/2010 02:56:26 AM
*/

-- SET NAMES utf8;
-- SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for "Adminacl"
-- ----------------------------
-- DROP TABLE "Adminacl";
CREATE TABLE "Adminacl" (
  "id" INTEGER PRIMARY KEY,
  "name" char(255) DEFAULT '',
  "users" char(255) DEFAULT '',
  "groups" char(255) DEFAULT '',
  "acl" text,
  "type" char(255) DEFAULT '',
  "objtype" char(255) DEFAULT ''
);

-- ----------------------------
--  Table structure for "Cache"
-- ----------------------------
-- DROP TABLE "Cache";
CREATE TABLE "Cache" (
  "id" char(255) NOT NULL,
  "content" longtext,
  "time" char(255) DEFAULT ''
);

-- ----------------------------
--  Table structure for "Checkout"
-- ----------------------------
-- DROP TABLE "Checkout";
CREATE TABLE "Checkout" (
  "id" char(255) PRIMARY KEY,
  "checked_out"   DEFAULT '',
  "checked_out_by" char(255) DEFAULT '',
  "checked_out_time" char(255) DEFAULT '',
  "item_id"   DEFAULT '',
  "item_type" char(255) DEFAULT '',
  "name" char(255) DEFAULT '',
  "type" char(255) DEFAULT '',
  "objtype" char(255) DEFAULT ''
);

-- ----------------------------
--  Table structure for "Configuration"
-- ----------------------------
-- DROP TABLE "Configuration";
CREATE TABLE "Configuration" (
  "id"    INTEGER PRIMARY KEY,
  "name" char(255) DEFAULT '',
  "value" char(255) DEFAULT '',
  "type" char(255) DEFAULT '',
  "objtype" char(255) DEFAULT ''
);

-- ----------------------------
--  Table structure for "Contact"
-- ----------------------------
-- DROP TABLE "Contact";
CREATE TABLE "Contact" (
  "id"    INTEGER PRIMARY KEY,
  "name" char(255) DEFAULT '',
  "title" char(255) DEFAULT '',
  "email" char(255) DEFAULT '',
  "phone" char(255) DEFAULT '',
  "fax" char(255) DEFAULT '',
  "address" char(255) DEFAULT '',
  "city" char(255) DEFAULT '',
  "state" char(255) DEFAULT '',
  "zip" char(255) DEFAULT '',
  "type" char(255) DEFAULT 'contacts',
  "objtype" char(255) DEFAULT 'contacts'
);

-- ----------------------------
--  Table structure for "Extension"
-- ----------------------------
-- DROP TABLE "Extension";
CREATE TABLE "Extension" (
  "id"    INTEGER PRIMARY KEY,
  "name" char(255) DEFAULT '',
  "content" text,
  "context" char(255) DEFAULT '',
  "ordinal"   DEFAULT '',
  "type" char(255) DEFAULT '',
  "objtype" char(255) DEFAULT ''
);

-- ----------------------------
--  Table structure for "Link"
-- ----------------------------
-- DROP TABLE "Link";
CREATE TABLE "Link" (
  "id"    INTEGER PRIMARY KEY,
  "name" char(255) DEFAULT '',
  "url" char(255) DEFAULT '',
  "relationship" char(255) DEFAULT '',
  "groups" char(255) DEFAULT '',
  "type" char(255) DEFAULT '',
  "objtype" char(255) DEFAULT ''
);

-- ----------------------------
--  Table structure for "Linksgroup"
-- ----------------------------
-- DROP TABLE "Linksgroup";
CREATE TABLE "Linksgroup" (
  "id"    INTEGER PRIMARY KEY,
  "name" char(255) DEFAULT '',
  "type" char(255) DEFAULT '',
  "objtype" char(255) DEFAULT ''
);

-- ----------------------------
--  Table structure for "Menu"
-- ----------------------------
-- DROP TABLE "Menu";
CREATE TABLE "Menu" (
  "id"    INTEGER PRIMARY KEY,
  "title" char(255) DEFAULT '',
  "type" char(255) DEFAULT '',
  "objtype" char(255) DEFAULT ''
);

-- ----------------------------
--  Table structure for "Meta"
-- ----------------------------
-- DROP TABLE "Meta";
CREATE TABLE "Meta" (
  "id"    INTEGER PRIMARY KEY,
  "name" char(255) DEFAULT '',
  "content" char(255) DEFAULT '',
  "groups" char(255) DEFAULT '',
  "type" char(255) DEFAULT '',
  "objtype" char(255) DEFAULT ''
);

-- ----------------------------
--  Table structure for "Metagroup"
-- ----------------------------
-- DROP TABLE "Metagroup";
CREATE TABLE "Metagroup" (
  "id"    INTEGER PRIMARY KEY,
  "name" char(255) DEFAULT '',
  "type" char(255) DEFAULT '',
  "objtype" char(255) DEFAULT ''
);

-- ----------------------------
--  Table structure for "Page"
-- ----------------------------
-- DROP TABLE "Page";
CREATE TABLE "Page" (
  "id"    INTEGER PRIMARY KEY,
  "name" text,
  "title" char(255) DEFAULT '',
  "menu"   DEFAULT '',
  "pagetype" char(255) DEFAULT '',
  "is_error_page"   DEFAULT '',
  "usesitename"   DEFAULT '',
  "parent"   DEFAULT '',
  "metagroup" char(255) DEFAULT '',
  "keywords" text,
  "isdefault"   DEFAULT '',
  "published"   DEFAULT '',
  "syndicate"   DEFAULT '',
  "modified" char(255) DEFAULT '',
  "meta_description" text,
  "acltype" char(255) DEFAULT '',
  "aclusers" char(255) DEFAULT '',
  "aclgroups" char(255) DEFAULT '',
  "permalink" char(255) DEFAULT '',
  "author" char(255) DEFAULT '',
  "story_content" text,
  "ordinal"   DEFAULT '',
  "type" char(255) DEFAULT '',
  "objtype" char(255) DEFAULT '',
  "show_in_navigation" int(1) DEFAULT '0'
);

-- ----------------------------
--  Table structure for "Plugin"
-- ----------------------------
-- DROP TABLE "Plugin";
CREATE TABLE "Plugin" (
  "id" char(255) PRIMARY KEY,
  "published"   DEFAULT '',
  "name" char(255) DEFAULT '',
  "ordinal" double(30,29) DEFAULT '',
  "type" char(255) DEFAULT '',
  "objtype" char(255) DEFAULT ''
);

-- ----------------------------
--  Table structure for "Skin"
-- ----------------------------
-- DROP TABLE "Skin";
CREATE TABLE "Skin" (
  "id" char(255)  PRIMARY KEY,
  "published"   DEFAULT '',
  "name" char(255) DEFAULT '',
  "type" char(255) DEFAULT '',
  "objtype" char(255) DEFAULT ''
);

-- ----------------------------
--  Table structure for "Snippet"
-- ----------------------------
-- DROP TABLE "Snippet";
CREATE TABLE "Snippet" (
  "id"    INTEGER PRIMARY KEY,
  "name" char(255) DEFAULT '',
  "snippetType" char(255) DEFAULT '',
  "content" text,
  "type" char(255) DEFAULT '',
  "objtype" char(255) DEFAULT ''
);

-- ----------------------------
--  Table structure for "Structure"
-- ----------------------------
-- DROP TABLE "Structure";
CREATE TABLE "Structure" (
  "site_id" text  PRIMARY KEY,
  "structure" text NOT NULL
);

-- ----------------------------
--  Table structure for "User"
-- ----------------------------
-- DROP TABLE "User";
CREATE TABLE "User" (
  "id"    INTEGER PRIMARY KEY,
  "username" char(255) DEFAULT '',
  "password" char(255) DEFAULT '',
  "email" char(255) DEFAULT '',
  "name" char(255) DEFAULT '',
  "groups" char(255) DEFAULT '',
  "block" char(255) DEFAULT '',
  "lastlogin" char(255) DEFAULT '',
  "tempkey" char(255) DEFAULT '',
  "tempkeyexpiration" char(255) DEFAULT '',
  "type" char(255) DEFAULT '',
  "objtype" char(255) DEFAULT ''
);

-- ----------------------------
--  Table structure for "Usergroup"
-- ----------------------------
-- DROP TABLE "Usergroup";
CREATE TABLE "Usergroup" (
  "id"    INTEGER PRIMARY KEY,
  "name" char(255) DEFAULT '',
  "siteadmin"   DEFAULT '',
  "type" char(255) DEFAULT '',
  "objtype" char(255) DEFAULT ''
);