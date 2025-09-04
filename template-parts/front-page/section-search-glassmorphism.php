<?php
/**
 * Search Section with Glassmorphism Design
 * 革新的なグラスモーフィズムデザインの検索セクション
 * 
 * @package Grant_Insight_Perfect
 * @version 1.0-glassmorphism
 */

// セキュリティチェック
if (!defined('ABSPATH')) {
    exit;
}

// 統計データ取得
$search_stats = array(
    'total_grants' => wp_count_posts('grant')->publish ?? 0,
    'total_prefectures' => wp_count_terms('grant_prefecture', array('hide_empty' => false)),
    'total_categories' => wp_count_terms('grant_category', array('hide_empty' => false)),
    'success_rate' => 87 // Demo value
);

// カテゴリと都道府県の取得
$categories = get_terms(array(
    'taxonomy' => 'grant_category',
    'hide_empty' => false,
    'number' => 8
));

$popular_prefectures = array('全国対応', '東京都', '大阪府', '愛知県', '福岡県', '神奈川県');
?>

<!-- Glassmorphism Search Section -->
<section id="search-section-glass" class="py-20 relative overflow-hidden">
    <!-- Dynamic Gradient Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500">
        <!-- Animated Blobs -->
        <div class="absolute top-0 -left-4 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-96 h-96 bg-yellow-300 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-96 h-96 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-4000"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <!-- Section Header with Glass Effect -->
        <div class="text-center mb-12">
            <div class="inline-block mb-6">
                <div class="backdrop-blur-md bg-white/20 rounded-2xl px-8 py-4 shadow-2xl border border-white/30">
                    <span class="text-white text-sm font-semibold tracking-widest">INTELLIGENT GRANT SEARCH</span>
                </div>
            </div>
            
            <h2 class="text-5xl lg:text-6xl font-black text-white mb-6 drop-shadow-2xl">
                <span class="block">次世代AI検索で</span>
                <span class="block mt-2 bg-gradient-to-r from-yellow-200 to-pink-200 bg-clip-text text-transparent">
                    最適な助成金を瞬時に発見
                </span>
            </h2>
            
            <p class="text-xl text-white/90 max-w-3xl mx-auto leading-relaxed drop-shadow-lg">
                <?php echo number_format($search_stats['total_grants']); ?>件以上の助成金データベースから
                AIが最適な支援制度をマッチング
            </p>
        </div>

        <!-- Glassmorphism Search Box -->
        <div class="max-w-5xl mx-auto">
            <div class="backdrop-blur-xl bg-white/10 rounded-3xl p-8 shadow-2xl border border-white/20">
                <!-- Search Form -->
                <form id="glassmorphic-search-form" class="space-y-6">
                    <!-- Main Search Input -->
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                            <i class="fas fa-search text-2xl text-white/60 group-focus-within:text-white transition-colors"></i>
                        </div>
                        <input type="text" 
                               id="glass-search-input" 
                               name="search_query"
                               class="w-full pl-16 pr-6 py-6 bg-white/10 backdrop-blur-md border-2 border-white/20 rounded-2xl text-white placeholder-white/50 text-lg focus:outline-none focus:border-white/50 focus:bg-white/20 transition-all duration-300"
                               placeholder="キーワード、業種、地域などで検索..."
                               autocomplete="off">
                        
                        <!-- Voice Search Button -->
                        <button type="button" class="absolute right-6 top-1/2 -translate-y-1/2 p-3 bg-white/20 hover:bg-white/30 rounded-lg transition-all duration-300">
                            <i class="fas fa-microphone text-white"></i>
                        </button>
                    </div>

                    <!-- Quick Filters -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Category Select -->
                        <div class="relative">
                            <select id="glass-category-select" 
                                    name="category"
                                    class="w-full px-6 py-4 bg-white/10 backdrop-blur-md border-2 border-white/20 rounded-xl text-white appearance-none focus:outline-none focus:border-white/50 focus:bg-white/20 transition-all duration-300">
                                <option value="" class="bg-purple-600">カテゴリを選択</option>
                                <?php if (!empty($categories)) : 
                                    foreach ($categories as $category) : ?>
                                    <option value="<?php echo esc_attr($category->slug); ?>" class="bg-purple-600">
                                        <?php echo esc_html($category->name); ?>
                                    </option>
                                <?php endforeach; 
                                endif; ?>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-white/60"></i>
                            </div>
                        </div>

                        <!-- Prefecture Select -->
                        <div class="relative">
                            <select id="glass-prefecture-select" 
                                    name="prefecture"
                                    class="w-full px-6 py-4 bg-white/10 backdrop-blur-md border-2 border-white/20 rounded-xl text-white appearance-none focus:outline-none focus:border-white/50 focus:bg-white/20 transition-all duration-300">
                                <option value="" class="bg-purple-600">地域を選択</option>
                                <?php foreach ($popular_prefectures as $prefecture) : ?>
                                    <option value="<?php echo esc_attr(strtolower($prefecture)); ?>" class="bg-purple-600">
                                        <?php echo esc_html($prefecture); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <i class="fas fa-map-marker-alt text-white/60"></i>
                            </div>
                        </div>

                        <!-- Amount Range -->
                        <div class="relative">
                            <select id="glass-amount-select" 
                                    name="amount_range"
                                    class="w-full px-6 py-4 bg-white/10 backdrop-blur-md border-2 border-white/20 rounded-xl text-white appearance-none focus:outline-none focus:border-white/50 focus:bg-white/20 transition-all duration-300">
                                <option value="" class="bg-purple-600">金額を選択</option>
                                <option value="0-100" class="bg-purple-600">〜100万円</option>
                                <option value="100-500" class="bg-purple-600">100〜500万円</option>
                                <option value="500-1000" class="bg-purple-600">500〜1000万円</option>
                                <option value="1000+" class="bg-purple-600">1000万円以上</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <i class="fas fa-yen-sign text-white/60"></i>
                            </div>
                        </div>
                    </div>

                    <!-- AI Suggestions -->
                    <div id="ai-suggestions" class="hidden">
                        <div class="backdrop-blur-md bg-white/10 rounded-xl p-4 border border-white/20">
                            <p class="text-white/80 text-sm mb-3">
                                <i class="fas fa-robot mr-2"></i>AIがおすすめするキーワード：
                            </p>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" class="ai-keyword px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-white text-sm transition-all duration-300">
                                    IT導入補助金
                                </button>
                                <button type="button" class="ai-keyword px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-white text-sm transition-all duration-300">
                                    ものづくり補助金
                                </button>
                                <button type="button" class="ai-keyword px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-white text-sm transition-all duration-300">
                                    創業支援
                                </button>
                                <button type="button" class="ai-keyword px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-white text-sm transition-all duration-300">
                                    雇用促進
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Search Button -->
                    <div class="flex justify-center">
                        <button type="submit" 
                                class="group relative px-12 py-5 bg-gradient-to-r from-yellow-400 to-pink-500 rounded-2xl font-bold text-lg text-white shadow-2xl hover:shadow-3xl transform hover:scale-105 transition-all duration-300 overflow-hidden">
                            <span class="relative z-10 flex items-center">
                                <i class="fas fa-search mr-3"></i>
                                AI検索を開始
                                <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"></i>
                            </span>
                            <div class="absolute inset-0 bg-gradient-to-r from-pink-500 to-yellow-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Live Statistics -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
                <?php
                $stat_items = array(
                    array('icon' => 'fa-database', 'value' => number_format($search_stats['total_grants']), 'label' => '助成金データ'),
                    array('icon' => 'fa-map-marked-alt', 'value' => $search_stats['total_prefectures'], 'label' => '対応地域'),
                    array('icon' => 'fa-tags', 'value' => $search_stats['total_categories'], 'label' => 'カテゴリ'),
                    array('icon' => 'fa-chart-line', 'value' => $search_stats['success_rate'] . '%', 'label' => '採択率')
                );
                
                foreach ($stat_items as $stat) : ?>
                <div class="backdrop-blur-md bg-white/10 rounded-xl p-4 border border-white/20 text-center hover:bg-white/20 transition-all duration-300 transform hover:scale-105">
                    <i class="fas <?php echo $stat['icon']; ?> text-2xl text-white/80 mb-2"></i>
                    <div class="text-2xl font-bold text-white"><?php echo $stat['value']; ?></div>
                    <div class="text-sm text-white/70"><?php echo $stat['label']; ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Search Results Preview (Hidden by default) -->
        <div id="glassmorphic-results" class="mt-12 hidden">
            <div class="backdrop-blur-xl bg-white/10 rounded-3xl p-8 shadow-2xl border border-white/20">
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-full mb-4">
                        <i class="fas fa-search text-3xl text-white"></i>
                    </div>
                    <p class="text-white text-xl">検索結果がここに表示されます</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Custom Styles for Glassmorphism -->
