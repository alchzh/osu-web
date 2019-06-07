const webpackConfig = require('./webpack.config.js');
webpackConfig['mode'] = 'development';
webpackConfig['devtool'] = 'inline-source-map';
delete webpackConfig.optimization.splitChunks; // karma doesn't work with splitChunks
delete webpackConfig.entry; // test runner doesn't use the entry points

const testIndex = 'resources/assets/tests/index.ts';

const files = [
  'public/js/vendor.js',
  'public/js/app-deps.js',
  'public/js/locales/en.js',
  'resources/assets/tests/globals.js', // shims for tests
  testIndex,
];

const preprocessors = {};
preprocessors[testIndex] = ['webpack', 'sourcemap'];

module.exports = function (config) {
  config.set({
    autoWatch: true,
    basePath: '.',
    browsers: ['ChromeHeadless'],
    colors: true,
    concurrency: Infinity,
    exclude: [],
    frameworks: ['jasmine'],
    files: files,
    logLevel: config.LOG_INFO,
    mime: { 'text/x-typescript': ['ts', 'tsx'] },
    port: 9876,
    preprocessors: preprocessors,
    reporters: ['progress'],
    singleRun: false, // set to true for the process to exit after completing.
    webpack: webpackConfig,
    webpackMiddleware: {
      noInfo: true,
      stats: 'errors-only'
    },
  });
};
