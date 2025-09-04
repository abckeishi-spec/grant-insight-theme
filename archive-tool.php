<?php
/**
 * Template Name: ビジネスツールアーカイブ（超強化版）- Tailwind CSS Play CDN対応版
 * Description: 統合検索機能とAjax対応の高機能ツール一覧ページ
 */

get_header();

// 統計情報の取得
$tool_count = wp_count_posts('tool')->publish ?: '500+';
$grant_count = wp_count_posts('grant')->publish ?: '3,000+';
$case_study_count = wp_count_posts('case_study')->publish ?: '200+';
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title('|', true, 'right'); ?></title>
    
    <!-- Tailwind CSS Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.8s ease-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'slide-down': 'slideDown 0.6s ease-out',
                        'bounce-gentle': 'bounceGentle 2s infinite',
                        'pulse-soft': 'pulseSoft 3s infinite',
                        'gradient-shift': 'gradientShift 8s ease-in-out infinite',
                        'float': 'float 3s ease-in-out infinite',
                        'scale-up': 'scaleUp 0.3s ease-out',
                        'shimmer': 'shimmer 2s infinite',
                        'typing': 'typing 1.5s infinite',
                        'counter': 'counter 2s ease-out',
                        'glow': 'glow 2s ease-in-out infinite alternate',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(40px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideDown: {
                            '0%': { opacity: '0', transform: 'translateY(-30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        bounceGentle: {
                            '0%, 20%, 50%, 80%, 100%': { transform: 'translateY(0)' },
                            '40%': { transform: 'translateY(-10px)' },
                            '60%': { transform: 'translateY(-5px)' }
                        },
                        pulseSoft: {
                            '0%, 100%': { opacity: '1', transform: 'scale(1)' },
                            '50%': { opacity: '0.7', transform: 'scale(1.05)' }
                        },
                        gradientShift: {
                            '0%, 100%': { backgroundPosition: '0% 50%' },
                            '50%': { backgroundPosition: '100% 50%' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' }
                        },
                        scaleUp: {
                            '0%': { transform: 'scale(0.95)', opacity: '0' },
                            '100%': { transform: 'scale(1)', opacity: '1' }
                        },
                        shimmer: {
                            '0%': { transform: 'translateX(-100%)' },
                            '100%': { transform: 'translateX(100%)' }
                        },
                        typing: {
                            '0%, 60%, 100%': { opacity: '1' },
                            '30%': { opacity: '0' }
                        },
                        counter: {
                            '0%': { transform: 'scale(0.5)', opacity: '0' },
                            '100%': { transform: 'scale(1)', opacity: '1' }
                        },
                        glow: {
                            '0%': { boxShadow: '0 0 20px rgba(99, 102, 241, 0.3)' },
                            '100%': { boxShadow: '0 0 30px rgba(99, 102, 241, 0.6)' }
                        }
                    },
                    backgroundImage: {
                        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                        'gradient-conic': 'conic-gradient(from 180deg at 50% 50%, var(--tw-gradient-stops))',
                        'mesh-gradient': 'linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%)',
                    },
                    backdropBlur: {
                        xs: '2px',
                    },
                    screens: {
                        'xs': '475px',
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class('antialiased'); ?>>

<div class="super-enhanced-tool-archive-page bg-gradient-to-br from-gray-50 to-indigo-50 min-h-screen overflow-x-hidden">
    
    <!-- Hero Section -->
    <section class="hero-section relative min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-blue-900 text-white pt-24 pb-32 overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/20 to-purple-600/20"></div>
        
        <!-- Background Patterns -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-20 h-20 bg-white rounded-full animate-pulse-soft"></div>
            <div class="absolute top-32 right-20 w-16 h-16 bg-cyan-300 rounded-full animate-bounce-gentle"></div>
            <div class="absolute bottom-20 left-1/4 w-12 h-12 bg-green-300 rounded-full animate-float"></div>
            <div class="absolute bottom-32 right-1/3 w-8 h-8 bg-pink-300 rounded-full animate-pulse"></div>
            <div class="absolute top-1/2 left-1/2 w-24 h-24 bg-purple-400/20 rounded-full animate-glow"></div>
        </div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <!-- Badge -->
            <div class="inline-flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 mb-6 animate-slide-down border border-white/20 hover:bg-white/15 transition-all duration-300">
                <i class="fas fa-tools text-cyan-300 text-2xl animate-pulse"></i>
                <span class="text-lg font-bold tracking-wider bg-gradient-to-r from-cyan-300 to-blue-300 bg-clip-text text-transparent">
                    BUSINESS TOOLS
                </span>
            </div>
            
            <!-- Main Title -->
            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black mb-6 leading-tight animate-fade-in">
                <span class="bg-gradient-to-r from-cyan-300 to-blue-300 bg-clip-text text-transparent block">
                    次世代
                </span>
                <span class="bg-gradient-to-r from-white via-blue-100 to-cyan-200 bg-clip-text text-transparent block">
                    ビジネスツール検索
                </span>
            </h1>
            
            <!-- Description -->
            <p class="text-lg sm:text-xl md:text-2xl text-indigo-200 max-w-4xl mx-auto mb-12 animate-slide-up opacity-90 leading-relaxed">
                AI搭載の高度な検索システムで、あなたのビジネスを革新するツールを発見。<br class="hidden md:block">
                料金、機能、評価など詳細な条件で絞り込み、最適なソリューションを見つけ出します。
            </p>
            
            <!-- Statistics -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 lg:gap-8 max-w-6xl mx-auto mb-16 animate-slide-up">
                <div class="text-center bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300 group">
                    <div class="text-3xl md:text-4xl font-black text-cyan-300 mb-2 counter animate-counter" data-target="<?php echo esc_attr(str_replace('+', '', $tool_count)); ?>">0</div>
                    <div class="text-indigo-200 font-medium text-sm md:text-base">ビジネスツール</div>
                    <div class="w-full h-1 bg-cyan-300/30 rounded-full mt-3">
                        <div class="h-full bg-cyan-300 rounded-full animate-glow" style="width: 85%"></div>
                    </div>
                </div>
                <div class="text-center bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300 group">
                    <div class="text-3xl md:text-4xl font-black text-green-300 mb-2 counter animate-counter" data-target="<?php echo esc_attr(str_replace(['+', ','], '', $grant_count)); ?>">0</div>
                    <div class="text-indigo-200 font-medium text-sm md:text-base">助成金・補助金</div>
                    <div class="w-full h-1 bg-green-300/30 rounded-full mt-3">
                        <div class="h-full bg-green-300 rounded-full animate-glow" style="width: 95%"></div>
                    </div>
                </div>
                <div class="text-center bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300 group">
                    <div class="text-3xl md:text-4xl font-black text-purple-300 mb-2 counter animate-counter" data-target="<?php echo esc_attr(str_replace('+', '', $case_study_count)); ?>">0</div>
                    <div class="text-indigo-200 font-medium text-sm md:text-base">成功事例</div>
                    <div class="w-full h-1 bg-purple-300/30 rounded-full mt-3">
                        <div class="h-full bg-purple-300 rounded-full animate-glow" style="width: 70%"></div>
                    </div>
                </div>
                <div class="text-center bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300 group">
                    <div class="text-3xl md:text-4xl font-black text-orange-300 mb-2">24/7</div>
                    <div class="text-indigo-200 font-medium text-sm md:text-base">サポート体制</div>
                    <div class="w-full h-1 bg-orange-300/30 rounded-full mt-3">
                        <div class="h-full bg-orange-300 rounded-full animate-glow" style="width: 100%"></div>
                    </div>
                </div>
            </div>
            
            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in">
                <button onclick="document.getElementById('search-section').scrollIntoView({behavior: 'smooth'})" 
                        class="bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 text-white font-bold py-4 px-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-105 group">
                    <i class="fas fa-search mr-2 group-hover:animate-pulse"></i>
                    ツール検索を開始
                    <i class="fas fa-arrow-down ml-2 animate-bounce"></i>
                </button>
                <button class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white font-bold py-4 px-8 rounded-2xl border border-white/30 hover:border-white/50 transition-all duration-300 transform hover:-translate-y-1 group">
                    <i class="fas fa-lightbulb mr-2 group-hover:animate-pulse"></i>
                    AI診断を受ける
                </button>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <div class="w-8 h-12 border-2 border-white/50 rounded-full flex justify-center">
                <div class="w-1 h-3 bg-white/70 rounded-full mt-2 animate-pulse"></div>
            </div>
        </div>
    </section>

    <!-- Integrated Search Section -->
    <section id="search-section" class="search-section py-12 -mt-16 relative z-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-2xl p-6 sm:p-8 lg:p-12 backdrop-blur-sm border border-white/20 animate-slide-up">
                <!-- Section Header -->
                <div class="text-center mb-8 lg:mb-12">
                    <div class="inline-flex items-center gap-3 bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 px-6 py-3 rounded-full text-sm font-medium mb-6">
                        <i class="fas fa-search-plus animate-pulse"></i>
                        Advanced Search System
                    </div>
                    <h2 class="text-3xl lg:text-4xl font-black text-gray-900 mb-6">
                        <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            ツール検索システム
                        </span>
                    </h2>
                    <p class="text-gray-600 max-w-3xl mx-auto text-lg leading-relaxed">
                        カテゴリ、料金、評価、機能など詳細な条件を組み合わせて、<br class="hidden md:block">
                        あなたのビジネスに最適なツールを見つけましょう
                    </p>
                </div>

                <!-- Integrated Search Form -->
                <form id="tool-search-form" class="space-y-8">
                    <!-- Basic Search -->
                    <div class="search-basic">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div class="lg:col-span-2 relative">
                                <label for="search-keyword" class="block text-sm font-bold text-gray-700 mb-3">
                                    <i class="fas fa-search text-indigo-500 mr-2"></i>キーワード検索
                                </label>
                                <div class="relative">
                                    <input type="text" 
                                           id="search-keyword" 
                                           name="keyword" 
                                           placeholder="例: 会計ソフト、CRM、プロジェクト管理、マーケティングツール..." 
                                           class="w-full p-4 pr-12 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all duration-300 text-lg bg-gradient-to-r from-white to-gray-50"
                                           autocomplete="off">
                                    <div class="absolute right-4 top-1/2 transform -translate-y-1/2">
                                        <i class="fas fa-search text-gray-400 animate-pulse"></i>
                                    </div>
                                </div>
                                <div id="search-suggestions" class="absolute z-50 w-full bg-white border-2 border-gray-200 rounded-xl shadow-2xl mt-2 hidden max-h-80 overflow-y-auto"></div>
                            </div>
                            <div>
                                <label for="search-post-types" class="block text-sm font-bold text-gray-700 mb-3">
                                    <i class="fas fa-filter text-indigo-500 mr-2"></i>検索対象
                                </label>
                                <select id="search-post-types" name="post_types" class="w-full p-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all duration-300 text-lg bg-gradient-to-r from-white to-gray-50">
                                    <option value="tool">🛠️ ビジネスツール</option>
                                    <option value="grant">💰 助成金・補助金</option>
                                    <option value="case_study">📊 成功事例</option>
                                    <option value="all">🌐 すべて</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Advanced Filters -->
                    <div class="search-filters">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                <i class="fas fa-sliders-h text-purple-500 mr-3"></i>
                                詳細フィルター
                            </h3>
                            <button type="button" id="toggle-filters" class="text-indigo-600 hover:text-indigo-800 font-semibold flex items-center group transition-colors">
                                <span class="mr-2">表示/非表示</span>
                                <i class="fas fa-chevron-down group-hover:animate-bounce"></i>
                            </button>
                        </div>
                        
                        <div id="filter-content" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- Category Filter -->
                            <div class="filter-group">
                                <label class="block text-sm font-bold text-gray-700 mb-3">
                                    <i class="fas fa-tags text-emerald-500 mr-2"></i>カテゴリ
                                </label>
                                <select id="search-category" name="category" class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300 bg-white">
                                    <option value="">全てのカテゴリ</option>
                                    <?php 
                                    $categories = get_terms(['taxonomy' => 'tool_category', 'hide_empty' => false]);
                                    if (!is_wp_error($categories)):
                                        foreach($categories as $cat): 
                                    ?>
                                        <option value="<?php echo esc_attr($cat->slug); ?>"><?php echo esc_html($cat->name); ?></option>
                                    <?php 
                                        endforeach;
                                    endif;
                                    ?>
                                </select>
                            </div>

                            <!-- Price Filter -->
                            <div class="filter-group">
                                <label class="block text-sm font-bold text-gray-700 mb-3">
                                    <i class="fas fa-yen-sign text-amber-500 mr-2"></i>料金プラン
                                </label>
                                <select id="search-price" name="price_range" class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-300 bg-white">
                                    <option value="">すべての料金</option>
                                    <option value="free">🆓 無料プランあり</option>
                                    <option value="0-5000">💰 〜 5,000円/月</option>
                                    <option value="5001-20000">💎 5,001円 〜 20,000円/月</option>
                                    <option value="20001">👑 20,001円/月 〜</option>
                                </select>
                            </div>

                            <!-- Rating Filter -->
                            <div class="filter-group">
                                <label class="block text-sm font-bold text-gray-700 mb-3">
                                    <i class="fas fa-star text-yellow-500 mr-2"></i>評価
                                </label>
                                <select id="search-rating" name="rating" class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-300 bg-white">
                                    <option value="">すべての評価</option>
                                    <option value="5">⭐⭐⭐⭐⭐ (5.0)</option>
                                    <option value="4">⭐⭐⭐⭐☆ (4.0以上)</option>
                                    <option value="3">⭐⭐⭐☆☆ (3.0以上)</option>
                                </select>
                            </div>

                            <!-- Features Filter -->
                            <div class="filter-group">
                                <label class="block text-sm font-bold text-gray-700 mb-3">
                                    <i class="fas fa-cogs text-blue-500 mr-2"></i>主要機能
                                </label>
                                <select id="search-features" name="features" class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 bg-white">
                                    <option value="">すべての機能</option>
                                    <option value="api">🔗 API連携</option>
                                    <option value="mobile">📱 モバイルアプリ</option>
                                    <option value="integration">🔄 他ツール連携</option>
                                    <option value="automation">🤖 自動化機能</option>
                                    <option value="analytics">📊 分析・レポート</option>
                                    <option value="collaboration">👥 チーム機能</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Sort & Display Settings -->
                    <div class="search-sort border-t-2 border-gray-100 pt-8">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-3">
                                    <i class="fas fa-sort text-indigo-500 mr-2"></i>並び順
                                </label>
                                <select id="search-sort" name="sort_by" class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 bg-white">
                                    <option value="date">📅 投稿日順</option>
                                    <option value="title">📝 タイトル順</option>
                                    <option value="rating">⭐ 評価順</option>
                                    <option value="price">💰 料金順</option>
                                    <option value="views">🔥 人気順</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-3">順序</label>
                                <select id="search-order" name="sort_order" class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 bg-white">
                                    <option value="DESC">⬇️ 降順</option>
                                    <option value="ASC">⬆️ 昇順</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-3">表示件数</label>
                                <select id="posts-per-page" name="posts_per_page" class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 bg-white">
                                    <option value="6">6件</option>
                                    <option value="12" selected>12件</option>
                                    <option value="24">24件</option>
                                    <option value="48">48件</option>
                                </select>
                            </div>
                            <div class="md:col-span-2 flex gap-3">
                                <button type="submit" class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-105 group">
                                    <i class="fas fa-search mr-2 group-hover:animate-pulse"></i>検索実行
                                </button>
                                <button type="button" id="reset-search" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-4 px-4 rounded-xl transition-all duration-300 transform hover:-translate-y-1 group">
                                    <i class="fas fa-redo group-hover:animate-spin"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Popular Keywords -->
                <div class="mt-8 pt-8 border-t-2 border-gray-100">
                    <h3 class="text-sm font-bold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-fire text-orange-500 mr-2 animate-pulse"></i>人気の検索キーワード
                    </h3>
                    <div class="flex flex-wrap gap-3">
                        <?php 
                        $popular_keywords = [
                            ['会計ソフト', 'fas fa-calculator', 'from-blue-400 to-blue-600'],
                            ['CRM', 'fas fa-users', 'from-green-400 to-green-600'], 
                            ['プロジェクト管理', 'fas fa-tasks', 'from-purple-400 to-purple-600'],
                            ['チャットツール', 'fas fa-comments', 'from-cyan-400 to-cyan-600'],
                            ['マーケティング', 'fas fa-bullhorn', 'from-pink-400 to-pink-600'],
                            ['デザインツール', 'fas fa-paint-brush', 'from-orange-400 to-orange-600'],
                            ['バックアップ', 'fas fa-shield-alt', 'from-indigo-400 to-indigo-600'],
                            ['セキュリティ', 'fas fa-lock', 'from-red-400 to-red-600']
                        ];
                        foreach($popular_keywords as $index => $keyword): 
                        ?>
                            <button class="popular-keyword-tag bg-gradient-to-r <?php echo $keyword[2]; ?> text-white px-4 py-2 rounded-full text-sm font-medium hover:scale-110 hover:shadow-lg transition-all duration-300 transform group" 
                                    data-keyword="<?php echo esc_attr($keyword[0]); ?>">
                                <i class="<?php echo $keyword[1]; ?> mr-2 group-hover:animate-bounce"></i>
                                <?php echo esc_html($keyword[0]); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Results Section -->
    <section class="results-section py-12 lg:py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Search Statistics -->
            <div id="search-stats" class="bg-white rounded-2xl shadow-xl p-6 mb-8 hidden animate-fade-in border border-gray-100">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-chart-bar text-white text-2xl animate-pulse"></i>
                        </div>
                        <div>
                            <div class="text-3xl font-black text-gray-900 mb-1" id="results-count">0</div>
                            <div class="text-gray-600 font-medium">件のツールが見つかりました</div>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-6 text-sm text-gray-600">
                        <div class="flex items-center gap-2 bg-indigo-50 px-4 py-2 rounded-lg">
                            <i class="fas fa-clock text-indigo-500"></i>
                            <span>検索時間: </span>
                            <span id="search-time" class="font-bold text-indigo-600">0.00秒</span>
                        </div>
                        <div class="flex items-center gap-2 bg-purple-50 px-4 py-2 rounded-lg">
                            <i class="fas fa-eye text-purple-500"></i>
                            <span id="current-page" class="font-bold text-purple-600">1</span> 
                            <span>/</span> 
                            <span id="total-pages" class="font-bold text-purple-600">1</span> 
                            <span>ページ</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Results Container -->
            <div id="search-results">
                <!-- Initial Display -->
                <div class="initial-display text-center py-20 animate-fade-in">
                    <div class="w-32 h-32 bg-gradient-to-r from-indigo-400 via-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-8 animate-glow">
                        <i class="fas fa-tools text-white text-4xl animate-pulse"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-6">ツール検索を開始してください</h3>
                    <p class="text-gray-600 max-w-2xl mx-auto text-lg leading-relaxed mb-8">
                        上記の検索フォームを使用して、あなたのビジネスに最適なツールを見つけましょう。<br>
                        詳細な条件設定で、より精密な検索が可能です。
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4 mx-auto">
                                <i class="fas fa-search text-blue-600 text-xl"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 mb-2">高度な検索</h4>
                            <p class="text-gray-600 text-sm">料金・機能・評価で絞り込み</p>
                        </div>
                        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4 mx-auto">
                                <i class="fas fa-robot text-green-600 text-xl"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 mb-2">AI推奨</h4>
                            <p class="text-gray-600 text-sm">AIがベストマッチを提案</p>
                        </div>
                        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4 mx-auto">
                                <i class="fas fa-heart text-purple-600 text-xl"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 mb-2">詳細比較</h4>
                            <p class="text-gray-600 text-sm">複数ツールを並べて比較</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div id="search-loading" class="text-center py-20 hidden">
                <div class="inline-flex items-center gap-6 mb-6">
                    <div class="animate-spin rounded-full h-16 w-16 border-4 border-indigo-200 border-t-indigo-600"></div>
                    <div class="text-2xl font-bold text-gray-700">検索中...</div>
                </div>
                <p class="text-gray-500 text-lg mb-8">最適なツールを検索しています。しばらくお待ちください。</p>
                <div class="flex justify-center space-x-2">
                    <div class="w-3 h-3 bg-indigo-500 rounded-full animate-bounce"></div>
                    <div class="w-3 h-3 bg-purple-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                    <div class="w-3 h-3 bg-pink-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tool Comparison Section -->
    <section class="comparison-section py-16 lg:py-20 bg-gradient-to-r from-blue-50 via-indigo-50 to-purple-50 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-10 left-10 w-20 h-20 bg-indigo-600 rounded-full"></div>
            <div class="absolute top-32 right-20 w-16 h-16 bg-purple-600 rounded-full"></div>
            <div class="absolute bottom-20 left-1/4 w-12 h-12 bg-blue-600 rounded-full"></div>
        </div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-5xl mx-auto text-center">
                <div class="w-24 h-24 bg-gradient-to-r from-blue-500 via-indigo-600 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-8 animate-glow">
                    <i class="fas fa-balance-scale text-white text-3xl animate-pulse"></i>
                </div>
                
                <h2 class="text-4xl lg:text-5xl font-black text-gray-900 mb-6">
                    <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        ツール比較機能
                    </span>
                </h2>
                
                <p class="text-xl lg:text-2xl text-gray-600 mb-12 max-w-3xl mx-auto leading-relaxed">
                    複数のツールを詳細に比較して、あなたのビジネスに最適な選択肢を見つけ出しましょう。<br class="hidden lg:block">
                    機能・料金・評価を一覧で確認できます。
                </p>
                
                <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto mb-12">
                    <div class="bg-white rounded-2xl p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 group">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                            <i class="fas fa-balance-scale text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">詳細比較</h3>
                        <p class="text-gray-600">機能・料金・評価を並べて比較し、最適な選択をサポートします。</p>
                    </div>
                    
                    <div class="bg-white rounded-2xl p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 group">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                            <i class="fas fa-lightbulb text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">AI診断</h3>
                        <p class="text-gray-600">ビジネス要件に基づいて、AIがおすすめツールを自動で診断します。</p>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-6 justify-center">
                    <a href="#" class="bg-gradient-to-r from-blue-500 via-indigo-600 to-purple-600 hover:from-blue-600 hover:via-indigo-700 hover:to-purple-700 text-white font-bold py-4 px-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-105 group">
                        <i class="fas fa-balance-scale mr-3 group-hover:animate-pulse"></i>
                        ツール比較を開始
                        <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="#" class="bg-white hover:bg-gray-50 text-gray-700 hover:text-gray-900 font-bold py-4 px-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 border-2 border-gray-200 hover:border-gray-300 group">
                        <i class="fas fa-lightbulb mr-3 group-hover:animate-pulse"></i>
                        おすすめツール診断
                        <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>

<!-- Custom JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize search system
    const searchForm = document.getElementById('tool-search-form');
    const searchKeyword = document.getElementById('search-keyword');
    const searchSuggestions = document.getElementById('search-suggestions');
    const searchResults = document.getElementById('search-results');
    const searchLoading = document.getElementById('search-loading');
    const searchStats = document.getElementById('search-stats');
    const toggleFilters = document.getElementById('toggle-filters');
    const filterContent = document.getElementById('filter-content');
    const resetButton = document.getElementById('reset-search');
    
    let searchTimeout;
    let isSearching = false;

    // Counter Animation
    function animateCounters() {
        const counters = document.querySelectorAll('.counter');
        counters.forEach((counter, index) => {
            const target = parseInt(counter.dataset.target) || 0;
            const increment = target / 100;
            let current = 0;
            
            setTimeout(() => {
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        counter.textContent = target.toLocaleString();
                        clearInterval(timer);
                    } else {
                        counter.textContent = Math.floor(current).toLocaleString();
                    }
                }, 20);
            }, index * 200);
        });
    }

    // Filter Toggle
    if (toggleFilters && filterContent) {
        toggleFilters.addEventListener('click', function() {
            filterContent.classList.toggle('hidden');
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-chevron-down');
            icon.classList.toggle('fa-chevron-up');
            
            // Add smooth animation
            if (filterContent.classList.contains('hidden')) {
                filterContent.style.maxHeight = '0';
                filterContent.style.opacity = '0';
            } else {
                filterContent.style.maxHeight = 'none';
                filterContent.style.opacity = '1';
            }
        });
    }

    // Search Suggestions
    if (searchKeyword && searchSuggestions) {
        searchKeyword.addEventListener('input', function() {
            const query = this.value.trim();
            
            clearTimeout(searchTimeout);
            
            if (query.length < 2) {
                searchSuggestions.classList.add('hidden');
                return;
            }
            
            searchTimeout = setTimeout(() => {
                fetchSearchSuggestions(query);
            }, 300);
        });

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchKeyword.contains(e.target) && !searchSuggestions.contains(e.target)) {
                searchSuggestions.classList.add('hidden');
            }
        });
    }

    // Form Submission
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!isSearching) {
                performSearch();
            }
        });
    }

    // Reset Button
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            searchForm.reset();
            resetSearchResults();
        });
    }

    // Popular Keywords
    document.querySelectorAll('.popular-keyword-tag').forEach(tag => {
        tag.addEventListener('click', function() {
            const keyword = this.dataset.keyword;
            searchKeyword.value = keyword;
            performSearch();
        });
    });

    // Search Suggestions Function
    function fetchSearchSuggestions(query) {
        // Mock data for demo - replace with actual AJAX call
        const mockSuggestions = [
            { value: 'CRM ツール', label: 'CRM ツール', type: 'ツール' },
            { value: '会計ソフト', label: '会計ソフト', type: 'ツール' },
            { value: 'プロジェクト管理', label: 'プロジェクト管理', type: 'ツール' }
        ];

        displaySearchSuggestions(mockSuggestions.filter(item => 
            item.value.toLowerCase().includes(query.toLowerCase())
        ));
    }

    // Display Search Suggestions
    function displaySearchSuggestions(suggestions) {
        if (suggestions.length === 0) {
            searchSuggestions.classList.add('hidden');
            return;
        }

        const html = suggestions.map(suggestion => `
            <div class="suggestion-item flex items-center justify-between p-4 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 cursor-pointer transition-all duration-200 border-b border-gray-100 last:border-b-0" data-value="${suggestion.value}">
                <span class="font-medium text-gray-800">${suggestion.label}</span>
                <span class="text-xs text-indigo-600 bg-indigo-100 px-3 py-1 rounded-full">${suggestion.type}</span>
            </div>
        `).join('');

        searchSuggestions.innerHTML = html;
        searchSuggestions.classList.remove('hidden');

        // Add click events
        searchSuggestions.querySelectorAll('.suggestion-item').forEach(item => {
            item.addEventListener('click', function() {
                searchKeyword.value = this.dataset.value;
                searchSuggestions.classList.add('hidden');
                performSearch();
            });
        });
    }

    // Perform Search
    function performSearch() {
        if (isSearching) return;
        
        isSearching = true;
        const startTime = performance.now();
        
        // Show loading
        searchLoading.classList.remove('hidden');
        searchResults.classList.add('hidden');
        searchStats.classList.add('hidden');

        // Mock search results - replace with actual AJAX call
        setTimeout(() => {
            const endTime = performance.now();
            const searchTime = ((endTime - startTime) / 1000).toFixed(2);
            
            const mockResults = {
                success: true,
                data: {
                    html: generateMockResults(),
                    stats: {
                        total_found: 42,
                        current_page: 1,
                        total_pages: 4
                    }
                }
            };
            
            displaySearchResults(mockResults.data, searchTime);
            isSearching = false;
            searchLoading.classList.add('hidden');
            searchResults.classList.remove('hidden');
        }, 1500);
    }

    // Generate Mock Results
    function generateMockResults() {
        return `
            <div class="search-results-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                ${Array.from({length: 6}).map((_, i) => `
                    <div class="tool-card bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-tools text-white text-xl"></i>
                                </div>
                                <div class="flex items-center gap-1 text-yellow-500">
                                    ${'⭐'.repeat(5)}
                                    <span class="text-sm text-gray-600 ml-1">(4.8)</span>
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">サンプルツール ${i + 1}</h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                このツールはビジネス効率を大幅に向上させる優れた機能を提供します。
                            </p>
                            <div class="flex items-center justify-between text-sm">
                                <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full font-medium">
                                    ¥${(Math.random() * 10000 + 1000).toFixed(0)}/月
                                </span>
                                <button class="text-indigo-600 hover:text-indigo-800 font-semibold">
                                    詳細を見る →
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }

    // Display Search Results
    function displaySearchResults(data, searchTime) {
        // Update statistics
        if (data.stats) {
            document.getElementById('results-count').textContent = data.stats.total_found.toLocaleString();
            document.getElementById('search-time').textContent = searchTime + '秒';
            document.getElementById('current-page').textContent = data.stats.current_page;
            document.getElementById('total-pages').textContent = data.stats.total_pages;
            searchStats.classList.remove('hidden');
        }

        // Display results
        searchResults.innerHTML = data.html;

        // Animate results
        const resultItems = searchResults.querySelectorAll('.tool-card');
        resultItems.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(30px)';
            setTimeout(() => {
                item.style.transition = 'all 0.6s ease';
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }

    // Reset Search Results
    function resetSearchResults() {
        searchResults.innerHTML = `
            <div class="initial-display text-center py-20 animate-fade-in">
                <div class="w-32 h-32 bg-gradient-to-r from-indigo-400 via-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-8 animate-glow">
                    <i class="fas fa-tools text-white text-4xl animate-pulse"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-6">ツール検索を開始してください</h3>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg leading-relaxed">
                    上記の検索フォームを使用して、あなたのビジネスに最適なツールを見つけましょう。
                </p>
            </div>
        `;
        searchStats.classList.add('hidden');
    }

    // Initialize animations
    setTimeout(animateCounters, 1000);
    
    // Smooth scroll for internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>

<style>
/* Custom Animations */
.animate-glow {
    animation: glow 2s ease-in-out infinite alternate;
}

@keyframes glow {
    from {
        box-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
    }
    to {
        box-shadow: 0 0 30px rgba(99, 102, 241, 0.6);
    }
}

/* Enhanced Hover Effects */
.tool-card:hover {
    transform: translateY(-8px) scale(1.02);
}

/* Search Suggestions Styling */
.suggestion-item:hover {
    background: linear-gradient(90deg, rgba(99, 102, 241, 0.1), rgba(168, 85, 247, 0.1));
}

/* Filter Content Animation */
#filter-content {
    transition: all 0.3s ease-in-out;
}

#filter-content.hidden {
    max-height: 0;
    opacity: 0;
    overflow: hidden;
}

/* Custom Scrollbar */
#search-suggestions::-webkit-scrollbar {
    width: 6px;
}

#search-suggestions::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

#search-suggestions::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

#search-suggestions::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Enhanced Focus States */
input:focus,
select:focus,
textarea:focus {
    outline: none;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
}

/* Loading Animation */
.animate-bounce:nth-child(2) {
    animation-delay: 0.1s;
}

.animate-bounce:nth-child(3) {
    animation-delay: 0.2s;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .search-results-grid {
        grid-template-columns: 1fr;
    }
    
    #filter-content {
        grid-template-columns: 1fr;
    }
    
    .text-4xl {
        font-size: 2.5rem;
    }
    
    .text-6xl {
        font-size: 3.5rem;
    }
}

/* Print Styles */
@media print {
    .hero-section,
    .search-section,
    button,
    .popular-keyword-tag {
        display: none;
    }
}
</style>

<?php wp_footer(); ?>
</body>
</html>

<?php get_footer(); ?>
