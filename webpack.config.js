const path = require('path');
const shell = require('shelljs');
const outputPath = path.resolve( __dirname, 'views/assets/js')
const plugins = [];
const isProduction = (process.env.NODE_ENV == 'production');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

//Remove all webpack build file
shell.rm('-rf', outputPath)
shell.rm('-rf', path.resolve( __dirname, 'views/assets/vendor/wp-hooks/pm-hooks.js'))

function resolve (dir) {
  return path.join(__dirname, './views/assets/', dir)
}

// extract css into its own file
const extractCss = new MiniCssExtractPlugin({
  filename: 'assets/css/pm-style.css'
});

plugins.push( extractCss );

//const pusher = require('./src/pusher/webpack.config.js');

module.exports =[ 
    {
        mode: 'production', //necessary for WordPress functionality,
        entry: {
            'assets/js/pm': './views/assets/src/start.js',
            'assets/js/library': './views/assets/src/helpers/library.js',
            'assets/js/pmglobal': './views/assets/src/helpers/pmglobal.js',
            'assets/vendor/wp-hooks/pm-hooks': './views/assets/vendor/wp-hooks/wp-hooks.js',
            'assets/vendor/vue-fullscreen/vue-fullscreen.min': './views/assets/vendor/vue-fullscreen/vue-fullscreen.js',
        },

        output: {
            path: path.resolve(__dirname, 'views'),
            filename: '[name].js',
            publicPath: '',
            //chunkFilename: 'chunk/[chunkhash].chunk-bundle.js',
            //jsonpFunction: 'wedevsPmWebpack',
            // hotUpdateFunction: 'wedevsPmWebpacks',
        },

        resolve: {
            extensions: ['.js', '.vue', '.json'],
            alias: {
              '@assets': resolve(''),
              '@components': resolve('src/components'),
              '@directives': resolve('src/directives'),
              '@helpers': resolve('src/helpers'),
              '@router': resolve('src/router'),
              '@store': resolve('src/store'),
              '@src': resolve('src/')
            }
        },
        
        module: {
            rules: [
                // doc url https://vue-loader.vuejs.org/en/options.html#loaders
                {   
                    test: /\.vue$/,
                    loader: 'vue-loader',
                    options: {
                        loaders: {
                            js: 'babel-loader',
                        }
                    },
                },
                {
                    test: /\.js$/,
                    loader: 'babel-loader',
                    exclude: /node_modules/,
                    options: {
                        presets: ['@babel/preset-env']
                    }
                },
                {
                    test: /\.(png|jpg|gif|svg)$/,
                    loader: 'file-loader',
                    exclude: /node_modules/,
                    options: {
                        name: '[name].[ext]?[hash]',
                        outputPath: '../css/images/'
                    }
                },
                {
                    test: /\.less$/,
                    use: [ MiniCssExtractPlugin.loader, "css-loader" , "less-loader" ]
                },
                {
                    test: /\.css$/,
                    use: [MiniCssExtractPlugin.loader, 'css-loader']
                },
                {
                    test: /\.(png|woff|woff2|eot|ttf|svg)$/,
                    use: ['url-loader?limit=100000']
                }
            ]
        },
        plugins: plugins
    },
    //pusher
]


