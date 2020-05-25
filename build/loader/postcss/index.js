const MiniCssExtractPlugin = require("mini-css-extract-plugin");

module.exports = {
  test: /\.css$/,
  exclude: /node_modules/,
  use: [
    {
      loader: MiniCssExtractPlugin.loader,
      options: {
        publicPath: __dirname + "/../../../public/static/css/"
      }
    },
    {
      loader: "css-loader",
      options: { importLoaders: 1 }
    },
    {
      loader: "postcss-loader",
      options: {
        config: {
          path: __dirname + "/postcss.config.js"
        }
      }
    }
  ]
};
