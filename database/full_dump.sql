SET FOREIGN_KEY_CHECKS = 0;
-- Data for admin_users
INSERT INTO admin_users (id, username, password_hash, email, created_at) VALUES ('1', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@kmf.org.np', '2026-03-15 20:00:03');

-- Data for settings
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('1', 'site_name', 'Kanchhi Maya Tamang Foundation', '2026-03-16 08:49:54');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('2', 'site_tagline', 'Education, Community & Health', '2026-03-16 08:49:54');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('3', 'logo_url', 'assets/images/logo.jpg', '2026-03-16 08:49:54');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('4', 'email', 'info@kmf.org.np', '2026-03-16 08:49:54');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('5', 'phone', '', '2026-03-16 08:49:54');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('6', 'address', '', '2026-03-16 08:49:54');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('7', 'mission', 'To advance education, community welfare, and health through inclusive programs and partnerships.', '2026-03-16 08:49:54');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('8', 'vision', 'A society where every individual has access to quality education, healthcare, and community support.', '2026-03-16 08:49:54');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('9', 'goal', 'To build lasting impact in education, health, and community development for future generations.', '2026-03-16 08:49:54');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('10', 'facebook', 'https://www.facebook.com/nischal.kc.589', '2026-03-16 08:49:54');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('11', 'twitter', '', '2026-03-16 08:49:54');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('12', 'linkedin', '', '2026-03-16 08:49:54');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('13', 'youtube', '', '2026-03-16 08:49:54');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('27', 'popup_enabled', '1', '2026-03-17 08:26:41');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('28', 'popup_title', 'Welcome to KMF!', '2026-03-17 08:26:41');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('29', 'popup_content', 'We are excited to announce our new community healthcare initiative in the Tamang region. Join us in making a difference.', '2026-03-17 08:26:41');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('30', 'popup_image_url', 'assets/images/hero_2.jpg', '2026-03-17 08:26:41');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('31', 'popup_cta_text', 'Learn More', '2026-03-17 08:26:41');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('32', 'popup_cta_link', 'what-we-do.php', '2026-03-17 08:26:41');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('33', 'popup_frequency', 'once_per_session', '2026-03-17 08:26:41');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('34', 'hero_badge', 'Helping Nepal since 2024', '2026-03-17 09:54:24');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('35', 'hero_title', 'Every Life<br><span class=\"text-kmf-orange\">Deserves</span> a<br>Future.', '2026-03-17 09:54:24');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('36', 'hero_subtitle', 'Education, Community & Health. We are a community-driven foundation dedicated to health, education, and sustainable development.', '2026-03-17 09:54:24');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('37', 'president_enabled', '1', '2026-03-17 14:12:03');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('38', 'president_name', 'Dr. Ram Bahadur Tamang', '2026-03-17 14:12:03');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('39', 'president_role', 'Chairperson (President)', '2026-03-17 14:12:03');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('40', 'president_image_url', 'assets/images/president.png', '2026-03-17 14:16:45');
INSERT INTO settings (id, setting_key, setting_value, updated_at) VALUES ('41', 'president_message', 'Welcome to the Kanchhi Maya Tamang Foundation. Our journey began with a simple yet profound vision: to empower communities through education and healthcare. Every initiative we undertake is driven by the belief that every life deserves a future. I invite you to explore our work and join hands with us to create a lasting impact across Nepal.', '2026-03-17 14:12:03');

-- Data for pages
INSERT INTO pages (id, slug, title, content, meta_description, updated_at, parent_id, sort_order) VALUES ('1', 'index', 'Home', '<h1>Empowering Nepal\'s Future</h1><p>We work in education, health, and community development.</p>', 'Kanchhi Maya Tamang Foundation - Empowering Nepal through Education, Health, and Community.', '2026-03-17 12:40:16', NULL, '1');
INSERT INTO pages (id, slug, title, content, meta_description, updated_at, parent_id, sort_order) VALUES ('2', 'about', 'About Us', '<p>Kanchhi Maya Tamang Foundation (KMF) is dedicated to advancing education, community welfare, and health in Nepal. Our foundation was born from a desire to create lasting change in the lives of the marginalized and underserved populations of the Himalayan region.</p><p class=\"mt-4\">We believe that every child deserves a quality education, every mother deserves safe healthcare, and every community deserves the opportunity to thrive. Our programs are designed and implemented in close collaboration with local leaders and community members to ensure sustainability and cultural relevance.</p>', 'Learn about Kanchhi Maya Tamang Foundation and our mission in Nepal.', '2026-03-17 12:46:28', NULL, '2');
INSERT INTO pages (id, slug, title, content, meta_description, updated_at, parent_id, sort_order) VALUES ('3', 'what-we-do', 'Our Work', '<p>Our work is organized around education, community welfare, and health—carefully aligned with our foundation\'s core mission to empower Nepal\'s local communities.</p>', 'Strategic areas of KMF impact: Education, Community, and Health.', '2026-03-17 12:40:16', NULL, '3');
INSERT INTO pages (id, slug, title, content, meta_description, updated_at, parent_id, sort_order) VALUES ('4', 'resources', 'Resources', '<p>Access our publications, reports, and media center to stay informed about our impact.</p>', 'Publications, reports, and media resources from KMF.', '2026-03-17 12:40:16', NULL, '4');
INSERT INTO pages (id, slug, title, content, meta_description, updated_at, parent_id, sort_order) VALUES ('5', 'donate', 'Donate', '<h2>Support Our Cause</h2><p>Your contribution helps us continue our mission in Nepal.</p>', 'Support Kanchhi Maya Tamang Foundation through your donations.', '2026-03-17 13:54:04', NULL, '6');
INSERT INTO pages (id, slug, title, content, meta_description, updated_at, parent_id, sort_order) VALUES ('6', 'history', 'Our History', NULL, NULL, '2026-03-17 12:37:31', '2', '1');
INSERT INTO pages (id, slug, title, content, meta_description, updated_at, parent_id, sort_order) VALUES ('7', 'team', 'Our Team', NULL, NULL, '2026-03-17 12:37:31', '2', '2');
INSERT INTO pages (id, slug, title, content, meta_description, updated_at, parent_id, sort_order) VALUES ('8', 'partners', 'Partners', NULL, NULL, '2026-03-17 12:37:31', '2', '3');
INSERT INTO pages (id, slug, title, content, meta_description, updated_at, parent_id, sort_order) VALUES ('9', 'areas', 'Strategic Areas', NULL, NULL, '2026-03-17 12:37:31', '3', '1');
INSERT INTO pages (id, slug, title, content, meta_description, updated_at, parent_id, sort_order) VALUES ('10', 'programs', 'Impact Programs', NULL, NULL, '2026-03-17 12:24:39', '3', '2');
INSERT INTO pages (id, slug, title, content, meta_description, updated_at, parent_id, sort_order) VALUES ('11', 'publications', 'Publications', NULL, NULL, '2026-03-17 12:37:31', '4', '1');
INSERT INTO pages (id, slug, title, content, meta_description, updated_at, parent_id, sort_order) VALUES ('12', 'news', 'Media Center', NULL, NULL, '2026-03-17 12:24:39', '4', '2');
INSERT INTO pages (id, slug, title, content, meta_description, updated_at, parent_id, sort_order) VALUES ('13', 'contact', 'Contact Us', '', 'Contact Kanchhi Maya Tamang Foundation.', '2026-03-17 13:54:04', NULL, '5');

-- Data for strategic_areas
INSERT INTO strategic_areas (id, title, slug, excerpt, content, icon, image_url, sort_order, is_active, created_at, updated_at) VALUES ('1', 'Education', 'education', 'Supporting access to quality education and learning for all.', '<p>We focus on educational programs, scholarships, and school support.</p>\r\n<p> This one is just test</p>', 'education', 'assets/uploads/strategic-areas/education.png', '1', '0', '2026-03-15 20:00:03', '2026-03-18 19:21:56');
INSERT INTO strategic_areas (id, title, slug, excerpt, content, icon, image_url, sort_order, is_active, created_at, updated_at) VALUES ('2', 'Community', 'community', 'Strengthening communities and inclusive participation.', '<p>Community programs and local partnerships.</p>', 'people', 'assets/uploads/strategic-areas/community.png', '2', '1', '2026-03-15 20:00:03', '2026-03-18 19:21:56');
INSERT INTO strategic_areas (id, title, slug, excerpt, content, icon, image_url, sort_order, is_active, created_at, updated_at) VALUES ('3', 'Health', 'health', 'Improving access to healthcare and wellness initiatives.', '<p>Health camps, awareness, and medical support.</p>', 'health', 'assets/uploads/strategic-areas/health.png', '3', '0', '2026-03-15 20:00:03', '2026-03-18 19:21:56');
INSERT INTO strategic_areas (id, title, slug, excerpt, content, icon, image_url, sort_order, is_active, created_at, updated_at) VALUES ('4', 'Education', 'education', 'Supporting access to quality education and learning for all.', '<p>We focus on educational programs, scholarships, and school support.</p>', 'education', 'assets/uploads/strategic-areas/education.png', '1', '1', '2026-03-17 08:18:37', '2026-03-18 19:21:56');
INSERT INTO strategic_areas (id, title, slug, excerpt, content, icon, image_url, sort_order, is_active, created_at, updated_at) VALUES ('5', 'Community', 'community', 'Strengthening communities and inclusive participation.', '<p>Community programs and local partnerships.</p>', 'people', 'assets/uploads/strategic-areas/community.png', '2', '0', '2026-03-17 08:18:37', '2026-03-18 19:21:56');
INSERT INTO strategic_areas (id, title, slug, excerpt, content, icon, image_url, sort_order, is_active, created_at, updated_at) VALUES ('6', 'Health', 'health', 'Improving access to healthcare and wellness initiatives.', '<p>Health camps, awareness, and medical support.</p>', 'health', 'assets/uploads/strategic-areas/health.png', '3', '1', '2026-03-17 08:18:37', '2026-03-18 19:21:56');

-- Data for programs

-- Data for publications

-- Data for news

-- Data for events

-- Data for team
INSERT INTO team (id, name, role, bio, image_url, type, sort_order, is_active, created_at, updated_at) VALUES ('5', 'Dr. Ram Bahadur Tamang', 'Chairperson', 'Dr. Tamang brings over 30 years of experience in rural development and community advocacy. A native of the region, he has dedicated his life to improving access to education and preserving the cultural heritage of the Tamang community. Under his leadership, KMF has grown into a cornerstone of local development.', 'assets/images/team-chairperson.png', 'board', '1', '1', '2026-03-17 08:18:37', '2026-03-17 08:18:37');
INSERT INTO team (id, name, role, bio, image_url, type, sort_order, is_active, created_at, updated_at) VALUES ('6', 'Saraswati Thapa', 'Executive Director', 'Saraswati is a passionate social entrepreneur with 15 years of experience in managing non-profit organizations. She specializes in women empowerment and sustainable healthcare initiatives. She holds a Master’s degree in Social Work from Tribhuvan University and has pioneered several award-winning community health programs.', 'assets/images/team-director.png', 'staff', '1', '1', '2026-03-17 08:18:37', '2026-03-17 08:18:37');
INSERT INTO team (id, name, role, bio, image_url, type, sort_order, is_active, created_at, updated_at) VALUES ('7', 'Anita Gurung', 'Program Manager', 'Anita manages the education and scholarship initiatives at KMF. With a background in teaching and educational administration, she ensures that every child in our target communities has the resources and support they need to succeed in school. She is a firm believer in the power of inclusive education.', '', 'staff', '2', '1', '2026-03-17 08:18:37', '2026-03-17 08:18:37');
INSERT INTO team (id, name, role, bio, image_url, type, sort_order, is_active, created_at, updated_at) VALUES ('8', 'Bikash Lama', 'Health Officer', 'Bikash oversees our community health camps and wellness awareness programs. He is a certified healthcare professional who transitioned into the NGO sector to focus on preventive medicine and rural health infrastructure. His hands-on approach has greatly improved the health outcomes for hundreds of families.', '', 'staff', '3', '1', '2026-03-17 08:18:37', '2026-03-17 08:18:37');
INSERT INTO team (id, name, role, bio, image_url, type, sort_order, is_active, created_at, updated_at) VALUES ('9', 'Nischal KC', 'IT Officer', 'Have more than 10 years of experenice in IT industry', '', 'staff', '0', '1', '2026-03-17 13:25:48', '2026-03-17 13:25:48');

-- Data for partners
INSERT INTO partners (id, name, logo_url, link_url, sort_order, is_active, created_at) VALUES ('1', 'Nischal KC', '', '', '0', '0', '2026-03-17 08:22:27');
INSERT INTO partners (id, name, logo_url, link_url, sort_order, is_active, created_at) VALUES ('2', 'Global Education Initiative', 'https://placehold.co/400x200/1e3a5f/white?text=GEI+Logo', 'https://example.com/gei', '1', '1', '2026-03-23 08:12:11');
INSERT INTO partners (id, name, logo_url, link_url, sort_order, is_active, created_at) VALUES ('3', 'Himalayan Health Care', 'https://placehold.co/400x200/52b788/white?text=HHC+Logo', 'https://example.com/hhc', '2', '1', '2026-03-23 08:12:11');
INSERT INTO partners (id, name, logo_url, link_url, sort_order, is_active, created_at) VALUES ('4', 'Unity Communities', 'https://placehold.co/400x200/e85d04/white?text=Unity+Logo', 'https://example.com/unity', '3', '1', '2026-03-23 08:12:11');
INSERT INTO partners (id, name, logo_url, link_url, sort_order, is_active, created_at) VALUES ('5', 'EcoNepal', 'https://placehold.co/400x200/2d5a87/white?text=EcoNepal', 'https://example.com/econepal', '4', '1', '2026-03-23 08:12:11');
INSERT INTO partners (id, name, logo_url, link_url, sort_order, is_active, created_at) VALUES ('6', 'Bright Futures', 'https://placehold.co/400x200/f48c06/white?text=Bright+Futures', 'https://example.com/brightfutures', '5', '1', '2026-03-23 08:12:11');

-- Data for gallery

-- Data for impact_stats
INSERT INTO impact_stats (id, title, stat_value, icon, sort_order, is_active, created_at, updated_at) VALUES ('1', 'Beneficiaries', '10,000+', 'users', '1', '1', '2026-03-17 08:18:37', '2026-03-17 08:18:37');
INSERT INTO impact_stats (id, title, stat_value, icon, sort_order, is_active, created_at, updated_at) VALUES ('2', 'Schools Supported', '50+', 'academic-cap', '2', '1', '2026-03-17 08:18:37', '2026-03-17 08:18:37');
INSERT INTO impact_stats (id, title, stat_value, icon, sort_order, is_active, created_at, updated_at) VALUES ('3', 'Health Camps', '120+', 'heart', '3', '1', '2026-03-17 08:18:37', '2026-03-17 08:18:37');
INSERT INTO impact_stats (id, title, stat_value, icon, sort_order, is_active, created_at, updated_at) VALUES ('4', 'Volunteers', '200+', 'user-group', '4', '1', '2026-03-17 08:18:37', '2026-03-17 08:18:37');

SET FOREIGN_KEY_CHECKS = 1;
