# Emergency Call System

A comprehensive emergency call management system built with PHP Yii Framework, featuring Docker containerization and role-based access control.

## Features

### User Roles
- **Patient**: Can create issues, view their own issues, and add comments
- **Reception**: Can manage all issues, assign priorities, add labels, and assign doctors
- **Doctor**: Can view assigned issues, add comments, and mark issues as closed

### Core Functionality
- User registration and authentication
- Issue creation and management
- Priority-based issue sorting
- Label system for issue categorization
- Comment system for communication
- Role-based access control
- Responsive Bootstrap 5 UI

## Technology Stack

- **Backend**: PHP 8.1 with Yii Framework 2
- **Database**: MySQL 8.0
- **Web Server**: Nginx
- **Containerization**: Docker & Docker Compose
- **Frontend**: Bootstrap 5, CSS3, JavaScript

## Prerequisites

- Docker
- Docker Compose

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd EmergencyCall
   ```

1b. **Update Config**
   - Open the file `docker-compose.yml` and adjust the ports or labels (if you are using Traefik)
   - Change DB Password and anything you like

2. **Start the Docker containers**
   ```bash
   docker-compose up -d
   ```

3. **Install Composer dependencies**
   ```bash
   docker-compose exec app composer install
   ```

4. **Set proper permissions (Not neccessary yet!)**
   ```bash
   docker-compose exec app chmod 777 runtime/
   docker-compose exec app chmod 777 web/assets/
   ```

5. **Access the application**
   - Open your browser and navigate to `http://localhost:8080`
   - The application will be available at port 8080

## Default Users

The system comes with pre-configured sample users:

### Patient
- **Username**: patient1
- **Password**: password
- **Email**: patient1@numanoglu.org

### Reception
- **Username**: reception1
- **Password**: password
- **Email**: reception1@numanoglu.org

### Doctor
- **Username**: doctor1
- **Password**: password
- **Email**: doctor1@numanoglu.org

## Usage

### For Patients
1. Login with patient credentials
2. Create new issues with detailed descriptions
3. View your submitted issues and their status
4. Add comments to communicate with medical staff
5. Track the progress of your cases

### For Reception Staff
1. Login with reception credentials
2. View all issues in the system
3. Sort issues by priority (Critical > High > Medium > Low)
4. Assign labels to categorize issues
5. Assign doctors to handle specific cases
6. Monitor issue progress

### For Doctors
1. Login with doctor credentials
2. View assigned cases
3. Add medical comments and updates
4. Mark cases as closed when resolved
5. Communicate with patients through comments

## Database Schema

### Users Table
- Stores user information with role-based access
- Supports patient, reception, and doctor roles

### Issues Table
- Contains issue details, priority, and status
- Links to assigned doctors and receptionists

### Comments Table
- Enables communication between users
- Tracks comment history with timestamps

### Issue Labels Table
- Allows categorization of issues
- Supports multiple labels per issue

## Docker Services

- **app**: PHP-FPM application server
- **nginx**: Web server for handling HTTP requests
- **db**: MySQL database server

## Configuration

### Environment Variables
- Database credentials are configured in `docker-compose.yml`
- Application settings are in `config/main.php`

### Database Connection
- Host: `db` (Docker service name)
- Database: `emergency_call`
- Username: `emergency_user`
- Password: `emergency_password`

## Development

### Adding New Features
1. Create models in `common/models/`
2. Add controllers in `frontend/controllers/`
3. Create views in `frontend/views/`
4. Update database schema if needed

### Running Migrations
```bash
docker-compose exec app php yii migrate
```

### Accessing the Database
```bash
docker-compose exec db mysql -u emergency_user -p emergency_call
```

## Security Features

- Password hashing using Yii's security component
- CSRF protection enabled
- Role-based access control
- Input validation and sanitization
- SQL injection prevention through Active Record

## Troubleshooting

### Common Issues

1. **Permission Denied Errors**
   ```bash
   docker-compose exec app chmod -R 777 runtime/
   docker-compose exec app chmod -R 777 web/assets/
   ```

2. **Database Connection Issues**
   - Ensure MySQL container is running: `docker-compose ps`
   - Check database logs: `docker-compose logs db`

3. **Composer Dependencies**
   ```bash
   docker-compose exec app composer install --no-dev
   ```

### Logs
- Application logs: `docker-compose logs app`
- Nginx logs: `docker-compose logs nginx`
- Database logs: `docker-compose logs db`

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is licensed under the MIT License.

## Support

For support and questions, please open an issue in the repository. 