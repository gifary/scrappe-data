let mix = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');

mix.scripts([
    'resources/assets/bower/jquery/dist/jquery.js',
    'resources/assets/bower/moment/min/moment.min.js',
    'resources/assets/bower/bootstrap/dist/js/bootstrap.js',
    'resources/assets/bower/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
    'resources/assets/bower/jquery-validation/dist/jquery.validate.js',
    'resources/assets/bower/jquery-validation/dist/additional-methods.js',
    'resources/assets/bower/jquery.steps/build/jquery.steps.js',
    'resources/assets/bower/select2/dist/js/select2.full.js',
    'resources/assets/bower/datatables.net/js/jquery.dataTables.js',
    'resources/assets/js/typeahead.js',
    'resources/assets/js/bootstrap-tagsinput.js'
], 'public/js/vendor.js');

mix.styles([
    'resources/assets/bower/bootstrap/dist/css/bootstrap.css',
    'resources/assets/bower/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
    'resources/assets/bower/jquery.steps/demo/css/jquery.steps.css',
    'resources/assets/bower/select2/dist/css/select2.css',
    'resources/assets/bower/datatables.net-dt/css/jquery.dataTables.css',
    'resources/assets/css/bootstrap-tagsinput.css'
], 'public/css/all.css');
