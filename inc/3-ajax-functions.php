<?php
/**
 * Grant Insight Perfect - 3. AJAX Functions File (Complete Enhanced Edition)
 *
 * サイトの動的な機能（検索、フィルタリング、お気に入りなど）を
 * 担当する全てのAJAX処理をここにまとめます。
 * 新しいカードデザインに対応した完全修正版です。
 * 4-helper-functions.phpのヘルパー関数を活用しています。
 *
 * @package Grant_Insight_Perfect
 */

// セキュリティチェック
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 【修正】AJAX - 助成金読み込み処理（新カードデザイン・全フィルター対応版）
 */
function gi_ajax_load_grants() {
    // nonceチェック
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'gi_ajax_nonce')) {
        wp_send_json_error('セキュリティチェックに失敗しました');
    }

    // ★修正点: difficultyとsuccess_rateのフィルター値を取得
    $search = sanitize_text_field($_POST['search'] ?? '');
    $categories = json_decode(stripslashes($_POST['categories'] ?? '[]'), true);
    $prefectures = json_decode(stripslashes($_POST['prefectures'] ?? '[]'), true);
    $amount = sanitize_text_field($_POST['amount'] ?? '');
    $status = json_decode(stripslashes($_POST['status'] ?? '[]'), true);
    $difficulty = json_decode(stripslashes($_POST['difficulty'] ?? '[]'), true);
    $success_rate = json_decode(stripslashes($_POST['success_rate'] ?? '[]'), true);
    
    // UIステータスをDBの値にマッピング（ヘルパー関数を使用）
    if (is_array($status)) {
        $status = array_map(function($s) { 
            return $s === 'active' ? 'open' : $s; 
        }, $status);
    }
    
    $sort = sanitize_text_field($_POST['sort'] ?? 'date_desc');
    $view = sanitize_text_field($_POST['view'] ?? 'grid');
    $page = intval($_POST['page'] ?? 1);
    $posts_per_page = 12;

    $args = array(
        'post_type' => 'grant',
        'posts_per_page' => $posts_per_page,
        'paged' => $page,
        'post_status' => 'publish'
    );

    if (!empty($search)) {
        $args['s'] = $search;
    }

    $tax_query = array('relation' => 'AND');
    if (!empty($categories)) {
        $tax_query[] = array('taxonomy' => 'grant_category', 'field' => 'slug', 'terms' => $categories);
    }
    if (!empty($prefectures)) {
        $tax_query[] = array('taxonomy' => 'grant_prefecture', 'field' => 'slug', 'terms' => $prefectures);
    }
    if (count($tax_query) > 1) {
        $args['tax_query'] = $tax_query;
    }

    $meta_query = array('relation' => 'AND');

    if (!empty($status)) {
        $meta_query[] = array('key' => 'application_status', 'value' => $status, 'compare' => 'IN');
    }
    
    // ★修正点: 難易度フィルターのロジック
    if (!empty($difficulty)) {
        $meta_query[] = array('key' => 'grant_difficulty', 'value' => $difficulty, 'compare' => 'IN');
    }
    
    // ★修正点: 採択率フィルターのロジック
    if (!empty($success_rate)) {
        $rate_query = array('relation' => 'OR');
        if (in_array('high', $success_rate, true)) {
            $rate_query[] = array('key' => 'grant_success_rate', 'value' => 70, 'compare' => '>=', 'type' => 'NUMERIC');
        }
        if (in_array('medium', $success_rate, true)) {
            $rate_query[] = array('key' => 'grant_success_rate', 'value' => array(50, 69), 'compare' => 'BETWEEN', 'type' => 'NUMERIC');
        }
        if (in_array('low', $success_rate, true)) {
            $rate_query[] = array('key' => 'grant_success_rate', 'value' => 50, 'compare' => '<', 'type' => 'NUMERIC');
        }
        if(count($rate_query) > 1) {
            $meta_query[] = $rate_query;
        }
    }

    if (!empty($amount)) {
        switch ($amount) {
            case '0-100': 
                $meta_query[] = array('key' => 'max_amount_numeric', 'value' => 1000000, 'compare' => '<=', 'type' => 'NUMERIC'); 
                break;
            case '100-500': 
                $meta_query[] = array('key' => 'max_amount_numeric', 'value' => array(1000001, 5000000), 'compare' => 'BETWEEN', 'type' => 'NUMERIC'); 
                break;
            case '500-1000': 
                $meta_query[] = array('key' => 'max_amount_numeric', 'value' => array(5000001, 10000000), 'compare' => 'BETWEEN', 'type' => 'NUMERIC'); 
                break;
            case '1000+': 
                $meta_query[] = array('key' => 'max_amount_numeric', 'value' => 10000000, 'compare' => '>=', 'type' => 'NUMERIC'); 
                break;
        }
    }

    if (count($meta_query) > 1) {
        $args['meta_query'] = $meta_query;
    }

    // ★修正点: success_rate_desc をソート順に追加
    switch ($sort) {
        case 'date_asc': 
            $args['orderby'] = 'date'; 
            $args['order'] = 'ASC'; 
            break;
        case 'amount_desc': 
            $args['orderby'] = 'meta_value_num'; 
            $args['meta_key'] = 'max_amount_numeric'; 
            $args['order'] = 'DESC'; 
            break;
        case 'amount_asc': 
            $args['orderby'] = 'meta_value_num'; 
            $args['meta_key'] = 'max_amount_numeric'; 
            $args['order'] = 'ASC'; 
            break;
        case 'deadline_asc': 
            $args['orderby'] = 'meta_value_num'; 
            $args['meta_key'] = 'deadline_date'; 
            $args['order'] = 'ASC'; 
            break;
        case 'success_rate_desc': 
            $args['orderby'] = 'meta_value_num'; 
            $args['meta_key'] = 'grant_success_rate'; 
            $args['order'] = 'DESC'; 
            break;
        case 'title_asc': 
            $args['orderby'] = 'title'; 
            $args['order'] = 'ASC'; 
            break;
        default: 
            $args['orderby'] = 'date'; 
            $args['order'] = 'DESC';
    }

    $query = new WP_Query($args);
    $grants = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            
            // ★修正点: ヘルパー関数を使用してカード表示に必要なデータを先に集める
            $grant_terms = get_the_terms($post_id, 'grant_category');
            $prefecture_terms = get_the_terms($post_id, 'grant_prefecture');
            
            $grant_data = array(
                'id' => $post_id,
                'title' => get_the_title(),
                'permalink' => get_permalink(),
                'excerpt' => get_the_excerpt(),
                'thumbnail' => get_the_post_thumbnail_url($post_id, 'gi-card-thumb'),
                'main_category' => (!is_wp_error($grant_terms) && !empty($grant_terms)) ? $grant_terms[0]->name : '',
                'prefecture' => (!is_wp_error($prefecture_terms) && !empty($prefecture_terms)) ? $prefecture_terms[0]->name : '',
                'organization' => gi_safe_get_meta($post_id, 'organization', ''),
                'deadline' => gi_get_formatted_deadline($post_id), // ヘルパー関数を使用
                'amount' => gi_safe_get_meta($post_id, 'max_amount', '-'),
                'amount_numeric' => gi_safe_get_meta($post_id, 'max_amount_numeric', 0),
                'deadline_timestamp' => gi_safe_get_meta($post_id, 'deadline_date', ''),
                'status' => gi_map_application_status_ui(gi_safe_get_meta($post_id, 'application_status', 'open')), // ヘルパー関数を使用
            );
            
            // ★修正点: 集めたデータを使ってカードのHTMLを作る
            $html = '';
            if ($view === 'grid') {
                $html = gi_render_grant_card_grid_enhanced($grant_data);
            } else {
                $html = gi_render_grant_card_list_enhanced($grant_data);
            }

            $grants[] = array(
                'id' => $post_id,
                'html' => $html
            );
        }
        wp_reset_postdata();
    }

    wp_send_json_success(array(
        'grants' => $grants,
        'found_posts' => $query->found_posts,
        'pagination' => array(
            'current_page' => $page,
            'total_pages' => $query->max_num_pages,
            'total_posts' => $query->found_posts,
            'posts_per_page' => $posts_per_page
        ),
        'query_info' => compact('search', 'categories', 'prefectures', 'amount', 'status', 'difficulty', 'success_rate', 'sort'),
        'view' => $view
    ));
}
add_action('wp_ajax_gi_load_grants', 'gi_ajax_load_grants');
add_action('wp_ajax_nopriv_gi_load_grants', 'gi_ajax_load_grants');

