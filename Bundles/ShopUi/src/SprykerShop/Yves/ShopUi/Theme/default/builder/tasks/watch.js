const builder = require('../libs/builder');
const configuration = require('../webpack.dev');

builder.build(Object.assign({}, configuration, {
    watch: true,
    watchOptions: {
        aggregateTimeout: 300,
        poll: 500,
        ignored: /(node_modules)/
    }
}));
