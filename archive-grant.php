<?php
/**
 * Template for displaying grant archive with enhanced card design
 * Grant Insight Perfect - Enhanced Archive Page with New Card Design
 * 
 * Features:
 * - New enhanced card design integration
 * - Complete prefecture filter with toggle button
 * - 47 prefectures + nationwide support
 * - Popular prefectures priority display
 * - Perfect AJAX integration
 * - Responsive design
 * - Complete error handling
 * - New card features: success rate, difficulty, target business, subsidy rate
 */

// セキュリティチェック
if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<!-- 新カードデザイン用のスタイル読み込み -->
<?php echo gi_generate_card_hover_styles(); ?>

<div class="min-h-screen bg-gradient-to-br from-emerald-50 to-teal-50">
    <!-- ヒーローセクション -->
    <section class="relative bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 text-white py-16 md:py-24">
        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
        <div class="relative container mx-auto px-4">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-500 rounded-full mb-6 animate-bounce-gentle">
                    <i class="fas fa-coins text-2xl text-white"></i>
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 animate-fade-in-up">
                    助成金・補助金一覧
                </h1>
                <p class="text-xl md:text-2xl text-emerald-100 mb-8 animate-fade-in-up animation-delay-200">
                    全国の助成金・補助金情報を都道府県別に検索
                </p>
                
                <!-- 統計情報（新機能追加） -->
                <div class="flex flex-wrap justify-center gap-6 md:gap-12 animate-fade-in-up animation-delay-400">
                    <?php
                    $total_grants = wp_count_posts('grant')->publish;
                    $active_grants = get_posts(array(
                        'post_type' => 'grant',
                        'meta_query' => array(
                            array(
                                'key' => 'application_status',
                                'value' => 'open',
                                'compare' => '='
                            )
                        ),
                        'fields' => 'ids'
                    ));
                    $prefecture_count = wp_count_terms(array('taxonomy' => 'grant_prefecture', 'hide_empty' => false));
                    
                    // 【新機能】平均採択率を計算
                    $success_rates = get_posts(array(
                        'post_type' => 'grant',
                        'posts_per_page' => -1,
                        'fields' => 'ids',
                        'meta_query' => array(
                            array(
                                'key' => 'grant_success_rate',
                                'value' => 0,
                                'compare' => '>'
                            )
                        )
                    ));
                    $avg_success_rate = 0;
                    if (!empty($success_rates)) {
                        $total_rate = 0;
                        foreach ($success_rates as $grant_id) {
                            $total_rate += intval(gi_safe_get_meta($grant_id, 'grant_success_rate', 0));
                        }
                        $avg_success_rate = round($total_rate / count($success_rates));
                    }
                    ?>
                    <div class="text-center">
                        <div class="text-3xl md:text-4xl font-bold text-yellow-300">
                            <?php echo gi_safe_number_format($total_grants); ?>
                        </div>
                        <div class="text-sm md:text-base text-emerald-100">件</div>
                        <div class="text-xs text-emerald-200">助成金総数</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl md:text-4xl font-bold text-green-300">
                            <?php echo gi_safe_number_format(count($active_grants)); ?>
                        </div>
                        <div class="text-sm md:text-base text-emerald-100">募集中</div>
                        <div class="text-xs text-emerald-200">現在応募可能</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl md:text-4xl font-bold text-orange-300">
                            <?php echo gi_safe_number_format($prefecture_count); ?>
                        </div>
                        <div class="text-sm md:text-base text-emerald-100">都道府県</div>
                        <div class="text-xs text-emerald-200">全国対応</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl md:text-4xl font-bold text-blue-300">
                            <?php echo $avg_success_rate; ?>%
                        </div>
                        <div class="text-sm md:text-base text-emerald-100">平均採択率</div>
                        <div class="text-xs text-emerald-200">成功の目安</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 背景アニメーション -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-white opacity-5 rounded-full animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-white opacity-3 rounded-full animate-pulse animation-delay-1000"></div>
        </div>
    </section>

    <!-- 検索・フィルターセクション -->
    <section class="py-8 bg-white shadow-sm border-b">
        <div class="container mx-auto px-4">
            <!-- 検索バー -->
            <div class="mb-6">
                <div class="relative max-w-2xl mx-auto">
                    <input type="text" 
                           id="grant-search" 
                           class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-full focus:border-emerald-500 focus:ring-4 focus:ring-emerald-200 transition-all duration-300 pr-14"
                           placeholder="キーワードを入力してください（例：IT導入補助金、設備投資支援など）">
                    <button type="button" 
                            id="search-btn"
                            class="absolute right-2 top-2 w-12 h-12 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full flex items-center justify-center transition-colors duration-200">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <!-- 表示切り替え・並び順 -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <!-- クイックフィルター -->
                    <div class="flex gap-2 flex-wrap">
                        <button class="quick-filter active px-4 py-2 rounded-full text-sm font-medium bg-emerald-600 text-white hover:bg-emerald-700 transition-colors" data-filter="all">すべて</button>
                        <button class="quick-filter px-4 py-2 rounded-full text-sm font-medium bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors" data-filter="active">募集中</button>
                        <button class="quick-filter px-4 py-2 rounded-full text-sm font-medium bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors" data-filter="upcoming">募集予定</button>
                        <button class="quick-filter px-4 py-2 rounded-full text-sm font-medium bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors" data-filter="national">全国対応</button>
                        <button class="quick-filter px-4 py-2 rounded-full text-sm font-medium bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors" data-filter="high-rate">高採択率</button>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <!-- 並び順 -->
                    <select id="sort-order" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="date_desc">新着順</option>
                        <option value="date_asc">古い順</option>
                        <option value="amount_desc">金額が高い順</option>
                        <option value="amount_asc">金額が安い順</option>
                        <option value="deadline_asc">締切が近い順</option>
                        <option value="success_rate_desc">採択率が高い順</option>
                        <option value="title_asc">タイトル順</option>
                    </select>

                    <!-- 表示切り替え -->
                    <div class="flex bg-gray-100 rounded-lg p-1">
                        <button id="grid-view" class="view-toggle active flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 bg-white text-emerald-600 shadow-sm">
                            <i class="fas fa-th-large"></i>
                            <span class="hidden sm:inline">グリッド</span>
                        </button>
                        <button id="list-view" class="view-toggle flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 text-gray-600 hover:text-gray-900">
                            <i class="fas fa-list"></i>
                            <span class="hidden sm:inline">リスト</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- メインコンテンツ -->
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- サイドバー（フィルター） -->
            <aside class="lg:w-80 shrink-0">
                <div class="bg-white rounded-xl shadow-sm border p-6 sticky top-24">
                    <!-- フィルターヘッダー -->
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-filter text-emerald-600"></i>
                            絞り込み検索
                        </h3>
                        <button id="clear-filters" class="text-sm text-emerald-600 hover:text-emerald-800 font-medium">
                            クリア
                        </button>
                    </div>

                    <!-- 都道府県フィルター（完全修正版） -->
                    <div class="mb-8">
                        <h4 class="font-medium text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-red-600"></i>
                            対象地域
                        </h4>
                        <div id="prefecture-filter">
                            <!-- 人気都道府県（初期表示） -->
                            <div id="popular-prefectures">
                                <?php
                                $popular_prefectures = array('全国対応', '東京都', '大阪府', '愛知県', '神奈川県', '福岡県');
                                foreach ($popular_prefectures as $pref_name) {
                                    $term = get_term_by('name', $pref_name, 'grant_prefecture');
                                    if ($term && !is_wp_error($term)) :
                                ?>
                                <label class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors group">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" name="prefecture[]" value="<?php echo gi_safe_attr($term->slug); ?>" class="prefecture-checkbox w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                        <span class="text-sm text-gray-700 group-hover:text-gray-900"><?php echo gi_safe_escape($term->name); ?></span>
                                    </div>
                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full"><?php echo $term->count; ?></span>
                                </label>
                                <?php 
                                    endif;
                                }
                                ?>
                            </div>

                            <!-- 全都道府県（折りたたみ） -->
                            <div id="all-prefectures" class="hidden">
                                <?php
                                $all_prefectures = get_terms(array(
                                    'taxonomy' => 'grant_prefecture',
                                    'hide_empty' => false,
                                    'orderby' => 'name',
                                    'order' => 'ASC'
                                ));

                                // 人気都道府県以外を表示
                                if (!empty($all_prefectures) && !is_wp_error($all_prefectures)) {
                                    foreach ($all_prefectures as $prefecture) {
                                        if (!in_array($prefecture->name, $popular_prefectures)) :
                                ?>
                                <label class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors group">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" name="prefecture[]" value="<?php echo gi_safe_attr($prefecture->slug); ?>" class="prefecture-checkbox w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                        <span class="text-sm text-gray-700 group-hover:text-gray-900"><?php echo gi_safe_escape($prefecture->name); ?></span>
                                    </div>
                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full"><?php echo $prefecture->count; ?></span>
                                </label>
                                <?php 
                                        endif;
                                    }
                                }
                                ?>
                            </div>

                            <!-- 都道府県展開ボタン -->
                            <?php if (!empty($all_prefectures) && count($all_prefectures) > 6) : ?>
                            <button id="toggle-prefectures" class="w-full mt-3 py-2 px-4 text-sm text-emerald-600 hover:text-emerald-800 border border-emerald-200 hover:border-emerald-300 rounded-lg transition-colors flex items-center justify-center gap-2">
                                <span class="toggle-text">その他の都道府県を表示</span>
                                <i class="fas fa-chevron-down toggle-icon transition-transform duration-200"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- カテゴリフィルター -->
                    <div class="mb-8">
                        <h4 class="font-medium text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-tags text-green-600"></i>
                            カテゴリ
                        </h4>
                        <div id="category-filter">
                            <?php
                            // 代表カテゴリを取得
                            $categories = get_terms(array(
                                'taxonomy' => 'grant_category',
                                'hide_empty' => false,
                                'orderby' => 'count',
                                'order' => 'DESC',
                                'number' => 6
                            ));

                            $all_categories = get_terms(array(
                                'taxonomy' => 'grant_category',
                                'hide_empty' => false,
                                'orderby' => 'name',
                                'order' => 'ASC'
                            ));

                            if (!empty($categories) && !is_wp_error($categories)) :
                                // 代表カテゴリ表示（上位5個）
                                foreach (array_slice($categories, 0, 5) as $category) :
                            ?>
                            <label class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors group">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="category[]" value="<?php echo gi_safe_attr($category->slug); ?>" class="category-checkbox w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                    <span class="text-sm text-gray-700 group-hover:text-gray-900"><?php echo gi_safe_escape($category->name); ?></span>
                                </div>
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full"><?php echo $category->count; ?></span>
                            </label>
                            <?php endforeach; ?>

                            <!-- その他のカテゴリ（折りたたみ） -->
                            <?php if (!empty($all_categories) && !is_wp_error($all_categories) && count($all_categories) > 5) : ?>
                            <div id="more-categories" class="hidden">
                                <?php foreach (array_slice($all_categories, 5) as $category) : ?>
                                <label class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors group">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" name="category[]" value="<?php echo gi_safe_attr($category->slug); ?>" class="category-checkbox w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                        <span class="text-sm text-gray-700 group-hover:text-gray-900"><?php echo gi_safe_escape($category->name); ?></span>
                                    </div>
                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full"><?php echo $category->count; ?></span>
                                </label>
                                <?php endforeach; ?>
                            </div>

                            <button id="toggle-categories" class="w-full mt-3 py-2 px-4 text-sm text-emerald-600 hover:text-emerald-800 border border-emerald-200 hover:border-emerald-300 rounded-lg transition-colors flex items-center justify-center gap-2">
                                <span class="toggle-text">その他のカテゴリを表示</span>
                                <i class="fas fa-chevron-down toggle-icon transition-transform duration-200"></i>
                            </button>
                            <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- 【新機能】難易度フィルター -->
                    <div class="mb-8">
                        <h4 class="font-medium text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-star text-orange-600"></i>
                            申請難易度
                        </h4>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                <input type="checkbox" name="difficulty[]" value="easy" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <div class="flex items-center gap-2">
                                    <div class="flex text-green-400">
                                        <i class="fas fa-star text-xs"></i>
                                    </div>
                                    <span class="text-sm text-gray-700">易しい</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                <input type="checkbox" name="difficulty[]" value="normal" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <div class="flex items-center gap-2">
                                    <div class="flex text-blue-400">
                                        <i class="fas fa-star text-xs"></i>
                                        <i class="fas fa-star text-xs"></i>
                                    </div>
                                    <span class="text-sm text-gray-700">普通</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                <input type="checkbox" name="difficulty[]" value="hard" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <div class="flex items-center gap-2">
                                    <div class="flex text-orange-400">
                                        <i class="fas fa-star text-xs"></i>
                                        <i class="fas fa-star text-xs"></i>
                                        <i class="fas fa-star text-xs"></i>
                                    </div>
                                    <span class="text-sm text-gray-700">難しい</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                <input type="checkbox" name="difficulty[]" value="expert" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <div class="flex items-center gap-2">
                                    <div class="flex text-red-400">
                                        <i class="fas fa-star text-xs"></i>
                                        <i class="fas fa-star text-xs"></i>
                                        <i class="fas fa-star text-xs"></i>
                                        <i class="fas fa-star text-xs"></i>
                                    </div>
                                    <span class="text-sm text-gray-700">専門的</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- 金額フィルター -->
                    <div class="mb-8">
                        <h4 class="font-medium text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-yen-sign text-yellow-600"></i>
                            助成金額
                        </h4>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                <input type="radio" name="amount" value="" checked class="w-4 h-4 text-emerald-600 border-gray-300 focus:ring-emerald-500">
                                <span class="text-sm text-gray-700">すべて</span>
                            </label>
                            <label class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                <input type="radio" name="amount" value="0-100" class="w-4 h-4 text-emerald-600 border-gray-300 focus:ring-emerald-500">
                                <span class="text-sm text-gray-700">100万円以下</span>
                            </label>
                            <label class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                <input type="radio" name="amount" value="100-500" class="w-4 h-4 text-emerald-600 border-gray-300 focus:ring-emerald-500">
                                <span class="text-sm text-gray-700">100万円〜500万円</span>
                            </label>
                            <label class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                <input type="radio" name="amount" value="500-1000" class="w-4 h-4 text-emerald-600 border-gray-300 focus:ring-emerald-500">
                                <span class="text-sm text-gray-700">500万円〜1000万円</span>
                            </label>
                            <label class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                <input type="radio" name="amount" value="1000+" class="w-4 h-4 text-emerald-600 border-gray-300 focus:ring-emerald-500">
                                <span class="text-sm text-gray-700">1000万円以上</span>
                            </label>
                        </div>
                    </div>

                    <!-- 【新機能】採択率フィルター -->
                    <div class="mb-8">
                        <h4 class="font-medium text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-chart-line text-green-600"></i>
                            採択率
                        </h4>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                <input type="checkbox" name="success_rate[]" value="high" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <span class="text-sm text-gray-700">高い（70%以上）</span>
                                <span class="ml-auto w-3 h-3 bg-green-500 rounded-full"></span>
                            </label>
                            <label class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                <input type="checkbox" name="success_rate[]" value="medium" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <span class="text-sm text-gray-700">普通（50-69%）</span>
                                <span class="ml-auto w-3 h-3 bg-yellow-500 rounded-full"></span>
                            </label>
                            <label class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                <input type="checkbox" name="success_rate[]" value="low" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <span class="text-sm text-gray-700">低い（50%未満）</span>
                                <span class="ml-auto w-3 h-3 bg-red-500 rounded-full"></span>
                            </label>
                        </div>
                    </div>

                    <!-- ステータスフィルター -->
                    <div class="mb-6">
                        <h4 class="font-medium text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-clock text-orange-600"></i>
                            募集状況
                        </h4>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                <input type="checkbox" name="status[]" value="active" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <span class="text-sm text-gray-700">募集中</span>
                                <span class="ml-auto w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
                            </label>
                            <label class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                <input type="checkbox" name="status[]" value="upcoming" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <span class="text-sm text-gray-700">募集予定</span>
                                <span class="ml-auto w-3 h-3 bg-yellow-500 rounded-full"></span>
                            </label>
                        </div>
                    </div>

                    <!-- フィルター統計表示 -->
                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-lg p-4 text-center border border-emerald-200">
                        <div class="text-2xl font-bold text-emerald-600" id="filter-stats-count">-</div>
                        <div class="text-sm text-emerald-700">該当する助成金</div>
                        <div class="text-xs text-emerald-600 mt-1" id="filter-stats-detail">条件を設定してください</div>
                    </div>
                </div>
            </aside>

            <!-- メインコンテンツエリア -->
            <main class="flex-1">
                <!-- 検索結果ヘッダー -->
                <div id="results-header" class="mb-6 p-4 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-lg border border-emerald-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <span id="results-count" class="text-lg font-semibold text-emerald-900">検索中...</span>
                            <span id="results-query" class="text-sm text-emerald-700 ml-2"></span>
                        </div>
                        <div id="loading-spinner" class="hidden">
                            <div class="flex items-center gap-2">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-emerald-600"></div>
                                <span class="text-sm text-emerald-600">検索中</span>
                            </div>
                        </div>
                    </div>
                    <!-- 選択中のフィルター表示 -->
                    <div id="active-filters" class="mt-3 flex flex-wrap gap-2"></div>
                </div>

                <!-- 助成金カード表示エリア -->
                <div id="grants-container">
                    <!-- グリッド表示 -->
                    <div id="grid-container" class="<?php echo gi_get_responsive_grid_classes(3, 8); ?>">
                        <?php
                        // 🎯 【修正】初期表示用：新カードデザインで最新助成金を表示
                        $initial_grants = get_posts(array(
                            'post_type' => 'grant',
                            'posts_per_page' => 6,
                            'post_status' => 'publish',
                            'orderby' => 'date',
                            'order' => 'DESC'
                        ));
                        
                        if (!empty($initial_grants)) {
                            foreach ($initial_grants as $post) {
                                setup_postdata($post);
                                
                                // 新しいカードテンプレートの確認と読み込み
                                $card_template_path = get_template_directory() . '/template-parts/grant-card-v4-enhanced.php';
                                
                                if (file_exists($card_template_path)) {
                                    include $card_template_path;
                                } else {
                                    // フォールバック：エラー表示
                                    ?>
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                                        <div class="text-red-600 mb-2">
                                            <i class="fas fa-exclamation-triangle text-2xl"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-red-800 mb-2">カードテンプレートが見つかりません</h3>
                                        <p class="text-red-700 text-sm mb-4"><?php echo esc_html(get_the_title()); ?></p>
                                        <code class="text-xs text-red-600 bg-red-100 px-2 py-1 rounded block">
                                            <?php echo esc_html($card_template_path); ?>
                                        </code>
                                        <div class="mt-4">
                                            <a href="<?php echo esc_url(get_permalink()); ?>" class="inline-block px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition-colors text-sm">
                                                詳細を見る
                                            </a>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            wp_reset_postdata();
                        } else {
                            // 助成金データが0件の場合
                            ?>
                            <div class="col-span-full text-center py-16">
                                <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-coins text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-600 mb-2">助成金データがありません</h3>
                                <p class="text-gray-500">管理画面から助成金を追加してください。</p>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                    <!-- リスト表示 -->
                    <div id="list-container" class="hidden space-y-6">
                        <!-- 新しいリストカードがここに動的に読み込まれます -->
                    </div>
                </div>

                <!-- ページネーション -->
                <div id="pagination-container" class="mt-12 flex justify-center">
                    <!-- ページネーションがここに表示されます -->
                </div>

                <!-- ローディング表示 -->
                <div id="main-loading" class="hidden text-center py-12">
                    <div class="inline-flex items-center px-8 py-4 bg-white rounded-2xl shadow-lg border border-gray-100">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-600 mr-4"></div>
                        <div>
                            <p class="text-lg font-medium text-gray-800 mb-1">助成金情報を読み込んでいます...</p>
                            <p class="text-sm text-gray-600">新しいデザインで表示されます</p>
                        </div>
                    </div>
                </div>

                <!-- 結果なし表示 -->
                <div id="no-results" class="hidden text-center py-16">
                    <div class="w-32 h-32 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-8">
                        <i class="fas fa-search text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-900 mb-4">該当する助成金が見つかりませんでした</h3>
                    <p class="text-gray-600 mb-8 max-w-lg mx-auto">検索条件を変更して再度お試しください。または、より広い条件で検索してみてください。</p>
                    <div class="flex flex-wrap justify-center gap-4">
                        <button id="reset-search" class="px-6 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="fas fa-refresh mr-2"></i>検索条件をリセット
                        </button>
                        <a href="<?php echo home_url('/'); ?>" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-home mr-2"></i>トップページに戻る
                        </a>
                    </div>
                </div>

                <!-- エラー表示 -->
                <div id="error-display" class="hidden text-center py-16">
                    <div class="w-32 h-32 bg-gradient-to-r from-red-100 to-red-200 rounded-full flex items-center justify-center mx-auto mb-8">
                        <i class="fas fa-exclamation-triangle text-4xl text-red-500"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-900 mb-4">エラーが発生しました</h3>
                    <p class="text-gray-600 mb-8 max-w-lg mx-auto" id="error-message">通信エラーが発生しました。しばらく時間をおいて再度お試しください。</p>
                    <button id="retry-loading" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="fas fa-redo mr-2"></i>再試行
                    </button>
                </div>
            </main>
        </div>
    </div>

    <!-- フローティングヘルプボタン -->
    <div class="fixed bottom-6 right-6 z-50">
        <button id="help-toggle" class="w-14 h-14 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110 flex items-center justify-center">
            <i class="fas fa-question text-lg"></i>
        </button>
        
        <!-- ヘルプパネル -->
        <div id="help-panel" class="hidden absolute bottom-16 right-0 w-80 bg-white rounded-xl shadow-2xl border p-6">
            <h4 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-emerald-600"></i>
                検索のヒント
            </h4>
            <div class="space-y-4 text-sm text-gray-600">
                <div class="flex items-start gap-3">
                    <i class="fas fa-lightbulb text-yellow-500 mt-1 shrink-0"></i>
                    <div>
                        <strong>キーワード検索：</strong><br>
                        「IT導入」「設備投資」「人材育成」など具体的なキーワードで検索できます
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <i class="fas fa-star text-orange-500 mt-1 shrink-0"></i>
                    <div>
                        <strong>新機能：</strong><br>
                        採択率や申請難易度で絞り込み可能。成功しやすい助成金を見つけられます
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <i class="fas fa-map-marker-alt text-red-500 mt-1 shrink-0"></i>
                    <div>
                        <strong>都道府県フィルター：</strong><br>
                        複数の都道府県を同時に選択可能です
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <i class="fas fa-filter text-emerald-500 mt-1 shrink-0"></i>
                    <div>
                        <strong>絞り込み：</strong><br>
                        金額、募集状況、カテゴリで詳細に絞り込めます
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 【新機能】フローティング統計ボタン -->
    <div class="fixed bottom-24 right-6 z-50">
        <button id="stats-toggle" class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110 flex items-center justify-center">
            <i class="fas fa-chart-bar text-sm"></i>
        </button>
        
        <!-- 統計パネル -->
        <div id="stats-panel" class="hidden absolute bottom-14 right-0 w-72 bg-white rounded-xl shadow-2xl border p-4">
            <?php echo gi_render_card_statistics(array(
                'total_grants' => $total_grants,
                'active_grants' => count($active_grants),
                'average_amount' => 5000000, // 仮の値
                'success_rate' => $avg_success_rate
            )); ?>
        </div>
    </div>
</div>

<!-- JavaScript（新カードデザイン対応版） -->
<script>
// Grant Archive JavaScript - Enhanced Version (新カードデザイン完全対応)
document.addEventListener('DOMContentLoaded', function() {
    const GrantArchive = {
        currentView: 'grid',
        currentPage: 1,
        isLoading: false,
        filters: {
            search: '',
            categories: [],
            categorySlugs: [],
            prefectures: [],
            prefectureSlugs: [],
            amount: '',
            status: [],
            difficulty: [],
            success_rate: [],
            sort: 'date_desc'
        },

        init() {
            this.bindEvents();
            // 初期読み込みはスキップ（PHPで既に表示済み）
            this.updateResultsHeader(<?php echo $total_grants; ?>, {});
            this.updateFilterStats(<?php echo $total_grants; ?>);
            this.initializeHelpers();
            this.initializeCardEvents();
        },

        bindEvents() {
            // 検索
            const searchInput = document.getElementById('grant-search');
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    this.filters.search = e.target.value;
                    this.debounce(() => this.loadGrants(), 500)();
                });
            }

            const searchBtn = document.getElementById('search-btn');
            if (searchBtn) {
                searchBtn.addEventListener('click', () => {
                    this.loadGrants();
                });
            }

            // 表示切り替え
            const gridView = document.getElementById('grid-view');
            if (gridView) {
                gridView.addEventListener('click', () => {
                    this.switchView('grid');
                });
            }

            const listView = document.getElementById('list-view');
            if (listView) {
                listView.addEventListener('click', () => {
                    this.switchView('list');
                });
            }

            // 並び順
            const sortOrder = document.getElementById('sort-order');
            if (sortOrder) {
                sortOrder.addEventListener('change', (e) => {
                    this.filters.sort = e.target.value;
                    this.loadGrants();
                });
            }

            // クイックフィルター（拡張版）
            document.querySelectorAll('.quick-filter').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    document.querySelectorAll('.quick-filter').forEach(b => {
                        b.classList.remove('active', 'bg-emerald-600', 'text-white');
                        b.classList.add('bg-gray-200', 'text-gray-700');
                    });
                    
                    e.target.classList.add('active', 'bg-emerald-600', 'text-white');
                    e.target.classList.remove('bg-gray-200', 'text-gray-700');

                    const filter = e.target.dataset.filter;
                    this.applyQuickFilter(filter);
                });
            });

            // 都道府県・カテゴリ展開
            const togglePrefectures = document.getElementById('toggle-prefectures');
            if (togglePrefectures) {
                togglePrefectures.addEventListener('click', () => {
                    this.togglePrefectures();
                });
            }

            const toggleCategories = document.getElementById('toggle-categories');
            if (toggleCategories) {
                toggleCategories.addEventListener('click', () => {
                    this.toggleCategories();
                });
            }

            // フィルターイベント
            document.addEventListener('change', (e) => {
                if (e.target.classList.contains('prefecture-checkbox')) {
                    this.updatePrefectureFilters();
                } else if (e.target.classList.contains('category-checkbox')) {
                    this.updateCategoryFilters();
                } else if (e.target.name === 'amount') {
                    this.filters.amount = e.target.value;
                    this.updateFilterDisplay();
                    this.loadGrants();
                } else if (e.target.name === 'status[]') {
                    this.updateStatusFilters();
                } else if (e.target.name === 'difficulty[]') {
                    this.updateDifficultyFilters();
                } else if (e.target.name === 'success_rate[]') {
                    this.updateSuccessRateFilters();
                }
            });

            // フィルタークリア
            const clearFilters = document.getElementById('clear-filters');
            if (clearFilters) {
                clearFilters.addEventListener('click', () => {
                    this.clearFilters();
                });
            }

            // 検索リセット
            const resetSearch = document.getElementById('reset-search');
            if (resetSearch) {
                resetSearch.addEventListener('click', () => {
                    this.resetSearch();
                });
            }

            // 再試行
            const retryLoading = document.getElementById('retry-loading');
            if (retryLoading) {
                retryLoading.addEventListener('click', () => {
                    this.hideError();
                    this.loadGrants();
                });
            }

            // ヘルプトグル
            const helpToggle = document.getElementById('help-toggle');
            const helpPanel = document.getElementById('help-panel');
            if (helpToggle && helpPanel) {
                helpToggle.addEventListener('click', () => {
                    helpPanel.classList.toggle('hidden');
                    // 統計パネルは閉じる
                    const statsPanel = document.getElementById('stats-panel');
                    if (statsPanel) statsPanel.classList.add('hidden');
                });
            }

            // 【新機能】統計トグル
            const statsToggle = document.getElementById('stats-toggle');
            const statsPanel = document.getElementById('stats-panel');
            if (statsToggle && statsPanel) {
                statsToggle.addEventListener('click', () => {
                    statsPanel.classList.toggle('hidden');
                    // ヘルプパネルは閉じる
                    if (helpPanel) helpPanel.classList.add('hidden');
                });
            }

            // パネル外クリックで閉じる
            document.addEventListener('click', (e) => {
                const isHelpClick = helpToggle && helpToggle.contains(e.target);
                const isHelpPanelClick = helpPanel && helpPanel.contains(e.target);
                const isStatsClick = statsToggle && statsToggle.contains(e.target);
                const isStatsPanelClick = statsPanel && statsPanel.contains(e.target);

                if (!isHelpClick && !isHelpPanelClick && helpPanel) {
                    helpPanel.classList.add('hidden');
                }
                if (!isStatsClick && !isStatsPanelClick && statsPanel) {
                    statsPanel.classList.add('hidden');
                }
            });
        },

        // 【新機能】クイックフィルター適用
        applyQuickFilter(filter) {
            // フィルターリセット
            this.resetFiltersToDefault();

            switch (filter) {
                case 'all':
                    // すべてリセット済み
                    break;
                case 'national':
                    // 全国対応のslugをDOMから取得
                    let nationalSlug = '';
                    document.querySelectorAll('.prefecture-checkbox').forEach(cb => {
                        const label = cb.closest('label');
                        if (label && label.textContent.includes('全国対応')) {
                            nationalSlug = cb.value;
                            cb.checked = true;
                        } else {
                            cb.checked = false;
                        }
                    });
                    this.filters.prefectures = ['全国対応'];
                    this.filters.prefectureSlugs = nationalSlug ? [nationalSlug] : [];
                    break;
                case 'high-rate':
                    this.filters.success_rate = ['high'];
                    // UIのチェックボックス状態を更新
                    document.querySelectorAll('input[name="success_rate[]"]').forEach(cb => {
                        cb.checked = cb.value === 'high';
                    });
                    break;
                default:
                    this.filters.status = [filter];
                    // ステータスチェックボックスの状態を更新
                    document.querySelectorAll('input[name="status[]"]').forEach(cb => {
                        cb.checked = cb.value === filter;
                    });
                    break;
            }
            this.updateFilterDisplay();
            this.loadGrants();
        },

        // フィルターをデフォルトにリセット
        resetFiltersToDefault() {
            this.filters = {
                search: this.filters.search, // 検索キーワードは保持
                categories: [],
                categorySlugs: [],
                prefectures: [],
                prefectureSlugs: [],
                amount: '',
                status: [],
                difficulty: [],
                success_rate: [],
                sort: this.filters.sort // ソート順は保持
            };

            // UI要素もリセット
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                if (!cb.classList.contains('prefecture-checkbox') || !cb.name.includes('search')) {
                    cb.checked = false;
                }
            });
            document.querySelectorAll('input[type="radio"]').forEach(rb => {
                rb.checked = rb.value === '';
            });
        },

        switchView(view) {
            this.currentView = view;
            
            // ボタンの状態更新
            document.querySelectorAll('.view-toggle').forEach(btn => {
                btn.classList.remove('active', 'bg-white', 'text-emerald-600', 'shadow-sm');
                btn.classList.add('text-gray-600');
            });
            
            const activeBtn = document.getElementById(view + '-view');
            if (activeBtn) {
                activeBtn.classList.add('active', 'bg-white', 'text-emerald-600', 'shadow-sm');
                activeBtn.classList.remove('text-gray-600');
            }

            // コンテナの表示切り替え
            const gridContainer = document.getElementById('grid-container');
            const listContainer = document.getElementById('list-container');
            
            if (view === 'grid') {
                if (gridContainer) gridContainer.classList.remove('hidden');
                if (listContainer) listContainer.classList.add('hidden');
            } else {
                if (gridContainer) gridContainer.classList.add('hidden');
                if (listContainer) listContainer.classList.remove('hidden');
            }

            this.loadGrants();
        },

        // 都道府県展開トグル
        togglePrefectures() {
            const allPrefectures = document.getElementById('all-prefectures');
            const toggleBtn = document.getElementById('toggle-prefectures');
            const toggleText = toggleBtn.querySelector('.toggle-text');
            const toggleIcon = toggleBtn.querySelector('.toggle-icon');

            if (allPrefectures && allPrefectures.classList.contains('hidden')) {
                allPrefectures.classList.remove('hidden');
                if (toggleText) toggleText.textContent = '都道府県を閉じる';
                if (toggleIcon) toggleIcon.style.transform = 'rotate(180deg)';
            } else if (allPrefectures) {
                allPrefectures.classList.add('hidden');
                if (toggleText) toggleText.textContent = 'その他の都道府県を表示';
                if (toggleIcon) toggleIcon.style.transform = 'rotate(0deg)';
            }
        },

        toggleCategories() {
            const moreCategories = document.getElementById('more-categories');
            const toggleBtn = document.getElementById('toggle-categories');
            const toggleText = toggleBtn.querySelector('.toggle-text');
            const toggleIcon = toggleBtn.querySelector('.toggle-icon');

            if (moreCategories && moreCategories.classList.contains('hidden')) {
                moreCategories.classList.remove('hidden');
                if (toggleText) toggleText.textContent = 'カテゴリを閉じる';
                if (toggleIcon) toggleIcon.style.transform = 'rotate(180deg)';
            } else if (moreCategories) {
                moreCategories.classList.add('hidden');
                if (toggleText) toggleText.textContent = 'その他のカテゴリを表示';
                if (toggleIcon) toggleIcon.style.transform = 'rotate(0deg)';
            }
        },

        updatePrefectureFilters() {
            const checkboxes = document.querySelectorAll('.prefecture-checkbox:checked');
            const names = [];
            const slugs = [];
            Array.from(checkboxes).forEach(cb => {
                const label = cb.closest('label');
                const nameSpan = label ? label.querySelector('span') : null;
                names.push(nameSpan ? nameSpan.textContent.trim() : cb.value);
                slugs.push(cb.value);
            });
            this.filters.prefectures = names;
            this.filters.prefectureSlugs = slugs;
            this.updateFilterDisplay();
            this.loadGrants();
        },

        updateCategoryFilters() {
            const checkboxes = document.querySelectorAll('.category-checkbox:checked');
            const names = [];
            const slugs = [];
            Array.from(checkboxes).forEach(cb => {
                const label = cb.closest('label');
                const nameSpan = label ? label.querySelector('span') : null;
                names.push(nameSpan ? nameSpan.textContent.trim() : cb.value);
                slugs.push(cb.value);
            });
            this.filters.categories = names;
            this.filters.categorySlugs = slugs;
            this.updateFilterDisplay();
            this.loadGrants();
        },

        updateStatusFilters() {
            const checkboxes = document.querySelectorAll('input[name="status[]"]:checked');
            this.filters.status = Array.from(checkboxes).map(cb => cb.value);
            this.updateFilterDisplay();
            this.loadGrants();
        },

        // 【新機能】難易度フィルター更新
        updateDifficultyFilters() {
            const checkboxes = document.querySelectorAll('input[name="difficulty[]"]:checked');
            this.filters.difficulty = Array.from(checkboxes).map(cb => cb.value);
            this.updateFilterDisplay();
            this.loadGrants();
        },

        // 【新機能】採択率フィルター更新
        updateSuccessRateFilters() {
            const checkboxes = document.querySelectorAll('input[name="success_rate[]"]:checked');
            this.filters.success_rate = Array.from(checkboxes).map(cb => cb.value);
            this.updateFilterDisplay();
            this.loadGrants();
        },

        updateFilterDisplay() {
            const container = document.getElementById('active-filters');
            if (!container) return;
            
            container.innerHTML = '';

            // 都道府県フィルターバッジ
            this.filters.prefectures.forEach(pref => {
                const badge = this.createFilterBadge(pref, 'prefecture', '📍');
                container.appendChild(badge);
            });

            // カテゴリフィルターバッジ
            this.filters.categories.forEach(cat => {
                const badge = this.createFilterBadge(cat, 'category', '🏷️');
                container.appendChild(badge);
            });

            // 金額フィルターバッジ
            if (this.filters.amount) {
                const amountLabels = {
                    '0-100': '100万円以下',
                    '100-500': '100万円〜500万円',
                    '500-1000': '500万円〜1000万円',
                    '1000+': '1000万円以上'
                };
                const badge = this.createFilterBadge(amountLabels[this.filters.amount], 'amount', '💰');
                container.appendChild(badge);
            }

            // 【新機能】難易度フィルターバッジ
            this.filters.difficulty.forEach(diff => {
                const diffLabels = {
                    'easy': '易しい',
                    'normal': '普通',
                    'hard': '難しい',
                    'expert': '専門的'
                };
                const badge = this.createFilterBadge(diffLabels[diff], 'difficulty', '⭐');
                container.appendChild(badge);
            });

            // 【新機能】採択率フィルターバッジ
            this.filters.success_rate.forEach(rate => {
                const rateLabels = {
                    'high': '高採択率（70%以上）',
                    'medium': '普通採択率（50-69%）',
                    'low': '低採択率（50%未満）'
                };
                const badge = this.createFilterBadge(rateLabels[rate], 'success_rate', '📊');
                container.appendChild(badge);
            });

            // ステータスフィルターバッジ
            this.filters.status.forEach(status => {
                const statusLabels = {
                    'active': '募集中',
                    'upcoming': '募集予定',
                    'closed': '募集終了'
                };
                const badge = this.createFilterBadge(statusLabels[status], 'status', '⏰');
                container.appendChild(badge);
            });
        },

        createFilterBadge(text, type, icon) {
            const badge = document.createElement('span');
            badge.className = 'inline-flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-800 text-sm rounded-full animate-fade-in';
            badge.innerHTML = `
                <span>${icon}</span>
                <span>${this.escapeHtml(text)}</span>
                <button class="ml-1 hover:bg-emerald-200 rounded-full w-4 h-4 flex items-center justify-center transition-colors" onclick="GrantArchive.removeFilter('${type}', '${this.escapeHtml(text)}')">
                    <i class="fas fa-times text-xs"></i>
                </button>
            `;
            return badge;
        },

        removeFilter(type, value) {
            // フィルター削除処理
            if (type === 'prefecture') {
                this.filters.prefectures = this.filters.prefectures.filter(p => p !== value);
                document.querySelectorAll('.prefecture-checkbox').forEach(cb => {
                    const label = cb.closest('label');
                    const nameSpan = label.querySelector('span');
                    const prefName = nameSpan ? nameSpan.textContent.trim() : cb.value;
                    if (prefName === value) cb.checked = false;
                });
            } else if (type === 'category') {
                this.filters.categories = this.filters.categories.filter(c => c !== value);
                document.querySelectorAll('.category-checkbox').forEach(cb => {
                    const label = cb.closest('label');
                    const nameSpan = label.querySelector('span');
                    const catName = nameSpan ? nameSpan.textContent.trim() : cb.value;
                    if (catName === value) cb.checked = false;
                });
            } else if (type === 'amount') {
                this.filters.amount = '';
                document.querySelectorAll('input[name="amount"]').forEach(rb => {
                    rb.checked = rb.value === '';
                });
            } else if (type === 'difficulty') {
                const diffValues = {
                    '易しい': 'easy',
                    '普通': 'normal', 
                    '難しい': 'hard',
                    '専門的': 'expert'
                };
                const diffValue = diffValues[value];
                this.filters.difficulty = this.filters.difficulty.filter(d => d !== diffValue);
                document.querySelectorAll('input[name="difficulty[]"]').forEach(cb => {
                    if (cb.value === diffValue) cb.checked = false;
                });
            } else if (type === 'success_rate') {
                const rateValues = {
                    '高採択率（70%以上）': 'high',
                    '普通採択率（50-69%）': 'medium',
                    '低採択率（50%未満）': 'low'
                };
                const rateValue = rateValues[value];
                this.filters.success_rate = this.filters.success_rate.filter(r => r !== rateValue);
                document.querySelectorAll('input[name="success_rate[]"]').forEach(cb => {
                    if (cb.value === rateValue) cb.checked = false;
                });
            } else if (type === 'status') {
                const statusValues = {
                    '募集中': 'active',
                    '募集予定': 'upcoming',
                    '募集終了': 'closed'
                };
                const statusValue = statusValues[value];
                this.filters.status = this.filters.status.filter(s => s !== statusValue);
                document.querySelectorAll('input[name="status[]"]').forEach(cb => {
                    if (cb.value === statusValue) cb.checked = false;
                });
            }

            this.updateFilterDisplay();
            this.loadGrants();
        },

        clearFilters() {
            // フォームリセット
            const searchInput = document.getElementById('grant-search');
            if (searchInput) searchInput.value = '';
            
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
            document.querySelectorAll('input[type="radio"]').forEach(rb => {
                rb.checked = rb.value === '';
            });

            // フィルター初期化
            this.filters = {
                search: '',
                categories: [],
                categorySlugs: [],
                prefectures: [],
                prefectureSlugs: [],
                amount: '',
                status: [],
                difficulty: [],
                success_rate: [],
                sort: 'date_desc'
            };

            // クイックフィルターリセット
            document.querySelectorAll('.quick-filter').forEach(btn => {
                btn.classList.remove('active', 'bg-emerald-600', 'text-white');
                btn.classList.add('bg-gray-200', 'text-gray-700');
            });
            
            const allFilter = document.querySelector('.quick-filter[data-filter="all"]');
            if (allFilter) {
                allFilter.classList.add('active', 'bg-emerald-600', 'text-white');
                allFilter.classList.remove('bg-gray-200', 'text-gray-700');
            }

            this.updateFilterDisplay();
            this.loadGrants();
        },

        resetSearch() {
            this.clearFilters();
            this.hideNoResults();
            this.hideError();
        },

        async loadGrants() {
            if (this.isLoading) return;
            
            this.isLoading = true;
            this.showLoading();
            this.hideNoResults();
            this.hideError();

            try {
                const ajaxUrl = (typeof gi_ajax !== 'undefined' && gi_ajax.ajax_url) ? gi_ajax.ajax_url : (typeof giAjax !== 'undefined' ? giAjax.ajaxurl : '<?php echo admin_url('admin-ajax.php'); ?>');

                const response = await fetch(ajaxUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'gi_load_grants',
                        nonce: giAjax.nonce,
                        search: this.filters.search,
                        amount: this.filters.amount,
                        sort: this.filters.sort,
                        view: this.currentView,
                        page: this.currentPage,
                        categories: JSON.stringify(this.filters.categorySlugs || []),
                        prefectures: JSON.stringify(this.filters.prefectureSlugs || []),
                        status: JSON.stringify(this.filters.status),
                        difficulty: JSON.stringify(this.filters.difficulty || []),
                        success_rate: JSON.stringify(this.filters.success_rate || [])
                    })
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                const data = await response.json();
                
                if (data.success) {
                    this.renderGrants(data.data);
                } else {
                    throw new Error(data.data?.message || '検索中にエラーが発生しました');
                }
            } catch (error) {
                console.error('Load grants error:', error);
                this.showError(error.message || '通信エラーが発生しました');
            } finally {
                this.isLoading = false;
                this.hideLoading();
            }
        },

        renderGrants(data) {
            const { grants, found_posts, query_info } = data;
            
            // 結果数表示更新
            this.updateResultsHeader(found_posts, query_info);
            this.updateFilterStats(found_posts);

            if (!grants || grants.length === 0) {
                this.showNoResults();
                return;
            }

            // コンテナ表示
            this.showGrantsContainer();

            // 新しいカードデザインでレンダリング
            if (this.currentView === 'grid') {
                this.renderGridView(grants);
            } else {
                this.renderListView(grants);
            }

            // カード動作の初期化
            this.initializeCardEvents();
        },

        renderGridView(grants) {
            const container = document.getElementById('grid-container');
            if (!container) return;
            
            // 新しいカードデザインのHTMLを直接挿入
            container.innerHTML = grants.map(grant => grant.html).join('');
            this.animateCards();
        },

        renderListView(grants) {
            const container = document.getElementById('list-container');
            if (!container) return;
            
            // 新しいカードデザインのHTMLを直接挿入（リスト版）
            container.innerHTML = grants.map(grant => grant.html).join('');
            this.animateCards();
        },

        // 【新機能】カードイベントの初期化
        initializeCardEvents() {
            // お気に入りボタン
            document.querySelectorAll('.favorite-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.toggleFavorite(btn);
                });
            });

            // 共有ボタン
            document.querySelectorAll('.share-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.shareGrant(btn);
                });
            });
        },

        updateResultsHeader(count, queryInfo) {
            const header = document.getElementById('results-count');
            const query = document.getElementById('results-query');
            
            if (header) {
                header.textContent = `${count || 0}件の助成金が見つかりました`;
            }
            
            if (query) {
                let queryText = [];
                if (this.filters.search) queryText.push(`「${this.filters.search}」`);
                if ((this.filters.prefectures || []).length > 0) queryText.push(`${this.filters.prefectures.join('、')}`);
                if ((this.filters.categories || []).length > 0) queryText.push(`${this.filters.categories.join('、')}`);
                
                query.textContent = queryText.length > 0 ? `${queryText.join(' / ')}の検索結果` : '';
            }
        },

        updateFilterStats(count) {
            const statsCount = document.getElementById('filter-stats-count');
            const statsDetail = document.getElementById('filter-stats-detail');
            
            if (statsCount) {
                statsCount.textContent = count || 0;
            }
            
            if (statsDetail) {
                const activeFilters = [];
                if (this.filters.prefectures.length > 0) activeFilters.push(`地域: ${this.filters.prefectures.length}`);
                if (this.filters.categories.length > 0) activeFilters.push(`カテゴリ: ${this.filters.categories.length}`);
                if (this.filters.difficulty.length > 0) activeFilters.push(`難易度: ${this.filters.difficulty.length}`);
                if (this.filters.success_rate.length > 0) activeFilters.push(`採択率: ${this.filters.success_rate.length}`);
                
                statsDetail.textContent = activeFilters.length > 0 ? activeFilters.join(', ') : '条件を設定してください';
            }
        },

        showLoading() {
            const spinner = document.getElementById('loading-spinner');
            const mainLoading = document.getElementById('main-loading');
            
            if (spinner) spinner.classList.remove('hidden');
            if (mainLoading) mainLoading.classList.remove('hidden');
        },

        hideLoading() {
            const spinner = document.getElementById('loading-spinner');
            const mainLoading = document.getElementById('main-loading');
            
            if (spinner) spinner.classList.add('hidden');
            if (mainLoading) mainLoading.classList.add('hidden');
        },

        showNoResults() {
            const grantsContainer = document.getElementById('grants-container');
            const noResults = document.getElementById('no-results');
            
            if (grantsContainer) grantsContainer.classList.add('hidden');
            if (noResults) noResults.classList.remove('hidden');
        },

        hideNoResults() {
            const grantsContainer = document.getElementById('grants-container');
            const noResults = document.getElementById('no-results');
            
            if (grantsContainer) grantsContainer.classList.remove('hidden');
            if (noResults) noResults.classList.add('hidden');
        },

        showGrantsContainer() {
            const grantsContainer = document.getElementById('grants-container');
            const noResults = document.getElementById('no-results');
            const errorDisplay = document.getElementById('error-display');
            
            if (grantsContainer) grantsContainer.classList.remove('hidden');
            if (noResults) noResults.classList.add('hidden');
            if (errorDisplay) errorDisplay.classList.add('hidden');
        },

        showError(message) {
            console.error('Grant Archive Error:', message);
            
            const grantsContainer = document.getElementById('grants-container');
            const noResults = document.getElementById('no-results');
            const errorDisplay = document.getElementById('error-display');
            const errorMsg = document.getElementById('error-message');
            
            if (grantsContainer) grantsContainer.classList.add('hidden');
            if (noResults) noResults.classList.add('hidden');
            if (errorDisplay) errorDisplay.classList.remove('hidden');
            if (errorMsg) errorMsg.textContent = message;
            
            this.updateResultsHeader(0, {});
            this.updateFilterStats(0);
        },

        hideError() {
            const errorDisplay = document.getElementById('error-display');
            if (errorDisplay) errorDisplay.classList.add('hidden');
        },

        animateCards() {
            // カードのアニメーション
            const cards = document.querySelectorAll('.grant-card-enhanced, .grant-list-item-enhanced');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        },

        async toggleFavorite(btn) {
            const postId = btn.dataset.postId;
            
            try {
                const ajaxUrl = (typeof gi_ajax !== 'undefined' && gi_ajax.ajax_url) ? gi_ajax.ajax_url : (typeof giAjax !== 'undefined' ? giAjax.ajaxurl : '<?php echo admin_url('admin-ajax.php'); ?>');

                const response = await fetch(ajaxUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'gi_toggle_favorite',
                        nonce: giAjax.nonce,
                        post_id: postId
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    const icon = btn.querySelector('i');
                    if (data.data.action === 'added') {
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                        btn.title = 'お気に入りから削除';
                    } else {
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                        btn.title = 'お気に入りに追加';
                    }
                    
                    // アニメーション効果
                    btn.style.transform = 'scale(1.2)';
                    setTimeout(() => {
                        btn.style.transform = 'scale(1)';
                    }, 200);

                    this.showToast(data.data.message, 'success');
                } else {
                    throw new Error(data.data?.message || 'お気に入りの更新に失敗しました');
                }
            } catch (error) {
                console.error('Favorite toggle error:', error);
                this.showToast('お気に入りの更新中にエラーが発生しました', 'error');
            }
        },

        shareGrant(btn) {
            const url = btn.dataset.url;
            const title = btn.dataset.title;
            
            if (navigator.share) {
                // Web Share API対応
                navigator.share({
                    title: title,
                    url: url
                }).catch(console.error);
            } else {
                // フォールバック: クリップボードにコピー
                navigator.clipboard.writeText(url).then(() => {
                    this.showToast('URLをクリップボードにコピーしました', 'success');
                }).catch(() => {
                    // さらなるフォールバック: 新しいウィンドウで開く
                    window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`, '_blank');
                });
            }
        },

        showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white font-medium transition-all duration-300 transform translate-x-full ${
                type === 'error' ? 'bg-red-600' : 
                type === 'success' ? 'bg-green-600' : 'bg-emerald-600'
            }`;
            toast.textContent = message;
            
            document.body.appendChild(toast);
            
            // アニメーション
            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
            }, 100);
            
            // 自動削除
            setTimeout(() => {
                toast.style.transform = 'translateX(full)';
                setTimeout(() => {
                    if (document.body.contains(toast)) {
                        document.body.removeChild(toast);
                    }
                }, 300);
            }, 3000);
        },

        initializeHelpers() {
            // Enterキーでの検索
            const searchInput = document.getElementById('grant-search');
            if (searchInput) {
                searchInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        this.loadGrants();
                    }
                });
            }

            // Escapeキーでパネルを閉じる
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    const helpPanel = document.getElementById('help-panel');
                    const statsPanel = document.getElementById('stats-panel');
                    if (helpPanel && !helpPanel.classList.contains('hidden')) {
                        helpPanel.classList.add('hidden');
                    }
                    if (statsPanel && !statsPanel.classList.contains('hidden')) {
                        statsPanel.classList.add('hidden');
                    }
                }
            });
        },

        escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        },

        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    };

    // グローバルに公開（フィルターバッジから呼び出すため）
    window.GrantArchive = GrantArchive;

    // 初期化
    GrantArchive.init();
});
</script>

