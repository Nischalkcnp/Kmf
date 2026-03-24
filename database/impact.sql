-- Database enhancement for dynamic impact statistics
USE kmf_website;

CREATE TABLE IF NOT EXISTS impact_stats (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    stat_value VARCHAR(50) NOT NULL,
    icon VARCHAR(50),
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Seed initial impact stats
INSERT INTO impact_stats (title, stat_value, icon, sort_order) VALUES
('Beneficiaries', '10,000+', 'users', 1),
('Schools Supported', '50+', 'academic-cap', 2),
('Health Camps', '120+', 'heart', 3),
('Volunteers', '200+', 'user-group', 4);
