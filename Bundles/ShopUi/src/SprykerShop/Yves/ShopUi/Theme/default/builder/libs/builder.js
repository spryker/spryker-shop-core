const webpack = require('webpack');

function build(configuration) { 
    console.log('Building...');
    
    webpack(configuration, (err, stats) => {
        if (err) {
            console.error(err.stack || err);
            if (err.details) {
                console.error(err.details);
            }

            return;
        }

        console.log(stats.toString(configuration.stats), '\n');
    });
}

module.exports = {
    build
};