/**
 * 【追加】デバッグ用AJAX関数 - 助成金データの確認用（ヘルパー関数使用）
 */
function gi_ajax_debug_grants() {
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'gi_ajax_nonce')) {
        wp_send_json_error('Invalid nonce');
    }
    
    // 助成金の投稿数を確認
    $grant_count = wp_count_posts('grant');
    
    // 最新の助成金10件を取得
    $recent_grants = get_posts(array(
        'post_type' => 'grant',
        'posts_per_page' => 10,
        'post_status' => 'publish'
    ));
    
    $grants_data = array();
    foreach ($recent_grants as $grant) {
        $grants_data[] = array(
            'id' => $grant->ID,
            'title' => $grant->post_title,
            'status' => get_post_status($grant->ID),
            'meta_fields' => array(
                'max_amount' => gi_safe_get_meta($grant->ID, 'max_amount', ''),
                'max_amount_numeric' => gi_safe_get_meta($grant->ID, 'max_amount_numeric', 0),
                'grant_difficulty' => gi_safe_get_meta($grant->ID, 'grant_difficulty', ''),
                'grant_success_rate' => gi_safe_get_meta($grant->ID, 'grant_success_rate', 0),
                'application_status' => gi_safe_get_meta($grant->ID, 'application_status', ''),
                'deadline_formatted' => gi_get_formatted_deadline($grant->ID),
                'organization' => gi_safe_get_meta($grant->ID, 'organization', ''),
            ),
            'categories' => gi_get_post_categories($grant->ID),
        );
    }
    
    wp_send_json_success(array(
        'total_grants' => $grant_count,
        'recent_grants' => $grants_data,
        'template_path' => get_template_directory() . '/grant-card-v4-enhanced.php',
        'template_exists' => file_exists(get_template_directory() . '/grant-card-v4-enhanced.php'),
        'helper_functions_available' => array(
            'gi_safe_get_meta' => function_exists('gi_safe_get_meta'),
            'gi_get_formatted_deadline' => function_exists('gi_get_formatted_deadline'),
            'gi_map_application_status_ui' => function_exists('gi_map_application_status_ui'),
            'gi_get_post_categories' => function_exists('gi_get_post_categories'),
        )
    ));
}
add_action('wp_ajax_gi_debug_grants', 'gi_ajax_debug_grants');
add_action('wp_ajax_nopriv_gi_debug_grants', 'gi_ajax_debug_grants');

/**
 * AJAX - Search suggestions（ヘルパー関数使用）
 */
