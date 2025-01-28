module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({

    // Clean the build directory
    clean: {
      build: ['build/']
    },

    // Copy necessary files to the build directory
    copy: {
      main: {
        files: [
          // Copy PHP files
          {expand: true, src: ['**/*.php'], dest: 'build/'},
          // Copy the compiled JavaScript file
          {expand: true, src: ['view/dist/**'], dest: 'build/'},
          // Include other necessary files, like readme and license
          {expand: true, src: ['README.md'], dest: 'build/'},
        ],
      },
    },

    // Compress the build directory into a zip file - located at build/super-logistics.zip
    compress: {
      main: {
        options: {
          archive: 'build/super-logistics.zip'
        },
        files: [
          {expand: true, cwd: 'build/', src: ['**'], dest: ''}
        ]
      }
    },

  });

  // Load the plugins
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-compress');

  // Default tasks
  grunt.registerTask('default', ['clean', 'copy', 'compress']);

};
