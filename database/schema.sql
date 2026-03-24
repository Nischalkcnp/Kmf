-- Kanchhi Maya Tamang Foundation - Database Schema
-- MySQL 5.7+ / MariaDB

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS kmf_website;
USE kmf_website;

-- Admin users for CMS
CREATE TABLE IF NOT EXISTS admin_users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  email VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Key-value settings (site name, contact, mission, vision, goal, social links)
CREATE TABLE IF NOT EXISTS settings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(100) NOT NULL UNIQUE,
  setting_value TEXT,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Static pages (about, get involved, etc.)
CREATE TABLE IF NOT EXISTS pages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(100) NOT NULL UNIQUE,
  title VARCHAR(200) NOT NULL,
  content LONGTEXT,
  meta_description VARCHAR(255),
  parent_id INT UNSIGNED DEFAULT NULL,
  sort_order INT DEFAULT 0,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Strategic areas (What We Do) - Education, Health, Community
CREATE TABLE IF NOT EXISTS strategic_areas (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  slug VARCHAR(100) NOT NULL,
  excerpt TEXT,
  content LONGTEXT,
  icon VARCHAR(50) DEFAULT NULL,
  image_url VARCHAR(255),
  sort_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Programs / Projects (current & completed)
CREATE TABLE IF NOT EXISTS programs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  slug VARCHAR(100) NOT NULL,
  excerpt TEXT,
  content LONGTEXT,
  type ENUM('current','completed') DEFAULT 'current',
  image_url VARCHAR(255),
  sort_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Publications, reports, articles
CREATE TABLE IF NOT EXISTS publications (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(100) NOT NULL,
  excerpt TEXT,
  type ENUM('publication','report','article') DEFAULT 'publication',
  file_url VARCHAR(255),
  image_url VARCHAR(255),
  published_at DATE,
  sort_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- News
CREATE TABLE IF NOT EXISTS news (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(100) NOT NULL,
  excerpt TEXT,
  content LONGTEXT,
  image_url VARCHAR(255),
  published_at DATETIME,
  is_featured TINYINT(1) DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Events (upcoming / past)
CREATE TABLE IF NOT EXISTS events (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(100) NOT NULL,
  excerpt TEXT,
  content LONGTEXT,
  event_date DATE NOT NULL,
  end_date DATE,
  venue VARCHAR(255),
  image_url VARCHAR(255),
  type ENUM('upcoming','past') DEFAULT 'upcoming',
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Team (board / staff)
CREATE TABLE IF NOT EXISTS team (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  role VARCHAR(150),
  bio TEXT,
  image_url VARCHAR(255),
  type ENUM('board','staff') DEFAULT 'staff',
  sort_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Partners
CREATE TABLE IF NOT EXISTS partners (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  logo_url VARCHAR(255),
  link_url VARCHAR(255),
  sort_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Gallery (photo / video)
CREATE TABLE IF NOT EXISTS gallery (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200),
  image_url VARCHAR(255) NOT NULL,
  category ENUM('photo','video') DEFAULT 'photo',
  video_url VARCHAR(255),
  sort_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact form submissions
CREATE TABLE IF NOT EXISTS contact_submissions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  subject VARCHAR(200),
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed default admin (password: admin123 - CHANGE IN PRODUCTION)
INSERT INTO admin_users (username, password_hash, email) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@kmf.org.np');

-- Seed default settings
INSERT INTO settings (setting_key, setting_value) VALUES
('site_name', 'Kanchhi Maya Tamang Foundation'),
('site_tagline', 'Education, Community & Health'),
('logo_url', 'assets/images/logo.jpg'),
('email', 'info@kmf.org.np'),
('phone', ''),
('address', ''),
('mission', 'To advance education, community welfare, and health through inclusive programs and partnerships.'),
('vision', 'A society where every individual has access to quality education, healthcare, and community support.'),
('goal', 'To build lasting impact in education, health, and community development for future generations.'),
('facebook', ''),
('twitter', ''),
('linkedin', ''),
('youtube', '');

-- Seed sample pages
INSERT INTO pages (slug, title, content, meta_description) VALUES
('about', 'About Us', '<p>Kanchhi Maya Tamang Foundation (KMF) is dedicated to advancing education, community welfare, and health in Nepal. Our work spans programs, partnerships, and advocacy.</p>', 'Learn about Kanchhi Maya Tamang Foundation'),
('get-involved', 'Get Involved', '<p>Join us through volunteering, partnerships, or donations. Contact us for opportunities.</p>', 'Get involved with KMF');

-- Seed sample strategic areas (aligned with logo: education, community, health)
INSERT INTO strategic_areas (title, slug, excerpt, content, icon, sort_order) VALUES
('Education', 'education', 'Supporting access to quality education and learning for all.', '<p>We focus on educational programs, scholarships, and school support.</p>', 'education', 1),
('Community', 'community', 'Strengthening communities and inclusive participation.', '<p>Community programs and local partnerships.</p>', 'people', 2),
('Health', 'health', 'Improving access to healthcare and wellness initiatives.', '<p>Health camps, awareness, and medical support.</p>', 'health', 3);

SET FOREIGN_KEY_CHECKS = 1;
