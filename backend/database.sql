-- database.sql
CREATE DATABASE IF NOT EXISTS garage_db;
USE garage_db;

DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS car_models;
DROP TABLE IF EXISTS services;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  phone VARCHAR(20),
  password VARCHAR(255) NOT NULL,
  role ENUM('user','admin') DEFAULT 'user',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE services (
  service_id INT AUTO_INCREMENT PRIMARY KEY,
  service_name VARCHAR(150) NOT NULL,
  description TEXT,
  category VARCHAR(100),
  price DECIMAL(10,2),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE car_models (
  car_model_id INT AUTO_INCREMENT PRIMARY KEY,
  make VARCHAR(100),
  model VARCHAR(100),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE bookings (
  booking_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  car_model VARCHAR(150),
  service_id INT,
  pickup_address TEXT,
  contact_phone VARCHAR(30),
  booking_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  status ENUM('Pending','Scheduled','In Progress','Completed','Cancelled') DEFAULT 'Pending',
  FOREIGN KEY (user_id) REFERENCES users(user_id),
  FOREIGN KEY (service_id) REFERENCES services(service_id)
);

-- Admin account (email: admin@garage.com password: Admin@123)
-- The password hash below is bcrypt for Admin@123
INSERT INTO users (name, email, phone, password, role) VALUES
('Admin','admin@garage.com','9999999999','$2y$10$YhbY1bDrD1Zgu9tuLO9h6O0aPbkoRt7vI4PVyXY5H1oW5v0qGuxWW','admin');

-- Seed services (GoMechanic-like)
INSERT INTO services (service_name, description, category, price) VALUES
('Periodic Service','Complete periodic maintenance — oil/filter change, multi-point check.','Scheduled Service',2699.00),
('Basic Service','Essential checks and basic oil top-up.','Scheduled Service',1499.00),
('Standard Service','Extended inspection + fluids + brake check.','Scheduled Service',3299.00),
('Comprehensive Service','Extensive checks and overhaul for older cars.','Scheduled Service',4999.00),
('AC Service & Repair','Gas top-up, leak detection, blower cleaning.', 'AC Service',1499.00),
('Denting & Painting','Dent removal and paint touch-ups.', 'Bodywork',6999.00),
('Car Spa & Detailing','Interior shampoo, polishing and waxing.', 'Cleaning',999.00),
('Tyre Replacement & Alignment','Tyre fitment, balancing & alignment.', 'Tyres',3499.00),
('Battery Replacement','Test & replace battery with warranty.', 'Battery',2999.00),
('Windshield & Lights','Glass and headlamp/taillight repair.', 'Glass & Lights',1999.00),
('Brake Maintenance','Brake pad/disc replacement and servicing.', 'Mechanical',2499.00),
('Suspension Repair','Shocks, struts inspection & repair.', 'Mechanical',3499.00);

-- Seed some car models
INSERT INTO car_models (make, model) VALUES
('Maruti','Swift'),
('Hyundai','i20'),
('Honda','City'),
('Tata','Nexon'),
('Kia','Seltos'),
('Mahindra','Thar'),
('Toyota','Innova'),
('Skoda','Fabia');
