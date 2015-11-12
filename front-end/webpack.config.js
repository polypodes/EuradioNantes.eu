var path = require('path');
var webpack = require('webpack');

var config = {
  entry: './src',
  output: {
    filename: './dist/assets/bundle.js'
  },
  module: {
    loaders: [
      {
        test: path.join(__dirname, 'src'),
        loader: 'babel-loader'
      }
    ]
  },
  plugins: [
    new webpack.optimize.UglifyJsPlugin({minimize: true})
  ]
};

module.exports = config;
