 CXIS QR Attendance System

 Description

A web-based employee attendance management system that utilizes QR codes for efficient and secure check-in and check-out processes. This system is designed for CXIS to streamline employee attendance tracking.

 Features

- **Employee Management**: Add, edit, and view employee details including profile pictures and department information.
- **QR Code Generation**: Automatically generate unique QR codes for each employee for easy identification.
- **Attendance Tracking**: Real-time attendance logging with time-in and time-out records.
- **Mobile Scanner**: QR code scanner interface for employees to mark their attendance using mobile devices.
- **Dashboard**: Comprehensive dashboard displaying daily attendance status for all employees.
- **Export Functionality**: Export attendance data to Excel for reporting and analysis.
- **ID Card Generation**: Generate printable ID cards for employees.
- **User Authentication**: Secure login system for HR and administrators.
- **Shift Attendance**: Support for shift-based attendance tracking.

 Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache, Nginx, or similar)
- phpqrcode library (included in the project)
- Modern web browser with JavaScript enabled

 Installation

1. Download/Clone the Project:
   - Place the project files in your web server's document root directory (e.g., `htdocs` for XAMPP or `www` for WAMP).

2. Database Setup:
   - Create a new MySQL database (e.g., `company_attendance`).
   - Import the database schema using the SQL commands provided below or run the `company_attendance.sql` file.

3. Configuration:
   - Open `db.php` and update the database connection details:
     ```php
     $servername = "localhost";
     $username = "your_username";
     $password = "your_password";
     $dbname = "company_attendance";
     ```

4. Permissions:
   - Ensure the following directories are writable by the web server:
     - `uploads/` (for employee profile pictures)
     - `qrcodes/` (for generated QR codes)
     - `img/` (for additional images)

5. **Access the Application**:
   - Open your web browser and navigate to `http://localhost/CXIS-QR-ATTENDANCE/` (adjust the path as needed).

 Usage

1. Login:
   - Access the login page and enter your credentials.

2. Employee Management:
   - Navigate to the employee management section.
   - Add new employees with their details.
   - Edit existing employee information.

3. QR Code Generation:
   - Generate QR codes for employees from the employee list.

4. Attendance Scanning:
   - Use the scanner page to scan employee QR codes.
   - Employees can scan their codes to record time-in and time-out.

5. Dashboard:
   - View the attendance dashboard for real-time status.
   - Monitor daily attendance records.

6. Reports:
   - Export attendance data to Excel for further analysis.

 Database Schema

The system uses two main tables:

 Employees Table
```sql
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
```

 Attendance Table
```sql
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
```

 Technologies Used

- **Backend**: PHP
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **QR Code Library**: phpqrcode
- **Styling**: Custom CSS with responsive design

 File Structure

- `index.php`: Main attendance dashboard
- `login.php`: User authentication
- `hr_dashboard.php`: HR management interface
- `add_employee.php`: Add new employees
- `edit_employee.php`: Edit employee details
- `view_employees.php`: View all employees
- `scanner.php`: QR code scanner interface
- `generate_qr.php`: QR code generation
- `save_attendance.php`: Process attendance records
- `export_excel.php`: Export attendance to Excel
- `id_card.php`: Generate employee ID cards
- `db.php`: Database connection
- `auth.php`: Authentication functions
- `navbar.php`: Navigation bar
- `phpqrcode/`: QR code generation library
- `uploads/`: Employee profile pictures
- `qrcodes/`: Generated QR codes
- `img/`: Static images

 Security Notes

- Ensure your web server is configured securely.
- Use HTTPS in production.
- Regularly update PHP and MySQL for security patches.
- Implement proper user access controls.

 Troubleshooting

- Database Connection Issues**: Verify credentials in `db.php`.
- Permission Errors**: Check write permissions for upload directories.
- QR Code Not Generating**: Ensure GD library is enabled in PHP.
- Scanner Not Working**: Ensure camera access is allowed in the browser.

Contributing

If you'd like to contribute to this project, please fork the repository and submit a pull request.

 License

This project is licensed under the MIT License - see the LICENSE file for details.

 Support

For support or questions, please contact the development team.
