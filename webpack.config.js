const Encore = require('@symfony/webpack-encore');
const webpack = require('webpack');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin')


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

const webpackConfig = Encore.getWebpackConfig();

// Remove the old version first
webpackConfig.plugins = webpackConfig.plugins.filter(
    plugin => !(plugin instanceof webpack.optimize.UglifyJsPlugin)
);

// Add the new one
webpackConfig.plugins.push(new UglifyJsPlugin());

// export the final configuration
module.exports = webpackConfig;
