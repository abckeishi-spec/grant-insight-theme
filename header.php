<?php
/**
 * The header for our theme
 * ヘッダーファイル（シンプル版 - レスポンシブ完全対応版）
 */

// セキュリティチェック
if (!defined('ABSPATH')) {
    exit;
}

// 必要なヘルパー関数を定義
if (!function_exists('gi_get_option')) {
    function gi_get_option($option_name, $default = '') {
        return get_theme_mod($option_name, $default);
    }
}

if (!function_exists('gi_safe_excerpt')) {
    function gi_safe_excerpt($text, $length = 160) {
        return mb_substr(strip_tags($text), 0, $length);
    }
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="scroll-smooth">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- メタ情報 -->
    <meta name="description" content="<?php 
        if (is_singular()) {
            echo esc_attr(gi_safe_excerpt(get_the_excerpt(), 160));
        } else {
            echo esc_attr(get_bloginfo('description'));
        }
    ?>">
    
    <!-- Open Graph / Twitter Card -->
    <?php if (is_singular()) : ?>
        <meta property="og:title" content="<?php echo esc_attr(get_the_title()); ?>">
        <meta property="og:description" content="<?php echo esc_attr(gi_safe_excerpt(get_the_excerpt(), 160)); ?>">
        <meta property="og:url" content="<?php echo esc_url(get_permalink()); ?>">
        <?php if (has_post_thumbnail()) : ?>
            <meta property="og:image" content="<?php echo esc_url(get_the_post_thumbnail_url(null, 'large')); ?>">
        <?php endif; ?>
    <?php else : ?>
        <meta property="og:title" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
        <meta property="og:description" content="<?php echo esc_attr(get_bloginfo('description')); ?>">
        <meta property="og:url" content="<?php echo esc_url(home_url()); ?>">
    <?php endif; ?>
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
    
    <!-- プリロード（パフォーマンス最適化） -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.tailwindcss.com">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    
    <!-- DNS Prefetch -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//cdn.tailwindcss.com">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    
    <!-- Google Fonts - シンプルなフォント -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <?php wp_head(); ?>
    
    <!-- 追加のTailwind設定 -->
    <script>
        if (typeof tailwind !== 'undefined' && tailwind.config) {
            const headerConfig = {
                theme: {
                    extend: {
                        ...tailwind.config.theme.extend,
                        zIndex: {
                            'header': '1000',
                            'mobile-menu': '1050',
                            'overlay': '1040'
                        },
                        fontFamily: {
                            'japanese': ['Noto Sans JP', 'sans-serif']
                        }
                    }
                }
            };
            
            Object.assign(tailwind.config.theme.extend, headerConfig.theme.extend);
        }
    </script>
</head>
<body <?php body_class('bg-white text-gray-900 antialiased font-japanese'); ?>>
    
    <!-- WordPressフック -->
    <?php wp_body_open(); ?>
    
    <!-- スキップリンク -->
    <a class="skip-link sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-blue-600 text-white px-4 py-2 rounded-md z-header transition-all duration-200" href="#content">
        <?php esc_html_e('メインコンテンツへスキップ', 'grant-insight'); ?>
    </a>

    <?php
    // カスタマイザー設定の取得
    $header_layout = gi_get_option('gi_header_layout', 'default');
    $show_search = gi_get_option('gi_header_show_search', false);
    ?>

    <!-- メインヘッダー（レスポンシブ完全対応版） -->
    <header class="header-main sticky top-0 bg-white shadow-md border-b border-gray-200 z-header" role="banner">
        
        <div class="header-container">
            
            <!-- レスポンシブ対応サイトロゴエリア -->
            <div class="site-logo">
                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="logo-link">
                    
                    <!-- ロゴ画像 -->
                    <div class="logo-main">
                        <img src="http://joseikin-insight.com/wp-content/uploads/2025/09/1757335941511.png" 
                             alt="助成金・補助金情報サイト" 
                             loading="eager"
                             decoding="async">
                    </div>
                    
                    <!-- サイトタイトル -->
                    <div class="site-title-simple">
                        <h1>助成金・補助金情報サイト</h1>
                        <div class="subtitle-simple">
                            <p>あなたの成功への第一歩をサポート</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- デスクトップナビゲーション -->
            <nav class="desktop-nav hidden lg:flex items-center space-x-2" 
                 role="navigation" 
                 aria-label="メインナビゲーション">
                <?php
                if (has_nav_menu('primary')) {
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'items_wrap'     => '<ul id="menu-primary-navigation" class="flex items-center space-x-2">%3$s</ul>',
                        'depth'          => 2,
                        'walker'         => class_exists('Custom_Nav_Walker') ? new Custom_Nav_Walker() : null,
                    ));
                } else {
                    ?>
                    <ul class="flex items-center space-x-2">
                        <li>
                            <a href="<?php echo esc_url(home_url('/')); ?>" 
                               class="nav-link px-4 py-2.5 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all duration-200 font-medium text-sm <?php echo is_front_page() ? 'bg-gray-100 text-gray-900' : ''; ?>">
                                <i class="fas fa-home mr-2 text-xs" aria-hidden="true"></i>
                                ホーム
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/grants/')); ?>" 
                               class="nav-link px-4 py-2.5 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all duration-200 font-medium text-sm <?php echo (is_post_type_archive('grant') || is_singular('grant')) ? 'bg-gray-100 text-gray-900' : ''; ?>">
                                <i class="fas fa-money-bill-wave mr-2 text-xs" aria-hidden="true"></i>
                                助成金一覧
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/tools/')); ?>" 
                               class="nav-link px-4 py-2.5 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all duration-200 font-medium text-sm <?php echo (is_post_type_archive('tool') || is_singular('tool')) ? 'bg-gray-100 text-gray-900' : ''; ?>">
                                <i class="fas fa-tools mr-2 text-xs" aria-hidden="true"></i>
                                ツール
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/case-studies/')); ?>" 
                               class="nav-link px-4 py-2.5 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all duration-200 font-medium text-sm <?php echo (is_post_type_archive('case_study') || is_singular('case_study')) ? 'bg-gray-100 text-gray-900' : ''; ?>">
                                <i class="fas fa-trophy mr-2 text-xs" aria-hidden="true"></i>
                                成功事例
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/guides/')); ?>" 
                               class="nav-link px-4 py-2.5 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all duration-200 font-medium text-sm <?php echo (is_post_type_archive('guide') || is_singular('guide')) ? 'bg-gray-100 text-gray-900' : ''; ?>">
                                <i class="fas fa-book mr-2 text-xs" aria-hidden="true"></i>
                                ガイド
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/contact/')); ?>" 
                               class="nav-link px-4 py-2.5 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all duration-200 font-medium text-sm <?php echo is_page('contact') ? 'bg-gray-100 text-gray-900' : ''; ?>">
                                <i class="fas fa-envelope mr-2 text-xs" aria-hidden="true"></i>
                                お問い合わせ
                            </a>
                        </li>
                    </ul>
                    <?php
                }
                ?>
                
                <!-- ヘッダー検索（オプション） -->
                <?php if ($show_search) : ?>
                <div class="header-search ml-4">
                    <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="relative">
                        <input type="search" 
                               name="s" 
                               value="<?php echo esc_attr(get_search_query()); ?>"
                               placeholder="助成金を検索..."
                               class="w-64 px-4 py-2.5 pr-11 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                               aria-label="助成金検索">
                        <button type="submit" 
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors duration-200"
                                aria-label="検索実行">
                            <i class="fas fa-search text-sm"></i>
                        </button>
                    </form>
                </div>
                <?php endif; ?>
                
                <!-- シンプルなCTAボタン -->
                <div class="ml-6">
                    <?php
                    $cta_text = gi_get_option('gi_header_cta_text', '無料相談');
                    $cta_url = gi_get_option('gi_header_cta_url', home_url('/contact/'));
                    ?>
                    <a href="<?php echo esc_url($cta_url); ?>" 
                       class="cta-button inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg font-bold text-sm shadow-md hover:shadow-lg transition-all duration-200"
                       aria-label="<?php echo esc_attr($cta_text . 'ページへ移動'); ?>">
                        <i class="fas fa-comments mr-2" aria-hidden="true"></i>
                        <span class="text-white"><?php echo esc_html($cta_text); ?></span>
                    </a>
                </div>
            </nav>
            
            <!-- モバイルメニューボタン -->
            <div class="mobile-menu-toggle flex items-center lg:hidden">
                <button id="mobile-menu-button" 
                        class="menu-button p-3 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all duration-200 z-mobile-menu"
                        aria-label="メニューを開く"
                        aria-expanded="false"
                        aria-controls="mobile-menu">
                    <i class="fas fa-bars text-xl" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- モバイルメニューオーバーレイ -->
    <div id="mobile-menu-overlay" 
         class="mobile-overlay fixed inset-0 bg-black bg-opacity-50 z-overlay hidden opacity-0 transition-all duration-300"
         aria-hidden="true"></div>

    <!-- モバイルメニュー -->
    <aside id="mobile-menu" 
           class="mobile-menu fixed top-0 right-0 w-80 max-w-full h-full bg-white shadow-2xl z-mobile-menu transform translate-x-full transition-transform duration-300 ease-in-out overflow-y-auto"
           aria-label="モバイルナビゲーション"
           aria-hidden="true">
        
        <!-- メニューヘッダー -->
        <div class="menu-header flex items-center justify-between p-6 border-b border-gray-200 bg-gray-50">
            <div class="menu-title flex items-center space-x-3">
                <!-- モバイルメニュー内のロゴ -->
                <img src="http://joseikin-insight.com/wp-content/uploads/2025/09/1757335941511.png" 
                     alt="助成金・補助金情報サイト" 
                     class="h-10 w-auto">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">メニュー</h2>
                    <p class="text-sm text-gray-600">助成金・補助金情報サイト</p>
                </div>
            </div>
            <button id="mobile-menu-close-button" 
                    class="close-button p-2 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all duration-200"
                    aria-label="メニューを閉じる">
                <i class="fas fa-times text-xl" aria-hidden="true"></i>
            </button>
        </div>

        <!-- メニューコンテンツ -->
        <div class="menu-content p-6">
            <!-- ナビゲーションメニュー -->
            <nav class="mobile-navigation mb-8" role="navigation" aria-label="モバイルメインナビゲーション">
                <?php
                // モバイル専用メニューがあればそれを、なければプライマリメニューを流用
                if (has_nav_menu('mobile')) {
                    wp_nav_menu(array(
                        'theme_location' => 'mobile',
                        'container'      => false,
                        'items_wrap'     => '<ul class="nav-list space-y-2">%3$s</ul>',
                        'depth'          => 1,
                    ));
                } elseif (has_nav_menu('primary')) {
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'items_wrap'     => '<ul class="nav-list space-y-2">%3$s</ul>',
                        'depth'          => 1,
                    ));
                } else {
                    // フォールバック: 基本的なメニューを表示
                    ?>
                    <ul class="nav-list space-y-2">
                        <li>
                            <a href="<?php echo esc_url(home_url('/')); ?>" 
                               class="nav-item flex items-center px-4 py-3 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all duration-200 font-medium <?php echo is_front_page() ? 'text-gray-900 bg-gray-100' : ''; ?>">
                                <i class="fas fa-home w-5 text-center mr-3 text-gray-500" aria-hidden="true"></i>
                                ホーム
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/grants/')); ?>" 
                               class="nav-item flex items-center px-4 py-3 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all duration-200 font-medium <?php echo (is_post_type_archive('grant') || is_singular('grant')) ? 'text-gray-900 bg-gray-100' : ''; ?>">
                                <i class="fas fa-money-bill-wave w-5 text-center mr-3 text-gray-500" aria-hidden="true"></i>
                                助成金一覧
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/tools/')); ?>" 
                               class="nav-item flex items-center px-4 py-3 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all duration-200 font-medium <?php echo (is_post_type_archive('tool') || is_singular('tool')) ? 'text-gray-900 bg-gray-100' : ''; ?>">
                                <i class="fas fa-tools w-5 text-center mr-3 text-gray-500" aria-hidden="true"></i>
                                ツール
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/case-studies/')); ?>" 
                               class="nav-item flex items-center px-4 py-3 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all duration-200 font-medium <?php echo (is_post_type_archive('case_study') || is_singular('case_study')) ? 'text-gray-900 bg-gray-100' : ''; ?>">
                                <i class="fas fa-trophy w-5 text-center mr-3 text-gray-500" aria-hidden="true"></i>
                                成功事例
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/guides/')); ?>" 
                               class="nav-item flex items-center px-4 py-3 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all duration-200 font-medium <?php echo (is_post_type_archive('guide') || is_singular('guide')) ? 'text-gray-900 bg-gray-100' : ''; ?>">
                                <i class="fas fa-book w-5 text-center mr-3 text-gray-500" aria-hidden="true"></i>
                                ガイド・解説
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/grant-tips/')); ?>" 
                               class="nav-item flex items-center px-4 py-3 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all duration-200 font-medium <?php echo (is_post_type_archive('grant_tip') || is_singular('grant_tip')) ? 'text-gray-900 bg-gray-100' : ''; ?>">
                                <i class="fas fa-lightbulb w-5 text-center mr-3 text-gray-500" aria-hidden="true"></i>
                                申請のコツ
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/contact/')); ?>" 
                               class="nav-item flex items-center px-4 py-3 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all duration-200 font-medium <?php echo is_page('contact') ? 'text-gray-900 bg-gray-100' : ''; ?>">
                                <i class="fas fa-envelope w-5 text-center mr-3 text-gray-500" aria-hidden="true"></i>
                                お問い合わせ
                            </a>
                        </li>
                    </ul>
                    <?php
                }
                ?>
            </nav>
            
            <!-- モバイル検索（オプション） -->
            <?php if ($show_search) : ?>
            <div class="mobile-search mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-search mr-2 text-gray-500" aria-hidden="true"></i>
                    助成金検索
                </h3>
                <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="relative">
                    <input type="search" 
                           name="s" 
                           value="<?php echo esc_attr(get_search_query()); ?>"
                           placeholder="キーワードを入力..."
                           class="w-full px-4 py-3 pr-12 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                           aria-label="助成金検索">
                    <button type="submit" 
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 transition-colors duration-200"
                            aria-label="検索実行">
                        <i class="fas fa-search text-xs"></i>
                    </button>
                </form>
            </div>
            <?php endif; ?>
            
            <!-- CTAボタン -->
            <div class="cta-section mb-8">
                <?php
                $cta_text = gi_get_option('gi_header_cta_text', '無料相談');
                $cta_url = gi_get_option('gi_header_cta_url', home_url('/contact/'));
                ?>
                <a href="<?php echo esc_url($cta_url); ?>" 
                   class="cta-button block w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-4 px-6 rounded-lg font-bold text-base shadow-md hover:shadow-lg transition-all duration-200"
                   aria-label="<?php echo esc_attr($cta_text . 'ページへ移動'); ?>">
                    <i class="fas fa-comments mr-2" aria-hidden="true"></i>
                    <span class="text-white"><?php echo esc_html($cta_text . 'を始める'); ?></span>
                </a>
            </div>

            <!-- 追加情報 -->
            <div class="additional-info pt-6 border-t border-gray-200">
                <div class="contact-info mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">
                        <i class="fas fa-phone mr-2 text-gray-500" aria-hidden="true"></i>
                        お問い合わせ
                    </h3>
                    <div class="info-grid grid grid-cols-2 gap-4 text-center">
                        <div class="info-item bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors duration-200">
                            <div class="info-icon text-2xl text-gray-600 mb-2" aria-hidden="true">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-label text-xs text-gray-600 font-medium">お電話</div>
                            <div class="info-value text-sm text-gray-800 font-semibold">平日 9-18時</div>
                        </div>
                        <div class="info-item bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors duration-200">
                            <div class="info-icon text-2xl text-gray-600 mb-2" aria-hidden="true">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-label text-xs text-gray-600 font-medium">メール</div>
                            <div class="info-value text-sm text-gray-800 font-semibold">24時間受付</div>
                        </div>
                    </div>
                </div>
                
                <!-- サイト情報 -->
                <div class="site-info text-center text-xs text-gray-500">
                    <div class="mb-3">
                        <p class="font-medium text-gray-600">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
                        <p class="mt-1">All rights reserved.</p>
                    </div>
                    
                    <?php 
                    $privacy_url = gi_get_option('gi_privacy_policy_url');
                    $terms_url = gi_get_option('gi_terms_url');
                    if ($privacy_url || $terms_url) : 
                    ?>
                    <div class="legal-links flex justify-center space-x-4 pt-3 border-t border-gray-200">
                        <?php if ($privacy_url) : ?>
                        <a href="<?php echo esc_url($privacy_url); ?>" 
                           class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200">
                            プライバシーポリシー
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($terms_url) : ?>
                        <a href="<?php echo esc_url($terms_url); ?>" 
                           class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200">
                            利用規約
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </aside>

    <!-- メインコンテンツ開始 -->
    <main id="content" class="site-main" role="main">

