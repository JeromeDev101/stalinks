const mix = require('laravel-mix');

/**
 * CKE stuff
 */

Mix.listen('configReady', webpackConfig => {
    const rules = webpackConfig.module.rules;
    const targetSVG = /(\.(png|jpe?g|gif|webp)$|^((?!font).)*\.svg$)/;
    const targetFont = /(\.(woff2?|ttf|eot|otf)$|font.*\.svg$)/;
    const targetCSS = /\.css$/;

    // exclude CKE regex from mix's default rules
    for (let rule of rules) {
        if (rule.test.toString() === targetSVG.toString()) {
            rule.exclude = CKERegex.svg;
        }
        else if (rule.test.toString() === targetFont.toString()) {
            rule.exclude = CKERegex.svg;
        }
        else if (rule.test.toString() === targetCSS.toString()) {
            rule.exclude = CKERegex.css;
        }
    }
});

const CKEditorWebpackPlugin = require( '@ckeditor/ckeditor5-dev-webpack-plugin' );
const CKEStyles = require('@ckeditor/ckeditor5-dev-utils').styles;
const CKERegex = {
    svg: /ckeditor5-[^/\\]+[/\\]theme[/\\]icons[/\\][^/\\]+\.svg$/,
    css: /ckeditor5-[^/\\]+[/\\]theme[/\\].+\.css/,
};

mix.webpackConfig({
    plugins: [
        new CKEditorWebpackPlugin({
            language: 'en'
        })
    ],
    module: {
        rules: [
            {
                test: CKERegex.svg,
                use: [ 'raw-loader' ]
            },
            {
                test: CKERegex.css,
                use: [
                    {
                        loader: 'style-loader',
                        options: {
                            singleton: true
                        }
                    },
                    {
                        loader: 'postcss-loader',
                        options: CKEStyles.getPostCssConfig({
                            themeImporter: {
                                themePath: require.resolve('@ckeditor/ckeditor5-theme-lark')
                            },
                            minify: true
                        })
                    },
                ]
            }
        ]
    },
    resolve: {
      extensions: ['.js', '.vue', '.json'],
      alias: {
        '@': path.resolve('resources/js'),
        'scss@': path.resolve('resources/sass'),
        'public' : path.resolve('public'),
      },
    },
  })
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.copyDirectory('resources/images', 'public/images');
mix.js('resources/js/app.js', 'public/build/js');
mix.sass('resources/sass/app.scss', 'public/build/css');

mix.styles([
  'node_modules/bootstrap/dist/css/bootstrap.min.css',
  'node_modules/Ionicons/css/ionicons.min.css',
  'node_modules/admin-lte/dist/css/AdminLTE.css',
  'node_modules/admin-lte/dist/css/skins/_all-skins.min.css',
  'node_modules/morris.js/morris.css',
  'node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
  'node_modules/bootstrap-daterangepicker/daterangepicker.css',
  'node_modules/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css',
  'node_modules/jvectormap/jquery-jvectormap.css',
  'node_modules/font-awesome/css/font-awesome.css',
  'node_modules/jquery-contextmenu/dist/jquery.contextMenu.css',
  'node_modules/datatables.net-dt/css/jquery.dataTables.css',
  'node_modules/tinymce/skins/lightgray/skin.min.css',
  'node_modules/admin-lte/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css',
], 'public/build/css/vendor.css');


mix.scripts([
  'node_modules/treantjs/vendor/raphael.js',
  'node_modules/treantjs/Treant.js',
  'node_modules/admin-lte/dist/js/demo.js',
  'node_modules/alertifyjs/build/alertify.min.js',
  'node_modules/jquery-freeze-table/dist/js/freeze-table.js',
  'node_modules/datatables.net/js/jquery.dataTables.js',
  'node_modules/datatables.net-dt/js/dataTables.dataTables.js',
  'node_modules/tinymce/tinymce.min.js',
  'node_modules/admin-lte/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js',
], 'public/build/js/vendor.js');

mix.styles(['public/build/css/vendor.css', 'public/build/css/app.css'], 'public/css/app.css');
mix.scripts(['public/build/js/app.js', 'public/build/js/vendor.js'], 'public/js/app.js');
mix.disableSuccessNotifications();
