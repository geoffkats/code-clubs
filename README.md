# CodeClub Management System

A comprehensive, professional-grade platform for managing coding clubs, students, assessments, and educational projects. Built with Laravel 11 and featuring a modern, responsive UI with advanced features for educators and students.

## 🚀 Features

### 🎯 Core Functionality
- **Multi-School Support**: Complete multi-tenancy with school-based data isolation
- **Club Management**: Create and manage coding clubs with detailed tracking
- **Student Management**: Comprehensive student profiles with enrollment tracking
- **Attendance System**: Advanced attendance tracking with bulk operations
- **Assessment Engine**: Create quizzes, tests, and projects with image uploads
- **Scratch IDE Integration**: Built-in visual programming environment
- **Project Showcase**: Students can upload and showcase their coding projects
- **Parent Portal**: Professional landing page and progress reports

### 🎨 Professional UI/UX
- **Modern Design**: Glass morphism, gradients, and professional styling
- **Responsive Layout**: Works perfectly on desktop, tablet, and mobile
- **Dark Mode Support**: Complete dark/light theme switching
- **Interactive Elements**: Smooth animations and hover effects
- **Accessibility**: WCAG compliant with proper contrast and navigation

### 🔧 Technical Features
- **Laravel 11**: Latest Laravel framework with modern PHP features
- **Alpine.js**: Lightweight JavaScript framework for interactivity
- **Tailwind CSS**: Utility-first CSS framework for rapid styling
- **File Upload System**: Secure file handling with validation
- **Database Relationships**: Properly normalized database design
- **API Ready**: RESTful API endpoints for future integrations

## 📋 Requirements

- PHP 8.1 or higher
- Composer
- Node.js & NPM
- MySQL/SQLite
- Web server (Apache/Nginx)

## 🛠️ Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/codeclub-system.git
   cd codeclub-system
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database configuration**
   - Update `.env` with your database credentials
   - Run migrations: `php artisan migrate`
   - Seed the database: `php artisan db:seed`

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

## 🗄️ Database Schema

### Core Tables
- `schools` - School information and settings
- `clubs` - Coding clubs with school relationships
- `students` - Student profiles and enrollment data
- `users` - System users (teachers, administrators)
- `sessions` - Club session scheduling
- `attendance_records` - Student attendance tracking
- `assessments` - Quizzes, tests, and projects
- `assessment_scores` - Student performance data
- `attachments` - File uploads for assessments and projects
- `reports` - Generated reports and analytics

### Key Relationships
- Schools have many Clubs
- Clubs belong to Schools and have many Students
- Students belong to many Clubs (many-to-many)
- Assessments belong to Clubs
- Attendance Records link Students to Sessions

## 🎯 User Roles & Permissions

### Administrator
- Full system access
- School and club management
- User role management
- System configuration

### Teacher/Instructor
- Club management
- Student enrollment
- Assessment creation
- Attendance tracking
- Report generation

### Student
- Access to assignments and projects
- Scratch IDE integration
- Project showcase
- Progress tracking

## 📱 Key Pages & Features

### Dashboard
- **Overview**: Statistics, recent activity, quick actions
- **Schools Tab**: School management with club counts
- **Club Enrollments**: Student-club relationships

### School Management
- **Index**: Professional school listing with statistics
- **Create/Edit**: Comprehensive school information forms
- **Delete**: Safe deletion with relationship checks

### Club Management
- **Index**: Advanced club grid with filtering
- **Show**: Detailed club view with tabs for students, attendance, assessments
- **Create/Edit**: Full club creation with school selection
- **Delete**: Safe deletion with data preservation

### Student Management
- **Dashboard**: Student-focused interface with assignments and projects
- **Index**: Student listing with club relationships
- **Create/Edit**: Comprehensive student profiles

### Assessment System
- **Create**: Advanced assessment builder with file uploads
- **Index**: Assessment listing with statistics
- **Scores**: Grade management and tracking

### Attendance System
- **Grid View**: Interactive attendance tracking
- **Bulk Operations**: Mass attendance updates
- **Reports**: Attendance analytics and summaries

### Scratch IDE
- **Integrated Editor**: Full Scratch programming environment
- **Project Management**: Save, load, and share projects
- **Tutorials**: Built-in learning resources
- **Gallery**: Student project showcase

## 🔧 Configuration

### File Upload Settings
```php
// config/filesystems.php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
],
```

### Assessment File Types
- Images: JPEG, PNG, GIF, WebP
- Documents: PDF, DOC, DOCX, TXT
- Maximum file size: 10MB per file

### Scratch IDE Settings
- Integrated with official Scratch editor
- Project saving and loading
- Student collaboration features

## 🚀 Deployment

### Production Setup
1. **Server Requirements**
   - PHP 8.1+
   - MySQL 8.0+ or PostgreSQL
   - Redis (for caching)
   - SSL certificate

2. **Environment Configuration**
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   DB_CONNECTION=mysql
   CACHE_DRIVER=redis
   SESSION_DRIVER=redis
   ```

3. **Optimization**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   composer install --optimize-autoloader --no-dev
   ```

### Docker Deployment
```dockerfile
FROM php:8.1-fpm
# Add your Dockerfile configuration
```

## 📊 Performance & Security

### Performance Features
- **Database Optimization**: Proper indexing and relationships
- **Caching**: Redis-based caching for improved performance
- **Asset Optimization**: Minified CSS and JavaScript
- **Image Optimization**: Automatic image compression

### Security Features
- **Authentication**: Laravel Fortify integration
- **Authorization**: Role-based access control
- **File Upload Security**: Type validation and virus scanning
- **SQL Injection Protection**: Eloquent ORM with parameter binding
- **XSS Protection**: Input sanitization and output escaping

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Commit your changes: `git commit -m 'Add amazing feature'`
4. Push to the branch: `git push origin feature/amazing-feature`
5. Open a Pull Request

### Development Guidelines
- Follow PSR-12 coding standards
- Write comprehensive tests
- Update documentation
- Use meaningful commit messages

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- **Laravel Framework** - The PHP framework for web artisans
- **Tailwind CSS** - A utility-first CSS framework
- **Alpine.js** - A minimal framework for composing JavaScript behavior
- **Scratch** - Visual programming language for kids
- **Heroicons** - Beautiful hand-crafted SVG icons

## 📞 Support

For support, email support@codeclub.local or create an issue in the repository.

## 🔮 Roadmap

### Version 2.0
- [ ] Mobile app (React Native)
- [ ] Advanced analytics dashboard
- [ ] AI-powered assessment grading
- [ ] Video conferencing integration
- [ ] Multi-language support

### Version 2.1
- [ ] Parent mobile app
- [ ] Advanced reporting system
- [ ] Integration with learning management systems
- [ ] Automated attendance via facial recognition
- [ ] Advanced project collaboration tools

---

**Built with ❤️ for educators and students worldwide**