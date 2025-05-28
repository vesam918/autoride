# AutoRide WordPress Theme

A modern, responsive WordPress theme for car rental and automotive businesses, built with Tailwind CSS and modern PHP architecture.

## Features

- Modern, clean design with Tailwind CSS
- Fully responsive layout
- Object-oriented PHP 7.4+ architecture
- Namespaced class structure
- WooCommerce compatible
- Custom booking system
- Advanced vehicle management
- Optimized pagination
- Maintenance mode support
- Modern development workflow

## Requirements

- WordPress 6.0 or higher
- PHP 7.4 or higher
- Node.js 14.0 or higher
- npm or yarn

## Theme Architecture

```
autoride/
├── inc/                    # PHP includes
│   └── Theme/             # Theme namespace
│       ├── Core/          # Core functionality
│       │   ├── ThemeSetup.php
│       │   ├── Post.php
│       │   ├── Header.php
│       │   ├── Helper.php
│       │   ├── Validation.php
│       │   └── WidgetArea.php
│       ├── Admin/         # Admin functionality
│       ├── Assets/        # Asset management
│       ├── Features/      # Theme features
│       └── Integration/   # Third-party integrations
├── resources/             # Frontend source files
│   ├── js/               # JavaScript files
│   └── scss/             # SCSS files
└── public/               # Compiled assets
```

## Installation

1. **Install WordPress**
   - Download WordPress from [wordpress.org](https://wordpress.org/download/)
   - Set up your local environment (e.g., XAMPP, MAMP, or Local)
   - Install WordPress following the "Famous 5-Minute Installation"

2. **Install Theme via WordPress Admin**
   - Go to WordPress Admin → Appearance → Themes
   - Click "Add New" → "Upload Theme"
   - Choose the autoride.zip file
   - Click "Install Now"
   - After installation, click "Activate"

3. **Install Required Plugins**
   - After theme activation, you'll see a notice for required plugins
   - Click "Begin installing plugins"
   - Select all recommended plugins
   - Click "Install"
   - After installation, activate all plugins

## Development

1. **Install Dependencies**
   ```bash
   # Install PHP dependencies
   composer install

   # Install Node dependencies
   npm install
   ```

2. **Build Assets**
   ```bash
   # Development with watch
   npm run watch

   # Production build
   npm run production
   ```

## Theme Structure

### Core Classes

- **ThemeSetup**: Main theme initialization and setup
- **Post**: Handles post data and meta information
- **Header**: Manages header rendering and configuration
- **Helper**: Utility functions for common operations
- **Validation**: Input validation and sanitization
- **WidgetArea**: Widget area registration and rendering

### Key Features

- Modern PHP architecture with namespaced classes
- Autoloading using PSR-4 standard
- Type declarations for better reliability
- Separation of concerns with modular design
- Security-first approach with input validation
- Optimized asset loading and management

## Support

For support and bug reports, please create an issue in the GitHub repository.

## License

This theme is licensed under the GPL v2 or later.

## Credits

- Built with [Tailwind CSS](https://tailwindcss.com)
- Icons by [Lucide](https://lucide.dev)
- Fonts by [Google Fonts](https://fonts.google.com)