<!-- レスポンシブ完全対応JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 要素の取得
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenuCloseButton = document.getElementById('mobile-menu-close-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
    
    // メニュー状態の管理
    let isMenuOpen = false;

    // メニューを開く関数
    function openMobileMenu() {
        if (!mobileMenu || !mobileMenuOverlay) return;
        
        isMenuOpen = true;
        
        // オーバーレイを表示
        mobileMenuOverlay.classList.remove('hidden');
        mobileMenuOverlay.setAttribute('aria-hidden', 'false');
        setTimeout(() => {
            mobileMenuOverlay.classList.remove('opacity-0');
            mobileMenuOverlay.classList.add('opacity-100');
        }, 10);
        
        // メニューをスライドイン
        mobileMenu.classList.remove('translate-x-full');
        mobileMenu.classList.add('translate-x-0');
        
        // ボディのスクロールを無効化
        document.body.style.overflow = 'hidden';
        
        // ARIA属性を更新
        mobileMenuButton.setAttribute('aria-expanded', 'true');
        mobileMenuButton.setAttribute('aria-label', 'メニューを閉じる');
        mobileMenu.setAttribute('aria-hidden', 'false');
        
        // フォーカスをメニューに移動
        mobileMenuCloseButton.focus();
    }

    // メニューを閉じる関数
    function closeMobileMenu() {
        if (!mobileMenu || !mobileMenuOverlay || !isMenuOpen) return;
        
        isMenuOpen = false;
        
        // メニューをスライドアウト
        mobileMenu.classList.remove('translate-x-0');
        mobileMenu.classList.add('translate-x-full');
        
        // オーバーレイをフェードアウト
        mobileMenuOverlay.classList.remove('opacity-100');
        mobileMenuOverlay.classList.add('opacity-0');
        
        setTimeout(() => {
            mobileMenuOverlay.classList.add('hidden');
            mobileMenuOverlay.setAttribute('aria-hidden', 'true');
        }, 300);
        
        // ボディのスクロールを有効化
        document.body.style.overflow = '';
        
        // ARIA属性を更新
        mobileMenuButton.setAttribute('aria-expanded', 'false');
        mobileMenuButton.setAttribute('aria-label', 'メニューを開く');
        mobileMenu.setAttribute('aria-hidden', 'true');
        
        // フォーカスをボタンに戻す
        mobileMenuButton.focus();
    }

    // イベントリスナーの追加
    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            openMobileMenu();
        });
    }

    if (mobileMenuCloseButton) {
        mobileMenuCloseButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeMobileMenu();
        });
    }

    if (mobileMenuOverlay) {
        mobileMenuOverlay.addEventListener('click', closeMobileMenu);
    }

    // ESCキーでメニューを閉じる
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isMenuOpen) {
            closeMobileMenu();
        }
    });

    // ウィンドウサイズがPC幅になったら、開いているメニューを閉じる
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            if (window.innerWidth >= 1024 && isMenuOpen) {
                closeMobileMenu();
            }
        }, 250);
    });

    // メニュー内のリンクをクリックしたらメニューを閉じる
    const mobileNavLinks = mobileMenu ? mobileMenu.querySelectorAll('a') : [];
    if (mobileNavLinks.length > 0) {
        mobileNavLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (!this.getAttribute('href').startsWith('#')) {
                    setTimeout(closeMobileMenu, 200);
                }
            });
        });
    }
    
    // スムーズスクロール（アンカーリンク用）
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href').substring(1);
            const target = document.getElementById(targetId);
            if (target) {
                e.preventDefault();
                const header = document.querySelector('.header-main');
                const headerHeight = header ? header.offsetHeight : 0;
                const targetPosition = target.offsetTop - headerHeight - 20;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
                
                // モバイルメニューが開いていれば閉じる
                if (isMenuOpen) {
                    setTimeout(closeMobileMenu, 300);
                }
            }
        });
    });
    
    // ヘッダーのスクロール効果（簡素版）
    let lastScrollY = window.scrollY;
    const header = document.querySelector('.header-main');
    let ticking = false;
    
    function updateHeader() {
        const currentScrollY = window.scrollY;
        
        // スクロール量に応じてシャドウを調整
        if (currentScrollY > 10) {
            header.classList.add('shadow-lg');
            header.classList.remove('shadow-md');
        } else {
            header.classList.add('shadow-md');
            header.classList.remove('shadow-lg');
        }
        
        lastScrollY = currentScrollY;
        ticking = false;
    }

    window.addEventListener('scroll', function() {
        if (!ticking) {
            requestAnimationFrame(updateHeader);
            ticking = true;
        }
    });
});
</script>

