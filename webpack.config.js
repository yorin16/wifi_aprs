const Encore = require('@symfony/webpack-encore');
const isProduction = process.env.NODE_ENV === 'production';

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

const publicPath = isProduction ? '/public/build/' : '/build';

Encore
    .setOutputPath('public/build/')
    .setPublicPath(publicPath)
    .addEntry('app', './assets/app.js')
    .enableStimulusBridge('./assets/controllers.json')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!isProduction)
    .enableVersioning(isProduction)
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })
    .enableSassLoader()
    .autoProvidejQuery();

module.exports = Encore.getWebpackConfig();
