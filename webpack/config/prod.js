/* eslint-disable import/no-extraneous-dependencies */
const _ = require('lodash');
const globalConfig = require('./default');

const PROD_CONFIG = {
  /**
   * Debug mode (true|false). Should be disabled on production builds
   */
  debug: false,
};

module.exports = _.merge({}, globalConfig, PROD_CONFIG);
