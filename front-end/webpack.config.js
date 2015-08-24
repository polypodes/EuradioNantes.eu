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
      // {
      //   test: require.resolve('bootstrap-sass'),
      //   loader: 'expose?bootstrap'
      // },
    ]
  },
  plugins: []
};

// minfiying if builded in production
if(process.env.NODE_ENV === 'production') {
  config.plugins.push(new webpack.optimize.UglifyJsPlugin({minimize: true}));
}

module.exports = config;
