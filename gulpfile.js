var elixir = require('laravel-elixir');
require('laravel-elixir-vueify');

elixir(function(mix) {
    mix.sass('app.scss');
    mix.browserify('app.js');

    mix.copy('node_modules/bootstrap-sass/assets/fonts', 'public/fonts');
});
