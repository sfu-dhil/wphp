const path = require('path');

module.exports = {
    entry: './public/js/wphp.js',
    mode: 'production',
    output: {
        path: path.resolve(__dirname, 'public/js/dist'),
        filename: 'main.js'
    },
    devtool: 'source-map'
};

