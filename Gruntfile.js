module.exports = function( grunt ) {

	// Project configuration
	grunt.initConfig( {
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
	} );

	grunt.loadNpmTasks( 'grunt-phpcs' );

	// PHP Only
	grunt.registerTask( 'php', [ 'phpcs' ] );

	// Default task.
	grunt.registerTask( 'default', [ 'php' ] );

	grunt.util.linefeed = '\n';
};
