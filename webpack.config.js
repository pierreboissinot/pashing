var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
    .addEntry('app', './assets/js/app.js')
    
    .addEntry('main', './assets/js/main.js')
    
    .enableVueLoader()
    
    // uncomment if you use Sass/SCSS files
    .enableSassLoader()

    // enable source maps during development
    .enableSourceMaps(!Encore.isProduction())
    
    // empty the outputPath dir before each build
    .cleanupOutputBeforeBuild()
    
    // show OS notifications when builds finish/fail
    .enableBuildNotifications()

;

module.exports = Encore.getWebpackConfig();
