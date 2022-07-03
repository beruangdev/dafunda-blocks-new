const defaultConfig = require("@wordpress/scripts/config/webpack.config.js");
const path = require("path");
const IgnoreEmitPlugin = require("ignore-emit-webpack-plugin");
const { CleanWebpackPlugin } = require("clean-webpack-plugin");

module.exports = {
  ...defaultConfig,
  entry: {
    index: path.resolve(process.cwd(), "src", "blocks.js"),
  },
  plugins: [
    ...defaultConfig.plugins.filter((p) => !(p instanceof CleanWebpackPlugin)),
    new IgnoreEmitPlugin(["blocks.build.asset.php", "blocks.build.js.map"]),
  ],
  output: {
    filename: "blocks.build.js",
  },
  module: {
    rules: [
      {
        test: /\.(js|jsx)$/,
        exclude: /node_modules/,
        use: ["babel-loader"],
      },
      {
        test: /\.svg$/,
        use: ["@svgr/webpack"],
      },
    ],
  },
};
