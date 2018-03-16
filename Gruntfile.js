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

		eslint: {
			src: [
				'js/*.js',
				'!js/*min..js'
			],
			options: {
				extends: 'wordpress',
				fix: true,
				rules: {
					camelcase: [ 'error', { properties: 'never' } ],
					yoda: [ 'error', 'always', { onlyEquality: true } ]
				}
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
	grunt.loadNpmTasks( 'grunt-eslint' );

	// PHP
	grunt.registerTask( 'php', [ 'phpcs' ] );

	// JavaScript
	grunt.registerTask( 'js', [ 'eslint', 'uglify' ] );

	// Default task.
	grunt.registerTask( 'default', [ 'php' ] );

	grunt.util.linefeed = '\n';
};
