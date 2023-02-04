const path = require('path');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');

module.exports = {
  entry: {
    main: './js/wphp.js',
    base: './sass/base.scss',
    print: './sass/print.scss',
    timeline: './sass/timeline.scss',
  },
  mode: 'production',
  output: {
    path: path.resolve(__dirname),
    filename: 'js/dist/[name].js',
  },
  module: {
    rules: [
      {
        test: /\.scss$/,
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader',
          {
            loader: 'sass-loader',
            options: {
              sassOptions: {
                includePaths: ['./node_modules'],
                outputStyle: 'expanded',
              }
            }
          },
        ],
      },
      {
        test: /\.(woff|woff2|eot|ttf|otf)$/,
        type: 'asset/resource',
        generator: {
          filename: './fonts/[name][ext]',
        }
      },
      {
        test: /\.(png|jpg|gif)$/,
        type: 'asset/resource',
        generator: {
          filename: './images/[name][ext]',
        }
      },
    ],
  },
  plugins: [
    // css extraction into dedicated file
    new RemoveEmptyScriptsPlugin({ verbose: true }),
    new MiniCssExtractPlugin({
      filename: './css/[name].css',
    }),
  ],
  resolve: {
    extensions: ['.js', '.scss'],
    modules: ['node_modules'],
  },
  devtool: 'source-map'
};

