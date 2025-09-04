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
        initAIChatbot(); // 🆕 AI Chatbot初期化を追加
    });

    /**
     * 🆕 AI Chatbot完全初期化関数
     */
    function initAIChatbot() {
        console.log('AI Chatbot初期化開始...');
        
        const chatbotToggle = document.querySelector('.chatbot-toggle');
        const chatbotModal = document.querySelector('.chatbot-modal');
        const chatbotClose = document.querySelector('.chatbot-close');
        const chatbotInput = document.querySelector('.chatbot-input');
        const chatbotSend = document.querySelector('.chatbot-send');
        const chatbotMessages = document.querySelector('.chatbot-messages');

        if (!chatbotToggle || !chatbotModal) {
            console.warn('AI Chatbot要素が見つかりません');
            return;
        }

        console.log('AI Chatbot要素が見つかりました');

        // チャットボット開閉機能
        chatbotToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('チャットボットトグルクリック');
            
            if (chatbotModal.classList.contains('active')) {
                closeChatbot();
            } else {
                openChatbot();
            }
        });

        // チャットボット閉じるボタン
        if (chatbotClose) {
            chatbotClose.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                closeChatbot();
            });
        }

        // モーダル外クリックで閉じる
        document.addEventListener('click', function(e) {
            if (chatbotModal.classList.contains('active') && 
                !chatbotModal.contains(e.target) && 
                !chatbotToggle.contains(e.target)) {
                closeChatbot();
            }
        });

        // ESCキーで閉じる
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && chatbotModal.classList.contains('active')) {
                closeChatbot();
            }
        });

        // メッセージ送信機能
        if (chatbotSend && chatbotInput) {
            chatbotSend.addEventListener('click', function(e) {
                e.preventDefault();
                sendMessage();
            });

            chatbotInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    sendMessage();
                }
            });
        }

        // チャットボット開く関数
        function openChatbot() {
            chatbotModal.classList.add('active');
            if (chatbotInput) {
                setTimeout(() => {
                    chatbotInput.focus();
                }, 300);
            }
            
            // 通知バッジを隠す
            const notification = document.querySelector('.chatbot-notification');
            if (notification) {
                notification.style.display = 'none';
            }
            
            console.log('チャットボット opened');
        }

        // チャットボット閉じる関数
        function closeChatbot() {
            chatbotModal.classList.remove('active');
            console.log('チャットボット closed');
        }

        // メッセージ送信関数
        function sendMessage() {
            if (!chatbotInput || !chatbotMessages) return;
            
            const message = chatbotInput.value.trim();
            if (!message) return;

            console.log('メッセージ送信:', message);

            // ユーザーメッセージを追加
            addMessage(message, 'user');
            
            // 入力フィールドをクリア
            chatbotInput.value = '';
            
            // ローディング表示
            showTypingIndicator();
            
            // ボタンを一時的に無効化
            chatbotSend.disabled = true;
            
            // AI応答をシミュレート（実際のAPI連携時は置き換え）
            setTimeout(() => {
                hideTypingIndicator();
                chatbotSend.disabled = false;
                
                const responses = getAIResponse(message);
                addMessage(responses, 'bot');
            }, 1500);
        }

        // メッセージを追加する関数
        function addMessage(text, sender) {
            if (!chatbotMessages) return;
            
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${sender}`;
            messageDiv.textContent = text;
            
            chatbotMessages.appendChild(messageDiv);
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
            
            console.log(`${sender}メッセージ追加:`, text);
        }

        // タイピングインジケーター表示
        function showTypingIndicator() {
            if (!chatbotMessages) return;
            
            const typingDiv = document.createElement('div');
            typingDiv.className = 'typing-indicator';
            typingDiv.innerHTML = `
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            `;
            
            chatbotMessages.appendChild(typingDiv);
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        }

        // タイピングインジケーター非表示
        function hideTypingIndicator() {
            if (!chatbotMessages) return;
            
            const typingIndicator = chatbotMessages.querySelector('.typing-indicator');
            if (typingIndicator) {
                typingIndicator.remove();
            }
        }

        // AI応答生成（実際のAPI連携時は置き換え）
        function getAIResponse(userMessage) {
            const responses = {
                'こんにちは': 'こんにちは！助成金に関するご質問がございましたら、お気軽にお聞かせください。',
                '助成金': '助成金についてお調べですね。どのような用途の助成金をお探しでしょうか？事業拡大、研究開発、雇用創出など、具体的な目的を教えてください。',
                '補助金': '補助金制度について説明いたします。補助金は返済不要の資金支援で、様々な条件があります。どのような分野の補助金に興味がおありですか？',
                '申請': '助成金・補助金の申請についてですね。申請には事前準備が重要です。必要書類の準備や申請期限の確認など、具体的にお知りになりたいことはありますか？',
                '条件': '助成金・補助金には様々な条件があります。事業規模、業種、地域、用途などによって異なります。どのような条件について詳しく知りたいですか？',
                '金額': '助成金・補助金の金額は制度によって大きく異なります。数万円から数億円まで幅広くあります。どのような用途でお考えでしょうか？',
                'ありがとう': 'どういたしまして！他にも助成金や補助金について疑問がございましたら、いつでもお声かけください。',
                'さようなら': 'ありがとうございました！また何かご質問がございましたら、お気軽にご相談ください。'
            };

            // キーワードマッチング
            for (let keyword in responses) {
                if (userMessage.includes(keyword)) {
                    return responses[keyword];
                }
            }

            // デフォルト応答
            return 'ご質問ありがとうございます。助成金・補助金に関する詳しい情報については、具体的なご質問をいただけますと、より詳細にお答えできます。例えば「IT導入補助金について教えて」や「創業時に使える助成金は？」などお聞かせください。';
        }

        console.log('AI Chatbot初期化完了');
    }

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
            return;
        }

        $.ajax({
            url: gi_ajax.ajax_url,
            type: 'POST',
            data: formData + '&action=advanced_search&nonce=' + gi_ajax.nonce,
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