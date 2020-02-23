/* eslint-disable import/no-extraneous-dependencies */
const MjmlStore = require('tde-webpack-mjml-plugin');

module.exports = function (webpack, config) {
  webpack.plugins.push(new MjmlStore(config.components.mjml.source, {
    extension: config.components.mjml.extension,
    outputPath: config.components.mjml.dist,
  }));
};
