module.exports = function (grunt) {
  const pkg = grunt.file.readJSON("package.json");
  const pluginDir = "build/super-logistics/";

  grunt.initConfig({
    clean: {
      build: ["build/"],
    },

    copy: {
      main: {
        files: [
          { expand: true, src: ["super-logistics.php"], dest: pluginDir },
          { expand: true, src: ["src/**"], dest: pluginDir },
          { expand: true, src: ["vendor/**"], dest: pluginDir },
          { expand: true, src: ["view/dist/**"], dest: pluginDir },
          {
            expand: true,
            src: ["composer.json", "package.json", "README.md"],
            dest: pluginDir,
          },
        ],
      },
    },

    compress: {
      main: {
        options: {
          archive: "build/super-logistics-v" + pkg.version + ".zip",
        },
        files: [
          {
            expand: true,
            cwd: "build/",
            src: ["super-logistics/**"],
            dest: "",
          },
        ],
      },
    },
  });

  grunt.loadNpmTasks("grunt-contrib-clean");
  grunt.loadNpmTasks("grunt-contrib-copy");
  grunt.loadNpmTasks("grunt-contrib-compress");

  grunt.registerTask("default", ["clean", "copy", "compress"]);
};
