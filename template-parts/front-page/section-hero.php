<?php
/**
 * ヒーローセクション - 完璧実運用版
 * Hero Section - Perfect Production Version
 * 
 * @package Grant_Insight_Perfect
 * @version 6.2-production
 */

// セキュリティチェック
if (!defined('ABSPATH')) {
    exit;
}

// 実運用向けヘルパー関数群
if (!function_exists('gip_safe_output')) {
    function gip_safe_output($text, $allow_html = false) {
        return $allow_html ? wp_kses_post($text) : esc_html($text);
    }
}

if (!function_exists('gip_get_option')) {
    function gip_get_option($key, $default = '') {
        $value = get_option('gip_' . $key, $default);
        return !empty($value) ? $value : $default;
    }
}

if (!function_exists('gip_get_media_url')) {
    function gip_get_media_url($filename, $fallback = '') {
        // WordPressメディアライブラリから検索
        $attachment = get_posts(array(
            'post_type' => 'attachment',
            'meta_query' => array(
                array(
                    'key' => '_wp_attached_file',
                    'value' => $filename,
                    'compare' => 'LIKE'
                )
            ),
            'posts_per_page' => 1
        ));
        
        if (!empty($attachment)) {
            return wp_get_attachment_url($attachment[0]->ID);
        }
        
        // アップロードディレクトリから検索
        $upload_dir = wp_upload_dir();
        $file_path = $upload_dir['basedir'] . '/' . $filename;
        
        if (file_exists($file_path)) {
            return $upload_dir['baseurl'] . '/' . $filename;
        }
        
        // 2025/09フォルダから検索（既存パス対応）
        $date_path = $upload_dir['basedir'] . '/2025/09/' . $filename;
        if (file_exists($date_path)) {
            return $upload_dir['baseurl'] . '/2025/09/' . $filename;
        }
        
        return $fallback;
    }
}

if (!function_exists('gip_get_hero_stats')) {
    function gip_get_hero_stats() {
        global $wpdb;
        
        // 実際の統計データを取得
        $grants_count = wp_count_posts('grant');
        $total_grants = isset($grants_count->publish) ? $grants_count->publish : 0;
        
        $success_rate = get_option('gip_success_rate', 95);
        $total_funding = get_option('gip_total_funding', 50);
        
        return array(
            array(
                'number' => number_format($total_grants),
                'suffix' => '+',
                'label' => '登録助成金数',
                'gradient' => 'from-blue-500 to-blue-600',
                'progress' => min(100, ($total_grants / 10)),
                'icon' => 'fas fa-database'
            ),
            array(
                'number' => $success_rate,
                'suffix' => '%',
                'label' => '成功マッチング率',
                'gradient' => 'from-green-500 to-green-600',
                'progress' => $success_rate,
                'icon' => 'fas fa-chart-line'
            ),
            array(
                'number' => $total_funding,
                'suffix' => '億円+',
                'label' => '累計支援金額',
                'gradient' => 'from-purple-500 to-purple-600',
                'progress' => min(100, ($total_funding / 100) * 100),
                'icon' => 'fas fa-yen-sign'
            )
        );
    }
}

// 設定値の取得
$hero_config = array(
    'title' => gip_get_option('hero_title', 'AI が提案する助成金・補助金情報サイト'),
    'subtitle' => gip_get_option('hero_subtitle', '最先端のAI技術で、あなたのビジネスに最適な助成金・補助金を瞬時に発見。成功への道筋を明確にします。'),
    'cta_primary_text' => gip_get_option('hero_cta_primary_text', '今すぐ検索開始'),
    'cta_secondary_text' => gip_get_option('hero_cta_secondary_text', 'AI相談を開始'),
    'video_url' => gip_get_media_url('video-1756704200376.mp4', 'http://keishi0804.xsrv.jp/wp-content/uploads/2025/09/video-1756704200376.mp4'),
    'logo_url' => gip_get_media_url('1756738342522.png', 'http://keishi0804.xsrv.jp/wp-content/uploads/2025/09/1756738342522.png')
);

