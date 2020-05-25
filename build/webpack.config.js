"use strict";
const path = require("path");
const loaders = require("./loaders");
const plugins = require("./plugins");
module.exports = {
  mode: "development",
  entry: ["./src/js/main.js", "./src/css/main.css"],
  output: {
    filename: "js/[name].js",
    path: path.resolve(__dirname, "../public/static"),
    publicPath: "static/"
  },
  module: {
    rules: loaders
  },
  plugins: [plugins.MiniCssExtractPlugin]
};
