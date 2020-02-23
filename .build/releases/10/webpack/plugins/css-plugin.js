/* eslint-disable import/no-extraneous-dependencies */
const path = require('path');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssNano = require('cssnano');

module.exports = function (webpack, config) {
  webpack.module.rules.push({
    test: /\.scss$/,
    use: [
      MiniCssExtractPlugin.loader,
      {
        loader: 'css-loader',
        options: {
          sourceMap: config.debug,
        },
      }, {
        loader: 'postcss-loader',
        options: {
          sourceMap: config.debug,
          config: {
            path: path.resolve(__dirname, '../config/external/postcss.config.js'),
          },
        },
      }, {
        loader: 'sass-loader',
        options: {
          sourceMap: config.debug,
        },
      },
    ],
  });

  webpack.plugins.push(new MiniCssExtractPlugin({
    filename: config.components.css.fileMask,
  }));

  if (!config.debug) {
    webpack.plugins.push(new OptimizeCssAssetsPlugin({
      cssProcessor: CssNano,
      cssProcessorOptions: {
        discardComments: { removeAll: true },
      },
      canPrint: true,
    }));
  }
};