function gi_ajax_get_search_suggestions() {
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'gi_ajax_nonce')) {
        wp_send_json_error('Invalid nonce');
    }
    $query = sanitize_text_field($_POST['query'] ?? '');
    $suggestions = array();
    if ($query !== '') {
        $args = array(
            's' => $query,
            'post_type' => array('grant','tool','case_study','guide','grant_tip'),
            'post_status' => 'publish',
            'posts_per_page' => 5,
            'fields' => 'ids'
        );
        $posts = get_posts($args);
        foreach ($posts as $pid) {
            $suggestions[] = array(
                'label' => get_the_title($pid),
                'value' => get_the_title($pid),
                'url' => gi_safe_url(get_permalink($pid)), // ヘルパー関数を使用
                'type' => get_post_type($pid)
            );
        }
    }
    wp_send_json_success($suggestions);
}
add_action('wp_ajax_get_search_suggestions', 'gi_ajax_get_search_suggestions');
add_action('wp_ajax_nopriv_get_search_suggestions', 'gi_ajax_get_search_suggestions');

/**
 * AJAX - Advanced search（ヘルパー関数使用）
 */
function gi_ajax_advanced_search() {
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'gi_ajax_nonce')) {
        wp_send_json_error('Invalid nonce');
    }
    $keyword = sanitize_text_field($_POST['search_query'] ?? ($_POST['s'] ?? ''));
    $prefecture = sanitize_text_field($_POST['prefecture'] ?? '');
    $category = sanitize_text_field($_POST['category'] ?? '');
    $amount = sanitize_text_field($_POST['amount'] ?? '');
    $status = sanitize_text_field($_POST['status'] ?? '');

    $tax_query = array('relation' => 'AND');
    if ($prefecture) {
        $tax_query[] = array('taxonomy'=>'grant_prefecture','field'=>'slug','terms'=>array($prefecture),'operator'=>'IN');
    }
    if ($category) {
        $tax_query[] = array('taxonomy'=>'grant_category','field'=>'slug','terms'=>array($category),'operator'=>'IN');
    }

    $meta_query = array('relation' => 'AND');
    if ($amount) {
        switch ($amount) {
            case '0-100':
                $meta_query[] = array('key'=>'max_amount_numeric','value'=>1000000,'compare'=>'<=','type'=>'NUMERIC');
                break;
            case '100-500':
                $meta_query[] = array('key'=>'max_amount_numeric','value'=>array(1000000,5000000),'compare'=>'BETWEEN','type'=>'NUMERIC');
                break;
            case '500-1000':
                $meta_query[] = array('key'=>'max_amount_numeric','value'=>array(5000000,10000000),'compare'=>'BETWEEN','type'=>'NUMERIC');
                break;
            case '1000+':
                $meta_query[] = array('key'=>'max_amount_numeric','value'=>10000000,'compare'=>'>=','type'=>'NUMERIC');
                break;
        }
    }
    if ($status) {
        $status = $status === 'active' ? 'open' : $status;
        $meta_query[] = array('key'=>'application_status','value'=>array($status),'compare'=>'IN');
    }

    $args = array(
        'post_type' => 'grant',
        'post_status' => 'publish',
        'posts_per_page' => 6,
        's' => $keyword,
    );
    if (count($tax_query) > 1) $args['tax_query'] = $tax_query;
    if (count($meta_query) > 1) $args['meta_query'] = $meta_query;

    $q = new WP_Query($args);
    $html = '';
    if ($q->have_posts()) {
        ob_start();
        echo '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">';
        while ($q->have_posts()) { 
            $q->the_post();
            $card_template = get_template_directory() . '/grant-card-v4-enhanced.php';
            if (file_exists($card_template)) {
                include($card_template);
            } else {
                echo gi_render_grant_card(get_the_ID(), 'grid');
            }
        }
        echo '</div>';
        $html = ob_get_clean();
        wp_reset_postdata();
    }
    wp_send_json_success(array(
        'html' => $html ?: '<p class="text-gray-500 text-center py-8">該当する助成金が見つかりませんでした。</p>',
        'count' => $q->found_posts
    ));
}
add_action('wp_ajax_advanced_search', 'gi_ajax_advanced_search');
add_action('wp_ajax_nopriv_advanced_search', 'gi_ajax_advanced_search');

/**
 * AJAX - Grant Insight top page search（ヘルパー関数使用）
 */
