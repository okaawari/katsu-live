# Anime Streaming Website - Laravel 12

A fresh Laravel 12 implementation of your anime streaming website, migrated from the original project with improved architecture and modern Laravel features.

## Features

- **User Authentication**: Complete user registration, login, and profile management
- **Anime Catalog**: Browse and discover anime content
- **Video Streaming**: Watch anime episodes with progress tracking
- **User Sessions**: Track and manage user viewing sessions  
- **Roles & Permissions**: Role-based access control using Laratrust
- **Responsive Design**: Modern UI built with Tailwind CSS and Livewire
- **API Support**: RESTful API with Laravel Sanctum authentication

## Technology Stack

### Backend
- **Laravel 12**: Latest Laravel framework
- **PHP 8.4**: Modern PHP version
- **SQLite**: Database (can be switched to MySQL/PostgreSQL)
- **Laravel Sanctum**: API authentication
- **Laratrust**: Role and permission management
- **Livewire**: Real-time UI components

### Frontend  
- **Tailwind CSS**: Utility-first CSS framework
- **Livewire Volt**: Modern component architecture
- **Vite**: Fast build tool
- **TW Elements**: UI components

### Additional Packages
- **Intervention Image**: Image processing
- **Jenssegers Agent**: User agent detection
- **Predis**: Redis client
- **Laravel Breeze**: Authentication scaffolding

## Database Schema

The application includes the following main entities:

- **Users**: User accounts and profiles
- **Animes**: Anime series information
- **Episodes**: Individual episode data
- **Categories & Tags**: Content organization
- **Video Watch Progress**: User viewing progress tracking
- **User Sessions**: Session management
- **Roles & Permissions**: Access control system
- **Anime Lists**: User's personal anime lists
- **Comments**: User comments and reviews

## Installation

1. **Clone the project**:
   ```bash
   cd anime-streaming-v12
   ```

2. **Install dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**:
   ```bash
   php artisan migrate
   ```

5. **Build assets**:
   ```bash
   npm run build
   ```

6. **Start the server**:
   ```bash
   php artisan serve
   ```

## Key Routes

- `/` - Welcome page
- `/dashboard` - User dashboard
- `/home` - Anime home page
- `/browse` - Browse anime catalog
- `/watch/{id}` - Watch anime episode
- `/profile` - User profile
- `/pricing` - Subscription plans

## API Endpoints

- `POST /api/save-progress` - Save video progress
- `GET /api/get-progress/{animeId}` - Get video progress
- `DELETE /api/watching/{id}` - Remove from watching list

## Configuration

The project is configured with:

- **Storage**: Public disk for file uploads
- **Cache**: Redis support via Predis
- **Queue**: Database queue driver
- **Mail**: SMTP configuration ready
- **Authentication**: Laravel Breeze with Livewire

## Development

To work on this project:

1. **Run development server**:
   ```bash
   php artisan serve
   ```

2. **Watch for changes**:
   ```bash
   npm run dev
   ```

3. **Run tests**:
   ```bash
   php artisan test
   ```

## Migration Notes

This Laravel 12 project was migrated from an original Laravel project with the following improvements:

✅ **Completed Migrations**:
- Database schema and migrations
- All models with Laravel 12 compatibility
- Controllers and routing system
- Blade views and Livewire components
- Frontend assets (CSS/JS)
- Package dependencies
- Configuration files

✅ **Modern Laravel Features**:
- Updated to Laravel 12 framework
- Improved Eloquent relationships
- Enhanced security features
- Better performance optimizations
- Modern authentication system

## Next Steps

To fully deploy this application:

1. Configure production environment variables
2. Set up file storage (S3/local)
3. Configure email services
4. Set up Redis for caching
5. Configure database backups
6. Set up monitoring and logging

## Support

For any issues or questions about this Laravel 12 migration, please refer to the Laravel documentation or create an issue.
