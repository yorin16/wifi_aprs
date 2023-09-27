const Encore = require('@symfony/webpack-encore');

const isDev = process.env.npm_lifecycle_event === 'dev';

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

const publicPath = isDev ? '/build' : '/public/build/';

Encore
    .setOutputPath('public/build/')
    .setPublicPath(publicPath)
    .addEntry('app', './assets/app.js')
    .enableStimulusBridge('./assets/controllers.json')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!isDev)
    .enableVersioning(isDev)
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })
    .enableSassLoader()
    .autoProvidejQuery();

module.exports = Encore.getWebpackConfig();