function gi_ajax_grant_insight_search() {
    $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    if (!wp_verify_nonce($nonce, 'grant_insight_search_nonce')) {
        wp_send_json_error(array('message' => 'Invalid nonce'), 403);
    }

    $keyword    = sanitize_text_field($_POST['keyword'] ?? '');
    $post_type = sanitize_text_field($_POST['post_type'] ?? '');
    $orderby    = sanitize_text_field($_POST['orderby'] ?? 'relevance');
    $category   = sanitize_text_field($_POST['category'] ?? '');
    $amount_min = isset($_POST['amount_min']) ? intval($_POST['amount_min']) : 0;
    $amount_max = isset($_POST['amount_max']) ? intval($_POST['amount_max']) : 0;
    $deadline   = sanitize_text_field($_POST['deadline'] ?? '');
    $page       = max(1, intval($_POST['page'] ?? 1));
    $per_page = 12;

    $post_types = array('grant','tool','case_study','guide','grant_tip');
    if (!empty($post_type)) {
        $post_types = array($post_type);
    }

    $args = array(
        'post_type'      => $post_types,
        'post_status'    => 'publish',
        's'              => $keyword,
        'paged'          => $page,
        'posts_per_page' => $per_page,
    );

    switch ($orderby) {
        case 'date':
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
            break;
        case 'title':
            $args['orderby'] = 'title';
            $args['order'] = 'ASC';
            break;
        case 'modified':
            $args['orderby'] = 'modified';
            $args['order'] = 'DESC';
            break;
        default:
            $args['orderby'] = 'relevance';
            $args['order']   = 'DESC';
            break;
    }

    $tax_query = array('relation' => 'AND');
    if (!empty($category)) {
        $tax_query[] = array(
            'taxonomy' => 'grant_category',
            'field'    => 'term_id',
            'terms'    => array(intval($category)),
        );
    }
    if (count($tax_query) > 1) {
        $args['tax_query'] = $tax_query;
    }

    $meta_query = array('relation' => 'AND');
    if (in_array('grant', $post_types, true) || $post_type === 'grant') {
        if ($amount_min > 0 || $amount_max > 0) {
            $meta_query[] = array(
                'key'     => 'max_amount_numeric',
                'value'   => $amount_max > 0 && $amount_min > 0 ? array($amount_min, $amount_max) : ($amount_max > 0 ? $amount_max : $amount_min),
                'compare' => ($amount_min > 0 && $amount_max > 0) ? 'BETWEEN' : ($amount_max > 0 ? '<=' : '>='),
                'type'    => 'NUMERIC',
            );
        }

        if (!empty($deadline)) {
            $todayYmd = intval(current_time('Ymd'));
            $targetYmd = $todayYmd;
            switch ($deadline) {
                case '1month':
                    $targetYmd = intval(date('Ymd', strtotime('+1 month', current_time('timestamp'))));
                    break;
                case '3months':
                    $targetYmd = intval(date('Ymd', strtotime('+3 months', current_time('timestamp'))));
                    break;
                case '6months':
                    $targetYmd = intval(date('Ymd', strtotime('+6 months', current_time('timestamp'))));
                    break;
                case '1year':
                    $targetYmd = intval(date('Ymd', strtotime('+1 year', current_time('timestamp'))));
                    break;
            }
            $meta_query[] = array(
                'key'     => 'deadline_date',
                'value'   => array($todayYmd, $targetYmd),
                'compare' => 'BETWEEN',
                'type'    => 'NUMERIC',
            );
        }
    }
    if (count($meta_query) > 1) {
        $args['meta_query'] = $meta_query;
    }

    $q = new WP_Query($args);

    $favorites = gi_get_user_favorites(); // ヘルパー関数を使用
    $posts = array();
    if ($q->have_posts()) {
        while ($q->have_posts()) { $q->the_post();
            $pid = get_the_ID();
            $ptype = get_post_type($pid);
            $amount_yen = ($ptype === 'grant') ? intval(gi_safe_get_meta($pid, 'max_amount_numeric', 0)) : 0;
            $deadline_date = ($ptype === 'grant') ? gi_safe_get_meta($pid, 'deadline_date', '') : '';

            $posts[] = array(
                'id'        => $pid,
                'title'     => get_the_title($pid),
                'excerpt'   => gi_safe_excerpt(get_the_excerpt($pid), 100), // ヘルパー関数を使用
                'permalink' => gi_safe_url(get_permalink($pid)), // ヘルパー関数を使用
                'thumbnail' => get_the_post_thumbnail_url($pid, 'medium'),
                'date'      => gi_safe_date_format(get_the_date('Y-m-d', $pid)), // ヘルパー関数を使用
                'post_type' => $ptype,
                'amount'    => $amount_yen,
                'deadline'  => $deadline_date,
                'is_featured'=> false,
                'is_favorite'=> in_array($pid, $favorites, true),
            );
        }
        wp_reset_postdata();
    }

    $response = array(
        'posts' => $posts,
        'pagination' => array(
            'current_page' => $page,
            'total_pages'  => max(1, intval($q->max_num_pages)),
        ),
        'total' => intval($q->found_posts),
    );

    wp_send_json_success($response);
}
add_action('wp_ajax_grant_insight_search', 'gi_ajax_grant_insight_search');
add_action('wp_ajax_nopriv_grant_insight_search', 'gi_ajax_grant_insight_search');

/**
 * AJAX - Export search results as CSV（ヘルパー関数使用）
 */
function gi_ajax_grant_insight_export_results() {
    $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    if (!wp_verify_nonce($nonce, 'grant_insight_search_nonce') && !wp_verify_nonce($nonce, 'gi_ajax_nonce')) {
        wp_send_json_error(array('message' => 'Invalid nonce'), 403);
    }

    $_POST['page'] = 1;
    $_POST['orderby'] = sanitize_text_field($_POST['orderby'] ?? 'date');

    $keyword   = sanitize_text_field($_POST['keyword'] ?? '');
    $post_type = sanitize_text_field($_POST['post_type'] ?? 'grant');
    $category  = sanitize_text_field($_POST['category'] ?? '');

    $args = array(
        'post_type'      => $post_type ? array($post_type) : array('grant'),
        'post_status'    => 'publish',
        's'              => $keyword,
        'posts_per_page' => 200, // cap export size
        'paged'          => 1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    );
    if (!empty($category)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'grant_category',
                'field'    => 'term_id',
                'terms'    => array(intval($category)),
            )
        );
    }

    $q = new WP_Query($args);

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="grant_search_results_' . date('Y-m-d') . '.csv"');
    $fp = fopen('php://output', 'w');
    fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM for Excel

    fputcsv($fp, array('ID','Title','Permalink','Post Type','Date','Amount(yen)','Deadline','Organization'));
    if ($q->have_posts()) {
        while ($q->have_posts()) { $q->the_post();
            $pid = get_the_ID();
            $ptype = get_post_type($pid);
            $amount_yen = ($ptype === 'grant') ? intval(gi_safe_get_meta($pid, 'max_amount_numeric', 0)) : 0;
            $deadline_date = ($ptype === 'grant') ? gi_safe_get_meta($pid, 'deadline_date', '') : '';
            $organization = ($ptype === 'grant') ? gi_safe_get_meta($pid, 'organization', '') : '';
            
            fputcsv($fp, array(
                $pid,
                get_the_title($pid),
                gi_safe_url(get_permalink($pid)), // ヘルパー関数を使用
                $ptype,
                gi_safe_date_format(get_the_date('Y-m-d', $pid)), // ヘルパー関数を使用
                gi_safe_number_format($amount_yen), // ヘルパー関数を使用
                gi_safe_date_format($deadline_date, 'Y-m-d'), // ヘルパー関数を使用
                $organization,
            ));
        }
        wp_reset_postdata();
    }
    fclose($fp);
    exit;
}
add_action('wp_ajax_grant_insight_export_results', 'gi_ajax_grant_insight_export_results');
add_action('wp_ajax_nopriv_grant_insight_export_results', 'gi_ajax_grant_insight_export_results');

