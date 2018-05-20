/* eslint-disable import/no-extraneous-dependencies */
const SvgPlugin = require('tde-webpack-svg-plugin');
const path = require('path');

module.exports = function (webpack, config) {
  webpack.plugins.push(new SvgPlugin(config.components.svg.source, {
    mode: {
      symbol: {
        inline: false,
      },
    },
    dest: path.join(config.components.svg.dist),
  }));
};