$hero_stats = gip_get_hero_stats();
$site_name = get_bloginfo('name');
$current_user_id = get_current_user_id();
?>

<section id="hero-section" class="hero-section relative overflow-hidden min-h-screen flex items-center bg-gradient-to-br from-emerald-50 via-teal-50 to-blue-50" role="banner" aria-label="ヒーローセクション">
    <!-- 構造化データ -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "<?php echo esc_js($site_name); ?>",
        "description": "<?php echo esc_js($hero_config['subtitle']); ?>",
        "url": "<?php echo esc_url(home_url()); ?>",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "<?php echo esc_url(home_url()); ?>?s={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>

    <!-- 背景装飾パターン -->
    <div class="absolute inset-0 opacity-5" aria-hidden="true">
        <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="hero-grid-pattern" width="10" height="10" patternUnits="userSpaceOnUse">
                    <path d="M 10 0 L 0 0 0 10" fill="none" stroke="currentColor" stroke-width="0.5"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#hero-grid-pattern)" class="text-emerald-300"/>
        </svg>
    </div>

    <!-- フローティング装飾要素 -->
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="floating-element absolute top-20 left-10 w-32 h-32 bg-gradient-to-r from-emerald-400/20 to-teal-400/20 rounded-full blur-3xl animate-float-1"></div>
        <div class="floating-element absolute bottom-20 right-10 w-40 h-40 bg-gradient-to-r from-blue-400/20 to-indigo-400/20 rounded-full blur-3xl animate-float-2"></div>
        <div class="floating-element absolute top-1/2 left-1/3 w-24 h-24 bg-gradient-to-r from-purple-400/20 to-pink-400/20 rounded-full blur-3xl animate-float-3"></div>
        <div class="floating-element absolute top-10 right-1/4 w-28 h-28 bg-gradient-to-r from-yellow-400/20 to-orange-400/20 rounded-full blur-3xl animate-float-4"></div>
    </div>

    <!-- メインコンテンツ -->
    <div class="container relative z-20 mx-auto px-4 lg:px-8">
        <div class="grid lg:grid-cols-12 gap-12 items-center min-h-screen py-20">
            
            <!-- ヒーローコンテンツ（左側 7カラム） -->
            <div class="lg:col-span-7 hero-content">
                <!-- プレミアムバッジ -->
                <div class="inline-flex items-center gap-3 bg-white/90 backdrop-blur-sm text-emerald-700 px-6 py-3 rounded-full text-sm font-bold mb-8 shadow-xl border border-emerald-200 hover:shadow-2xl transition-all duration-300 animate-on-scroll opacity-0 translate-y-8" data-aos="fade-up" data-aos-delay="200">
                    <div class="relative">
                        <i class="fas fa-sparkles animate-pulse text-emerald-500" aria-hidden="true"></i>
                        <div class="absolute -inset-2 bg-emerald-200 rounded-full opacity-30 animate-ping"></div>
                    </div>
                    <span class="bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent font-black">AI搭載</span>
                    <span class="hidden sm:inline">次世代プラットフォーム</span>
                    <i class="fas fa-rocket text-teal-500 animate-bounce" aria-hidden="true"></i>
                </div>
                
                <!-- メインタイトル -->
                <h1 class="hero-title text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-black leading-tight mb-8 animate-on-scroll opacity-0 translate-y-8" data-aos="fade-up" data-aos-delay="400">
                    <span class="bg-gradient-to-r from-gray-800 via-emerald-700 to-teal-700 bg-clip-text text-transparent drop-shadow-sm">
                        <?php echo gip_safe_output($hero_config['title']); ?>
                    </span>
                </h1>
                
                <!-- サブタイトル -->
                <p class="hero-subtitle text-lg md:text-xl lg:text-2xl text-gray-600 mb-10 leading-relaxed max-w-2xl font-medium animate-on-scroll opacity-0 translate-y-8" data-aos="fade-up" data-aos-delay="600">
                    <?php echo gip_safe_output($hero_config['subtitle']); ?>
                </p>
                
                <!-- CTAボタン群 -->
                <div class="hero-cta flex flex-col sm:flex-row gap-4 mb-12 animate-on-scroll opacity-0 translate-y-8" data-aos="fade-up" data-aos-delay="800">
                    <a href="#grant-search" 
                       class="cta-primary group relative inline-flex items-center justify-center gap-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-8 py-5 rounded-full font-bold text-lg transition-all duration-500 transform hover:scale-105 hover:shadow-2xl shadow-emerald-500/25 overflow-hidden focus:outline-none focus:ring-4 focus:ring-emerald-200"
                       role="button"
                       aria-label="助成金検索を開始する">
                        <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                        <i class="fas fa-search relative z-10 group-hover:animate-pulse" aria-hidden="true"></i>
                        <span class="relative z-10"><?php echo gip_safe_output($hero_config['cta_primary_text']); ?></span>
                        <i class="fas fa-arrow-right relative z-10 group-hover:translate-x-1 transition-transform duration-300" aria-hidden="true"></i>
                    </a>
                    
                    <!-- AI相談ボタンとロゴのコンビネーション -->
                    <div class="flex items-center gap-4">
                        <button onclick="gipOpenAIChat()" 
                               class="cta-secondary group inline-flex items-center justify-center gap-3 bg-white/90 hover:bg-white text-emerald-700 hover:text-emerald-800 px-8 py-5 rounded-full font-bold text-lg transition-all duration-300 backdrop-blur-sm border border-emerald-200 hover:border-emerald-300 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-emerald-200"
                               aria-label="AI相談を開始する">
                            <i class="fas fa-robot group-hover:animate-bounce text-emerald-600" aria-hidden="true"></i>
                            <span><?php echo gip_safe_output($hero_config['cta_secondary_text']); ?></span>
                        </button>
                        
                        <!-- サイトロゴ -->
                        <div class="flex-shrink-0 animate-on-scroll opacity-0 translate-x-4" data-aos="fade-left" data-aos-delay="1000">
                            <?php if (!empty($hero_config['logo_url'])): ?>
                            <img src="<?php echo esc_url($hero_config['logo_url']); ?>" 
                                 alt="<?php echo esc_attr($site_name); ?>のロゴ" 
                                 class="h-12 md:h-16 lg:h-20 w-auto object-contain hover:scale-110 transition-transform duration-300 cursor-pointer drop-shadow-lg"
                                 onclick="gipScrollToTop()"
                                 loading="lazy"
                                 decoding="async">
                            <?php else: ?>
                            <div class="h-12 md:h-16 lg:h-20 w-20 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg hover:scale-110 transition-transform duration-300 cursor-pointer"
                                 onclick="gipScrollToTop()"
                                 role="button"
                                 aria-label="トップへ戻る">
                                <i class="fas fa-coins text-white text-2xl" aria-hidden="true"></i>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- 統計情報 -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 animate-on-scroll opacity-0 translate-y-8" data-aos="fade-up" data-aos-delay="1200">
                    <?php foreach ($hero_stats as $index => $stat): ?>
                    <div class="stat-card bg-white/90 backdrop-blur-sm rounded-xl p-6 text-center shadow-lg border border-white/50 hover:shadow-xl transition-all duration-300 transform hover:scale-105 animate-on-scroll opacity-0 translate-y-4" data-aos="fade-up" data-aos-delay="<?php echo 1400 + ($index * 100); ?>">
                        <div class="flex items-center justify-center mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r <?php echo gip_safe_output($stat['gradient']); ?> rounded-lg flex items-center justify-center shadow-md">
                                <i class="<?php echo gip_safe_output($stat['icon']); ?> text-white text-xl" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="text-3xl md:text-4xl font-black text-transparent bg-gradient-to-r <?php echo gip_safe_output($stat['gradient']); ?> bg-clip-text mb-2 counter" 
                             data-target="<?php echo esc_attr(str_replace(',', '', $stat['number'])); ?>"
                             data-suffix="<?php echo esc_attr($stat['suffix']); ?>">
                            0
                        </div>
                        <div class="text-sm md:text-base text-gray-600 font-semibold mb-3"><?php echo gip_safe_output($stat['label']); ?></div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r <?php echo gip_safe_output($stat['gradient']); ?> h-2 rounded-full progress-bar transition-all duration-1000 ease-out" 
                                 data-width="<?php echo esc_attr($stat['progress']); ?>" 
                                 style="width: 0%;"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ヒーロービジュアル（右側 5カラム） -->
            <div class="lg:col-span-5 hero-visual">
                <div class="relative max-w-xl mx-auto animate-on-scroll opacity-0 translate-y-8" data-aos="fade-up" data-aos-delay="1600">
                    
                    <!-- 回転リング群 -->
                    <div class="absolute inset-0 flex items-center justify-center" aria-hidden="true">
                        <div class="absolute w-96 h-96 border-2 border-emerald-200/30 rounded-full animate-spin-slow"></div>
                        <div class="absolute w-80 h-80 border border-teal-200/40 rounded-full animate-spin-reverse"></div>
                        <div class="absolute w-64 h-64 border border-emerald-300/20 rounded-full animate-spin-slow-2"></div>
                    </div>
                    
                    <!-- メイン動画エリア -->
                    <div class="relative z-10 mx-auto w-80 h-80 lg:w-96 lg:h-96 rounded-3xl overflow-hidden shadow-2xl bg-white/10 backdrop-blur-sm border border-white/20">
                        <div class="relative w-full h-full">
                            <?php if (!empty($hero_config['video_url'])): ?>
                            <video 
                                id="hero-video"
                                class="hero-video w-full h-full object-cover rounded-3xl opacity-0 transition-opacity duration-1000"
                                autoplay 
                                muted 
                                loop 
                                playsinline 
                                preload="metadata"
                                disablePictureInPicture
                                controlslist="nodownload noplaybackrate"
                                style="pointer-events: none;"
                                oncanplay="this.style.opacity='1'; console.log('✅ Hero video loaded successfully');"
                                onerror="gipHandleVideoError(this)"
                                aria-label="AI助成金システムのデモ動画">
                                <source src="<?php echo esc_url($hero_config['video_url']); ?>" type="video/mp4">
                                <track kind="captions" src="" srclang="ja" label="Japanese" default>
                            </video>
                            <?php endif; ?>
                            
                            <!-- フォールバック -->
                            <div id="video-fallback" class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl <?php echo !empty($hero_config['video_url']) ? 'hidden' : ''; ?>">
                                <div class="text-center text-white">
                                    <i class="fas fa-robot text-6xl animate-pulse mb-4" aria-hidden="true"></i>
                                    <h3 class="text-xl font-bold">AI Assistant</h3>
                                    <p class="text-sm opacity-80 mt-2">助成金検索システム</p>
                                </div>
                            </div>
                            
                            <!-- 動画上のグロー効果 -->
                            <div class="absolute inset-0 rounded-3xl bg-gradient-to-t from-emerald-500/10 to-transparent pointer-events-none"></div>
                        </div>
                    </div>

                    <!-- フローティング装飾要素 -->
                    <div class="floating-decorations" aria-hidden="true">
                        <div class="absolute -top-6 -right-6 w-24 h-24 bg-gradient-to-r from-yellow-400 to-orange-400 rounded-full opacity-80 animate-pulse flex items-center justify-center shadow-lg">
                            <i class="fas fa-star text-white text-xl"></i>
                        </div>
                        <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full opacity-60 animate-bounce flex items-center justify-center shadow-lg">
                            <i class="fas fa-lightbulb text-white text-lg"></i>
                        </div>
                        <div class="absolute top-1/2 -left-10 w-16 h-16 bg-gradient-to-r from-pink-400 to-red-400 rounded-full opacity-50 animate-ping flex items-center justify-center">
                            <i class="fas fa-heart text-white text-sm"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- スクロールインジケーター -->
    <div class="scroll-indicator absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20 animate-bounce">
        <a href="#grant-search" 
           class="flex flex-col items-center gap-2 text-emerald-600 hover:text-emerald-800 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-emerald-400 rounded-lg p-2"
           aria-label="助成金検索セクションへスクロール">
            <span class="text-xs font-medium">スクロール</span>
            <div class="w-6 h-10 border-2 border-emerald-400 rounded-full flex justify-center">
                <div class="w-1 h-3 bg-emerald-500 rounded-full mt-2 animate-scroll-dot"></div>
            </div>
        </a>
    </div>

    <!-- パフォーマンス監視用 -->
    <div id="hero-performance-monitor" class="hidden" data-load-time="<?php echo microtime(true); ?>"></div>
