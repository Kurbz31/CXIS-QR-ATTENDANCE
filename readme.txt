-- Drop tables if they exist (optional)
DROP TABLE IF EXISTS `attendance`;
DROP TABLE IF EXISTS `employees`;

-- Create employees table
CREATE TABLE `employees` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `employee_code` VARCHAR(20) NOT NULL,
  `emp_id` VARCHAR(50) NOT NULL,
  `fullname` VARCHAR(100) NOT NULL,
  `department` VARCHAR(100) NOT NULL,
  `profile_pic` VARCHAR(255),
  `qr_path` VARCHAR(255),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create attendance table
CREATE TABLE `attendance` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `employee_id` INT NOT NULL,
  `employee_code` VARCHAR(20) NOT NULL,
  `date` DATE NOT NULL,
  `time_in` TIME DEFAULT NULL,
  `time_out` TIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Optional: Reset AUTO_INCREMENT explicitly
ALTER TABLE `employees` AUTO_INCREMENT = 1;
ALTER TABLE `attendance` AUTO_INCREMENT = 1;

-- No data inserted → completely empty database ready to start fresh