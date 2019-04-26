module.exports = function(grunt) {
	require('jit-grunt')(grunt);

	grunt.initConfig({
		less: {
			development: {
				options: {
				compress: true,
				yuicompress: true,
				optimization: 2
		},
		files: {
			"css/uwdug.css": "less/uwdug.less" // destination file and source file
			}
		}
	},
	watch: {
		styles: {
			files: ['less/**/*.less'], // which files to watch
			tasks: ['less'],
			options: {
				nospawn: true
				}
			}
		}
	});

	grunt.registerTask('default', ['less', 'watch']);
};
