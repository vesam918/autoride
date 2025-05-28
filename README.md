# AutoRide WordPress Theme

A modern, responsive WordPress theme for car rental and automotive businesses, built with Tailwind CSS.

## Features

- Modern, clean design
- Fully responsive layout
- Built with Tailwind CSS
- WooCommerce compatible
- Custom booking system
- Advanced vehicle management
- Optimized pagination
- Maintenance mode support

## Requirements

- WordPress 6.0 or higher
- PHP 7.4 or higher
- Node.js 14.0 or higher
- npm or yarn

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

4. **Theme Setup via WordPress Admin**
   - Go to Appearance → Customize
   - Configure theme settings:
     - Site Identity (logo, title, tagline)
     - Colors
     - Typography
     - Layout Options
     - Menu Locations
     - Homepage Settings
   - Click "Publish" to save changes

5. **Import Demo Content** (Optional)
   - Go to Tools → Demo Import
   - Click "Import Demo Data"
   - Wait for the import to complete
   - Your site will now look like the demo

## Development Setup (Optional)

If you want to customize theme assets (CSS/JS), you'll need:

1. **Install Development Tools**
   - Install [Composer](https://getcomposer.org/)
   - Install [Node.js](https://nodejs.org/)

2. **Access Theme via Terminal**
   ```bash
   # Navigate to WordPress themes directory
   cd path/to/wordpress/wp-content/themes/autoride
   ```

3. **Install Development Dependencies**
   ```bash
   # Install PHP dependencies (if modifying PHP functionality)
   composer install

   # Install Node dependencies (if modifying CSS/JS)
   npm install
   ```

4. **Work with Assets**
   ```bash
   # Watch for changes during development
   npm run watch

   # Build for production before deploying
   npm run production
   ```

## Configuration

1. **Theme Settings**
   - Go to WordPress Admin → Appearance → Customize
   - Configure theme colors, typography, and layout options

2. **WooCommerce Setup** (if using e-commerce)
   - Install and activate WooCommerce
   - Follow WooCommerce setup wizard
   - Configure theme's WooCommerce integration settings

3. **Booking System**
   - Configure booking settings in Theme Options
   - Set up vehicle categories and availability
   - Customize booking form fields

## Development

### File Structure
```
autoride/
├── inc/                  # PHP includes
├── resources/           # Source files
│   ├── js/             # JavaScript files
│   └── scss/           # SCSS files
├── public/             # Compiled assets
├── template-parts/     # Template partials
└── functions.php       # Theme functions
```

### Build Commands
```bash
# Watch for changes during development
npm run watch

# Build for production
npm run production
```

## Customization

1. **Styles**
   - Edit `tailwind.config.js` for theme customization
   - Modify SCSS files in `resources/scss/`
   - Add custom styles to `resources/scss/app.scss`

2. **Templates**
   - Override WooCommerce templates in `woocommerce/`
   - Modify page templates in `template-parts/`
   - Create custom page templates in theme root

3. **Functions**
   - Add custom functionality to `functions.php`
   - Create new functionality in `inc/` directory

## Support

For support and bug reports, please create an issue in the GitHub repository.

## License

This theme is licensed under the GPL v2 or later.

## Credits

- Built with [Tailwind CSS](https://tailwindcss.com)
- Icons by [Lucide](https://lucide.dev)
- Font by [Google Fonts](https://fonts.google.com)
