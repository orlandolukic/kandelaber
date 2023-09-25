const defaults = require('@wordpress/scripts/config/webpack.config');

module.exports = {
    entry: [
        './inc/elementor/product-category-listing/react/index.js',
        './inc/react/product-category-preview/index.js',
        './assets/js/react-main.js'
    ],
    output: {
        filename: '../assets/js/react-rendered.js'
    },
    module: {
        rules: [
            {
                test: /\.(js|jsx)$/, // .js and .jsx files
                exclude: /node_modules/, // excluding the node_modules folder
                use: {
                    loader: "babel-loader",
                },
            },
            {
                test: /\.(sa|sc|c)ss$/, // styles files
                use: ["style-loader", "css-loader", "sass-loader"],
            },
            {
                test: /\.(png|woff|woff2|eot|ttf|svg)$/, // to import images and fonts
                loader: "url-loader",
                options: { limit: false },
            },
        ],
    }
};