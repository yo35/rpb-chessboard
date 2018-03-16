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
		},

		shell: {
			stats: {
				command: [
					"echo",
					"echo '\033[34;1mJavaScript source code\033[0m'",
					"assets/dev-tools/statistics.sh `find js -name '*.js' -not -name '*.min.js'`",
					"echo",
					"echo '\033[34;1mPHP source code\033[0m'",
					"assets/dev-tools/statistics.sh `find rpb-chessboard.php css fonts helpers images js languages models templates wp -name '*.php'`",
					"echo"
				].join( '&&' )
			}
		},

		clean: [
			'.temp',
			'cache',
			'rpb-chessboard.zip',
			'languages/*.mo',
			'js/*.min.js'
		]
	});

	grunt.loadNpmTasks( 'grunt-phpcs' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-eslint' );
	grunt.loadNpmTasks( 'grunt-shell' );
	grunt.loadNpmTasks( 'grunt-contrib-clean' );
	grunt.loadNpmTasks( 'grunt-mkdir' );
	grunt.loadNpmTasks( 'grunt-contrib-copy' );

	grunt.registerTask( 'done', function() {
		grunt.log.writeln( grunt.config.get( 'deployment.file' )['blue'].bold + ' updated'['blue'].bold );
	});

	// PHP
	grunt.registerTask( 'php', [ 'phpcs' ] );

	// JavaScript
	grunt.registerTask( 'js', [ 'eslint', 'uglify' ] );

	// Shell
	grunt.registerTask( 'stats', [ 'shell' ] );

	// Default task.
	grunt.registerTask( 'default', [ 'php', 'js' ] );

	grunt.util.linefeed = '\n';
};
