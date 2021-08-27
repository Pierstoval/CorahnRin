var Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the 'encore' command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    // When enabled, Webpack 'splits' your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    //.cleanupOutputBeforeBuild()
    //.enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(true)

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    .enableSassLoader()
    //.enableLessLoader()
    .enableTypeScriptLoader()

    // uncomment to get integrity='...' attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    .autoProvidejQuery()

    // uncomment if you use API Platform Admin (composer req api-admin)
    //.enableReactPreset()
    //.addEntry('admin', './assets/js/admin.js')


    .copyFiles({
        from: './node_modules/@fortawesome/fontawesome-free/webfonts/',
        to: 'fonts/[path][name].[ext]'
    })
    /*
     * Commented here because leaflet-draw uses the same base images as Leaflet.
     * If it ever changes, feel free to check it out and uncomment this ;)
    .copyFiles({
        from: './node_modules/leaflet/dist/images/',
        to: 'images/[path][name].[ext]'
    })
     */
    .copyFiles({
        from: './node_modules/leaflet-draw/dist/images/',
        to: 'images/[path][name].[ext]'
    })
    .copyFiles({
        from: './assets/esteren/fonts/',
        to: 'fonts/[path][name].[ext]'
    })
    .copyFiles({
        from: './assets/flags/',
        to: 'flags/[path][name].[ext]'
    })
    .copyFiles({
        from: './assets/corahn_rin/img/',
        to: 'generator/[path][name].[ext]'
    })
    .copyFiles({
        from: './assets/esteren/images/',
        to: '[path][name].[ext]'
    })


    .addStyleEntry('style_global', './assets/esteren/sass/main.scss')
    .addStyleEntry('generator_styles', './assets/corahn_rin/generator/css/main_steps.scss')
    .addStyleEntry('white_layout', './assets/esteren/sass/white_layout.scss')
    .addStyleEntry('initializer', './assets/esteren/css/initializer.css')
    .addStyleEntry('feond-beer', './assets/esteren/css/feond-beer.css')
    .addStyleEntry('character_details', './assets/corahn_rin/generator/css/character_details.scss')
    .addStyleEntry('character_details_print', './assets/corahn_rin/generator/css/character_details_print.scss')
    .addStyleEntry('fa', './node_modules/@fortawesome/fontawesome-free/css/all.css')


    .addEntry('global', [
        './node_modules/@materializecss/materialize/dist/js/materialize.js',
        './assets/esteren/js/helpers.js',
        './assets/esteren/js/global.js'
    ])
    .addEntry('generator', ['./assets/corahn_rin/generator/js/main_steps.js'])
    .addEntry('character_edit', [
        './assets/corahn_rin/character_edit/character_edit.js',
        './assets/corahn_rin/character_edit/inventory.js',
    ])
    .addEntry('character_spend_xp', ['./assets/corahn_rin/character_spend_xp.js'])
    .addEntry('step_03_birthplace', ['./assets/corahn_rin/generator/js/step_03_birthplace.js'])
    .addEntry('step_11_advantages', [
        './assets/corahn_rin/generator/js/step_11_advantage.js',
    ])
    .addEntry('step_13_primary_domains', ['./assets/corahn_rin/generator/js/step_13_primary_domains.js'])
    .addEntry('step_14_use_domain_bonuses', ['./assets/corahn_rin/generator/js/step_14_use_domain_bonuses.js'])
    .addEntry('step_15_domains_spend_exp', ['./assets/corahn_rin/generator/js/step_15_domains_spend_exp.js'])
    .addEntry('step_16_disciplines', ['./assets/corahn_rin/generator/js/step_16_disciplines.js'])
    .addEntry('step_17_combat_arts', ['./assets/corahn_rin/generator/js/step_17_combat_arts.js'])
    .addEntry('step_18_equipment', ['./assets/corahn_rin/generator/js/step_18_equipment.js'])
    .addEntry('admin', ['./assets/admin/main.js'])
;

if (Encore.isProduction()) {
    Encore.cleanupOutputBeforeBuild();
}

module.exports = Encore.getWebpackConfig();
