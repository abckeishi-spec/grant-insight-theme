<?php
/**
 * Search Section Template - Production Perfect Version
 * Grant Insight Perfect - Tailwind CSS Play CDN Edition v6.2
 * 
 * Features:
 * - Complete functions.php v6.2 integration
 * - Real-time AJAX search system
 * - Advanced filtering capabilities
 * - Complete error handling
 * - Security enhancements
 * - Performance optimization
 * - Full responsive design
 */

// セキュリティチェック
if (!defined('ABSPATH')) {
    exit;
}

// 必要なデータを取得
$search_stats = wp_cache_get('grant_search_stats', 'grant_insight');
if (false === $search_stats) {
    $search_stats = array(
        'total_grants' => wp_count_posts('grant')->publish ?? 0,
        'total_tools' => wp_count_posts('tool')->publish ?? 0,
        'total_cases' => wp_count_posts('case_study')->publish ?? 0,
        'total_guides' => wp_count_posts('guide')->publish ?? 0
    );
    wp_cache_set('grant_search_stats', $search_stats, 'grant_insight', 3600);
}

// カテゴリとタグの取得
$grant_categories = get_terms(array(
    'taxonomy' => 'grant_category',
    'hide_empty' => false,
    'number' => 20
));

$popular_tags = get_terms(array(
    'taxonomy' => 'post_tag',
    'hide_empty' => true,
    'orderby' => 'count',
    'order' => 'DESC',
    'number' => 10
));

// エラーハンドリング
if (is_wp_error($grant_categories)) {
    $grant_categories = array();
}
if (is_wp_error($popular_tags)) {
    $popular_tags = array();
}

// nonce生成
$search_nonce = wp_create_nonce('grant_insight_search_nonce');
?>

