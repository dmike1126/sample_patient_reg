# Patient Registration System (PHP)

This repository contains a simple patient registration system built using PHP. It demonstrates basic data handling and form processing for registering new patients.

## Table of Contents

- [Introduction](#introduction)
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Installation](#installation)
- [Usage](#usage)
- [File Structure](#file-structure)


## Introduction

This patient registration system is a basic example implemented in PHP. It provides a form for registering new patients, capturing information like name, address, contact details, and basic medical history.  This project is intended for educational purposes and demonstrates fundamental PHP concepts.  It is not intended for production use in a real-world healthcare setting due to its simplicity and lack of robust security measures.

## Features

*   **Patient Registration Form:**  Allows users to input patient information.
*   **Data Storage (Simulated):**  Currently, the system simulates data storage.  In a real application, you would typically use a database (e.g., MySQL, PostgreSQL).  *(Note: This README assumes the provided example uses simulated storage. If a database is used, update this section accordingly.)*
*   **Data Validation (Basic):**  Includes basic validation to ensure required fields are filled. *(If implemented. Update if not present.)*
*   **Display of Registered Patients (Optional):**  May include a page to display a list of registered patients. *(If implemented. Update if not present.)*

## Technologies Used

*   **PHP:** The primary language used for backend logic and form processing.
*   **HTML:** Used for creating the user interface (forms, etc.).
*   **CSS:** Used for styling the user interface. *(If used)*
*   **JavaScript:**  May be used for client-side form validation or enhancements. *(If used)*
*   **Database (If applicable):** [e.g., MySQL, PostgreSQL]  *(MySql/Xampp)*

## Installation

1.  **Clone the Repository:**
    ```bash
    git clone [https://github.com/dmike1126/sample_patient_reg.git](https://www.google.com/search?q=https://github.com/dmike1126/sample_patient_reg.git)
    ```

2.  **Set up a Web Server:** You'll need a web server (e.g., Apache, Nginx) with PHP support.  If you don't have one, consider using XAMPP or MAMP.

3.  **Place the Files:** Copy the contents of the repository (or the relevant directory) into your web server's document root (e.g., `htdocs` for XAMPP).

4.  **Database Configuration (If applicable):**
    -   Create a database.
    -   Update the database connection details in the PHP files (e.g., `config.php` or within the relevant scripts). *(Only if a database is used)*

## Usage

1.  **Access the Application:** Open your web browser and navigate to the URL where you placed the files (e.g., `http://localhost/patient_reg/`).

2.  **Register a Patient:** Fill out the registration form and submit it.

3.  **View Registered Patients (If applicable):**  Navigate to the page that displays the list of registered patients. *(If implemented)*

## File Structure

sample_patient_reg/
├── patient_reg.php         // Main registration form and Handles form submission and data processing
├── patient_details .php // To view the patient details
├── get_patient_details.php  // Process the data of patient to be viewed
├── css/              // (Optional) CSS stylesheets
│   └── style.css
├── js/               // (Optional) JavaScript files
│   └── script.js
└── ...               // Other files


*(Adjust the file structure to accurately reflect the actual files in the repository.)*

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request with your changes.
