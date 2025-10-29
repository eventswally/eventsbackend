-- Events Wally Database Structure
-- Created for Pakistan Event Planners Directory

CREATE DATABASE IF NOT EXISTS eventswally CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE eventswally;

-- Cities Table
CREATE TABLE IF NOT EXISTS cities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    is_active TINYINT(1) DEFAULT 1,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Categories Table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    icon VARCHAR(50),
    is_active TINYINT(1) DEFAULT 1,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Event Planners Table
CREATE TABLE IF NOT EXISTS event_planners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    city_id INT NOT NULL,
    category_id INT NOT NULL,
    description TEXT,
    short_description VARCHAR(300),
    phone VARCHAR(20),
    whatsapp VARCHAR(20),
    email VARCHAR(100),
    address TEXT,
    website VARCHAR(200),
    rating DECIMAL(2,1) DEFAULT 0.0,
    is_featured TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    INDEX idx_city_category (city_id, category_id),
    INDEX idx_featured (is_featured, is_active),
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Planner Images Table
CREATE TABLE IF NOT EXISTS planner_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    planner_id INT NOT NULL,
    image_url VARCHAR(500) NOT NULL,
    is_primary TINYINT(1) DEFAULT 0,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (planner_id) REFERENCES event_planners(id) ON DELETE CASCADE,
    INDEX idx_planner (planner_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Planner Packages Table
CREATE TABLE IF NOT EXISTS planner_packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    planner_id INT NOT NULL,
    package_name VARCHAR(200) NOT NULL,
    price VARCHAR(100),
    description TEXT,
    features TEXT,
    is_active TINYINT(1) DEFAULT 1,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (planner_id) REFERENCES event_planners(id) ON DELETE CASCADE,
    INDEX idx_planner (planner_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin Users Table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    full_name VARCHAR(100),
    is_active TINYINT(1) DEFAULT 1,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert Default Admin (username: admin, password: admin123)
INSERT INTO admin_users (username, password, email, full_name) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@eventswally.com', 'Administrator');

-- Insert Cities
INSERT INTO cities (name, slug, display_order) VALUES
('Karachi', 'karachi', 1),
('Lahore', 'lahore', 2),
('Islamabad', 'islamabad', 3),
('Faisalabad', 'faisalabad', 4),
('Rawalpindi', 'rawalpindi', 5),
('Multan', 'multan', 6),
('Peshawar', 'peshawar', 7),
('Quetta', 'quetta', 8);

-- Insert Categories
INSERT INTO categories (name, slug, icon, display_order) VALUES
('Wedding Planning', 'wedding-planning', '💒', 1),
('Photography', 'photography', '📸', 2),
('Catering', 'catering', '🍽️', 3),
('Decoration', 'decoration', '🎨', 4),
('Makeup & Beauty', 'makeup-beauty', '💄', 5),
('Venues', 'venues', '🏛️', 6),
('Entertainment', 'entertainment', '🎭', 7),
('Birthday Parties', 'birthday-parties', '🎂', 8),
('Corporate Events', 'corporate-events', '💼', 9);
