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

1. **Download and Install WordPress**
   ```bash
   # Make sure you have WordPress installed and running
   ```

2. **Install Theme**
   - Download this repository
   - Upload to `/wp-content/themes/` directory
   - Activate the theme through WordPress admin panel

3. **Install Dependencies**
   ```bash
   # Navigate to theme directory
   cd wp-content/themes/autoride

   # Install PHP dependencies
   composer install

   # Install Node dependencies
   npm install
   ```

4. **Build Assets**
   ```bash
   # Development build with watch
   npm run dev

   # Production build
   npm run build
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
