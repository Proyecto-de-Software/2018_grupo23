const Encore = require('@symfony/webpack-encore');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const Dotenv = require('dotenv-webpack');

if(Encore.isProduction()){
    Encore.setPublicPath('/final/deploy/public/build')
    //Encore.setPublicPath('/Proyecto/grupo23/final/deploy/public/build')
    //Encore.setPublicPath('/build')
}else{
    Encore.setPublicPath('/build')
}

Encore
    // the project directory where all compiled assets will be stored
    .setOutputPath('public/build/')

    .setManifestKeyPrefix('build')

    // will create web/build/app.js and web/build/app.css
    .addEntry('app', './assets/js/app.js')

    .addPlugin(new CopyWebpackPlugin([
        // copies to {output}/images
        { from: './assets/img', to: 'images' }
    ]))

    .addPlugin(new Dotenv())

    // allow legacy applications to use $/jQuery as a global variable
    .autoProvidejQuery()

    // enable source maps during development
    .enableSourceMaps(!Encore.isProduction())

    // empty the outputPath dir before each build
    .cleanupOutputBeforeBuild()

    // show OS notifications when builds finish/fail
    .enableBuildNotifications()

    // create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning()

    .enableSingleRuntimeChunk()

    // allow sass/scss files to be processed
    // .enableSassLoader()

    .enableVueLoader()

    ;

// export the final configuration
var config = Encore.getWebpackConfig();
//config.node = { fs: 'empty' };
module.exports = config;