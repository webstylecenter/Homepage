/* eslint-disable import/no-extraneous-dependencies */
const UglifyJSPlugin = require('uglifyjs-webpack-plugin');

module.exports = function (webpack, config) {
  const babelPresets = [
    [
      'env',
      {
        targets: {
          browsers: ['last 2 versions', 'ie >= 10'],
        },
        debug: true,
      },
    ],
  ];

  webpack.module.rules.push({
    test: /\.js$/,
    exclude: /(node_modules|bower_components)/,
    use: [
      {
        loader: 'babel-loader',
        options: {
          babelrc: false,
          presets: babelPresets,
        },
      },
    ],
  });

  if (!config.debug) {
    webpack.plugins.push(new UglifyJSPlugin({
      test: /\.js($|\?)/i,
    }));
  }
};
