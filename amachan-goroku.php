<?php
/**
 * @package Amachan_Goroku
 * @version 0.2
 */
/*
Plugin Name: あまちゃん語録
Version: 0.2
Description: NHK連続テレビ小説『あまちゃん』の主人公・天野秋ちゃんのセリフをダッシュボードに表示するだけのプラグインです。セリフのリストを非公式bot（https://twitter.com/amachan_aki）より取得しているので、Twitterのアプリケーション登録（https://dev.twitter.com/apps）と、プラグイン『oAuth Twitter Feed for Developers』（http://wordpress.org/plugins/oauth-twitter-feed-for-developers/）のインストールが必要です。
Author: Tecking
Author URI: http://www.tecking.org/
License: GPLv2
*/
/*  Copyright 2013 Tecking (email : tecking@tecking.org)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/*
 * 『oAuth Twitter Feed for Developers』の有効化を確認
 */
register_activation_hook( __FILE__, 'dependentplugin_activate' );
function dependentplugin_activate() {
	require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

	if ( is_plugin_active( 'oauth-twitter-feed-for-developers/twitter-feed-for-developers.php' ) ) {
		require_once ( WP_PLUGIN_DIR . '/oauth-twitter-feed-for-developers/twitter-feed-for-developers.php' );
	}
	else {
		deactivate_plugins( __FILE__ );
		exit( '<p style="margin-bottom: 0; font-size: 12px;">『あまちゃん語録』に必要なプラグイン『<a href="http://wordpress.org/plugins/oauth-twitter-feed-for-developers/">oAuth Twitter Feed for Developers</a>』が有効化されていません。プラグインを有効化して、必要な設定をすませてください。</p>' );
	}
}


/*
 * ウィジェットに表示するツイートの生成
 */
function hello_amachan_function() {
	$tweets = getTweets( 20, 'amachan_aki' );
	$tweet  = $tweets[ mt_rand( 0, count( $tweets ) - 1 ) ];
	echo wp_oembed_get( 'https://twitter.com/amachan_aki/status/' . $tweet['id_str'] );
	echo '<style>#hello_amachan .twitter-tweet { display: none; }</style>';
}


/*
 * アクションフックで使用する関数
 */
function hello_amachan_widget() {
	wp_add_dashboard_widget( 'hello_amachan', 'あまちゃん語録', 'hello_amachan_function' );	
} 


/*
 * wp_dashboard_setup アクションにフック
 */
add_action( 'wp_dashboard_setup', 'hello_amachan_widget' );
