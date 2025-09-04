<?php
/**
 * The template for displaying grant tips archive (High-Function Design Version)
 * 助成金・補助金申請のコツ一覧ページ（高機能デザイン版）
 */

get_header();

// ▼▼▼ WP_Query Arguments Builder ▼▼▼
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
    'post_type'      => 'grant_tip',
    'posts_per_page' => 9,
    'paged'          => $paged,
);

// Keyword Search
if (!empty($_GET['s'])) {
    $args['s'] = sanitize_text_field($_GET['s']);
}

// Taxonomy Query (Category)
if (!empty($_GET['grant_tip_category'])) {
    $args['tax_query'] = [
        [
            'taxonomy' => 'grant_tip_category',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_GET['grant_tip_category']),
        ]
    ];
}

// Meta Query (Difficulty)
$meta_queries = [];
if (!empty($_GET['difficulty'])) {
    $meta_queries[] = [
        'key'   => 'difficulty',
        'value' => sanitize_text_field($_GET['difficulty']),
    ];
}

// Sorting
$sort_by = isset($_GET['sort_by']) ? sanitize_text_field($_GET['sort_by']) : 'date_desc';
switch ($sort_by) {
    case 'popular':
        $args['orderby'] = 'comment_count';
        $args['order']   = 'DESC';
        break;
    default: // date_desc
        $args['orderby'] = 'date';
        $args['order']   = 'DESC';
        break;
}

if (!empty($meta_queries)) {
    $args['meta_query'] = $meta_queries;
}

$tip_query = new WP_Query($args);
// ▲▲▲ End of WP_Query Arguments Builder ▲▲▲
?>

