var Encore = require('@symfony/webpack-encore')

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev')
}

const serverside = Encore
    .setOutputPath('build/')
    .setPublicPath('/')

    .addEntry('liform', './assets/js/liform.jsx')

    .enableReactPreset()

    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()

    .enableSourceMaps(!Encore.isProduction())

.getWebpackConfig()

Encore.reset()

const clientside = Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    .addEntry('liform', ['./assets/js/liform.jsx', './assets/css/liform.css'])

    .enableReactPreset()

    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()

    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enableIntegrityHashes(Encore.isProduction())

.getWebpackConfig()

module.exports = [serverside, clientside]