<style>
@keyframes blob {
    0% {
        transform: translate(0px, 0px) scale(1);
    }
    33% {
        transform: translate(30px, -50px) scale(1.1);
    }
    66% {
        transform: translate(-20px, 20px) scale(0.9);
    }
    100% {
        transform: translate(0px, 0px) scale(1);
    }
}

.animate-blob {
    animation: blob 7s infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

.animation-delay-4000 {
    animation-delay: 4s;
}

/* Glass effect enhancement */
.backdrop-blur-xl {
    -webkit-backdrop-filter: blur(20px);
    backdrop-filter: blur(20px);
}

.backdrop-blur-md {
    -webkit-backdrop-filter: blur(12px);
    backdrop-filter: blur(12px);
}

/* Shadow enhancement */
.shadow-3xl {
    box-shadow: 0 35px 60px -15px rgba(0, 0, 0, 0.5);
}

/* Custom scrollbar for selects */
select option {
    padding: 10px;
}

/* Pulse animation for AI elements */
@keyframes ai-pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.ai-pulse {
    animation: ai-pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>

<!-- Glassmorphism Search JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('glass-search-input');
    const aiSuggestions = document.getElementById('ai-suggestions');
    const searchForm = document.getElementById('glassmorphic-search-form');
    const resultsContainer = document.getElementById('glassmorphic-results');
    
    // Show AI suggestions on focus
    searchInput?.addEventListener('focus', function() {
        aiSuggestions?.classList.remove('hidden');
    });
    
    // Hide AI suggestions on blur (with delay)
    searchInput?.addEventListener('blur', function() {
        setTimeout(() => {
            aiSuggestions?.classList.add('hidden');
        }, 200);
    });
    
    // Handle AI keyword clicks
    document.querySelectorAll('.ai-keyword').forEach(button => {
        button.addEventListener('click', function() {
            searchInput.value = this.textContent.trim();
            searchInput.focus();
        });
    });
    
    // Handle form submission
    searchForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        resultsContainer?.classList.remove('hidden');
        resultsContainer.innerHTML = `
            <div class="backdrop-blur-xl bg-white/10 rounded-3xl p-8 shadow-2xl border border-white/20">
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-full mb-4 animate-spin">
                        <i class="fas fa-spinner text-3xl text-white"></i>
                    </div>
                    <p class="text-white text-xl">AI検索中...</p>
                </div>
            </div>
        `;
        
        // Trigger actual search (integrate with your AJAX function)
        if (typeof updateTailwindSearchResults === 'function') {
            updateTailwindSearchResults();
        }
    });
    
    // Voice search placeholder
    document.querySelector('.fa-microphone')?.parentElement?.addEventListener('click', function() {
        alert('音声検索機能は準備中です');
    });
});
</script>