</section>

<!-- 実運用JavaScript -->
<script>
// グローバル名前空間の設定
window.GrantInsightPerfect = window.GrantInsightPerfect || {};

(function(GIP) {
    'use strict';
    
    // 設定
    const CONFIG = {
        ANIMATION_DURATION: 700,
        COUNTER_DURATION: 2000,
        PROGRESS_DURATION: 1500,
        DEBUG: <?php echo WP_DEBUG ? 'true' : 'false'; ?>
    };
    
    // デバッグログ
    function log(message, type = 'info') {
        if (CONFIG.DEBUG) {
            console.log(`🎨 Hero Section [${type.toUpperCase()}]: ${message}`);
        }
    }
    
    // 初期化
    function init() {
        log('初期化開始', 'info');
        
        // DOM要素の取得と検証
        const heroSection = document.getElementById('hero-section');
        if (!heroSection) {
            log('ヒーローセクションが見つかりません', 'error');
            return;
        }
        
        // 各機能の初期化
        initScrollAnimations();
        initCounterAnimations();
        initVideoHandler();
        initCTAButtons();
        initSmoothScroll();
        initPerformanceMonitor();
        
        log('初期化完了', 'success');
        
        // カスタムイベントの発火
        window.dispatchEvent(new CustomEvent('heroSectionReady', {
            detail: { timestamp: Date.now() }
        }));
    }
    
    // スクロールアニメーション
    function initScrollAnimations() {
        if (!('IntersectionObserver' in window)) {
            log('IntersectionObserver未サポート - フォールバック実行', 'warning');
            fallbackAnimations();
            return;
        }
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const element = entry.target;
                    const delay = element.dataset.aosDelay || 0;
                    
                    setTimeout(() => {
                        element.classList.remove('opacity-0', 'translate-y-8', 'translate-y-4', 'translate-x-4');
                        element.classList.add('opacity-100', 'translate-y-0', 'translate-x-0');
                    }, parseInt(delay));
                    
                    observer.unobserve(element);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });
        
        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });
        
        log('スクロールアニメーション初期化完了');
    }
    
    // フォールバックアニメーション
    function fallbackAnimations() {
        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            el.classList.remove('opacity-0', 'translate-y-8', 'translate-y-4', 'translate-x-4');
            el.classList.add('opacity-100', 'translate-y-0', 'translate-x-0');
        });
    }
    
    // カウンターアニメーション
    function initCounterAnimations() {
        const counters = document.querySelectorAll('.counter');
        
        if (counters.length === 0) {
            log('カウンター要素が見つかりません', 'warning');
            return;
        }
        
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const target = parseInt(counter.dataset.target) || 0;
                    const suffix = counter.dataset.suffix || '';
                    
                    animateCounter(counter, target, suffix);
                    counterObserver.unobserve(counter);
                }
            });
        }, { threshold: 0.5 });
        
        counters.forEach(counter => {
            counterObserver.observe(counter);
        });
        
        // プログレスバーアニメーション
        const progressBars = document.querySelectorAll('.progress-bar');
        const progressObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const bar = entry.target;
                    const width = bar.dataset.width || 0;
                    
                    setTimeout(() => {
                        bar.style.width = width + '%';
                    }, 500);
                    
                    progressObserver.unobserve(bar);
                }
            });
        }, { threshold: 0.5 });
        
        progressBars.forEach(bar => {
            progressObserver.observe(bar);
        });
        
        log('カウンターアニメーション初期化完了');
    }
    
    // カウンターアニメーション実行
    function animateCounter(element, target, suffix) {
        const duration = CONFIG.COUNTER_DURATION;
        const stepTime = 16; // 60fps
        const steps = duration / stepTime;
        const increment = target / steps;
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target.toLocaleString() + suffix;
                clearInterval(timer);
                log(`カウンター完了: ${target}${suffix}`);
            } else {
                element.textContent = Math.floor(current).toLocaleString() + suffix;
            }
        }, stepTime);
    }
    
    // 動画ハンドリング
    function initVideoHandler() {
        const video = document.getElementById('hero-video');
        if (!video) {
            log('動画要素が見つかりません', 'info');
            return;
        }
        
        video.addEventListener('loadeddata', function() {
            this.style.opacity = '1';
            log('動画読み込み完了', 'success');
        });
        
        video.addEventListener('error', function(e) {
            log(`動画エラー: ${e.message}`, 'error');
            GIP.handleVideoError(this);
        });
        
        video.addEventListener('canplay', function() {
            log('動画再生準備完了', 'info');
        });
        
        // 遅延読み込み対応
        if (video.readyState >= 2) {
            video.style.opacity = '1';
        }
    }
    
    // CTAボタンの初期化
    function initCTAButtons() {
        const ctaButtons = document.querySelectorAll('.cta-primary, .cta-secondary');
        
        ctaButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                // クリック効果
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
                
                // Google Analytics追跡
                if (typeof gtag !== 'undefined') {
                    const buttonType = this.classList.contains('cta-primary') ? 'primary' : 'secondary';
                    const buttonText = this.textContent.trim();
                    
                    gtag('event', 'click', {
                        event_category: 'hero_cta',
                        event_label: `${buttonType}: ${buttonText}`,
                        value: 1
                    });
                    
                    log(`Analytics追跡: ${buttonType} CTA clicked`, 'info');
                }
            });
            
            // キーボード対応
            button.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        });
        
        log('CTAボタン初期化完了');
    }
    
    // スムーズスクロール
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    
                    // フォーカス管理
                    target.focus();
                    
                    log(`スムーズスクロール: ${this.getAttribute('href')}`, 'info');
                }
            });
        });
    }
    
    // パフォーマンス監視
    function initPerformanceMonitor() {
        if (!('PerformanceObserver' in window)) {
            log('PerformanceObserver未サポート', 'warning');
            return;
        }
        
        const perfObserver = new PerformanceObserver((list) => {
            list.getEntries().forEach((entry) => {
                if (entry.entryType === 'measure') {
                    log(`Performance: ${entry.name} - ${entry.duration.toFixed(2)}ms`, 'perf');
                }
            });
        });
        
        perfObserver.observe({ entryTypes: ['measure'] });
        
        // 初期化時間計測
        const loadTime = document.getElementById('hero-performance-monitor');
        if (loadTime) {
            const startTime = parseFloat(loadTime.dataset.loadTime);
            const endTime = performance.now() / 1000;
            const duration = ((endTime - startTime) * 1000).toFixed(2);
            
            log(`Hero Section load time: ${duration}ms`, 'perf');
        }
    }
    
    // 公開メソッド
    GIP.hero = {
        init: init,
        animateCounters: initCounterAnimations,
        log: log
    };
    
    // DOM読み込み完了時に初期化
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
})(window.GrantInsightPerfect);

