const webpack = require('webpack');

class Compiler {
    run(configuration) {
        console.log(`Building${configuration.mode ? ' for ' + configuration.mode : ''}...`);

        if (configuration.watch) {
            console.log('Watch mode: ON');
        }

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
}

module.exports = Compiler;
