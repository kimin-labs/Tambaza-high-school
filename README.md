# Contact Form Project

This project implements a simple contact form that allows users to submit their information, including their name, email, phone number, and a message. The submitted data is then sent to a specified email address.

## Project Structure

```
contact-form-project
├── public
│   └── contact1.html      # HTML structure for the contact form
├── email.php              # Handles incoming data from the contact form
├── send_email.php         # Processes the form submission and sends the email
├── composer.json          # Composer configuration file for managing dependencies
└── README.md              # Documentation for the project
```

## Setup Instructions

1. **Clone the Repository**: 
   Clone this repository to your local machine using the following command:
   ```
   git clone <repository-url>
   ```

2. **Navigate to the Project Directory**:
   ```
   cd contact-form-project
   ```

3. **Install Dependencies**:
   If you have any dependencies listed in `composer.json`, run:
   ```
   composer install
   ```

4. **Configure Email Settings**:
   Ensure that the `send_email.php` file is properly configured to send emails. You may need to set up SMTP settings or use a mail service provider.

5. **Access the Contact Form**:
   Open `public/contact1.html` in your web browser to view and use the contact form.

## Usage

- Fill in the contact form with your name, email, phone number, and message.
- Click the "Send Message" button to submit the form.
- The information will be sent to the email address `kiminsalmin169@gmail.com`.

## License

This project is open-source and available under the [MIT License](LICENSE).