// グローバル関数（後方互換性）
function gipScrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
    
    if (window.GrantInsightPerfect.hero) {
        window.GrantInsightPerfect.hero.log('トップへスクロール実行', 'action');
    }
}

function gipOpenAIChat() {
    // AI チャット機能の実装
    if (typeof window.aiChatSystem !== 'undefined') {
        window.aiChatSystem.open();
    } else {
        // フォールバック: 検索セクションへスクロール
        const searchSection = document.getElementById('grant-search');
        if (searchSection) {
            searchSection.scrollIntoView({ behavior: 'smooth' });
        }
    }
    
    if (window.GrantInsightPerfect.hero) {
        window.GrantInsightPerfect.hero.log('AIチャット開始', 'action');
    }
}

function gipHandleVideoError(videoElement) {
    const fallback = document.getElementById('video-fallback');
    
    if (videoElement && fallback) {
        videoElement.style.display = 'none';
        fallback.classList.remove('hidden');
        fallback.style.display = 'flex';
        
        if (window.GrantInsightPerfect.hero) {
            window.GrantInsightPerfect.hero.log('動画エラー - フォールバック表示', 'error');
        }
    }
}

// 外部からの統計更新（管理画面から呼び出し可能）
function gipUpdateHeroStats(newStats) {
    if (!Array.isArray(newStats)) {
        console.error('Invalid stats data provided');
        return;
    }
    
    const counters = document.querySelectorAll('.counter');
    const progressBars = document.querySelectorAll('.progress-bar');
    
    newStats.forEach((stat, index) => {
        if (counters[index]) {
            counters[index].dataset.target = stat.number;
            counters[index].dataset.suffix = stat.suffix;
            counters[index].textContent = '0';
        }
        
        if (progressBars[index]) {
            progressBars[index].dataset.width = stat.progress;
            progressBars[index].style.width = '0%';
        }
    });
    
    // アニメーション再実行
    setTimeout(() => {
        if (window.GrantInsightPerfect.hero) {
            window.GrantInsightPerfect.hero.animateCounters();
        }
    }, 100);
    
    if (window.GrantInsightPerfect.hero) {
        window.GrantInsightPerfect.hero.log('統計データ更新完了', 'success');
    }
}
</script>

