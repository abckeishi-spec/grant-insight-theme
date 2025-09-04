<?php
/**
 * Grant Insight Perfect Theme - Front Page Customizer Settings
 * Version: 5.0-perfect
 * Description: フロントページ専用カスタマイザー設定
 * Author: Genspark AI
 */

// 直接アクセスを防止
if (!defined('ABSPATH')) {
    exit;
}

/**
 * フロントページカスタマイザー設定クラス
 */
class Grant_Insight_Front_Page_Customizer {
    
    /**
     * コンストラクタ
     */
    public function __construct() {
        add_action('customize_register', array($this, 'register_front_page_settings'));
        add_action('wp_head', array($this, 'output_custom_styles'));
    }

    /**
     * フロントページ設定の登録
     */
    public function register_front_page_settings($wp_customize) {
        
        /**
         * ★★★ ヒーローセクション設定 ★★★
         */
        $wp_customize->add_section('grant_insight_hero_section', array(
            'title' => __('ヒーローセクション', 'grant-insight'),
            'priority' => 40,
            'description' => __('トップページのメインビジュアル部分の設定を行います。', 'grant-insight'),
        ));

        // ヒーローメインタイトル
        $wp_customize->add_setting('hero_main_title', array(
            'default' => __('助成金・補助金情報を\nわかりやすく', 'grant-insight'),
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('hero_main_title', array(
            'label' => __('メインタイトル', 'grant-insight'),
            'section' => 'grant_insight_hero_section',
            'type' => 'textarea',
            'description' => __('改行は\nで表現してください。', 'grant-insight'),
        ));

        // ヒーローサブタイトル
        $wp_customize->add_setting('hero_subtitle', array(
            'default' => __('中小企業・スタートアップのための支援制度を網羅的にご紹介。\nAIアシスタントがあなたに最適な助成金を見つけます。', 'grant-insight'),
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('hero_subtitle', array(
            'label' => __('サブタイトル', 'grant-insight'),
            'section' => 'grant_insight_hero_section',
            'type' => 'textarea',
        ));

        // ヒーローボタンテキスト
        $wp_customize->add_setting('hero_button_text', array(
            'default' => __('助成金を探す', 'grant-insight'),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('hero_button_text', array(
            'label' => __('ボタンテキスト', 'grant-insight'),
            'section' => 'grant_insight_hero_section',
            'type' => 'text',
        ));

        // ヒーローボタンURL
        $wp_customize->add_setting('hero_button_url', array(
            'default' => '/grants/',
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control('hero_button_url', array(
            'label' => __('ボタンURL', 'grant-insight'),
            'section' => 'grant_insight_hero_section',
            'type' => 'url',
        ));

        // ヒーロー背景画像
        $wp_customize->add_setting('hero_background_image', array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_background_image', array(
            'label' => __('背景画像', 'grant-insight'),
            'section' => 'grant_insight_hero_section',
            'description' => __('1200x600px以上の画像を推奨します。', 'grant-insight'),
        )));

        // ヒーロー背景オーバーレイ
        $wp_customize->add_setting('hero_overlay_opacity', array(
            'default' => 0.7,
            'sanitize_callback' => array($this, 'sanitize_float'),
        ));

        $wp_customize->add_control('hero_overlay_opacity', array(
            'label' => __('背景オーバーレイの透明度', 'grant-insight'),
            'section' => 'grant_insight_hero_section',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 0,
                'max' => 1,
                'step' => 0.1,
            ),
        ));

        /**
         * ★★★ 統計セクション設定 ★★★
         */
        $wp_customize->add_section('grant_insight_stats_section', array(
            'title' => __('統計セクション', 'grant-insight'),
            'priority' => 45,
            'description' => __('実績数値を表示するセクションの設定を行います。', 'grant-insight'),
        ));

        // 統計セクション表示切り替え
        $wp_customize->add_setting('stats_section_enabled', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ));

        $wp_customize->add_control('stats_section_enabled', array(
            'label' => __('統計セクションを表示', 'grant-insight'),
            'section' => 'grant_insight_stats_section',
            'type' => 'checkbox',
        ));

        // 統計項目1
        $wp_customize->add_setting('stats_1_number', array(
            'default' => '1,200',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('stats_1_number', array(
            'label' => __('統計1 - 数値', 'grant-insight'),
            'section' => 'grant_insight_stats_section',
            'type' => 'text',
        ));

        $wp_customize->add_setting('stats_1_label', array(
            'default' => __('掲載助成金数', 'grant-insight'),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('stats_1_label', array(
            'label' => __('統計1 - ラベル', 'grant-insight'),
            'section' => 'grant_insight_stats_section',
            'type' => 'text',
        ));

        // 統計項目2
        $wp_customize->add_setting('stats_2_number', array(
            'default' => '15,000',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('stats_2_number', array(
            'label' => __('統計2 - 数値', 'grant-insight'),
            'section' => 'grant_insight_stats_section',
            'type' => 'text',
        ));

        $wp_customize->add_setting('stats_2_label', array(
            'default' => __('利用企業数', 'grant-insight'),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('stats_2_label', array(
            'label' => __('統計2 - ラベル', 'grant-insight'),
            'section' => 'grant_insight_stats_section',
            'type' => 'text',
        ));

        // 統計項目3
        $wp_customize->add_setting('stats_3_number', array(
            'default' => '98%',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('stats_3_number', array(
            'label' => __('統計3 - 数値', 'grant-insight'),
            'section' => 'grant_insight_stats_section',
            'type' => 'text',
        ));

        $wp_customize->add_setting('stats_3_label', array(
            'default' => __('満足度', 'grant-insight'),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('stats_3_label', array(
            'label' => __('統計3 - ラベル', 'grant-insight'),
            'section' => 'grant_insight_stats_section',
            'type' => 'text',
        ));

        // 統計項目4
        $wp_customize->add_setting('stats_4_number', array(
            'default' => '24時間',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('stats_4_number', array(
            'label' => __('統計4 - 数値', 'grant-insight'),
            'section' => 'grant_insight_stats_section',
            'type' => 'text',
        ));

        $wp_customize->add_setting('stats_4_label', array(
            'default' => __('サポート対応', 'grant-insight'),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('stats_4_label', array(
            'label' => __('統計4 - ラベル', 'grant-insight'),
            'section' => 'grant_insight_stats_section',
            'type' => 'text',
        ));

        /**
         * ★★★ 特徴セクション設定 ★★★
         */
        $wp_customize->add_section('grant_insight_features_section', array(
            'title' => __('特徴セクション', 'grant-insight'),
            'priority' => 50,
            'description' => __('サービスの特徴を紹介するセクションの設定を行います。', 'grant-insight'),
        ));

        // 特徴セクションタイトル
        $wp_customize->add_setting('features_section_title', array(
            'default' => __('Grant Insightが選ばれる理由', 'grant-insight'),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('features_section_title', array(
            'label' => __('セクションタイトル', 'grant-insight'),
            'section' => 'grant_insight_features_section',
            'type' => 'text',
        ));

        // 特徴1
        $wp_customize->add_setting('feature_1_icon', array(
            'default' => 'fas fa-search',
            'sanitize_callback' => 'sanitize_text_field',
        ));

        $wp_customize->add_control('feature_1_icon', array(
            'label' => __('特徴1 - アイコン (Font Awesomeクラス)', 'grant-insight'),
            'section' => 'grant_insight_features_section',
            'type' => 'text',
            'description' => __('例: fas fa-search', 'grant-insight'),
        ));

        $wp_customize->add_setting('feature_1_title', array(
            'default' => __('簡単検索', 'grant-insight'),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('feature_1_title', array(
            'label' => __('特徴1 - タイトル', 'grant-insight'),
            'section' => 'grant_insight_features_section',
            'type' => 'text',
        ));

        $wp_customize->add_setting('feature_1_description', array(
            'default' => __('業種や条件から最適な助成金を素早く見つけることができます。', 'grant-insight'),
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('feature_1_description', array(
            'label' => __('特徴1 - 説明', 'grant-insight'),
            'section' => 'grant_insight_features_section',
            'type' => 'textarea',
        ));

        // 特徴2
        $wp_customize->add_setting('feature_2_icon', array(
            'default' => 'fas fa-robot',
            'sanitize_callback' => 'sanitize_text_field',
        ));

        $wp_customize->add_control('feature_2_icon', array(
            'label' => __('特徴2 - アイコン', 'grant-insight'),
            'section' => 'grant_insight_features_section',
            'type' => 'text',
        ));

        $wp_customize->add_setting('feature_2_title', array(
            'default' => __('AI相談', 'grant-insight'),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('feature_2_title', array(
            'label' => __('特徴2 - タイトル', 'grant-insight'),
            'section' => 'grant_insight_features_section',
            'type' => 'text',
        ));

        $wp_customize->add_setting('feature_2_description', array(
            'default' => __('AIアシスタントが24時間いつでもあなたの質問にお答えします。', 'grant-insight'),
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('feature_2_description', array(
            'label' => __('特徴2 - 説明', 'grant-insight'),
            'section' => 'grant_insight_features_section',
            'type' => 'textarea',
        ));

        // 特徴3
        $wp_customize->add_setting('feature_3_icon', array(
            'default' => 'fas fa-clock',
            'sanitize_callback' => 'sanitize_text_field',
        ));

        $wp_customize->add_control('feature_3_icon', array(
            'label' => __('特徴3 - アイコン', 'grant-insight'),
            'section' => 'grant_insight_features_section',
            'type' => 'text',
        ));

        $wp_customize->add_setting('feature_3_title', array(
            'default' => __('最新情報', 'grant-insight'),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('feature_3_title', array(
            'label' => __('特徴3 - タイトル', 'grant-insight'),
            'section' => 'grant_insight_features_section',
            'type' => 'text',
        ));

        $wp_customize->add_setting('feature_3_description', array(
            'default' => __('常に最新の助成金情報を提供し、申請期限もしっかりお知らせします。', 'grant-insight'),
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('feature_3_description', array(
            'label' => __('特徴3 - 説明', 'grant-insight'),
            'section' => 'grant_insight_features_section',
            'type' => 'textarea',
        ));

        /**
         * ★★★ CTA (Call to Action) セクション設定 ★★★
         */
        $wp_customize->add_section('grant_insight_cta_section', array(
            'title' => __('CTAセクション', 'grant-insight'),
            'priority' => 55,
            'description' => __('行動を促すセクションの設定を行います。', 'grant-insight'),
        ));

        // CTA タイトル
        $wp_customize->add_setting('cta_title', array(
            'default' => __('今すぐ助成金を探してみませんか？', 'grant-insight'),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('cta_title', array(
            'label' => __('CTAタイトル', 'grant-insight'),
            'section' => 'grant_insight_cta_section',
            'type' => 'text',
        ));

        // CTA サブタイトル
        $wp_customize->add_setting('cta_subtitle', array(
            'default' => __('あなたの事業に最適な支援制度を見つけて、成長を加速させましょう。', 'grant-insight'),
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('cta_subtitle', array(
            'label' => __('CTAサブタイトル', 'grant-insight'),
            'section' => 'grant_insight_cta_section',
            'type' => 'textarea',
        ));

        // CTA プライマリボタン
        $wp_customize->add_setting('cta_primary_button_text', array(
            'default' => __('無料で始める', 'grant-insight'),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('cta_primary_button_text', array(
            'label' => __('プライマリボタンテキスト', 'grant-insight'),
            'section' => 'grant_insight_cta_section',
            'type' => 'text',
        ));

        $wp_customize->add_setting('cta_primary_button_url', array(
            'default' => '/grants/',
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control('cta_primary_button_url', array(
            'label' => __('プライマリボタンURL', 'grant-insight'),
            'section' => 'grant_insight_cta_section',
            'type' => 'url',
        ));

        // CTA セカンダリボタン
        $wp_customize->add_setting('cta_secondary_button_text', array(
            'default' => __('詳しく見る', 'grant-insight'),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('cta_secondary_button_text', array(
            'label' => __('セカンダリボタンテキスト', 'grant-insight'),
            'section' => 'grant_insight_cta_section',
            'type' => 'text',
        ));

        $wp_customize->add_setting('cta_secondary_button_url', array(
            'default' => '/about/',
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control('cta_secondary_button_url', array(
            'label' => __('セカンダリボタンURL', 'grant-insight'),
            'section' => 'grant_insight_cta_section',
            'type' => 'url',
        ));

        // CTA 背景色
        $wp_customize->add_setting('cta_background_color', array(
            'default' => '#2563eb',
            'sanitize_callback' => 'sanitize_hex_color',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cta_background_color', array(
            'label' => __('CTA背景色', 'grant-insight'),
            'section' => 'grant_insight_cta_section',
        )));

        /**
         * ★★★ ニュースセクション設定 ★★★
         */
        $wp_customize->add_section('grant_insight_news_section', array(
            'title' => __('ニュースセクション', 'grant-insight'),
            'priority' => 60,
            'description' => __('最新ニュースを表示するセクションの設定を行います。', 'grant-insight'),
        ));

        // ニュースセクション表示切り替え
        $wp_customize->add_setting('news_section_enabled', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ));

        $wp_customize->add_control('news_section_enabled', array(
            'label' => __('ニュースセクションを表示', 'grant-insight'),
            'section' => 'grant_insight_news_section',
            'type' => 'checkbox',
        ));

        // ニュースセクションタイトル
        $wp_customize->add_setting('news_section_title', array(
            'default' => __('最新ニュース', 'grant-insight'),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('news_section_title', array(
            'label' => __('セクションタイトル', 'grant-insight'),
            'section' => 'grant_insight_news_section',
            'type' => 'text',
        ));

        // 表示記事数
        $wp_customize->add_setting('news_posts_count', array(
            'default' => 3,
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control('news_posts_count', array(
            'label' => __('表示記事数', 'grant-insight'),
            'section' => 'grant_insight_news_section',
            'type' => 'number',
            'input_attrs' => array(
                'min' => 1,
                'max' => 10,
                'step' => 1,
            ),
        ));

        /**
         * ★★★ 検索セクション設定 ★★★
         */
        $wp_customize->add_section('grant_insight_search_section', array(
            'title' => __('検索セクション', 'grant-insight'),
            'priority' => 65,
            'description' => __('助成金検索機能のセクション設定を行います。', 'grant-insight'),
        ));

        // 検索セクションタイトル
        $wp_customize->add_setting('search_section_title', array(
            'default' => __('助成金を検索', 'grant-insight'),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('search_section_title', array(
            'label' => __('検索セクションタイトル', 'grant-insight'),
            'section' => 'grant_insight_search_section',
            'type' => 'text',
        ));

        // 検索プレースホルダー
        $wp_customize->add_setting('search_placeholder', array(
            'default' => __('キーワードで検索...', 'grant-insight'),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('search_placeholder', array(
            'label' => __('検索プレースホルダー', 'grant-insight'),
            'section' => 'grant_insight_search_section',
            'type' => 'text',
        ));

        /**
         * ★★★ AI Chatbotセクション設定 ★★★
         */
        $wp_customize->add_section('grant_insight_chatbot_section', array(
            'title' => __('AI Chatbot設定', 'grant-insight'),
            'priority' => 70,
            'description' => __('AI Chatbotの表示設定を行います。', 'grant-insight'),
        ));

        // Chatbot表示位置
        $wp_customize->add_setting('chatbot_position', array(
            'default' => 'bottom-right',
            'sanitize_callback' => array($this, 'sanitize_chatbot_position'),
        ));

        $wp_customize->add_control('chatbot_position', array(
            'label' => __('Chatbot表示位置', 'grant-insight'),
            'section' => 'grant_insight_chatbot_section',
            'type' => 'select',
            'choices' => array(
                'bottom-right' => __('右下', 'grant-insight'),
                'bottom-left' => __('左下', 'grant-insight'),
                'top-right' => __('右上', 'grant-insight'),
                'top-left' => __('左上', 'grant-insight'),
            ),
        ));

        // Chatbotボタンテキスト
        $wp_customize->add_setting('chatbot_button_text', array(
            'default' => __('AI相談', 'grant-insight'),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('chatbot_button_text', array(
            'label' => __('Chatbotボタンテキスト', 'grant-insight'),
            'section' => 'grant_insight_chatbot_section',
            'type' => 'text',
        ));

        // Chatbot初期メッセージ
        $wp_customize->add_setting('chatbot_welcome_message', array(
            'default' => __('こんにちは！助成金に関するご質問をお気軽にどうぞ。', 'grant-insight'),
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control('chatbot_welcome_message', array(
            'label' => __('Chatbot初期メッセージ', 'grant-insight'),
            'section' => 'grant_insight_chatbot_section',
            'type' => 'textarea',
        ));

        /**
         * ★★★ レスポンシブ設定 ★★★
         */
        $wp_customize->add_section('grant_insight_responsive_section', array(
            'title' => __('レスポンシブ設定', 'grant-insight'),
            'priority' => 75,
            'description' => __('モバイル・タブレット表示の調整を行います。', 'grant-insight'),
        ));

        // モバイルでのヒーローテキストサイズ
        $wp_customize->add_setting('mobile_hero_font_size', array(
            'default' => '2rem',
            'sanitize_callback' => 'sanitize_text_field',
        ));

        $wp_customize->add_control('mobile_hero_font_size', array(
            'label' => __('モバイル版ヒーローフォントサイズ', 'grant-insight'),
            'section' => 'grant_insight_responsive_section',
            'type' => 'text',
            'description' => __('例: 2rem, 32px', 'grant-insight'),
        ));

        // モバイルでの特徴セクション表示形式
        $wp_customize->add_setting('mobile_features_layout', array(
            'default' => 'stacked',
            'sanitize_callback' => array($this, 'sanitize_mobile_layout'),
        ));

        $wp_customize->add_control('mobile_features_layout', array(
            'label' => __('モバイル版特徴セクションレイアウト', 'grant-insight'),
            'section' => 'grant_insight_responsive_section',
            'type' => 'select',
            'choices' => array(
                'stacked' => __('縦積み', 'grant-insight'),
                'carousel' => __('カルーセル', 'grant-insight'),
            ),
        ));

        /**
         * ★★★ アニメーション設定 ★★★
         */
        $wp_customize->add_section('grant_insight_animation_section', array(
            'title' => __('アニメーション設定', 'grant-insight'),
            'priority' => 80,
            'description' => __('ページのアニメーション効果を設定します。', 'grant-insight'),
        ));

        // アニメーション有効化
        $wp_customize->add_setting('animations_enabled', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ));

        $wp_customize->add_control('animations_enabled', array(
            'label' => __('アニメーションを有効化', 'grant-insight'),
            'section' => 'grant_insight_animation_section',
            'type' => 'checkbox',
        ));

        // アニメーション速度
        $wp_customize->add_setting('animation_speed', array(
            'default' => 'normal',
            'sanitize_callback' => array($this, 'sanitize_animation_speed'),
        ));

        $wp_customize->add_control('animation_speed', array(
            'label' => __('アニメーション速度', 'grant-insight'),
            'section' => 'grant_insight_animation_section',
            'type' => 'select',
            'choices' => array(
                'slow' => __('ゆっくり', 'grant-insight'),
                'normal' => __('普通', 'grant-insight'),
                'fast' => __('早い', 'grant-insight'),
            ),
        ));

        // スクロールアニメーション
        $wp_customize->add_setting('scroll_animations', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ));

        $wp_customize->add_control('scroll_animations', array(
            'label' => __('スクロール時アニメーション', 'grant-insight'),
            'section' => 'grant_insight_animation_section',
            'type' => 'checkbox',
            'description' => __('要素がスクロールして表示される際のアニメーション', 'grant-insight'),
        ));
    }

    /**
     * カスタムスタイルの出力
     */
    public function output_custom_styles() {
        $hero_bg = get_theme_mod('hero_background_image', '');
        $hero_overlay = get_theme_mod('hero_overlay_opacity', 0.7);
        $cta_bg_color = get_theme_mod('cta_background_color', '#2563eb');
        $mobile_hero_font = get_theme_mod('mobile_hero_font_size', '2rem');
        $animation_speed = get_theme_mod('animation_speed', 'normal');
        
        echo '<style id="grant-insight-customizer-styles">';
        
        // ヒーロー背景画像
        if ($hero_bg) {
            echo ".hero-section { 
                background-image: linear-gradient(rgba(0,0,0," . esc_attr($hero_overlay) . "), rgba(0,0,0," . esc_attr($hero_overlay) . ")), url('" . esc_url($hero_bg) . "'); 
                background-size: cover; 
                background-position: center; 
                background-attachment: fixed; 
            }";
        }
        
        // CTA背景色
        echo ".cta-section { background-color: " . esc_attr($cta_bg_color) . "; }";
        
        // モバイルヒーローフォントサイズ
        echo "@media (max-width: 768px) { 
            .hero-title { font-size: " . esc_attr($mobile_hero_font) . " !important; } 
        }";
        
        // アニメーション速度設定
        $speed_values = array(
            'slow' => '0.8s',
            'normal' => '0.6s',
            'fast' => '0.4s'
        );
        
        if (isset($speed_values[$animation_speed])) {
            echo ".fade-in-up, .fade-in, .bounce-gentle, .pulse-gentle { 
                animation-duration: " . $speed_values[$animation_speed] . "; 
            }";
        }
        
        // Chatbot位置設定
        $chatbot_position = get_theme_mod('chatbot_position', 'bottom-right');
        $positions = array(
            'bottom-right' => 'bottom: 20px; right: 20px;',
            'bottom-left' => 'bottom: 20px; left: 20px;',
            'top-right' => 'top: 20px; right: 20px;',
            'top-left' => 'top: 20px; left: 20px;'
        );
        
        if (isset($positions[$chatbot_position])) {
            echo ".ai-chatbot-toggle { position: fixed; " . $positions[$chatbot_position] . " z-index: 1000; }";
        }
        
        // アニメーション無効化
        if (!get_theme_mod('animations_enabled', true)) {
            echo "* { animation: none !important; transition: none !important; }";
        }
        
        // スクロールアニメーション無効化
        if (!get_theme_mod('scroll_animations', true)) {
            echo ".scroll-animate { animation: none !important; }";
        }
        
        echo '</style>';
    }

    /**
     * サニタイズ関数群
     */
    public function sanitize_float($input) {
        return floatval($input);
    }

    public function sanitize_chatbot_position($input) {
        $valid = array('bottom-right', 'bottom-left', 'top-right', 'top-left');
        return in_array($input, $valid) ? $input : 'bottom-right';
    }

    public function sanitize_mobile_layout($input) {
        $valid = array('stacked', 'carousel');
        return in_array($input, $valid) ? $input : 'stacked';
    }

    public function sanitize_animation_speed($input) {
        $valid = array('slow', 'normal', 'fast');
        return in_array($input, $valid) ? $input : 'normal';
    }
}

/**
 * カスタマイザープレビュー用JavaScript
 */
function grant_insight_customize_preview_js() {
    wp_enqueue_script(
        'grant-insight-customizer-preview',
        get_template_directory_uri() . '/js/customizer-preview.js',
        array('customize-preview'),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('customize_preview_init', 'grant_insight_customize_preview_js');

/**
 * カスタマイザーコントロール用JavaScript
 */
function grant_insight_customize_controls_js() {
    wp_enqueue_script(
        'grant-insight-customizer-controls',
        get_template_directory_uri() . '/js/customizer-controls.js',
        array('customize-controls'),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('customize_controls_enqueue_scripts', 'grant_insight_customize_controls_js');

/**
 * カスタマイザー用のヘルパー関数
 */

// テーマ設定値を取得するヘルパー関数
function grant_insight_get_option($option_name, $default = '') {
    return get_theme_mod($option_name, $default);
}

// セクション表示判定
function grant_insight_is_section_enabled($section_name) {
    return get_theme_mod($section_name . '_enabled', true);
}

// 統計データの取得
function grant_insight_get_stats_data() {
    return array(
        array(
            'number' => get_theme_mod('stats_1_number', '1,200'),
            'label' => get_theme_mod('stats_1_label', __('掲載助成金数', 'grant-insight'))
        ),
        array(
            'number' => get_theme_mod('stats_2_number', '15,000'),
            'label' => get_theme_mod('stats_2_label', __('利用企業数', 'grant-insight'))
        ),
        array(
            'number' => get_theme_mod('stats_3_number', '98%'),
            'label' => get_theme_mod('stats_3_label', __('満足度', 'grant-insight'))
        ),
        array(
            'number' => get_theme_mod('stats_4_number', '24時間'),
            'label' => get_theme_mod('stats_4_label', __('サポート対応', 'grant-insight'))
        )
    );
}

// 特徴データの取得
function grant_insight_get_features_data() {
    return array(
        array(
            'icon' => get_theme_mod('feature_1_icon', 'fas fa-search'),
            'title' => get_theme_mod('feature_1_title', __('簡単検索', 'grant-insight')),
            'description' => get_theme_mod('feature_1_description', __('業種や条件から最適な助成金を素早く見つけることができます。', 'grant-insight'))
        ),
        array(
            'icon' => get_theme_mod('feature_2_icon', 'fas fa-robot'),
            'title' => get_theme_mod('feature_2_title', __('AI相談', 'grant-insight')),
            'description' => get_theme_mod('feature_2_description', __('AIアシスタントが24時間いつでもあなたの質問にお答えします。', 'grant-insight'))
        ),
        array(
            'icon' => get_theme_mod('feature_3_icon', 'fas fa-clock'),
            'title' => get_theme_mod('feature_3_title', __('最新情報', 'grant-insight')),
            'description' => get_theme_mod('feature_3_description', __('常に最新の助成金情報を提供し、申請期限もしっかりお知らせします。', 'grant-insight'))
        )
    );
}

// クラスの初期化
if (class_exists('Grant_Insight_Front_Page_Customizer')) {
    new Grant_Insight_Front_Page_Customizer();
}

?>
