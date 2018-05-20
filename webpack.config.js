var Encore = require('@symfony/webpack-encore');

Encore
    .configureBabel(function(babelConfig) {
        // add additional presets
        babelConfig.presets.push('es2017');
        // no plugins are added by default, but you can add some
    })

    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
    .autoProvideVariables({
        Clipboard: 'Clipboard'
    })
    .addEntry(['js/app'], './assets/js/app.js')
    .addStyleEntry('css/app', './assets/css/app.scss')

    // uncomment if you use Sass/SCSS files
    .enableSassLoader()

    // uncomment for legacy applications that require $/jQuery as a global variable
    .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