/**
 * AJAX - Newsletter signup（ヘルパー関数使用）
 */
function gi_ajax_newsletter_signup() {
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'gi_ajax_nonce')) {
        wp_send_json_error('Invalid nonce');
    }
    $email = sanitize_email($_POST['email'] ?? '');
    if (!$email || !is_email($email)) {
        wp_send_json_error('メールアドレスが正しくありません');
    }
    $list = get_option('gi_newsletter_list', array());
    if (!is_array($list)) $list = array();
    if (!in_array($email, $list)) {
        $list[] = $email;
        update_option('gi_newsletter_list', $list);
    }
    wp_send_json_success(array(
        'message' => '登録しました',
        'email' => gi_safe_escape($email) // ヘルパー関数を使用
    ));
}
add_action('wp_ajax_newsletter_signup', 'gi_ajax_newsletter_signup');
add_action('wp_ajax_nopriv_newsletter_signup', 'gi_ajax_newsletter_signup');

/**
 * AJAX - Affiliate click tracking（ヘルパー関数使用）
 */
function gi_ajax_track_affiliate_click() {
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'gi_ajax_nonce')) {
        wp_send_json_error('Invalid nonce');
    }
    $url = gi_safe_url($_POST['url'] ?? ''); // ヘルパー関数を使用
    $post_id = intval($_POST['post_id'] ?? 0);
    if (!$url) wp_send_json_error('URLが無効です');
    
    $log = get_option('gi_affiliate_clicks', array());
    if (!is_array($log)) $log = array();
    $log[] = array(
        'time' => current_time('timestamp'), 
        'url' => $url, 
        'post_id' => $post_id, 
        'ip' => gi_safe_escape($_SERVER['REMOTE_ADDR'] ?? ''), // ヘルパー関数を使用
        'user_agent' => gi_safe_escape($_SERVER['HTTP_USER_AGENT'] ?? '') // ヘルパー関数を使用
    );
    update_option('gi_affiliate_clicks', $log);
    wp_send_json_success(array('message' => 'ok'));
}
add_action('wp_ajax_track_affiliate_click', 'gi_ajax_track_affiliate_click');
add_action('wp_ajax_nopriv_track_affiliate_click', 'gi_ajax_track_affiliate_click');

/**
 * AJAX - Related grants (新カードデザイン対応・ヘルパー関数使用)
 */
function gi_ajax_get_related_grants() {
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'get_related_grants_nonce')) {
        wp_send_json_error('Invalid nonce');
    }
    $post_id = intval($_POST['post_id'] ?? 0);
    $category_name = sanitize_text_field($_POST['category'] ?? '');
    $prefecture_name = sanitize_text_field($_POST['prefecture'] ?? '');

    $tax_query = array('relation' => 'AND');
    if ($category_name) {
        $term = get_term_by('name', $category_name, 'grant_category');
        if ($term) {
            $tax_query[] = array('taxonomy'=>'grant_category','field'=>'term_id','terms'=>array($term->term_id));
        }
    }
    if ($prefecture_name) {
        $term = get_term_by('name', $prefecture_name, 'grant_prefecture');
        if ($term) {
            $tax_query[] = array('taxonomy'=>'grant_prefecture','field'=>'term_id','terms'=>array($term->term_id));
        }
    }

    $args = array(
        'post_type' => 'grant',
        'post_status' => 'publish',
        'posts_per_page' => 3,
        'post__not_in' => array($post_id),
    );
    if (count($tax_query) > 1) $args['tax_query'] = $tax_query;

    $q = new WP_Query($args);
    $html = '';
    if ($q->have_posts()) {
        ob_start();
        echo '<div class="grid grid-cols-1 md:grid-cols-3 gap-6">';
        while ($q->have_posts()) { 
            $q->the_post();
            $card_template = get_template_directory() . '/grant-card-v4-enhanced.php';
            if (file_exists($card_template)) {
                include($card_template);
            } else {
                echo gi_render_grant_card(get_the_ID(), 'grid');
            }
        }
        echo '</div>';
        $html = ob_get_clean();
        wp_reset_postdata();
    }
    wp_send_json_success(array('html' => $html));
}
add_action('wp_ajax_get_related_grants', 'gi_ajax_get_related_grants');
add_action('wp_ajax_nopriv_get_related_grants', 'gi_ajax_get_related_grants');

/**
 * 【新機能】AJAX - お気に入り機能（新カードデザイン対応・ヘルパー関数使用）
 */
