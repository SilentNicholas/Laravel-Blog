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

mix.styles([
	'resources/admin/assets/bootstrap/css/bootstrap.min.css',
	'resources/admin/assets/font-awesome/4.5.0/css/font-awesome.min.css',
	'resources/admin/assets/ionicons/2.0.1/css/ionicons.min.css',
	'resources/admin/assets/plugins/iCheck/minimal/_all.css',
	'resources/admin/assets/plugins/datepicker/datepicker3.css',
	'resources/admin/assets/plugins/select2/select2.min.css',
	'resources/admin/assets/plugins/datatables/dataTables.bootstrap.css',
	'resources/admin/assets/dist/css/AdminLTE.min.css',
	'resources/admin/assets/dist/css/skins/_all-skins.min.css'
], 'public/css/admin.css');

mix.scripts([
	'resources/admin/assets/plugins/jQuery/jquery-2.2.3.min.js',
	'resources/admin/assets/bootstrap/js/bootstrap.min.js',
	'resources/admin/assets/plugins/select2/select2.full.min.js',
	'resources/admin/assets/plugins/datepicker/bootstrap-datepicker.js',
	'resources/admin/assets/plugins/datatables/jquery.dataTables.min.js',
	'resources/admin/assets/plugins/datatables/dataTables.bootstrap.min.js',
	'resources/admin/assets/plugins/slimScroll/jquery.slimscroll.min.js',
	'resources/admin/assets/plugins/fastclick/fastclick.js',
	'resources/admin/assets/plugins/iCheck/icheck.min.js',
	'resources/admin/assets/dist/js/app.min.js',
	'resources/admin/assets/dist/js/demo.js',
	'resources/admin/assets/dist/js/scripts.js'
], 'public/js/admin.js');

mix.copy('resources/admin/assets/bootstrap/fonts', 'public/fonts');
mix.copy('resources/admin/assets/dist/fonts', 'public/fonts');
mix.copy('resources/admin/assets/dist/img', 'public/img');
mix.copy('resources/admin/assets/plugins/iCheck/minimal/blue.png', 'public/css');

mix.styles([
	'resources/front/css/bootstrap.min.css',
	'resources/front/css/font-awesome.min.css',
	'resources/front/css/animate.min.css',
	'resources/front/css/owl.carousel.css',
	'resources/front/css/owl.theme.css',
	'resources/front/css/owl.transitions.css',
	'resources/front/css/style.css',
	'resources/front/css/responsive.css'
],'public/css/front.css');

mix.scripts([
	'resources/front/js/jquery-1.11.3.min.js',
	'resources/front/js/bootstrap.min.js',
	'resources/front/js/owl.carousel.min.js',
	'resources/front/js/jquery.stickit.min.js',
	'resources/front/js/menu.js',
	'resources/front/js/scripts.js'
], 'public/js/front.js');

mix.copy('resources/front/fonts', 'public/fonts');
mix.copy('resources/front/images', 'public/images');