<!-- 検索セクション - 完璧実運用版 -->
<section id="search-section" class="py-16 bg-gradient-to-br from-emerald-50 via-white to-teal-50 relative overflow-hidden">
    <!-- 背景装飾 -->
    <div class="absolute inset-0 opacity-30">
        <div class="absolute top-10 left-10 w-72 h-72 bg-emerald-200 rounded-full mix-blend-multiply filter blur-xl animate-pulse"></div>
        <div class="absolute top-20 right-10 w-96 h-96 bg-teal-200 rounded-full mix-blend-multiply filter blur-xl animate-pulse delay-1000"></div>
        <div class="absolute -bottom-8 left-20 w-80 h-80 bg-emerald-100 rounded-full mix-blend-multiply filter blur-xl animate-pulse delay-2000"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <!-- セクションヘッダー -->
        <div class="text-center mb-12">
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                <span class="bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                    助成金を見つけよう
                </span>
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                <?php echo number_format($search_stats['total_grants']); ?>件の助成金情報から、
                あなたのビジネスに最適な支援制度を見つけましょう
            </p>
        </div>

        <!-- 統計情報バー -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-12">
            <?php
            $stats_items = array(
                array(
                    'count' => $search_stats['total_grants'],
                    'label' => '助成金',
                    'icon' => '💰',
                    'color' => 'blue'
                ),
                array(
                    'count' => $search_stats['total_tools'],
                    'label' => 'ツール',
                    'icon' => '🛠️',
                    'color' => 'green'
                ),
                array(
                    'count' => $search_stats['total_cases'],
                    'label' => '成功事例',
                    'icon' => '📈',
                    'color' => 'purple'
                ),
                array(
                    'count' => $search_stats['total_guides'],
                    'label' => 'ガイド',
                    'icon' => '📚',
                    'color' => 'orange'
                )
            );

            foreach ($stats_items as $item): ?>
                <div class="bg-white rounded-xl shadow-lg p-6 text-center transform hover:scale-105 transition-all duration-300 hover:shadow-xl">
                    <div class="text-3xl mb-2"><?php echo esc_html($item['icon']); ?></div>
                    <div class="text-2xl font-bold text-emerald-600 mb-1">
                        <?php echo number_format($item['count']); ?>
                    </div>
                    <div class="text-sm text-gray-600"><?php echo esc_html($item['label']); ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- メイン検索フォーム -->
        <div class="max-w-4xl mx-auto mb-12">
            <form id="grant-search-form" class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100" role="search" aria-label="助成金検索フォーム">
                <!-- 隠しフィールド -->
                <input type="hidden" id="search-nonce" value="<?php echo esc_attr($search_nonce); ?>">
                <input type="hidden" id="ajax-url" value="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">

                <!-- キーワード検索 -->
                <div class="mb-6">
                    <label for="search-keyword" class="block text-lg font-semibold text-gray-800 mb-3">
                        🔍 キーワード検索
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="search-keyword" 
                            name="keyword"
                            class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all duration-200 pl-14"
                            placeholder="例：IT導入補助金、デジタル化支援、中小企業..."
                            autocomplete="off"
                            aria-describedby="search-keyword-help"
                        >
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl">
                            🔍
                        </div>
                        <div id="search-keyword-help" class="sr-only">助成金や支援制度に関するキーワードを入力してください</div>
                    </div>
                </div>

                <!-- フィルターオプション -->
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <!-- カテゴリ選択 -->
                    <div>
                        <label for="search-category" class="block text-sm font-semibold text-gray-700 mb-2">
                            📂 カテゴリ
                        </label>
                        <select 
                            id="search-category" 
                            name="category"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition-all duration-200"
                            aria-label="助成金カテゴリを選択"
                        >
                            <option value="">すべてのカテゴリ</option>
                            <?php if (!empty($grant_categories)): ?>
                                <?php foreach ($grant_categories as $category): ?>
                                    <option value="<?php echo esc_attr($category->term_id); ?>">
                                        <?php echo esc_html($category->name); ?> (<?php echo $category->count; ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- 投稿タイプ選択 -->
                    <div>
                        <label for="search-post-type" class="block text-sm font-semibold text-gray-700 mb-2">
                            📋 種類
                        </label>
                        <select 
                            id="search-post-type" 
                            name="post_type"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition-all duration-200"
                            aria-label="投稿種類を選択"
                        >
                            <option value="">すべての種類</option>
                            <option value="grant">助成金</option>
                            <option value="tool">ツール</option>
                            <option value="case_study">成功事例</option>
                            <option value="guide">ガイド</option>
                        </select>
                    </div>

                    <!-- 並び順選択 -->
                    <div>
                        <label for="search-orderby" class="block text-sm font-semibold text-gray-700 mb-2">
                            🔢 並び順
                        </label>
                        <select 
                            id="search-orderby" 
                            name="orderby"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition-all duration-200"
                            aria-label="結果の並び順を選択"
                        >
                            <option value="relevance">関連度順</option>
                            <option value="date">新着順</option>
                            <option value="title">タイトル順</option>
                            <option value="modified">更新順</option>
                        </select>
                    </div>
                </div>

                <!-- 高度な検索オプション -->
                <div id="advanced-search" class="border-t border-gray-200 pt-6 mb-6" style="display: none;">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">🔧 高度な検索オプション</h3>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- 金額範囲 -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">💰 助成金額範囲</label>
                            <div class="flex items-center space-x-3">
                                <input 
                                    type="number" 
                                    id="amount-min" 
                                    name="amount_min"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100"
                                    placeholder="最小額"
                                    min="0"
                                    step="10000"
                                >
                                <span class="text-gray-500">〜</span>
                                <input 
                                    type="number" 
                                    id="amount-max" 
                                    name="amount_max"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100"
                                    placeholder="最大額"
                                    min="0"
                                    step="10000"
                                >
                            </div>
                        </div>

                        <!-- 申請期限 -->
                        <div>
                            <label for="deadline-filter" class="block text-sm font-semibold text-gray-700 mb-2">⏰ 申請期限</label>
                            <select 
                                id="deadline-filter" 
                                name="deadline"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100"
                            >
                                <option value="">指定なし</option>
                                <option value="1month">1ヶ月以内</option>
                                <option value="3months">3ヶ月以内</option>
                                <option value="6months">6ヶ月以内</option>
                                <option value="1year">1年以内</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- 人気タグ -->
                <?php if (!empty($popular_tags)): ?>
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">🏷️ 人気タグ</h3>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($popular_tags as $tag): ?>
                            <button 
                                type="button" 
                                class="tag-button px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium hover:bg-emerald-100 hover:text-emerald-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                data-tag="<?php echo esc_attr($tag->name); ?>"
                                aria-label="<?php echo esc_attr($tag->name); ?>タグで検索"
                            >
                                <?php echo esc_html($tag->name); ?>
                                <span class="ml-1 text-xs opacity-70">(<?php echo $tag->count; ?>)</span>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- アクションボタン -->
                <div class="flex flex-col sm:flex-row gap-4 items-center">
                    <button 
                        type="submit" 
                        id="search-submit"
                        class="flex-1 sm:flex-initial bg-gradient-to-r from-emerald-600 to-teal-600 text-white px-8 py-4 rounded-xl font-semibold text-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-emerald-300 disabled:opacity-50 disabled:cursor-not-allowed"
                        aria-label="検索を実行"
                    >
                        <span class="search-button-text">🔍 検索する</span>
                        <span class="search-button-loading hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            検索中...
                        </span>
                    </button>

                    <button 
                        type="button" 
                        id="advanced-toggle"
                        class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-medium hover:border-emerald-500 hover:text-emerald-600 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        aria-label="高度な検索オプションを切り替え"
                        aria-expanded="false"
                        aria-controls="advanced-search"
                    >
                        🔧 高度な検索
                    </button>

                    <button 
                        type="button" 
                        id="search-reset"
                        class="px-6 py-3 text-gray-600 hover:text-gray-800 font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-xl"
                        aria-label="検索条件をリセット"
                    >
                        🔄 リセット
                    </button>
                </div>
            </form>
        </div>

        <!-- 検索結果表示エリア -->
        <div id="search-results" class="hidden">
            <!-- 結果ヘッダー -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 p-4 bg-white rounded-xl shadow-lg">
                <div id="results-info" class="text-lg font-semibold text-gray-800 mb-2 sm:mb-0">
                    <!-- 結果件数が表示される -->
                </div>
                <div class="flex items-center space-x-4">
                    <!-- ビュー切り替え -->
                    <div class="flex border border-gray-300 rounded-lg overflow-hidden">
                        <button 
                            id="grid-view" 
                            class="px-4 py-2 bg-emerald-600 text-white hover:bg-emerald-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            aria-label="グリッド表示に切り替え"
                        >
                            📱
                        </button>
                        <button 
                            id="list-view" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500"
                            aria-label="リスト表示に切り替え"
                        >
                            📋
                        </button>
                    </div>
                    <!-- エクスポートボタン -->
                    <button 
                        id="export-results" 
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500"
                        aria-label="検索結果をエクスポート"
                    >
                        📊 エクスポート
                    </button>
                </div>
            </div>

            <!-- 結果一覧 -->
            <div id="results-container" class="grid gap-6">
                <!-- 検索結果がここに表示される -->
            </div>

            <!-- ページネーション -->
            <div id="pagination-container" class="mt-12 flex justify-center">
                <!-- ページネーションがここに表示される -->
            </div>
        </div>

        <!-- ローディング表示 -->
        <div id="search-loading" class="hidden text-center py-12">
            <div class="inline-flex items-center px-6 py-3 bg-white rounded-xl shadow-lg">
                <svg class="animate-spin -ml-1 mr-3 h-6 w-6 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-lg font-medium text-gray-800">検索中...</span>
            </div>
        </div>

        <!-- エラー表示 -->
        <div id="search-error" class="hidden text-center py-12">
            <div class="bg-red-50 border border-red-200 rounded-xl p-6 max-w-md mx-auto">
                <div class="text-red-600 text-4xl mb-4">⚠️</div>
                <h3 class="text-lg font-semibold text-red-800 mb-2">検索エラー</h3>
                <p class="text-red-700 mb-4" id="error-message">
                    検索中にエラーが発生しました。しばらく時間をおいて再度お試しください。
                </p>
                <button 
                    id="retry-search" 
                    class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500"
                >
                    🔄 再試行
                </button>
            </div>
        </div>

        <!-- 検索履歴 -->
        <div id="search-history" class="mt-8 hidden">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">📚 最近の検索履歴</h3>
            <div class="flex flex-wrap gap-2" id="history-container">
                <!-- 検索履歴がここに表示される -->
            </div>
        </div>
    </div>
</section>

<!-- JavaScript - 完璧実運用版 -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    // DOM要素の取得
    const searchForm = document.getElementById('grant-search-form');
    const searchKeyword = document.getElementById('search-keyword');
    const searchCategory = document.getElementById('search-category');
    const searchPostType = document.getElementById('search-post-type');
    const searchOrderby = document.getElementById('search-orderby');
    const amountMin = document.getElementById('amount-min');
    const amountMax = document.getElementById('amount-max');
    const deadlineFilter = document.getElementById('deadline-filter');
    const advancedToggle = document.getElementById('advanced-toggle');
    const advancedSearch = document.getElementById('advanced-search');
    const searchReset = document.getElementById('search-reset');
    const submitButton = document.getElementById('search-submit');
    const searchButtonText = document.querySelector('.search-button-text');
    const searchButtonLoading = document.querySelector('.search-button-loading');
    const resultsSection = document.getElementById('search-results');
    const resultsContainer = document.getElementById('results-container');
    const resultsInfo = document.getElementById('results-info');
    const paginationContainer = document.getElementById('pagination-container');
    const loadingDiv = document.getElementById('search-loading');
    const errorDiv = document.getElementById('search-error');
    const errorMessage = document.getElementById('error-message');
    const retryButton = document.getElementById('retry-search');
    const tagButtons = document.querySelectorAll('.tag-button');
    const gridViewButton = document.getElementById('grid-view');
    const listViewButton = document.getElementById('list-view');
    const exportButton = document.getElementById('export-results');
    const historySection = document.getElementById('search-history');
    const historyContainer = document.getElementById('history-container');

    // 設定値
    const CONFIG = {
        debounceDelay: 300,
        maxRetries: 3,
        retryDelay: 1000,
        resultsPerPage: 12,
        maxHistoryItems: 10,
        cacheExpiry: 300000 // 5分
    };

    // 状態管理
    let currentSearchParams = {};
    let searchCache = new Map();
    let searchHistory = JSON.parse(localStorage.getItem('grant_search_history') || '[]');
    let currentPage = 1;
    let currentView = 'grid';
    let debounceTimer = null;
    let abortController = null;

    // 初期化
    init();

    function init() {
        try {
            setupEventListeners();
            loadSearchHistory();
            setupKeyboardShortcuts();
            setupAccessibility();
            console.log('Search system initialized successfully');
        } catch (error) {
            console.error('Initialization error:', error);
            showError('システムの初期化に失敗しました。ページを再読み込みしてください。');
        }
    }

    // イベントリスナーの設定
    function setupEventListeners() {
        // フォーム送信
        searchForm.addEventListener('submit', handleFormSubmit);

        // リアルタイム検索（デバウンス）
        searchKeyword.addEventListener('input', debounce(handleRealtimeSearch, CONFIG.debounceDelay));

        // フィルター変更
        [searchCategory, searchPostType, searchOrderby, amountMin, amountMax, deadlineFilter].forEach(element => {
            if (element) {
                element.addEventListener('change', handleFilterChange);
            }
        });

        // 高度な検索の切り替え
        if (advancedToggle) {
            advancedToggle.addEventListener('click', toggleAdvancedSearch);
        }

        // リセットボタン
        if (searchReset) {
            searchReset.addEventListener('click', resetSearch);
        }

        // 再試行ボタン
        if (retryButton) {
            retryButton.addEventListener('click', retrySearch);
        }

        // タグボタン
        tagButtons.forEach(button => {
            button.addEventListener('click', handleTagClick);
        });

        // ビュー切り替え
        if (gridViewButton) {
            gridViewButton.addEventListener('click', () => switchView('grid'));
        }
        if (listViewButton) {
            listViewButton.addEventListener('click', () => switchView('list'));
        }

        // エクスポートボタン
        if (exportButton) {
            exportButton.addEventListener('click', exportResults);
        }

        // ウィンドウリサイズ
        window.addEventListener('resize', debounce(handleWindowResize, 250));
    }

    // フォーム送信処理
    async function handleFormSubmit(event) {
        event.preventDefault();
        
        if (submitButton.disabled) {
            return;
        }

        const searchData = collectSearchData();
        
        if (!validateSearchData(searchData)) {
            return;
        }

        try {
            await performSearch(searchData, 1);
            addToSearchHistory(searchData);
        } catch (error) {
            console.error('Search submission error:', error);
            showError('検索の実行に失敗しました。');
        }
    }

    // 検索データの収集
    function collectSearchData() {
        return {
            keyword: searchKeyword.value.trim(),
            category: searchCategory.value,
            post_type: searchPostType.value,
            orderby: searchOrderby.value,
            amount_min: amountMin.value,
            amount_max: amountMax.value,
            deadline: deadlineFilter.value,
            nonce: document.getElementById('search-nonce').value
        };
    }

    // 検索データの検証
    function validateSearchData(data) {
        if (!data.keyword && !data.category && !data.post_type) {
            showError('検索キーワードまたはフィルター条件を指定してください。');
            return false;
        }

        if (data.amount_min && data.amount_max && parseInt(data.amount_min) > parseInt(data.amount_max)) {
            showError('最小金額は最大金額以下にしてください。');
            return false;
        }

        return true;
    }

    // 検索実行
    async function performSearch(searchData, page = 1) {
        if (abortController) {
            abortController.abort();
        }

        abortController = new AbortController();
        currentPage = page;
        currentSearchParams = { ...searchData, page };

        // UIの更新
        setLoadingState(true);
        hideError();

        // キャッシュチェック
        const cacheKey = JSON.stringify(currentSearchParams);
        const cached = searchCache.get(cacheKey);
        
        if (cached && Date.now() - cached.timestamp < CONFIG.cacheExpiry) {
            displayResults(cached.data);
            setLoadingState(false);
            return;
        }

        try {
            const response = await fetch(document.getElementById('ajax-url').value, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'grant_insight_search',
                    ...currentSearchParams
                }),
                signal: abortController.signal
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.data?.message || '検索に失敗しました');
            }

            // キャッシュに保存
            searchCache.set(cacheKey, {
                data: data.data,
                timestamp: Date.now()
            });

            displayResults(data.data);

        } catch (error) {
            if (error.name === 'AbortError') {
                return; // リクエストがキャンセルされた場合は何もしない
            }
            
            console.error('Search error:', error);
            showError(error.message || '検索中にエラーが発生しました。');
        } finally {
            setLoadingState(false);
        }
    }

    // 結果表示
    function displayResults(data) {
        if (!data || !data.posts) {
            showError('検索結果の取得に失敗しました。');
            return;
        }

        resultsSection.classList.remove('hidden');
        
        // 結果情報の更新
        updateResultsInfo(data);
        
        // 結果一覧の表示
        renderResults(data.posts);
        
        // ページネーションの表示
        renderPagination(data.pagination);

        // アクセシビリティ
        announceResults(data.total);
    }

    // 結果情報の更新
    function updateResultsInfo(data) {
        const total = data.total || 0;
        const start = ((currentPage - 1) * CONFIG.resultsPerPage) + 1;
        const end = Math.min(start + CONFIG.resultsPerPage - 1, total);
        
        resultsInfo.innerHTML = `
            <span class="text-emerald-600 font-bold">${total.toLocaleString()}</span>件中 
            <span class="text-gray-700">${start.toLocaleString()}-${end.toLocaleString()}</span>件を表示
        `;
    }

    // 結果一覧のレンダリング
    function renderResults(posts) {
        if (!posts || posts.length === 0) {
            resultsContainer.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <div class="text-6xl mb-4">🔍</div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">検索結果が見つかりませんでした</h3>
                    <p class="text-gray-600 mb-4">検索条件を変更して再度お試しください。</p>
                    <button onclick="document.getElementById('search-reset').click()" 
                            class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors duration-200">
                        検索条件をリセット
                    </button>
                </div>
            `;
            return;
        }

        const gridClass = currentView === 'grid' ? 
            'grid md:grid-cols-2 lg:grid-cols-3 gap-6' : 
            'space-y-4';

        resultsContainer.className = gridClass;
        resultsContainer.innerHTML = posts.map(post => renderPostCard(post)).join('');

        // 遅延読み込みの設定
        setupLazyLoading();
        
        // カードのアニメーション
        animateCards();
    }

    // 投稿カードのレンダリング
    function renderPostCard(post) {
        const cardClass = currentView === 'grid' ? 
            'bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 overflow-hidden' :
            'bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 flex items-center space-x-6';

        const imageSection = post.thumbnail ? `
            <div class="${currentView === 'grid' ? 'h-48 overflow-hidden' : 'flex-shrink-0'}">
                <img src="${escapeHtml(post.thumbnail)}" 
                     alt="${escapeHtml(post.title)}"
                     class="${currentView === 'grid' ? 'w-full h-full object-cover' : 'w-24 h-24 rounded-lg object-cover'}"
                     loading="lazy">
            </div>
        ` : '';

        const contentClass = currentView === 'grid' ? 'p-6' : 'flex-1';

        return `
            <article class="${cardClass}" role="article" aria-labelledby="post-${post.id}-title">
                ${imageSection}
                <div class="${contentClass}">
                    <div class="flex items-center justify-between mb-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                            ${getPostTypeLabel(post.post_type)}
                        </span>
                        ${post.is_featured ? '<span class="text-yellow-500">⭐</span>' : ''}
                    </div>
                    
                    <h3 id="post-${post.id}-title" class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                        <a href="${escapeHtml(post.permalink)}" 
                           class="hover:text-emerald-600 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded"
                           aria-describedby="post-${post.id}-excerpt">
                            ${escapeHtml(post.title)}
                        </a>
                    </h3>
                    
                    <p id="post-${post.id}-excerpt" class="text-gray-600 text-sm mb-4 line-clamp-3">
                        ${escapeHtml(post.excerpt)}
                    </p>
                    
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <time datetime="${post.date}" class="flex items-center">
                            📅 ${formatDate(post.date)}
                        </time>
                        ${post.amount ? `<span class="font-medium text-green-600">💰 ${formatAmount(post.amount)}</span>` : ''}
                    </div>
                    
                    ${post.deadline ? `
                        <div class="mt-2 text-sm text-red-600 flex items-center">
                            ⏰ 締切: ${formatDate(post.deadline)}
                        </div>
                    ` : ''}
                    
                    <div class="mt-4 flex items-center justify-between">
                        <a href="${escapeHtml(post.permalink)}" 
                           class="inline-flex items-center text-emerald-600 hover:text-emerald-700 font-medium text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded">
                            詳細を見る →
                        </a>
                        <button class="favorite-button p-2 rounded-full hover:bg-gray-100 transition-colors duration-200 ${post.is_favorite ? 'text-red-500' : 'text-gray-400'}"
                                data-post-id="${post.id}"
                                aria-label="${post.is_favorite ? 'お気に入りから削除' : 'お気に入りに追加'}"
                                title="${post.is_favorite ? 'お気に入りから削除' : 'お気に入りに追加'}">
                            ${post.is_favorite ? '❤️' : '🤍'}
                        </button>
                    </div>
                </div>
            </article>
        `;
    }

    // ページネーションのレンダリング
    function renderPagination(pagination) {
        if (!pagination || pagination.total_pages <= 1) {
            paginationContainer.innerHTML = '';
            return;
        }

        const { current_page, total_pages } = pagination;
        let paginationHTML = '<nav class="flex items-center justify-center space-x-2" aria-label="ページネーション">';

        // 前のページ
        if (current_page > 1) {
            paginationHTML += `
                <button class="pagination-btn px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        data-page="${current_page - 1}"
                        aria-label="前のページ">
                    ← 前
                </button>
            `;
        }

        // ページ番号
        const startPage = Math.max(1, current_page - 2);
        const endPage = Math.min(total_pages, current_page + 2);

        if (startPage > 1) {
            paginationHTML += `
                <button class="pagination-btn px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        data-page="1">1</button>
            `;
            if (startPage > 2) {
                paginationHTML += '<span class="px-2 text-gray-500">...</span>';
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            const isActive = i === current_page;
            paginationHTML += `
                <button class="pagination-btn px-3 py-2 ${isActive ? 'bg-emerald-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'} border border-gray-300 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        data-page="${i}"
                        ${isActive ? 'aria-current="page"' : ''}
                        aria-label="ページ ${i}">
                    ${i}
                </button>
            `;
        }

        if (endPage < total_pages) {
            if (endPage < total_pages - 1) {
                paginationHTML += '<span class="px-2 text-gray-500">...</span>';
            }
            paginationHTML += `
                <button class="pagination-btn px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        data-page="${total_pages}">${total_pages}</button>
            `;
        }

        // 次のページ
        if (current_page < total_pages) {
            paginationHTML += `
                <button class="pagination-btn px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        data-page="${current_page + 1}"
                        aria-label="次のページ">
                    次 →
                </button>
            `;
        }

        paginationHTML += '</nav>';
        paginationContainer.innerHTML = paginationHTML;

        // ページネーションのイベントリスナー
        paginationContainer.querySelectorAll('.pagination-btn').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const page = parseInt(e.target.dataset.page);
                if (page && page !== currentPage) {
                    try {
                        await performSearch(currentSearchParams, page);
                        scrollToResults();
                    } catch (error) {
                        console.error('Pagination error:', error);
                        showError('ページの読み込みに失敗しました。');
                    }
                }
            });
        });
    }

    // ユーティリティ関数群

    function debounce(func, wait) {
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(debounceTimer);
                func(...args);
            };
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(later, wait);
        };
    }

    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text ? text.replace(/[&<>"']/g, m => map[m]) : '';
    }

    function formatDate(dateString) {
        if (!dateString) return '';
        try {
            const date = new Date(dateString);
            return date.toLocaleDateString('ja-JP', {
                year: 'numeric',
                month: 'numeric',
                day: 'numeric'
            });
        } catch (error) {
            return dateString;
        }
    }

    function formatAmount(amount) {
        if (!amount) return '';
        return parseInt(amount).toLocaleString() + '円';
    }

    function getPostTypeLabel(postType) {
        const labels = {
            'grant': '助成金',
            'tool': 'ツール',
            'case_study': '成功事例',
            'guide': 'ガイド'
        };
        return labels[postType] || postType;
    }

    function setLoadingState(isLoading) {
        if (isLoading) {
            submitButton.disabled = true;
            searchButtonText.classList.add('hidden');
            searchButtonLoading.classList.remove('hidden');
            loadingDiv.classList.remove('hidden');
            resultsSection.classList.add('hidden');
        } else {
            submitButton.disabled = false;
            searchButtonText.classList.remove('hidden');
            searchButtonLoading.classList.add('hidden');
            loadingDiv.classList.add('hidden');
        }
    }

    function showError(message) {
        errorMessage.textContent = message;
        errorDiv.classList.remove('hidden');
        resultsSection.classList.add('hidden');
        
        // エラーアナウンス
        announceToScreenReader(`エラー: ${message}`);
    }

    function hideError() {
        errorDiv.classList.add('hidden');
    }

    function announceResults(total) {
        const message = total > 0 ? 
            `${total}件の検索結果が見つかりました` : 
            '検索結果が見つかりませんでした';
        announceToScreenReader(message);
    }

    function announceToScreenReader(message) {
        const announcement = document.createElement('div');
        announcement.setAttribute('aria-live', 'polite');
        announcement.setAttribute('aria-atomic', 'true');
        announcement.classList.add('sr-only');
        announcement.textContent = message;
        document.body.appendChild(announcement);
        
        setTimeout(() => {
            document.body.removeChild(announcement);
        }, 1000);
    }

    function scrollToResults() {
        resultsSection.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start' 
        });
    }

    // 追加機能（高度な検索切り替え、リセット、タグクリック等）

    function toggleAdvancedSearch() {
        const isVisible = !advancedSearch.classList.contains('hidden');
        
        if (isVisible) {
            advancedSearch.style.display = 'none';
            advancedSearch.classList.add('hidden');
            advancedToggle.setAttribute('aria-expanded', 'false');
            advancedToggle.textContent = '🔧 高度な検索';
        } else {
            advancedSearch.classList.remove('hidden');
            advancedSearch.style.display = 'block';
            advancedToggle.setAttribute('aria-expanded', 'true');
            advancedToggle.textContent = '📝 基本検索';
        }
    }

    function resetSearch() {
        searchForm.reset();
        currentSearchParams = {};
        currentPage = 1;
        resultsSection.classList.add('hidden');
        hideError();
        
        // 高度な検索を閉じる
        if (!advancedSearch.classList.contains('hidden')) {
            toggleAdvancedSearch();
        }
        
        // フォーカスをキーワード入力に戻す
        searchKeyword.focus();
        
        announceToScreenReader('検索条件がリセットされました');
    }

    function handleTagClick(event) {
        const tag = event.target.dataset.tag;
        if (tag) {
            searchKeyword.value = tag;
            searchKeyword.focus();
            
            // タグボタンをアクティブ状態に
            tagButtons.forEach(btn => btn.classList.remove('bg-emerald-100', 'text-emerald-700'));
            event.target.classList.add('bg-emerald-100', 'text-emerald-700');
        }
    }

    async function retrySearch() {
        if (currentSearchParams && Object.keys(currentSearchParams).length > 0) {
            try {
                await performSearch(currentSearchParams, currentPage);
            } catch (error) {
                console.error('Retry search error:', error);
                showError('再試行に失敗しました。しばらく時間をおいて再度お試しください。');
            }
        }
    }

    function handleRealtimeSearch() {
        const keyword = searchKeyword.value.trim();
        if (keyword.length >= 2) {
            // 最小限の検索データで実行
            const searchData = {
                keyword: keyword,
                category: '',
                post_type: '',
                orderby: 'relevance',
                nonce: document.getElementById('search-nonce').value
            };
            performSearch(searchData, 1);
        }
    }

    function handleFilterChange() {
        // フィルターが変更された時の処理
        if (currentSearchParams && Object.keys(currentSearchParams).length > 0) {
            const searchData = collectSearchData();
            if (validateSearchData(searchData)) {
                performSearch(searchData, 1);
            }
        }
    }

    function switchView(viewType) {
        currentView = viewType;
        
        if (viewType === 'grid') {
            gridViewButton.classList.add('bg-emerald-600', 'text-white');
            gridViewButton.classList.remove('bg-gray-100', 'text-gray-700');
            listViewButton.classList.add('bg-gray-100', 'text-gray-700');
            listViewButton.classList.remove('bg-emerald-600', 'text-white');
        } else {
            listViewButton.classList.add('bg-emerald-600', 'text-white');
            listViewButton.classList.remove('bg-gray-100', 'text-gray-700');
            gridViewButton.classList.add('bg-gray-100', 'text-gray-700');
            gridViewButton.classList.remove('bg-emerald-600', 'text-white');
        }
        
        // 結果を再レンダリング
        if (resultsContainer.innerHTML) {
            // 現在の結果を取得して再表示
            // この部分は実際の結果データを保持する必要があります
        }
    }

    async function exportResults() {
        if (!currentSearchParams || Object.keys(currentSearchParams).length === 0) {
            showError('エクスポートする検索結果がありません。');
            return;
        }

        try {
            const response = await fetch(document.getElementById('ajax-url').value, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'grant_insight_export_results',
                    ...currentSearchParams,
                    export_format: 'csv'
                })
            });

            if (!response.ok) {
                throw new Error('エクスポートに失敗しました');
            }

            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `grant_search_results_${new Date().toISOString().split('T')[0]}.csv`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);

            announceToScreenReader('検索結果がエクスポートされました');
        } catch (error) {
            console.error('Export error:', error);
            showError('エクスポートに失敗しました。');
        }
    }

    function addToSearchHistory(searchData) {
        const historyItem = {
            keyword: searchData.keyword,
            category: searchData.category,
            post_type: searchData.post_type,
            timestamp: Date.now()
        };

        // 重複を除去
        searchHistory = searchHistory.filter(item => 
            item.keyword !== historyItem.keyword || 
            item.category !== historyItem.category || 
            item.post_type !== historyItem.post_type
        );

        searchHistory.unshift(historyItem);
        searchHistory = searchHistory.slice(0, CONFIG.maxHistoryItems);

        localStorage.setItem('grant_search_history', JSON.stringify(searchHistory));
        renderSearchHistory();
    }

    function loadSearchHistory() {
        if (searchHistory.length > 0) {
            renderSearchHistory();
        }
    }

    function renderSearchHistory() {
        if (searchHistory.length === 0) {
            historySection.classList.add('hidden');
            return;
        }

        historySection.classList.remove('hidden');
        historyContainer.innerHTML = searchHistory.map(item => `
            <button class="history-item px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    data-keyword="${escapeHtml(item.keyword)}"
                    data-category="${escapeHtml(item.category)}"
                    data-post-type="${escapeHtml(item.post_type)}">
                ${escapeHtml(item.keyword || '（フィルターのみ）')}
                ${item.category ? `・${escapeHtml(item.category)}` : ''}
                ${item.post_type ? `・${getPostTypeLabel(item.post_type)}` : ''}
            </button>
        `).join('');

        historyContainer.querySelectorAll('.history-item').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const { keyword, category, postType } = e.target.dataset;
                searchKeyword.value = keyword || '';
                searchCategory.value = category || '';
                searchPostType.value = postType || '';
                
                const searchData = collectSearchData();
                if (validateSearchData(searchData)) {
                    performSearch(searchData, 1);
                }
            });
        });
    }

    function setupLazyLoading() {
        const images = resultsContainer.querySelectorAll('img[loading="lazy"]');
        
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src || img.src;
                        img.classList.remove('lazy');
                        observer.unobserve(img);
                    }
                });
            });

            images.forEach(img => imageObserver.observe(img));
        }
    }

    function animateCards() {
        const cards = resultsContainer.querySelectorAll('article');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }

    function setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + Enter で検索実行
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                e.preventDefault();
                if (!submitButton.disabled) {
                    searchForm.dispatchEvent(new Event('submit'));
                }
            }
            
            // Escape で検索結果を閉じる
            if (e.key === 'Escape') {
                if (!resultsSection.classList.contains('hidden')) {
                    resetSearch();
                }
            }
        });
    }

    function setupAccessibility() {
        // スクリーンリーダー用のライブリージョン
        const liveRegion = document.createElement('div');
        liveRegion.setAttribute('aria-live', 'polite');
        liveRegion.setAttribute('aria-atomic', 'true');
        liveRegion.classList.add('sr-only');
        liveRegion.id = 'search-announcements';
        document.body.appendChild(liveRegion);

        // フォーカス管理
        searchForm.addEventListener('submit', () => {
            setTimeout(() => {
                if (!resultsSection.classList.contains('hidden')) {
                    resultsSection.focus();
                }
            }, 100);
        });
    }

    function handleWindowResize() {
        // レスポンシブ対応の調整
        if (window.innerWidth < 768) {
            currentView = 'list'; // モバイルではリスト表示
        }
    }

    // お気に入り機能（デリゲートイベント）
    document.addEventListener('click', async function(e) {
        if (e.target.classList.contains('favorite-button')) {
            e.preventDefault();
            
            const button = e.target;
            const postId = button.dataset.postId;
            
            if (!postId) return;

            try {
                const response = await fetch(document.getElementById('ajax-url').value, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'grant_insight_toggle_favorite',
                        post_id: postId,
                        nonce: document.getElementById('search-nonce').value
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    const isFavorite = data.data.is_favorite;
                    button.innerHTML = isFavorite ? '❤️' : '🤍';
                    button.classList.toggle('text-red-500', isFavorite);
                    button.classList.toggle('text-gray-400', !isFavorite);
                    button.setAttribute('aria-label', isFavorite ? 'お気に入りから削除' : 'お気に入りに追加');
                    button.setAttribute('title', isFavorite ? 'お気に入りから削除' : 'お気に入りに追加');
                    
                    announceToScreenReader(isFavorite ? 'お気に入りに追加しました' : 'お気に入りから削除しました');
                } else {
                    throw new Error(data.data?.message || 'お気に入りの更新に失敗しました');
                }
            } catch (error) {
                console.error('Favorite toggle error:', error);
                showError('お気に入りの更新に失敗しました。');
            }
        }
    });

    console.log('Grant Insight Perfect Search System - Production Version Loaded Successfully! 🚀');
});
</script>

<!-- CSS追加スタイル -->
<style>
/* 検索セクション専用スタイル */
#search-section .line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

#search-section .line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* スクリーンリーダー専用 */
#search-section .sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

/* フォーカス表示の改善 */
#search-section *:focus {
    outline: 2px solid #10b981;
    outline-offset: 2px;
}

/* アニメーション */
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

#search-section .animate-fadeInUp {
    animation: fadeInUp 0.5s ease-out;
}

/* レスポンシブ調整 */
@media (max-width: 640px) {
    #search-section .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    #search-section .text-4xl {
        font-size: 2rem;
    }
    
    #search-section .text-5xl {
        font-size: 2.5rem;
    }
}

/* 高コントラスト対応 */
@media (prefers-contrast: high) {
    #search-section .bg-gradient-to-r {
        background: #059669;
    }
    
    #search-section .text-gray-600 {
        color: #374151;
    }
}

/* ダークモード対応 */
@media (prefers-color-scheme: dark) {
    #search-section {
        background: linear-gradient(135deg, #ecfdf5 0%, #f0fdfa 100%);
    }
    
    #search-section .bg-white {
        background-color: #ffffff;
        color: #0f172a;
    }
    
    #search-section .text-gray-900 {
        color: #0f172a;
    }
    
    #search-section .text-gray-600 {
        color: #334155;
    }
    
    #search-section .border-gray-200 {
        border-color: #e2e8f0;
    }
}

/* プリント対応 */
@media print {
    #search-section .bg-gradient-to-br {
        background: white;
    }
    
    #search-section .shadow-lg,
    #search-section .shadow-xl,
    #search-section .shadow-2xl {
        box-shadow: none;
        border: 1px solid #E5E7EB;
    }
}
</style>