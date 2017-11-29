var path = require('path');
var HtmlWebpackPlugin = require("html-webpack-plugin");
var ExtractTextPlugin = require('extract-text-webpack-plugin');

module.exports = {
	entry: "./src/index.js",
	output: {
		path: path.resolve(__dirname, '../src/Resources/public/assets/js'),
		filename: "ynfinite.js",
		publicPath: '/'
	},
	resolve: {
		extensions: ['.js'],
		alias: {
	      'waypoints': 'waypoints/lib'
	    }
	},	
	module: {
		loaders: [
			{
				test: /\.js$/,
				include: [
					path.resolve(__dirname, 'src')
				],
				loader: 'babel-loader'
			},
			{
		        test: /.jsx?$/,
		        loader: 'babel-loader',
		        exclude: /node_modules/,
		        query: {
		          presets: ['es2015', 'react']
		        }
		    },
		    {
		        test: /\.css$/,
				include: [
					path.resolve(__dirname, 'src')
				],		        
		        loader: 'css-loader'
		    },
			{
				test: /\.scss$/,
				include: [
					path.resolve(__dirname, 'src')
				],
				loader: ExtractTextPlugin.extract({
                    fallback: "style-loader",
                    use: ['css-loader' , 'sass-loader']
				})
			},
			{
		    	test: /\.(eot|svg|ttf|woff(2)?)(\?v=\d+\.\d+\.\d+)?/,
		        loader: 'url'
		    }			
		]
	},
    plugins: [
        new ExtractTextPlugin('ynfinite.css')
    ]	
}