<div class="new-archive-grant-tip-page bg-gray-100">

    <section class="hero-section bg-gradient-to-r from-blue-700 via-indigo-700 to-purple-800 text-white relative">
        <div class="container mx-auto px-6 py-24 text-center">
            <div class="inline-flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 mb-6">
                <i class="fas fa-lightbulb text-yellow-300 text-2xl"></i>
                <span class="text-lg font-bold tracking-wider">申請のノウハウ</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-black mb-4 animate-fade-in-down">実践的ノウハウ集</h1>
            <p class="text-lg md:text-xl text-indigo-200 max-w-3xl mx-auto animate-fade-in-down animation-delay-200">
                採択率を高める申請書の書き方から、専門家だけが知るコツまでを網羅。
            </p>
        </div>
    </section>

    <div class="container mx-auto px-6 py-12">
        <div class="lg:flex lg:gap-8">
            <aside class="lg:w-1/4 mb-8 lg:mb-0">
                <div class="lg:sticky lg:top-8 bg-white p-6 rounded-xl shadow-lg animate-fade-in-up">
                    <h3 class="text-xl font-bold mb-6 flex items-center"><i class="fas fa-filter text-indigo-500 mr-3"></i>記事を絞り込む</h3>
                    <form id="tip-search-form" method="get" action="<?php echo esc_url(get_post_type_archive_link('grant_tip')); ?>" class="space-y-6">
                        
                        <div>
                            <label for="s" class="block text-sm font-semibold text-gray-600 mb-2">キーワード</label>
                            <div class="relative">
                                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="s" id="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="申請書, 面談対策..." class="w-full pl-9 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 transition">
                            </div>
                        </div>

                        <div>
                            <label for="grant_tip_category" class="block text-sm font-semibold text-gray-600 mb-2">カテゴリ</label>
                            <select name="grant_tip_category" id="grant_tip_category" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 transition">
                                <option value="">すべてのカテゴリ</option>
                                <?php
                                $tip_categories = get_terms(['taxonomy' => 'grant_tip_category', 'hide_empty' => true]);
                                $current_category = isset($_GET['grant_tip_category']) ? $_GET['grant_tip_category'] : '';
                                foreach ($tip_categories as $cat) {
                                    echo "<option value=\"" . esc_attr($cat->slug) . "\"" . selected($current_category, $cat->slug, false) . ">" . esc_html($cat->name) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div>
                             <label class="block text-sm font-semibold text-gray-600 mb-2">難易度</label>
                             <div class="flex flex-col space-y-2">
                                <?php
                                $difficulties = ['beginner' => '初心者向け', 'intermediate' => '中級者向け', 'advanced' => '上級者向け'];
                                $current_difficulty = isset($_GET['difficulty']) ? $_GET['difficulty'] : '';
                                ?>
                                <label class="flex items-center"><input type="radio" name="difficulty" value="" <?php checked($current_difficulty, ''); ?> class="mr-2 text-indigo-600 focus:ring-indigo-500">すべて</label>
                                <?php foreach($difficulties as $slug => $label): ?>
                                <label class="flex items-center"><input type="radio" name="difficulty" value="<?php echo esc_attr($slug); ?>" <?php checked($current_difficulty, $slug); ?> class="mr-2 text-indigo-600 focus:ring-indigo-500"><?php echo esc_html($label); ?></label>
                                <?php endforeach; ?>
                             </div>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-200 space-y-3">
                             <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-indigo-700 transition-all transform hover:scale-105">
                                <i class="fas fa-sync-alt mr-2"></i>絞り込み更新
                            </button>
                             <a href="<?php echo esc_url(get_post_type_archive_link('grant_tip')); ?>" class="w-full text-center block text-sm text-gray-600 hover:text-gray-800 transition">
                                条件をリセット
                            </a>
                        </div>
                    </form>
                </div>
            </aside>

            <main class="lg:w-3/4">
                <div class="bg-white rounded-xl shadow-md p-4 mb-6 flex flex-col md:flex-row items-center justify-between gap-4">
                    <p class="text-gray-700 font-semibold">
                        <span class="font-black text-indigo-600"><?php echo $tip_query->found_posts; ?></span> 件の記事
                    </p>
                    <div class="flex items-center gap-2">
                        <label for="sort-by" class="text-sm text-gray-500 flex-shrink-0">並び替え:</label>
                        <form id="sort-form" method="get">
                            <?php foreach ($_GET as $key => $value) { if ($key != 'sort_by' && $key != 'paged') { echo '<input type="hidden" name="'.esc_attr($key).'" value="'.esc_attr(stripslashes($value)).'">'; } } ?>
                            <select id="sort-by" name="sort_by" onchange="this.form.submit()" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition">
                                <option value="date_desc" <?php selected($sort_by, 'date_desc'); ?>>新着順</option>
                                <option value="popular" <?php selected($sort_by, 'popular'); ?>>人気順</option>
                            </select>
                        </form>
                    </div>
                </div>

                <?php if ($tip_query->have_posts()) : ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        <?php while ($tip_query->have_posts()) : $tip_query->the_post(); ?>
                            <?php get_template_part('template-parts/tip-card'); // A new card template for tips ?>
                        <?php endwhile; ?>
                    </div>
                    
                    <div class="mt-12 flex justify-center">
                        <?php
                            echo paginate_links([
                                'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                                'format' => '?paged=%#%',
                                'current' => max(1, get_query_var('paged')),
                                'total' => $tip_query->max_num_pages,
                                'prev_text' => '<i class="fas fa-chevron-left"></i>',
                                'next_text' => '<i class="fas fa-chevron-right"></i>',
                                'type' => 'list',
                            ]);
                        ?>
                    </div>

                <?php else : ?>
                    <div class="bg-white p-8 md:p-16 rounded-xl shadow-lg text-center">
                        <div class="text-6xl text-gray-300 mb-6"><i class="fas fa-folder-open"></i></div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">記事が見つかりませんでした</h2>
                        <p class="mt-4 text-gray-600 max-w-lg mx-auto mb-8">
                            申し訳ございません。現在の検索条件に一致する記事はありません。<br>
                            条件を変更して再度お試しください。
                        </p>
                        <a href="<?php echo esc_url(get_post_type_archive_link('grant_tip')); ?>" class="inline-block bg-indigo-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-indigo-700 transition-colors transform hover:scale-105">
                            <i class="fas fa-redo mr-2"></i>検索条件をリセット
                        </a>
                    </div>
                <?php endif; wp_reset_postdata(); ?>
            </main>
        </div>
    </div>
</div>

<style>
/* Animations */
@keyframes fadeInDown{from{opacity:0;transform:translateY(-20px)}to{opacity:1;transform:translateY(0)}}
@keyframes fadeInUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
.animate-fade-in-down{animation:fadeInDown .6s ease-out forwards}
.animate-fade-in-up{animation:fadeInUp .6s ease-out forwards}
.animation-delay-200{animation-delay:.2s}
/* Pagination */
.pagination{display:flex;justify-content:center;list-style:none;padding:0}
.pagination li{margin:0 4px}
.pagination .page-numbers{display:flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:9999px;text-decoration:none;color:#4B5563;background-color:#F3F4F6;transition:all .2s ease;font-weight:600}
.pagination .page-numbers:hover{background-color:#E5E7EB;color:#1F2937}
.pagination .page-numbers.current{background-color:#4F46E5;color:#fff;box-shadow:0 4px 6px rgba(0,0,0,.1)}
</style>

<?php get_footer(); ?>