function gi_ajax_toggle_favorite() {
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'gi_ajax_nonce') && !wp_verify_nonce($_POST['nonce'] ?? '', 'grant_insight_search_nonce')) {
        wp_send_json_error('セキュリティチェックに失敗しました');
    }
    
    $post_id = intval($_POST['post_id']);
    $user_id = get_current_user_id();
    
    if (!$post_id || !get_post($post_id)) {
        wp_send_json_error('無効な投稿IDです');
    }
    
    if (!$user_id) {
        $cookie_name = 'gi_favorites';
        $favorites = isset($_COOKIE[$cookie_name]) ? array_filter(explode(',', $_COOKIE[$cookie_name])) : array();
        
        if (in_array($post_id, $favorites)) {
            $favorites = array_diff($favorites, array($post_id));
            $action = 'removed';
            $icon_class = 'far'; // 空のハート
        } else {
            $favorites[] = $post_id;
            $action = 'added';
            $icon_class = 'fas'; // 塗りつぶしハート
        }
        
        setcookie($cookie_name, implode(',', $favorites), time() + (86400 * 30), '/');
    } else {
        $favorites = gi_get_user_favorites($user_id); // ヘルパー関数を使用
        
        if (in_array($post_id, $favorites)) {
            $favorites = array_diff($favorites, array($post_id));
            $action = 'removed';
            $icon_class = 'far';
        } else {
            $favorites[] = $post_id;
            $action = 'added';
            $icon_class = 'fas';
        }
        
        update_user_meta($user_id, 'gi_favorites', $favorites);
    }
    
    wp_send_json_success(array(
        'action' => $action,
        'post_id' => $post_id,
        'post_title' => gi_safe_escape(get_the_title($post_id)), // ヘルパー関数を使用
        'count' => count($favorites),
        'is_favorite' => $action === 'added',
        'icon_class' => $icon_class,
        'message' => $action === 'added' ? 'お気に入りに追加しました' : 'お気に入りから削除しました'
    ));
}
add_action('wp_ajax_gi_toggle_favorite', 'gi_ajax_toggle_favorite');
add_action('wp_ajax_nopriv_gi_toggle_favorite', 'gi_ajax_toggle_favorite');
add_action('wp_ajax_toggle_favorite', 'gi_ajax_toggle_favorite');
add_action('wp_ajax_nopriv_toggle_favorite', 'gi_ajax_toggle_favorite');
add_action('wp_ajax_grant_insight_toggle_favorite', 'gi_ajax_toggle_favorite');
add_action('wp_ajax_nopriv_grant_insight_toggle_favorite', 'gi_ajax_toggle_favorite');

/**
 * AJAX - ビジネスツール読み込み処理（ヘルパー関数使用）
 */
function gi_ajax_load_tools() {
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'gi_ajax_nonce')) {
        wp_send_json_error('セキュリティチェックに失敗しました。');
    }

    $search = sanitize_text_field($_POST['keyword'] ?? '');
    $category = sanitize_text_field($_POST['category'] ?? '');
    $price_range = sanitize_text_field($_POST['price_range'] ?? '');
    $rating = sanitize_text_field($_POST['rating'] ?? '');
    $features = sanitize_text_field($_POST['features'] ?? '');
    $sort_by = sanitize_text_field($_POST['sort_by'] ?? 'date');
    $sort_order = sanitize_text_field($_POST['sort_order'] ?? 'DESC');
    $posts_per_page = intval($_POST['posts_per_page'] ?? 12);
    $page = intval($_POST['page'] ?? 1);

    $args = array(
        'post_type' => 'tool',
        'post_status' => 'publish',
        'posts_per_page' => $posts_per_page,
        'paged' => $page,
    );

    if (!empty($search)) {
        $args['s'] = $search;
    }

    if (!empty($category)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'tool_category',
                'field' => 'slug',
                'terms' => $category,
            ),
        );
    }

    $meta_query = array('relation' => 'AND');
    
    if (!empty($price_range)) {
        switch ($price_range) {
            case 'free':
                $meta_query[] = array(
                    'key' => 'price_free',
                    'value' => '1',
                    'compare' => '='
                );
                break;
            case '0-5000':
                $meta_query[] = array(
                    'key' => 'price_monthly',
                    'value' => 5000,
                    'compare' => '<=',
                    'type' => 'NUMERIC'
                );
                break;
            case '5001-20000':
                $meta_query[] = array(
                    'key' => 'price_monthly',
                    'value' => array(5001, 20000),
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC'
                );
                break;
            case '20001':
                $meta_query[] = array(
                    'key' => 'price_monthly',
                    'value' => 20001,
                    'compare' => '>=',
                    'type' => 'NUMERIC'
                );
                break;
        }
    }

    if (!empty($rating)) {
        $meta_query[] = array(
            'key' => 'rating',
            'value' => floatval($rating),
            'compare' => '>=',
            'type' => 'DECIMAL'
        );
    }

    if (!empty($features)) {
        $meta_query[] = array(
            'key' => 'features',
            'value' => $features,
            'compare' => 'LIKE'
        );
    }

    if (count($meta_query) > 1) {
        $args['meta_query'] = $meta_query;
    }
    
    switch ($sort_by) {
        case 'title':
            $args['orderby'] = 'title';
            break;
        case 'rating':
            $args['orderby'] = 'meta_value_num';
            $args['meta_key'] = 'rating';
            break;
        case 'price':
            $args['orderby'] = 'meta_value_num';
            $args['meta_key'] = 'price_monthly';
            break;
        case 'views':
            $args['orderby'] = 'meta_value_num';
            $args['meta_key'] = 'view_count';
            break;
        default: // date
            $args['orderby'] = 'date';
            break;
    }
    $args['order'] = $sort_order;

    $query = new WP_Query($args);
    $tools = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            
            $tools[] = array(
                'id' => $post_id,
                'title' => get_the_title(),
                'permalink' => gi_safe_url(get_permalink()), // ヘルパー関数を使用
                'thumbnail' => get_the_post_thumbnail_url($post_id, 'medium'),
                'excerpt' => gi_safe_excerpt(get_the_excerpt(), 80), // ヘルパー関数を使用
                'rating' => gi_safe_get_meta($post_id, 'rating', '4.5'),
                'price' => gi_safe_get_meta($post_id, 'price_monthly', '無料'),
                'price_free' => gi_safe_get_meta($post_id, 'price_free', '0'),
            );
        }
    }
    wp_reset_postdata();

    ob_start();
    if (!empty($tools)) {
        echo '<div class="search-results-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">';
        foreach ($tools as $tool) {
            $price_display = $tool['price_free'] === '1' ? '無料プランあり' : '¥' . gi_safe_number_format(intval($tool['price'])) . '/月'; // ヘルパー関数を使用
            if (!is_numeric($tool['price'])) {
                $price_display = gi_safe_escape($tool['price']); // ヘルパー関数を使用
            }
            ?>
            <div class="tool-card bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <?php if ($tool['thumbnail']) : ?>
                                <img src="<?php echo esc_url($tool['thumbnail']); ?>" alt="<?php echo gi_safe_attr($tool['title']); ?>" class="w-full h-full object-cover rounded-xl">
                            <?php else : ?>
                                <i class="fas fa-tools text-white text-xl"></i>
                            <?php endif; ?>
                        </div>
                        <div class="flex items-center gap-1 text-yellow-500">
                            <?php 
                            $rating = floatval($tool['rating']);
                            $full_stars = floor($rating);
                            $half_star = ($rating - $full_stars) >= 0.5;
                            
                            for ($i = 0; $i < $full_stars; $i++) {
                                echo '⭐';
                            }
                            if ($half_star) {
                                echo '⭐';
                            }
                            ?>
                            <span class="text-sm text-gray-600 ml-1">(<?php echo gi_safe_escape($tool['rating']); ?>)</span>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">
                        <a href="<?php echo esc_url($tool['permalink']); ?>"><?php echo gi_safe_escape($tool['title']); ?></a>
                    </h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                        <?php echo $tool['excerpt']; ?>
                    </p>
                    <div class="flex items-center justify-between text-sm">
                        <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full font-medium">
                            <?php echo $price_display; ?>
                        </span>
                        <a href="<?php echo esc_url($tool['permalink']); ?>" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                            詳細を見る →
                        </a>
                    </div>
                </div>
            </div>
            <?php
        }
        echo '</div>';
    } else {
        echo '<div class="text-center py-20">
                <div class="w-32 h-32 bg-gradient-to-r from-indigo-400 via-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-8">
                    <i class="fas fa-tools text-white text-4xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-6">該当するツールが見つかりませんでした</h3>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg leading-relaxed">
                    検索条件を変更して再度お試しください。
                </p>
            </div>';
    }
    $html = ob_get_clean();

    wp_send_json_success(array(
        'html' => $html,
        'stats' => array(
            'total_found' => $query->found_posts,
            'current_page' => $page,
            'total_pages' => $query->max_num_pages,
        ),
    ));
}
add_action('wp_ajax_gi_load_tools', 'gi_ajax_load_tools');
add_action('wp_ajax_nopriv_gi_load_tools', 'gi_ajax_load_tools');

