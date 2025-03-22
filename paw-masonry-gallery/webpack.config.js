const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

module.exports = {
	...defaultConfig,
	entry: {
		'js/frontend': path.resolve(process.cwd(), 'src/frontend.js'),
		'js/bundle': path.resolve(process.cwd(), 'src/bundle.js'),
		'css/frontend': path.resolve(process.cwd(), 'src/frontend.scss'),
		'css/bundle': path.resolve(process.cwd(), 'src/bundle.scss'),
	},
	output: {
		filename: '[name].js',
		path: path.resolve(process.cwd(), 'dist'),
	},
	module: {
		rules: [
			{
				test: /\.(js|jsx)$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: ['@babel/preset-react', '@babel/preset-env'],
						plugins: ['@babel/plugin-proposal-class-properties'],
					},
				},
			},
			{
				test: /\.s[ac]ss$/i,
				use: [
					'style-loader',
					'css-loader',
					{
						loader: 'sass-loader',
						options: {
							implementation: require('sass'), // Ensures Dart Sass is used
							sassOptions: {
								quietDeps: true, // Suppresses warnings from dependencies
							},
						},
					},
				],
			},
			...(defaultConfig.module?.rules || []),
		],
	},
	resolve: {
		extensions: ['.js', '.jsx'],
	},
};