<!-- CSSスタイル（新カードデザイン対応） -->
<style>
/* Grant Archive Enhanced Version Styles */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-out;
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

.animate-bounce-gentle {
    animation: bounceGentle 2s ease-in-out infinite;
}

.animation-delay-200 {
    animation-delay: 0.2s;
}

.animation-delay-400 {
    animation-delay: 0.4s;
}

.animation-delay-1000 {
    animation-delay: 1s;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes bounceGentle {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

/* 新カードデザイン用のスタイル強化 */
.grant-card-enhanced {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.grant-card-enhanced:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.grant-list-item-enhanced {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.grant-list-item-enhanced:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
}

/* フローティングボタンのスタイル */
.fixed.bottom-6.right-6,
.fixed.bottom-24.right-6 {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

/* レスポンシブ調整 */
@media (max-width: 640px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .text-4xl { font-size: 2rem; }
    .text-5xl { font-size: 2.5rem; }
    .text-6xl { font-size: 3rem; }
    
    .lg\:w-80 {
        width: 100%;
    }
    
    .sticky {
        position: relative;
    }
    
    .grant-card-enhanced {
        margin-bottom: 1.5rem;
    }
    
    .grant-list-item-enhanced {
        margin-bottom: 1.5rem;
    }
    
    .fixed.bottom-6.right-6 {
        bottom: 1rem;
        right: 1rem;
    }
    
    .fixed.bottom-24.right-6 {
        bottom: 4rem;
        right: 1rem;
    }
}


/* プリント対応 */
@media print {
    .fixed, .sticky {
        position: static;
    }
    
    .shadow-lg, .shadow-xl, .shadow-2xl {
        box-shadow: none;
        border: 1px solid #e5e7eb;
    }
    
    .hidden {
        display: none !important;
    }
    
    .animate-bounce-gentle,
    .animate-fade-in,
    .animate-fade-in-up {
        animation: none;
    }
}

/* アクセシビリティ対応 */
@media (prefers-reduced-motion: reduce) {
    .grant-card-enhanced,
    .grant-list-item-enhanced,
    .animate-bounce-gentle,
    .animate-fade-in,
    .animate-fade-in-up {
        animation: none;
        transition: none;
    }
}

/* フォーカス表示の改善 */
*:focus {
    outline: 2px solid #10b981;
    outline-offset: 2px;
}

.grant-card-enhanced:focus-within {
    ring: 2px;
    ring-color: #10b981;
}
</style>

<?php get_footer(); ?>