/**
 * AJAX - 申請のコツ読み込み処理（ヘルパー関数使用）
 */
function gi_ajax_load_grant_tips() {
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'gi_ajax_nonce')) {
        wp_send_json_error('セキュリティチェックに失敗しました。');
    }

    $args = array(
        'post_type'      => 'grant_tip',
        'posts_per_page' => 9,
        'paged'          => intval($_POST['page'] ?? 1),
        'post_status'    => 'publish',
    );

    if (!empty($_POST['s'])) {
        $args['s'] = sanitize_text_field($_POST['s']);
    }

    $tax_query = array();
    if (!empty($_POST['grant_tip_category'])) {
        $tax_query[] = array(
            'taxonomy' => 'grant_tip_category',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_POST['grant_tip_category']),
        );
    }
    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }

    $meta_query = array();
    if (!empty($_POST['difficulty'])) {
        $meta_query[] = array(
            'key'   => 'difficulty',
            'value' => sanitize_text_field($_POST['difficulty']),
            'compare' => '='
        );
    }
    if (!empty($meta_query)) {
        $args['meta_query'] = $meta_query;
    }

    $sort_by = sanitize_text_field($_POST['sort_by'] ?? 'date_desc');
    if ($sort_by === 'popular') {
        $args['orderby'] = 'comment_count';
        $args['order']   = 'DESC';
    } else {
        $args['orderby'] = 'date';
        $args['order']   = 'DESC';
    }

    $query = new WP_Query($args);

    ob_start();
    if ($query->have_posts()) {
        echo '<div class="search-results-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">';
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            ?>
            <div class="tip-card bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('thumbnail', array('class' => 'w-full h-full object-cover rounded-xl')); ?>
                            <?php else : ?>
                                <i class="fas fa-lightbulb text-white text-xl"></i>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 line-clamp-2">
                                <a href="<?php echo gi_safe_url(get_permalink()); ?>"><?php echo gi_safe_escape(get_the_title()); ?></a>
                            </h3>
                        </div>
                    </div>
                    
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                        <?php echo gi_safe_excerpt(get_the_excerpt(), 75); // ヘルパー関数を使用 ?>
                    </p>
                    
                    <div class="flex items-center justify-between text-sm">
                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full font-medium">
                            <?php echo gi_safe_escape(gi_safe_get_meta($post_id, 'difficulty', '初級')); ?>
                        </span>
                        <a href="<?php echo gi_safe_url(get_permalink()); ?>" class="text-yellow-600 hover:text-yellow-800 font-semibold">
                            詳細を見る →
                        </a>
                    </div>
                </div>
            </div>
            <?php
        }
        echo '</div>';
    } else {
        echo '<div class="text-center py-20">
                <div class="w-32 h-32 bg-gradient-to-r from-yellow-400 via-orange-500 to-red-500 rounded-full flex items-center justify-center mx-auto mb-8">
                    <i class="fas fa-lightbulb text-white text-5xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-6">該当するコツが見つかりませんでした</h3>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg leading-relaxed">
                    検索条件を変更して再度お試しください。
                </p>
            </div>';
    }
    $html = ob_get_clean();
    
    ob_start();
    if ($query->max_num_pages > 1) {
        echo paginate_links([
            'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
            'format' => '?paged=%#%',
            'current' => max(1, $args['paged']),
            'total' => $query->max_num_pages,
            'prev_text' => '<i class="fas fa-chevron-left"></i>',
            'next_text' => '<i class="fas fa-chevron-right"></i>',
            'type' => 'list',
        ]);
    }
    $pagination = ob_get_clean();

    wp_reset_postdata();

    wp_send_json_success(array(
        'html' => $html,
        'pagination' => $pagination,
        'found_posts' => $query->found_posts
    ));
}
add_action('wp_ajax_gi_load_grant_tips', 'gi_ajax_load_grant_tips');
add_action('wp_ajax_nopriv_gi_load_grant_tips', 'gi_ajax_load_grant_tips');

