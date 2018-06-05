const path = require('path');

const BASE_PATH = path.resolve(__dirname, '../..');

const PUBLIC_FOLDER = 'public';

const DEFAULT_CONFIG = {
  /**
   * Debug mode (true|false). Should be disabled on production builds
   */
  debug: false,

  /**
   * Public folder prefix. This is usually the private path prefixed with /web or /public
   */
  public: '/build/',

  /**
   * Private build folder
   */
  private: `${BASE_PATH}/${PUBLIC_FOLDER}/build`,

  /**
   * Manifest file name
   */
  manifestFile: `${BASE_PATH}/public/build/manifest.json`,

  /**
   * The base entry files that have to go through the loader
   */
  entries: {
    'js-app': `${BASE_PATH}/assets/js/app.js`,
    'js-vendor': `${BASE_PATH}/assets/js/vendor.js`,
    'css-app': `${BASE_PATH}/assets/css/app.scss`,
  },

  /**
   * Registered and enabled plugins, executed IN order
   */
  enabledPlugins: [
    'cleaner',
    'css',
    'js',
    'misc',
  ],

  /**
   * Components
   */
  components: {
    /**
     * Cleaner: Clean build folder prior to a new build
     */
    cleaner: {
      excludes: ['svg', 'manifest.json'],
    },

    /**
     * Javascript (ES6)
     */
    js: {
      source: `${BASE_PATH}/assets/js/`,
      dist: `${BASE_PATH}/${PUBLIC_FOLDER}/build/js/`,
      fileMask: 'js/[name].js?v=[hash:6]',
    },

    /**
     * CSS with SSS
     */
    css: {
      source: `${BASE_PATH}/assets/css/`,
      dist: `${BASE_PATH}/${PUBLIC_FOLDER}/build/css/`,
      fileMask: 'css/[name].css?h=[hash:6]',
    }
  },
};

module.exports = DEFAULT_CONFIG;
