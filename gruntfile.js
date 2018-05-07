module.exports = function(grunt) {
	
	// grun task
	grunt.initConfig({
		pkg: grunt.file.readJSON("package.json"),
		config: {
			css: "public/stylesheets/bootstrap.css",
			js: "public/javascript/bootstrap.js",
			php: "php",
		},
		phplint: {
			options: {
				phpCmd: "<%= config.php %>"
			},
			all: [
				"applications/**/*.php",
				"framework/**/*.php",
				"public/index.php",
			]
		},
		sass: {
			dev: {
				options: {
					sourceMap: true
				},
				files: {
			    	"<%= config.css %>": "bootstrap.scss"
				}
			},
			live: {
				files: {
			    	"<%= config.css %>": "bootstrap.scss"
				}
			}
		},
		postcss: {
      		dev: {
				options: {
					processors: [
				        require("autoprefixer")({browsers: "last 2 versions"}), // add vendor prefixes
	      			],
	      			map: true
	      		},
      			src: "<%= config.css %>"
      		},
      		live: {
				options: {
					processors: [
				        require("autoprefixer")({browsers: "last 2 versions"}), // add vendor prefixes
				        require("cssnano")() // minify the result
	      			]
	      		},
      			src: "<%= config.css %>"
      		}
		},
		requirejs: {
			dev: {
				options: {
					findNestedDependencies: true,
					optimize: "none",
					include: ["bootstrap"],
					name: "framework/javascript/almond",
					baseUrl: "",
					generateSourceMaps: true,
					out: "<%= config.js %>",
					paths: {
						jquery: "framework/javascript/jquery.min"
					},
					wrap: true,
					preserveLicenseComments: false
				}
			},
			live: {
				options: {
					findNestedDependencies: true,
					optimize: "uglify2",
					include: ["bootstrap"],
					name: "framework/javascript/almond",
					baseUrl: "",
					generateSourceMaps: true,
					out: "<%= config.js %>",
					paths: {
						jquery: "framework/javascript/jquery.min"
					},
					wrap: true,
					preserveLicenseComments: false
				}
			}
		},
		watch: {
			css: {
				files: ["applications/**/*.scss", 'bootstrap.scss'],
				tasks: ["sass:dev", "postcss:dev"]
			},
			js: {
				files: ["applications/**/*.js", "gruntfile.js", 'bootstrap.js'],
				tasks: ["requirejs:dev"]
			},
		}
	});
	grunt.loadNpmTasks("grunt-phplint");
	grunt.loadNpmTasks("grunt-sass");
	grunt.loadNpmTasks("grunt-postcss");
	grunt.loadNpmTasks("grunt-contrib-requirejs");
	grunt.loadNpmTasks("grunt-contrib-watch");
	
	grunt.registerTask("default", ["phplint", "sass:live", "postcss:live", "requirejs:live"]);
	grunt.registerTask("css", ["sass:dev", "postcss:dev"]);
	grunt.registerTask("js", ["requirejs:dev"]);
};