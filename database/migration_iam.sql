-- KMF Website - IAM Migration Schema

CREATE TABLE IF NOT EXISTS roles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL UNIQUE,
  description TEXT,
  is_system TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS permissions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  code_name VARCHAR(50) NOT NULL UNIQUE,
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS role_permissions (
  role_id INT UNSIGNED NOT NULL,
  permission_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (role_id, permission_id),
  FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
  FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Modify admin_users if role_id does not exist
-- (Note: SQL DDL ALTER can be tricky if repeated, but we will handle safety checks in PHP migrate script)
-- ALTER TABLE admin_users ADD COLUMN role_id INT UNSIGNED NULL AFTER email;
-- ALTER TABLE admin_users ADD COLUMN status ENUM('active', 'inactive') DEFAULT 'active' AFTER role_id;
-- ALTER TABLE admin_users ADD CONSTRAINT fk_admin_users_roles FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL;

-- Seed default permissions (using INSERT IGNORE to allow safe re-runs)
INSERT IGNORE INTO permissions (name, code_name, description) VALUES
('Access Admin Dashboard', 'access_admin', 'Allows the user to view the admin overview and basic statistics.'),
('Manage Settings', 'manage_settings', 'Allows the user to edit global website identity, theme settings, and homepage details.'),
('Manage Pages', 'manage_pages', 'Allows the user to view, create, edit, and delete static pages.'),
('Manage Strategic Areas', 'manage_areas', 'Allows the user to manage content in the "What We Do" sections.'),
('Manage Programs', 'manage_programs', 'Allows the user to manage projects and programs.'),
('Manage Publications', 'manage_publications', 'Allows the user to upload and manage reports/articles.'),
('Manage News & Stories', 'manage_news', 'Allows the user to publish and edit news, stories, and announcements.'),
('Manage Careers', 'manage_careers', 'Allows the user to manage job postings.'),
('Manage Events', 'manage_events', 'Allows the user to schedule and edit events.'),
('Manage Team & Partners', 'manage_team', 'Allows the user to manage team members and partner logos.'),
('Manage Contact Submissions', 'manage_messages', 'Allows the user to read and reply to contact/support messages.'),
('Manage IAM (Identity & Access)', 'manage_iam', 'Allows full access to create/edit users, assign roles, and modify permissions.');

-- Seed default roles
INSERT IGNORE INTO roles (id, name, description, is_system) VALUES
(1, 'Super Admin', 'Full control over the CMS, settings, and user access.', 1),
(2, 'Editor', 'Manage content items (pages, news, programs, events, etc.), but cannot change settings or manage users.', 1),
(3, 'Viewer', 'Read-only access to view stats and contact submissions.', 1);

-- Re-assign permissions (using REPLACE or DELETE+INSERT for role_permissions)
-- For Super Admin (Role ID = 1): Grant all permissions
DELETE FROM role_permissions WHERE role_id = 1;
INSERT INTO role_permissions (role_id, permission_id)
SELECT 1, id FROM permissions;

-- For Editor (Role ID = 2): Grant content management permissions
DELETE FROM role_permissions WHERE role_id = 2;
INSERT INTO role_permissions (role_id, permission_id)
SELECT 2, id FROM permissions 
WHERE code_name IN ('access_admin', 'manage_pages', 'manage_areas', 'manage_programs', 'manage_publications', 'manage_news', 'manage_careers', 'manage_events', 'manage_team', 'manage_messages');

-- For Viewer (Role ID = 3): Grant read-only / dashboard / messages permissions
DELETE FROM role_permissions WHERE role_id = 3;
INSERT INTO role_permissions (role_id, permission_id)
SELECT 3, id FROM permissions 
WHERE code_name IN ('access_admin', 'manage_messages');
