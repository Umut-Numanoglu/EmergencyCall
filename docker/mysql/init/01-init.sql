-- Emergency Call Database Schema

USE emergency_call;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('patient', 'reception', 'doctor') NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Issues table
CREATE TABLE issues (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    priority ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    status ENUM('open', 'in_progress', 'closed') DEFAULT 'open',
    assigned_doctor_id INT,
    receptionist_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES users(id),
    FOREIGN KEY (assigned_doctor_id) REFERENCES users(id),
    FOREIGN KEY (receptionist_id) REFERENCES users(id)
);

-- Issue labels table
CREATE TABLE issue_labels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    issue_id INT NOT NULL,
    label VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (issue_id) REFERENCES issues(id) ON DELETE CASCADE
);

-- Comments table
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    issue_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (issue_id) REFERENCES issues(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Insert sample data
INSERT INTO users (username, email, password_hash, role, first_name, last_name, phone) VALUES
('patient1', 'patient1@numanoglu.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'patient', 'John', 'Doe', '+1234567890'),
('reception1', 'reception1@numanoglu.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'reception', 'Jane', 'Smith', '+1234567891'),
('doctor1', 'doctor1@numanoglu.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'doctor', 'Dr. Mike', 'Johnson', '+1234567892');

-- Insert sample issues
INSERT INTO issues (patient_id, title, description, priority, status) VALUES
(1, 'Chest Pain', 'Experiencing sharp chest pain for the last 2 hours', 'high', 'open'),
(1, 'Fever and Cough', 'High fever with persistent cough for 3 days', 'medium', 'open');

-- Insert sample labels
INSERT INTO issue_labels (issue_id, label) VALUES
(1, 'cardiology'),
(1, 'urgent'),
(2, 'respiratory'),
(2, 'infection'); 