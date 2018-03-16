module.exports = function( grunt ) {

	// Project configuration
	grunt.initConfig({
		pkg: grunt.file.readJSON( 'package.json' ),

		phpcs: {
			application: {
				src: [ '*.php', 'helpers/*.php', 'models/**/*.php', 'template/*.php', 'templates/**/*.php', 'wp/*.php' ]
			},
			options: {
				bin: 'vendor/bin/phpcs',
				standard: 'phpcs.xml'
			}
		},

		uglify: {
			all: {
				files: [ {
					expand: true,
					cwd: 'js',
					src: [ '*.js', '!*.min.js' ],
					dest: 'js',
					ext: '.min.js'
				} ]
			}
		}
	});

	grunt.loadNpmTasks( 'grunt-phpcs' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );

	// PHP
	grunt.registerTask( 'php', [ 'phpcs' ] );

	// JavaScript
	grunt.registerTask( 'js', [ 'uglify' ] );

	// Default task.
	grunt.registerTask( 'default', [ 'php' ] );

	grunt.util.linefeed = '\n';
};
