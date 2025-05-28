const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

// Set public path
mix.setPublicPath('assets');

// Disable success notifications
mix.disableSuccessNotifications();

// Process JavaScript
mix.js('resources/js/app.js', 'assets/js')
   .js('resources/js/admin.js', 'assets/js')
   .js('resources/js/blocks.js', 'assets/js')
   .react()
   .sourceMaps();

// Process SCSS
mix.sass('resources/scss/app.scss', 'assets/css')
   .sass('resources/scss/admin.scss', 'assets/css')
   .sass('resources/scss/editor-style.scss', 'assets/css')
   .options({
       processCssUrls: false,
       postCss: [
           tailwindcss('./tailwind.config.js'),
           require('autoprefixer'),
       ],
   })
   .sourceMaps();

// Copy and optimize images
mix.copyDirectory('resources/images', 'assets/images');

// Copy fonts
mix.copyDirectory('resources/fonts', 'assets/fonts');

// Version files in production
if (mix.inProduction()) {
    mix.version();
}

// Configure BrowserSync
mix.browserSync({
    proxy: 'localhost:8000',
    files: [
        'assets/**/*',
        '**/*.php',
    ],
    open: false
});

// Configure Webpack
mix.webpackConfig({
    externals: {
        jquery: 'jQuery',
        '@wordpress/element': 'wp.element',
        '@wordpress/blocks': 'wp.blocks',
        '@wordpress/components': 'wp.components',
        '@wordpress/i18n': 'wp.i18n'
    },
    optimization: {
        splitChunks: {
            cacheGroups: {
                commons: {
                    test: /[\\/]node_modules[\\/]/,
                    name: 'vendor',
                    chunks: 'all'
                }
            }
        }
    }
});

// Enable source maps for development
if (!mix.inProduction()) {
    mix.webpackConfig({
        devtool: 'source-map'
    });
}

// Add resource collection
mix.extract();

// Add version string to assets in production
if (mix.inProduction()) {
    mix.version();
}

// Disable processing URLs in CSS
mix.options({
    processCssUrls: false
});

// Configure Mix options
mix.options({
    terser: {
        extractComments: false,
        terserOptions: {
            compress: {
                drop_console: mix.inProduction()
            }
        }
    },
    cssNano: {
        discardComments: {
            removeAll: true
        }
    }
});

// Disable OS notifications
mix.disableNotifications();
