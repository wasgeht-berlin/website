var elixir = require('laravel-elixir');

require('laravel-elixir-vueify');
require('laravel-elixir-spritesmith');

elixir(function(mix) {
    mix.spritesmith('resources/assets/icons/', {
        imgOutput: 'public/img',
        imgName: 'icons.png',

        cssOutput: 'resources/assets/sass',
        cssName: '_icons.scss',

        cssClass: function (item) { return 'icons_' + item.name; },

        'spritesheet-image': 'img/icons.png'
    });

    mix.sass('app.scss');
    mix.browserify('app.js');

    mix.copy('node_modules/bootstrap-sass/assets/fonts', 'public/fonts');
});
