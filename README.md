# QuestLife

QuestLife is a gamified task management web application that helps users track their personal goals and daily missions in a fun, RPG-style format.

## Features

- User registration and login system
- Personal dashboard with user level, experience, and title
- Add, edit, and complete missions (tasks)
- Experience bar that fills as missions are completed
- Level up system with changing titles
- Profile picture upload functionality
- Responsive design for desktop and mobile use

## Technologies Used

- PHP
- MySQL
- HTML5
- CSS3
- JavaScript (Vanilla)

## Setup

1. Clone this repository to your local machine or web server.
2. Create a MySQL database named `questlife`.
3. Import the `questlife.sql` file to set up the necessary tables.
4. Update the `db_connection.php` file with your database credentials.
5. Ensure your web server has write permissions for the `uploads/` directory.

## Usage

1. Navigate to the project URL in your web browser.
2. Register for a new account or log in if you already have one.
3. On your dashboard, you can:
   - Add new missions
   - Complete missions to gain experience
   - Level up and earn new titles
   - Upload a profile picture
   - View your progress and stats

## File Structure

- `index.php`: Landing page with login/register forms
- `dashboard.php`: Main user interface after login
- `login.php`: Handles user authentication
- `register.php`: Handles new user registration
- `add_mission.php`: Adds new missions to the database
- `update_mission.php`: Updates mission status (complete/incomplete)
- `get_missions.php`: Retrieves user missions from the database
- `upload_profile_pic.php`: Handles profile picture uploads
- `update_stats.php`: Updates user stats (level, exp, title)
- `logout.php`: Handles user logout
- `db_connection.php`: Database connection configuration

## Contributing

Contributions to QuestLife are welcome! Please feel free to submit a Pull Request.

## License

This project is open source and available under the [MIT License](LICENSE).
