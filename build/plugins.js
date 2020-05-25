const _MiniCssExtractPlugin = require("mini-css-extract-plugin");
const MiniCssExtractPlugin = new _MiniCssExtractPlugin({
  filename: "css/[name].css",
  chunkFilename: "css/[id].css"
});
module.exports = {
  MiniCssExtractPlugin: MiniCssExtractPlugin
};
