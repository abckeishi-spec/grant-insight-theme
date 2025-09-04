/**
 * Grant Insight Enhanced - Main JavaScript (Tailwind CSS Play CDN対応版)
 * サイト全体の共通機能を担当する司令塔ファイル
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        // --- Tailwind CSS Play CDN対応 - サイト全体機能初期化 ---
        initTailwindMobileMenu();
        initTailwindAffiliateTracking();
        initTailwindSmoothScrolling();
        initTailwindLazyLoading();
        initTailwindSearchEnhancements();
        initTailwindCTAOptimization();
        initTailwindUserEngagement();
        initTailwindComponents();
        // AI Chatbot機能は削除されました
    });

    // AI Chatbot機能は削除されました（ユーザー要望により）

    /**
     * Tailwind CSS対応モバイルメニューの初期化
     */
    function initTailwindMobileMenu() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        const mobileNavMenu = document.querySelector('.mobile-nav-menu');
        const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');
        const mobileMenuClose = document.querySelector('.mobile-menu-close');

        function openMobileMenu() {
            if (mobileNavMenu) {
                // Tailwind classes for mobile menu animation
                mobileNavMenu.classList.remove('translate-x-full', 'opacity-0');
                mobileNavMenu.classList.add('translate-x-0', 'opacity-100');
                document.body.classList.add('overflow-hidden');
            }
            if (mobileMenu) {
                mobileMenu.classList.add('is-open');
                mobileMenu.classList.remove('hidden');
            }
            if (mobileMenuOverlay) {
                mobileMenuOverlay.classList.remove('opacity-0', 'invisible');
                mobileMenuOverlay.classList.add('opacity-50', 'visible');
            }
        }

        function closeMobileMenu() {
            if (mobileNavMenu) {
                mobileNavMenu.classList.add('translate-x-full', 'opacity-0');
                mobileNavMenu.classList.remove('translate-x-0', 'opacity-100');
                document.body.classList.remove('overflow-hidden');
            }
            if (mobileMenu) {
                setTimeout(() => {
                    mobileMenu.classList.remove('is-open');
                    mobileMenu.classList.add('hidden');
                }, 300);
            }
            if (mobileMenuOverlay) {
                mobileMenuOverlay.classList.add('opacity-0', 'invisible');
                mobileMenuOverlay.classList.remove('opacity-50', 'visible');
            }
        }

        // 各種ボタンのイベントリスナー（Tailwind対応）
        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', function() {
                openMobileMenu();

                // Tailwind icon toggle
                const hamburgerIcon = this.querySelector('.hamburger-icon');
                const closeIcon = this.querySelector('.close-icon');
                if (hamburgerIcon && closeIcon) {
                    hamburgerIcon.classList.toggle('hidden');
                    closeIcon.classList.toggle('hidden');
                }
            });
        }

        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                openMobileMenu();
            });
        }

        if (mobileMenuClose) {
            mobileMenuClose.addEventListener('click', closeMobileMenu);
        }

        if (mobileMenuOverlay) {
            mobileMenuOverlay.addEventListener('click', closeMobileMenu);
        }

        // レスポンシブ対応（Tailwind breakpoint準拠）
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) { // lg breakpoint
                closeMobileMenu();
            }
        });

        // ESCキーでメニューを閉じる
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileMenu && mobileMenu.classList.contains('is-open')) {
                closeMobileMenu();
            }
        });
    }

    /**
     * Tailwind CSS対応アフィリエイトクリック追跡
     */
    function initTailwindAffiliateTracking() {
        // アフィリエイトボタンのクリック追跡（Tailwind classes対応）
        $(document).on('click', '.affiliate-btn, .btn-affiliate, .affiliate-link', function(e) {
            const btn = $(this);
            const url = btn.attr('href');
            const postId = $('body').data('post-id') || 0;

            // Tailwind loading state
            btn.addClass('opacity-75 cursor-not-allowed pointer-events-none');
            
            // 追跡データを送信
            if (typeof gi_track_affiliate_click === 'function') {
                gi_track_affiliate_click(url, postId);
            }

            // Reset button state after delay
            setTimeout(() => {
                btn.removeClass('opacity-75 cursor-not-allowed pointer-events-none');
            }, 1000);
        });

        // 外部リンクの追跡（GA4 + Tailwind visual feedback）
        $(document).on('click', 'a[rel*="nofollow"], a[rel*="sponsored"], .external-link', function(e) {
            const link = $(this);
            const url = link.attr('href');
            
            // Tailwind click animation
            link.addClass('transform scale-95 transition-transform duration-150');
            setTimeout(() => {
                link.removeClass('transform scale-95');
            }, 150);

            if (typeof gtag !== 'undefined') {
                gtag('event', 'click', {
                    event_category: 'affiliate',
                    event_label: url,
                    value: 1
                });
            }
        });
    }

    /**
     * Tailwind CSS対応スムーススクロール
     */
    function initTailwindSmoothScrolling() {
        // アンカーリンクにTailwindクラスを動的追加
        $('a[href^="#"]').addClass('smooth-scroll-link transition-colors duration-200 hover:text-emerald-600');

        $('a[href^="#"]').on('click', function(e) {
            e.preventDefault();
            
            const link = $(this);
            const targetId = this.getAttribute('href');
            const target = $(targetId);
            
            if (target.length) {
                // Tailwind scroll animation
                link.addClass('text-emerald-600');
                
                $('html, body').animate({
                    scrollTop: target.offset().top - 80 // ヘッダー高さ考慮
                }, {
                    duration: 600,
                    easing: 'swing',
                    complete: function() {
                        // アニメーション完了後にクラスをリセット
                        setTimeout(() => {
                            link.removeClass('text-emerald-600');
                        }, 500);
                    }
                });

                // フォーカス管理（アクセシビリティ対応）
                target.attr('tabindex', '-1').focus();
            }
        });

        // ネイティブスムーススクロール（モダンブラウザ対応）
        if (CSS.supports('scroll-behavior', 'smooth')) {
            document.documentElement.style.scrollBehavior = 'smooth';
        }
    }

    /**
     * Tailwind CSS対応画像遅延読み込み
     */
    function initTailwindLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        
                        // Tailwind loading classes
                        img.classList.add('animate-pulse', 'bg-gray-200');
                        
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                        }
                        if (img.dataset.srcset) {
                            img.srcset = img.dataset.srcset;
                        }
                        
                        img.onload = function() {
                            // Tailwind fade-in animation
                            this.classList.remove('lazy', 'animate-pulse', 'bg-gray-200', 'opacity-0');
                            this.classList.add('loaded', 'opacity-100', 'transition-opacity', 'duration-300');
                        };
                        
                        img.onerror = function() {
                            // Error state with Tailwind classes
                            this.classList.remove('animate-pulse', 'bg-gray-200');
                            this.classList.add('bg-gray-100', 'border-2', 'border-dashed', 'border-gray-300');
                        };
                        
                        imageObserver.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.1
            });

            document.querySelectorAll('img[data-src], img.lazy').forEach(img => {
                img.classList.add('opacity-0');
                imageObserver.observe(img);
            });
        }
    }

    /**
     * Tailwind CSS対応検索機能強化
     */
    function initTailwindSearchEnhancements() {
        const searchInput = $('#search-input, #main-search-input, #grant-search');
        
        if (searchInput.length) {
            let searchTimeout;

            // Tailwind focus states
            searchInput.addClass('focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200');

            searchInput.on('input', function() {
                const input = $(this);
                clearTimeout(searchTimeout);
                const query = input.val();

                // Tailwind loading indicator
                if (query.length >= 2) {
                    input.addClass('bg-gray-50');
                    searchTimeout = setTimeout(() => {
                        fetchTailwindSearchSuggestions(query);
                    }, 300);
                } else {
                    input.removeClass('bg-gray-50');
                    $('#search-suggestions').addClass('hidden opacity-0').removeClass('block opacity-100');
                }
            });

            searchInput.on('focus', function() {
                $(this).addClass('ring-2 ring-emerald-500 border-emerald-500');
            }).on('blur', function() {
                setTimeout(() => {
                    $(this).removeClass('ring-2 ring-emerald-500 border-emerald-500');
                    $('#search-suggestions').addClass('hidden opacity-0').removeClass('block opacity-100');
                }, 200);
            });
        }

        // 検索フィルターの動的更新（Tailwind対応）
        $('#search-filters, .grant-filter').on('change', '.filter-checkbox, .filter-select, select', function() {
            const filter = $(this);
            
            // Tailwind active state
            if (filter.is(':checked') || filter.val()) {
                filter.closest('.form-group, .filter-group').addClass('border-emerald-500 bg-emerald-50');
            } else {
                filter.closest('.form-group, .filter-group').removeClass('border-emerald-500 bg-emerald-50');
            }
            
            updateTailwindSearchResults();
        });

        // 検索フォーム送信（Tailwind対応）
        $('#grant-search-form, #advanced-search-form').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const submitBtn = form.find('button[type="submit"]');
            
            // Tailwind loading state
            submitBtn.addClass('opacity-75 cursor-not-allowed').prop('disabled', true);
            submitBtn.find('.btn-text').addClass('hidden');
            submitBtn.find('.btn-loading').removeClass('hidden');
            
            updateTailwindSearchResults();
        });

        // 人気キーワードクリック（Tailwind対応）
        $(document).on('click', '.popular-keyword, .keyword-tag', function(e) {
            e.preventDefault();
            const keyword = $(this).data('keyword') || $(this).text().trim();
            
            // Tailwind click animation
            $(this).addClass('transform scale-95 bg-emerald-600 text-white transition-all duration-150');
            
            setTimeout(() => {
                $(this).removeClass('transform scale-95 bg-emerald-600 text-white');
                searchInput.first().val(keyword).focus();
                updateTailwindSearchResults();
            }, 150);
        });
    }

    /**
     * Tailwind対応検索候補取得
     */
    function fetchTailwindSearchSuggestions(query) {
        if (typeof gi_ajax === 'undefined') return;

        $.ajax({
            url: gi_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_search_suggestions',
                query: query,
                nonce: gi_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    displayTailwindSearchSuggestions(response.data);
                }
            },
            error: function() {
                console.error('Failed to fetch search suggestions');
            }
        });
    }

    /**
     * Tailwind対応検索候補表示
     */
    function displayTailwindSearchSuggestions(suggestions) {
        const suggestionsContainer = $('#search-suggestions');
        if (suggestionsContainer.length) {
            if (suggestions.length === 0) {
                suggestionsContainer.addClass('hidden opacity-0').removeClass('block opacity-100');
                return;
            }

            let html = '<div class="bg-white border border-gray-200 rounded-lg shadow-lg mt-1 max-h-64 overflow-y-auto">';
            suggestions.forEach((suggestion, index) => {
                html += `
                    <div class="suggestion-item px-4 py-3 hover:bg-emerald-50 cursor-pointer border-b border-gray-100 last:border-b-0 transition-colors duration-150" 
                         data-value="${suggestion.value}">
                        <div class="flex items-center">
                            <i class="fas fa-search text-gray-400 mr-3"></i>
                            <span class="text-gray-800">${suggestion.label}</span>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            
            suggestionsContainer.html(html).removeClass('hidden opacity-0').addClass('block opacity-100');

            // 候補クリックイベント（Tailwind対応）
            suggestionsContainer.find('.suggestion-item').on('click', function() {
                const value = $(this).data('value');
                $(this).addClass('bg-emerald-100');
                
                setTimeout(() => {
                    $('#search-input, #main-search-input, #grant-search').val(value);
                    suggestionsContainer.addClass('hidden opacity-0').removeClass('block opacity-100');
                    updateTailwindSearchResults();
                }, 100);
            });
        }
    }

    /**
     * カテゴリーフィルタリング最適化
     */
    function initCategoryFiltering() {
        // カテゴリー選択UI
        const categoryFilters = $('.category-filter-checkbox, input[name="category[]"]');
        const prefectureFilters = $('.prefecture-checkbox, input[name="prefecture[]"]');
        const amountRangeSlider = $('#amount-range-slider');
        const activeFiltersDisplay = $('#active-filters');
        
        // チェックボックス変更時のリアルタイム更新
        categoryFilters.on('change', function() {
            updateActiveFilters();
            updateSearchResultsWithFilters();
        });
        
        prefectureFilters.on('change', function() {
            updateActiveFilters();
            updateSearchResultsWithFilters();
        });
        
        // アクティブフィルター表示更新
        function updateActiveFilters() {
            const activeFilters = [];
            
            // カテゴリーフィルター収集
            categoryFilters.filter(':checked').each(function() {
                activeFilters.push({
                    type: 'category',
                    value: $(this).val(),
                    label: $(this).next('label').text() || $(this).val()
                });
            });
            
            // 都道府県フィルター収集
            prefectureFilters.filter(':checked').each(function() {
                activeFilters.push({
                    type: 'prefecture',
                    value: $(this).val(),
                    label: $(this).next('label').text() || $(this).val()
                });
            });
            
            // アクティブフィルター表示
            if (activeFilters.length > 0) {
                let html = '<div class="flex flex-wrap gap-2 mb-4">';
                activeFilters.forEach(filter => {
                    const colorClass = filter.type === 'category' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700';
                    html += `
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${colorClass}">
                            ${filter.label}
                            <button type="button" class="ml-2 hover:text-red-500 remove-filter" data-type="${filter.type}" data-value="${filter.value}">
                                <i class="fas fa-times"></i>
                            </button>
                        </span>
                    `;
                });
                html += '</div>';
                activeFiltersDisplay.html(html).removeClass('hidden');
            } else {
                activeFiltersDisplay.addClass('hidden');
            }
        }
        
        // フィルター削除ボタン
        $(document).on('click', '.remove-filter', function() {
            const type = $(this).data('type');
            const value = $(this).data('value');
            
            if (type === 'category') {
                categoryFilters.filter('[value="' + value + '"]').prop('checked', false);
            } else if (type === 'prefecture') {
                prefectureFilters.filter('[value="' + value + '"]').prop('checked', false);
            }
            
            updateActiveFilters();
            updateSearchResultsWithFilters();
        });
        
        // フィルター付き検索結果更新
        function updateSearchResultsWithFilters() {
            const categories = [];
            const prefectures = [];
            
            categoryFilters.filter(':checked').each(function() {
                categories.push($(this).val());
            });
            
            prefectureFilters.filter(':checked').each(function() {
                prefectures.push($(this).val());
            });
            
            // AJAX検索呼び出し
            if (typeof gi_ajax !== 'undefined') {
                $.ajax({
                    url: gi_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'gi_search',
                        categories: categories,
                        prefectures: prefectures,
                        search_term: $('#search-input').val() || '',
                        nonce: gi_ajax.nonce
                    },
                    beforeSend: function() {
                        $('#search-results-container').addClass('opacity-50');
                        $('#filter-loading').removeClass('hidden');
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#search-results-container').html(response.data.html);
                            $('#results-count').text(response.data.total);
                        }
                        $('#search-results-container').removeClass('opacity-50');
                        $('#filter-loading').addClass('hidden');
                    },
                    error: function() {
                        console.error('フィルタリング失敗');
                        $('#filter-loading').addClass('hidden');
                    }
                });
            }
        }
        
        // すべてクリアボタン
        $('#clear-all-filters').on('click', function() {
            categoryFilters.prop('checked', false);
            prefectureFilters.prop('checked', false);
            updateActiveFilters();
            updateSearchResultsWithFilters();
        });
    }
    
    // 検索エンハンスメント初期化に追加
    if ($('.category-filter-checkbox').length > 0 || $('input[name="category[]"]').length > 0) {
        initCategoryFiltering();
    }
    
    /**
     * Tailwind対応検索結果更新
     */
    function updateTailwindSearchResults() {
        const form = $('#advanced-search-form, #grant-search-form');
        let formData;

        if (form.length) {
            formData = form.serialize();
        } else {
            const searchQuery = $('#search-input, #main-search-input, #grant-search').val() || '';
            const prefecture = $('#prefecture-select').val() || '';
            const category = $('#category-select').val() || '';
            const amount = $('#amount-select').val() || '';
            const status = $('#status-select').val() || '';

            formData = $.param({
                search_query: searchQuery,
                prefecture: prefecture,
                category: category,
                amount: amount,
                status: status
            });
        }

        if (typeof gi_ajax === 'undefined') {
            console.error('gi_ajax is not defined');
            // Fallback: try to get ajax url from WordPress
            if (typeof ajaxurl !== 'undefined') {
                gi_ajax = {
                    ajax_url: ajaxurl,
                    nonce: ''
                };
            } else {
                console.error('No AJAX URL available');
                return;
            }
        }

        $.ajax({
            url: gi_ajax.ajax_url,
            type: 'POST',
            data: formData + '&action=gi_search&nonce=' + (gi_ajax.nonce || ''),
            beforeSend: function() {
                const containers = $('#search-results-container, #results-preview, #results-grid');
                containers.addClass('opacity-50 pointer-events-none').html(`
                    <div class="flex items-center justify-center p-12">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500 mr-3"></div>
                        <span class="text-gray-600">検索中...</span>
                    </div>
                `);
            },
            success: function(response) {
                const containers = $('#search-results-container, #results-preview, #results-grid');
                const submitBtns = $('button[type="submit"]');

                if (response.success) {
                    containers.html(response.data.html).removeClass('opacity-50 pointer-events-none');
                    $('#search-results-count, #results-count').text(response.data.count);

                    // 結果アニメーション
                    containers.find('.result-item, .grant-card').addClass('opacity-0 transform translate-y-4');
                    setTimeout(() => {
                        containers.find('.result-item, .grant-card').each(function(index) {
                            const item = $(this);
                            setTimeout(() => {
                                item.removeClass('opacity-0 translate-y-4').addClass('opacity-100 translate-y-0 transition-all duration-300');
                            }, index * 100);
                        });
                    }, 100);

                    $('#search-results-preview, #results-preview').removeClass('hidden').addClass('block');
                } else {
                    containers.html(`
                        <div class="text-center p-12">
                            <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-600">検索結果が見つかりませんでした。</p>
                        </div>
                    `).removeClass('opacity-50 pointer-events-none');
                }

                // Reset button states
                submitBtns.removeClass('opacity-75 cursor-not-allowed').prop('disabled', false);
                submitBtns.find('.btn-text').removeClass('hidden');
                submitBtns.find('.btn-loading').addClass('hidden');
            },
            error: function() {
                const containers = $('#search-results-container, #results-preview, #results-grid');
                containers.html(`
                    <div class="text-center p-12">
                        <i class="fas fa-exclamation-triangle text-4xl text-red-400 mb-4"></i>
                        <p class="text-gray-600">エラーが発生しました。時間をおいて再試行してください。</p>
                    </div>
                `).removeClass('opacity-50 pointer-events-none');

                // Reset button states
                const submitBtns = $('button[type="submit"]');
                submitBtns.removeClass('opacity-75 cursor-not-allowed').prop('disabled', false);
                submitBtns.find('.btn-text').removeClass('hidden');
                submitBtns.find('.btn-loading').addClass('hidden');
            }
        });
    }

    /**
     * Tailwind対応CTA最適化
     */
    function initTailwindCTAOptimization() {
        const floatingCTA = $('.floating-cta');
        if (floatingCTA.length) {
            // Tailwind classes for initial state
            floatingCTA.addClass('transform translate-y-full opacity-0 transition-all duration-300');

            $(window).on('scroll', function() {
                const scrollTop = $(window).scrollTop();
                const windowHeight = $(window).height();
                const documentHeight = $(document).height();

                if (scrollTop > documentHeight * 0.3 && scrollTop + windowHeight < documentHeight - 200) {
                    floatingCTA.removeClass('translate-y-full opacity-0').addClass('translate-y-0 opacity-100');
                } else {
                    floatingCTA.removeClass('translate-y-0 opacity-100').addClass('translate-y-full opacity-0');
                }
            });

            // CTA click animation
            floatingCTA.on('click', function() {
                $(this).addClass('transform scale-95 transition-transform duration-150');
                setTimeout(() => {
                    $(this).removeClass('transform scale-95');
                }, 150);
            });
        }
    }

    /**
     * Tailwind対応ユーザー行動分析
     */
    function initTailwindUserEngagement() {
        // スクロール進捗バー（Tailwind対応）
        const progressBar = $('<div class="fixed top-0 left-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-600 z-50 transition-all duration-100"></div>');
        $('body').append(progressBar);

        let maxScroll = 0;
        let milestones = {25: false, 50: false, 75: false, 100: false};

        $(window).on('scroll', function() {
            const scrollPercent = Math.round(($(window).scrollTop() / ($(document).height() - $(window).height())) * 100);
            
            // 進捗バー更新
            progressBar.css('width', scrollPercent + '%');

            // マイルストーン追跡
            for (const milestone in milestones) {
                if (scrollPercent >= milestone && !milestones[milestone]) {
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'scroll_depth', {
                            event_category: 'engagement',
                            event_label: `${milestone}%`,
                            value: milestone
                        });
                    }
                    milestones[milestone] = true;

                    // Tailwind toast notification for milestones (optional)
                    if (milestone == 75) {
                        showTailwindToast('記事の75%まで読んでいただき、ありがとうございます！', 'info');
                    }
                }
            }
        });

        // ページ滞在時間追跡
        let startTime = Date.now();
        let engagementTracked = false;

        window.addEventListener('beforeunload', function() {
            if (!engagementTracked) {
                const timeSpent = Math.round((Date.now() - startTime) / 1000);
                if (typeof gtag !== 'undefined' && timeSpent > 10) {
                    gtag('event', 'page_engagement', {
                        event_category: 'engagement',
                        event_label: 'time_spent',
                        value: timeSpent
                    });
                }
                engagementTracked = true;
            }
        });

        // クリック追跡（Tailwind visual feedback付き）
        $(document).on('click', '[data-track]', function() {
            const element = $(this);
            const trackData = element.data('track');
            
            // Tailwind click animation
            element.addClass('transform scale-95 transition-transform duration-150');
            setTimeout(() => {
                element.removeClass('transform scale-95');
            }, 150);

            if (typeof gtag !== 'undefined' && trackData) {
                gtag('event', 'click', {
                    event_category: 'ui_interaction',
                    event_label: trackData,
                    value: 1
                });
            }
        });
    }

    /**
     * Tailwind専用コンポーネント初期化
     */
    function initTailwindComponents() {
        // ドロップダウンメニュー
        $('.dropdown-toggle').on('click', function() {
            const dropdown = $(this).next('.dropdown-menu');
            dropdown.toggleClass('hidden opacity-0').toggleClass('block opacity-100');
        });

        // タブ機能
        $('.tab-button').on('click', function() {
            const tabId = $(this).data('tab');
            const tabGroup = $(this).closest('.tab-group');
            
            // アクティブタブの切り替え
            tabGroup.find('.tab-button').removeClass('bg-emerald-500 text-white').addClass('bg-gray-200 text-gray-700');
            $(this).removeClass('bg-gray-200 text-gray-700').addClass('bg-emerald-500 text-white');
            
            // タブコンテンツの切り替え
            tabGroup.find('.tab-content').addClass('hidden');
            $(`#${tabId}`).removeClass('hidden');
        });

        // モーダル
        $('.modal-trigger').on('click', function() {
            const modalId = $(this).data('modal');
            $(`#${modalId}`).removeClass('hidden opacity-0').addClass('flex opacity-100');
        });

        $('.modal-close, .modal-overlay').on('click', function() {
            $(this).closest('.modal').removeClass('flex opacity-100').addClass('hidden opacity-0');
        });

        // アコーディオン
        $('.accordion-header').on('click', function() {
            const content = $(this).next('.accordion-content');
            const icon = $(this).find('.accordion-icon');
            
            content.slideToggle();
            icon.toggleClass('rotate-180');
        });

        // トースト通知の自動削除
        setTimeout(() => {
            $('.toast-notification').each(function() {
                $(this).addClass('translate-x-full opacity-0');
                setTimeout(() => {
                    $(this).remove();
                }, 300);
            });
        }, 5000);
    }

    /**
     * Tailwind Toast通知
     */
    function showTailwindToast(message, type = 'info', duration = 5000) {
        const iconMap = {
            'success': 'fa-check-circle text-green-500',
            'error': 'fa-exclamation-circle text-red-500',
            'warning': 'fa-exclamation-triangle text-yellow-500',
            'info': 'fa-info-circle text-blue-500'
        };

        const toast = $(`
            <div class="toast-notification fixed bottom-4 right-4 bg-white border border-gray-200 rounded-lg shadow-lg p-4 transform translate-x-full opacity-0 transition-all duration-300 z-50 max-w-sm">
                <div class="flex items-center">
                    <i class="fas ${iconMap[type]} mr-3 text-lg"></i>
                    <span class="text-gray-800 flex-1">${message}</span>
                    <button class="toast-close ml-3 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `);

        $('body').append(toast);

        setTimeout(() => {
            toast.removeClass('translate-x-full opacity-0');
        }, 100);

        const autoRemove = setTimeout(() => {
            toast.addClass('translate-x-full opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, duration);

        toast.find('.toast-close').on('click', () => {
            clearTimeout(autoRemove);
            toast.addClass('translate-x-full opacity-0');
            setTimeout(() => toast.remove(), 300);
        });
    }

    // --- グローバルAPI（Tailwind対応版） ---
    window.GrantInsight = {
        updateSearch: function() {
            updateTailwindSearchResults();
        },

        showNotification: function(message, type = 'info') {
            showTailwindToast(message, type);
        },

        showToast: showTailwindToast,

        // 🆕 AI Chatbot API
        openChatbot: function() {
            const modal = document.querySelector('.chatbot-modal');
            if (modal) modal.classList.add('active');
        },

        closeChatbot: function() {
            const modal = document.querySelector('.chatbot-modal');
            if (modal) modal.classList.remove('active');
        },

        debug: function() {
            console.log('Grant Insight Main JavaScript (Tailwind CSS Play CDN Edition) Status:');
            console.log('- Tailwind Mobile Menu: ✅ Initialized');
            console.log('- Tailwind Affiliate Tracking: ✅ Initialized');
            console.log('- Tailwind Smooth Scrolling: ✅ Initialized');
            console.log('- Tailwind Lazy Loading: ✅ Initialized');
            console.log('- Tailwind Search Enhancements: ✅ Initialized');
            console.log('- Tailwind CTA Optimization: ✅ Initialized');
            console.log('- Tailwind User Engagement: ✅ Initialized');
            console.log('- Tailwind Components: ✅ Initialized');
            console.log('- 🆕 AI Chatbot: ✅ Initialized');
        }
    };

    /**
     * アフィリエイトクリック追跡AJAX関数（Tailwind対応）
     */
    window.gi_track_affiliate_click = function(url, postId = 0) {
        if (typeof gi_ajax === 'undefined') return;

        $.ajax({
            url: gi_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'track_affiliate_click',
                url: url,
                post_id: postId,
                nonce: gi_ajax.nonce
            },
            success: function(response) {
                console.log('Affiliate click tracked:', url);
                showTailwindToast('リンクを記録しました', 'success', 2000);
            },
            error: function(xhr, status, error) {
                console.error('Failed to track affiliate click:', error);
            }
        });
    };

})(jQuery);