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
					'echo',
					'echo "<%= color.in %>JavaScript source code<%= color.out %>"',
					'assets/dev-tools/statistics.sh `find js -name "*.js" -not -name "*.min.js"`',
					'echo',
					'echo "<%= color.in %>PHP source code<%= color.out %>"',
					'assets/dev-tools/statistics.sh `find <%= src.main.file %> <%= src.folders.join(" ") %> -name "*.php"`',
					'echo'
				].join( '&&' )
			}
		},

		mkdir: {
			snapshot: {
				options: {
					create: [ '<%= snapshot.folder %>/<%= plugin.name %>' ]
				}
			},
			assets: {
				options: {
					create: [ '<%= snapshot.folder %>/<%= plugin.name %>-assets' ]
				}
			}
		},

		clean: {
			build: [
			'.temp',
			'cache',
			'rpb-chessboard.zip',
			'languages/*.mo',
			'js/*.min.js'
			],
			release: [
				'.temp/snapshot',
				'rpb-chessboard.zip'
			]
		}
	});

	grunt.config.set( 'plugin.name', 'rpb-chessboard' );
	grunt.config.set( 'deployment.file', '<%= plugin.name %>.zip' );
	grunt.config.set( 'temp.folder', '.temp' );
	grunt.config.set( 'snapshot.folder', '<%= temp.folder %>/snapshot' );
	grunt.config.set( 'src.main.file', '<%= plugin.name %>.php' );
	grunt.config.set( 'src.folders', [ 'css', 'fonts', 'helpers', 'images', 'js', 'languages', 'models', 'templates', 'wp' ] );
	grunt.config.set( 'third.party.folder', 'third-party-libs' );
	grunt.config.set( 'asset.folder', 'assets' );
	grunt.config.set( 'cache.folder', 'cache' );
	grunt.config.set( 'wordpress.readme.file', 'wordpress.readme.txt' );
	grunt.config.set( 'info.files', [ 'LICENSE', 'examples.pgn' ] );

	grunt.config.set( 'color.in', '\033[34;1m' );
	grunt.config.set( 'color.out', '\033[0m' );
	grunt.config.set( 'color.arg.in', '\033[31m' );
	grunt.config.set( 'color.arg.out', '\033[34m' );
	grunt.config.set( 'color.item.in', '\033[35;1m' );
	grunt.config.set( 'color.item.out', '\033[0m' );

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