<style>
/* 実運用版スタイル */
.hero-section {
    font-family: 'Noto Sans JP', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    position: relative;
    min-height: 100vh;
    background-attachment: fixed;
}

/* カスタムアニメーション */
@keyframes float-1 { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-15px); } }
@keyframes float-2 { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-12px); } }
@keyframes float-3 { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-18px); } }
@keyframes float-4 { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }

@keyframes spin-slow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
@keyframes spin-reverse { from { transform: rotate(360deg); } to { transform: rotate(0deg); } }
@keyframes spin-slow-2 { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
@keyframes scroll-dot {
    0% { opacity: 1; transform: translateY(0); }
    50% { opacity: 0.3; transform: translateY(10px); }
    100% { opacity: 1; transform: translateY(0); }
}

/* アニメーションクラス */
.animate-float-1 { animation: float-1 4s ease-in-out infinite; }
.animate-float-2 { animation: float-2 5s ease-in-out infinite 1s; }
.animate-float-3 { animation: float-3 6s ease-in-out infinite 2s; }
.animate-float-4 { animation: float-4 4.5s ease-in-out infinite 0.5s; }

.animate-spin-slow { animation: spin-slow 20s linear infinite; }
.animate-spin-reverse { animation: spin-reverse 15s linear infinite; }
.animate-spin-slow-2 { animation: spin-slow-2 25s linear infinite; }
.animate-scroll-dot { animation: scroll-dot 2s ease-in-out infinite; }

/* 統計カードの強化 */
.stat-card {
    backdrop-filter: blur(10px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.stat-card:hover {
    background: rgba(255, 255, 255, 0.95);
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* プログレスバーの強化 */
.progress-bar {
    width: 0%;
    transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.progress-bar::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { left: -100%; }
    100% { left: 100%; }
}

/* CTAボタンの強化 */
.cta-primary, .cta-secondary {
    position: relative;
    overflow: hidden;
    transform: translateZ(0);
    will-change: transform;
}

.cta-primary:hover {
    box-shadow: 0 20px 40px rgba(16, 185, 129, 0.4);
}

.cta-secondary:hover {
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
}

.cta-primary:active, .cta-secondary:active {
    transform: scale(0.98);
}

/* ロゴの強化 */
.hero-cta img {
    filter: drop-shadow(0 10px 30px rgba(0, 0, 0, 0.1));
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.hero-cta img:hover {
    filter: drop-shadow(0 15px 40px rgba(0, 0, 0, 0.15));
    transform: scale(1.1) rotate(2deg);
}

/* 動画の強化 */
.hero-video {
    will-change: opacity;
    transform: translateZ(0);
}

.hero-visual:hover .animate-float-1,
.hero-visual:hover .animate-float-2,
.hero-visual:hover .animate-float-3,
.hero-visual:hover .animate-float-4 {
    animation-duration: 2s;
}

/* レスポンシブ最適化 */
@media (max-width: 1024px) {
    .hero-visual .absolute { transform: scale(0.85); }
    .w-80, .h-80 { width: 18rem; height: 18rem; }
    .lg\:w-96, .lg\:h-96 { width: 20rem; height: 20rem; }
}

@media (max-width: 768px) {
    .hero-section { padding: 2rem 0; }
    .hero-title { font-size: 2.5rem; line-height: 1.1; }
    .hero-subtitle { font-size: 1.125rem; }
    .hero-visual .absolute { transform: scale(0.7); }
    .w-80, .h-80 { width: 16rem; height: 16rem; }
    .hero-cta { flex-direction: column; gap: 1rem; }
    .hero-cta > div { flex-direction: column; align-items: center; gap: 1rem; }
    .hero-cta img { height: 3rem; }
    .stat-card { padding: 1rem; }
}

@media (max-width: 640px) {
    .hero-visual .absolute { transform: scale(0.6); }
    .w-80, .h-80 { width: 14rem; height: 14rem; }
    .hero-cta img { height: 2.5rem; }
    .hero-title { font-size: 2rem; }
    .hero-subtitle { font-size: 1rem; }
    .floating-element { display: none; }
}

/* アクセシビリティ対応 */
@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
    
    .animate-float-1, .animate-float-2, .animate-float-3, .animate-float-4,
    .animate-spin-slow, .animate-spin-reverse, .animate-spin-slow-2,
    .animate-bounce, .animate-pulse, .animate-ping {
        animation: none !important;
    }
}

/* ダークモード対応 */
@media (prefers-color-scheme: dark) {
    .hero-section {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
    }
    
    .stat-card {
        background: rgba(31, 41, 55, 0.9);
        border-color: rgba(75, 85, 99, 0.3);
        color: #f9fafb;
    }
    
    .hero-title span {
        background: linear-gradient(135deg, #f9fafb, #10b981, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
}

/* 高コントラスト対応 */
@media (prefers-contrast: high) {
    .hero-section {
        background: #ffffff;
        color: #000000;
    }
    
    .cta-primary, .cta-secondary {
        border: 2px solid;
    }
}

/* GPU加速最適化 */
.animate-float-1, .animate-float-2, .animate-float-3, .animate-float-4,
.animate-spin-slow, .animate-spin-reverse, .animate-spin-slow-2 {
    will-change: transform;
    transform: translateZ(0);
}

/* プリロード最適化 */
.hero-section.loaded .animate-on-scroll {
    animation-play-state: running;
}

/* フォーカス表示の強化 */
.cta-primary:focus, .cta-secondary:focus {
    outline: 3px solid #10b981;
    outline-offset: 2px;
}

/* 印刷対応 */
@media print {
    .hero-section {
        background: none !important;
        color: #000 !important;
    }
    
    .animate-on-scroll {
        opacity: 1 !important;
        transform: none !important;
    }
    
    .floating-element, .floating-decorations {
        display: none !important;
    }
}
</style>