<!-- レスポンシブ完全対応スタイル -->
<style>
/* ベーススタイル */
.font-japanese {
    font-family: 'Noto Sans JP', sans-serif;
}

/* ヘッダーの基本スタイル */
.header-main {
    background: white;
    transition: box-shadow 0.2s ease;
}

/* レスポンシブ対応ヘッダーコンテナ */
.header-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0.75rem 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: nowrap;
    gap: 0.5rem;
    min-height: 3rem;
}

.site-logo {
    flex-shrink: 1;
    min-width: 0;
    max-width: 75%;
    overflow: hidden;
}

.logo-link {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: inherit;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.logo-main img {
    height: 3rem;
    width: auto;
    max-width: 100%;
}

.site-title-simple {
    min-width: 0;
    flex: 1;
}

.site-title-simple h1 {
    font-size: 1rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
    line-height: 1.2;
    word-break: break-word;
}

.subtitle-simple p {
    font-size: 0.75rem;
    color: #6b7280;
    margin: 0.25rem 0 0 0;
    font-weight: 500;
}

.mobile-menu-toggle {
    flex: 0 0 auto;
}

/* ナビゲーションリンクのシンプルスタイル */
.nav-link {
    transition: all 0.2s ease;
}

.nav-link:hover {
    transform: translateY(-1px);
}

/* CTAボタンのシンプルスタイル */
.cta-button {
    transition: all 0.2s ease;
}

.cta-button:hover {
    transform: translateY(-1px);
}

/* モバイルメニューのスタイル */
.mobile-menu {
    box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
}

/* スマホ対応 */
@media (max-width: 640px) {
    .header-container {
        padding: 0.5rem 0.75rem;
    }
    
    .site-logo {
        max-width: 70%;
    }
    
    .logo-link {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
    
    .site-title-simple h1 {
        font-size: 0.875rem;
    }
    
    .subtitle-simple {
        display: none;
    }
    
    .logo-main img {
        height: 2.5rem;
    }
    
    .mobile-menu {
        width: 100vw;
    }
}

/* 横向き対応 */
@media (max-width: 640px) and (orientation: landscape) {
    .header-container {
        padding: 0.25rem 0.5rem;
    }
    
    .logo-main img {
        height: 2rem;
    }
    
    .site-title-simple h1 {
        font-size: 0.75rem;
    }
}

/* 超小型デバイス */
@media (max-width: 320px) {
    .site-logo {
        max-width: 65%;
    }
    
    .site-title-simple h1 {
        font-size: 0.75rem;
        word-break: break-all;
    }
    
    .logo-main img {
        height: 2rem;
    }
}

/* タブレット対応 */
@media (min-width: 641px) and (max-width: 768px) {
    .site-title-simple h1 {
        font-size: 1.125rem;
    }
    
    .logo-main img {
        height: 3.5rem;
    }
}

/* デスクトップ対応 */
@media (min-width: 1024px) {
    .site-title-simple h1 {
        font-size: 1.5rem;
    }
    
    .logo-main img {
        height: 4rem;
    }
    
    .logo-link {
        flex-direction: row;
        gap: 1.5rem;
    }
}

/* アクセシビリティ */
@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* フォーカス状態 */
button:focus-visible,
a:focus-visible,
input:focus-visible {
    outline: 2px solid #3B82F6;
    outline-offset: 2px;
    border-radius: 4px;
}

/* スキップリンク */
.skip-link {
    transform: translateY(-100%);
    transition: transform 0.3s ease;
}

.skip-link:focus {
    transform: translateY(0);
}

/* 非表示クラス */
.sr-only {
    position: absolute !important;
    width: 1px !important;
    height: 1px !important;
    padding: 0 !important;
    margin: -1px !important;
    overflow: hidden !important;
    clip: rect(0, 0, 0, 0) !important;
    white-space: nowrap !important;
    border: 0 !important;
}

.focus\:not-sr-only:focus {
    position: static !important;
    width: auto !important;
    height: auto !important;
    padding: inherit !important;
    margin: inherit !important;
    overflow: visible !important;
    clip: auto !important;
    white-space: normal !important;
}

/* z-indexクラス */
.z-header { z-index: 1000; }
.z-mobile-menu { z-index: 1050; }
.z-overlay { z-index: 1040; }
</style>