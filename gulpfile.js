var elixir = require('laravel-elixir');

var publicDir = 'public_html/';
var bowerDir = './resources/assets/vendor/';

elixir(function(mix) {
    mix.less('app.less', publicDir + 'css', { paths: [
        bowerDir + 'bootstrap/less',
        bowerDir + 'blueimp-file-upload/css'
    ] })
        .scripts([
            'bootstrap/js/tooltip.js',
            'bootstrap/js/popover.js'
            ], publicDir + 'js/vendor.js', bowerDir)
        .scripts([
            'blueimp-file-upload/js/vendor/jquery.ui.widget.js',
            'blueimp-file-upload/js/jquery.iframe-transport.js',
            'blueimp-file-upload/js/jquery.fileupload.js'
        ], publicDir + 'js/fileupload.js', bowerDir)
        .copy('resources/assets/js/app.js', publicDir + 'js/app.js')
        .copy('resources/assets/js/upload.js', publicDir + 'js/upload.js');
});