/**
 * 【修正】JavaScriptデバッグ情報出力（ヘルパー関数使用）
 */
function gi_add_debug_js() {
    if (is_page_template('archive-grant.php') || is_post_type_archive('grant') || is_page('grants')) {
        ?>
        <script>
        // デバッグ用：AJAX通信の詳細ログ
        window.giDebug = {
            logAjaxCall: function(action, data, response) {
                console.group('Grant Insight AJAX Debug');
                console.log('Action:', action);
                console.log('Request Data:', data);
                console.log('Response:', response);
                console.groupEnd();
            },
            
            testGrantsExist: function() {
                fetch('<?php echo gi_safe_url(admin_url('admin-ajax.php')); ?>', { // ヘルパー関数を使用
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: new URLSearchParams({
                        action: 'gi_debug_grants',
                        nonce: '<?php echo wp_create_nonce('gi_ajax_nonce'); ?>'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Grant Debug Info:', data);
                    if (data.success) {
                        console.log(`Total grants: ${data.data.total_grants.publish}`);
                        console.log(`Template exists: ${data.data.template_exists}`);
                        console.log('Recent grants:', data.data.recent_grants);
                        console.log('Helper functions:', data.data.helper_functions_available);
                    }
                })
                .catch(error => {
                    console.error('Debug test failed:', error);
                });
            }
        };
        
        // ページ読み込み完了後にデバッグ情報を出力
        document.addEventListener('DOMContentLoaded', function() {
            if (window.location.search.includes('debug=1')) {
                console.log('Grant Insight Debug Mode Enabled');
                window.giDebug.testGrantsExist();
            }
        });
        </script>
        <?php
    }
}
add_action('wp_footer', 'gi_add_debug_js');

/**
 * 【追加】AJAX エンドポイントの登録確認
 */
function gi_verify_ajax_endpoints() {
    $actions = array(
        'gi_load_grants',
        'gi_toggle_favorite', 
        'gi_debug_grants',
        'get_search_suggestions',
        'advanced_search',
        'grant_insight_search'
    );
    
    foreach ($actions as $action) {
        if (!has_action("wp_ajax_{$action}") && !has_action("wp_ajax_nopriv_{$action}")) {
            error_log("Grant Insight: Missing AJAX endpoint for action: {$action}");
        }
    }
}
add_action('init', 'gi_verify_ajax_endpoints');

/**
 * 【追加】AJAX処理のパフォーマンス監視
 */
function gi_ajax_performance_monitor() {
    if (defined('WP_DEBUG') && WP_DEBUG && isset($_POST['action']) && strpos($_POST['action'], 'gi_') === 0) {
        $start_time = microtime(true);
        
        add_action('wp_ajax_' . $_POST['action'], function() use ($start_time) {
            $execution_time = microtime(true) - $start_time;
            error_log("Grant Insight AJAX Performance: {$_POST['action']} took {$execution_time} seconds");
        }, 999);
    }
}
add_action('init', 'gi_ajax_performance_monitor');

/**
 * 【追加】クリーンアップ関数：古いAJAXログを削除
 */
function gi_cleanup_ajax_logs() {
    // 30日より古いアフィリエイトクリックログを削除
    $logs = get_option('gi_affiliate_clicks', array());
    if (is_array($logs)) {
        $cutoff_time = time() - (30 * 24 * 60 * 60); // 30日前
        $logs = array_filter($logs, function($log) use ($cutoff_time) {
            return isset($log['time']) && $log['time'] > $cutoff_time;
        });
        update_option('gi_affiliate_clicks', $logs);
    }
}

/**
 * 【追加】定期的なクリーンアップのスケジュール
 */
if (!wp_next_scheduled('gi_daily_cleanup')) {
    wp_schedule_event(time(), 'daily', 'gi_daily_cleanup');
}
add_action('gi_daily_cleanup', 'gi_cleanup_ajax_logs');

/**
 * 【追加】テーマ無効化時のクリーンアップ
 */
function gi_ajax_deactivation_cleanup() {
    wp_clear_scheduled_hook('gi_daily_cleanup');
}
register_deactivation_hook(__FILE__, 'gi_ajax_deactivation_cleanup');