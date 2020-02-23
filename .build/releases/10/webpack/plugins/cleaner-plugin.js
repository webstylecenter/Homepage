/* eslint-disable import/no-extraneous-dependencies */
const CleanWebpackPlugin = require('clean-webpack-plugin');
const ExtraneousFileCleanupPlugin = require('webpack-extraneous-file-cleanup-plugin');

module.exports = function (webpack, config) {
  webpack.plugins.push(new CleanWebpackPlugin([config.private], {
    root: process.cwd(),
    exclude: config.components.cleaner.excludes,
    watch: !config.debug,
    verbose: true,
  }));

  webpack.plugins.push(new ExtraneousFileCleanupPlugin({
    extensions: ['.js'],
    minBytes: 20,
    manifestJsonName: config.manifestFile,
  }));
};
