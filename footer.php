<?php
/**
 * The template for displaying the footer
 * フッターファイル（AIアシスタント統合版 - Tailwind CSS Play CDN対応）
 */

// 必要なヘルパー関数を定義
if (!function_exists('gi_get_sns_urls')) {
    function gi_get_sns_urls() {
        return [
            'twitter' => get_theme_mod('sns_twitter_url', ''),
            'facebook' => get_theme_mod('sns_facebook_url', ''),
            'linkedin' => get_theme_mod('sns_linkedin_url', ''),
            'instagram' => get_theme_mod('sns_instagram_url', ''),
            'youtube' => get_theme_mod('sns_youtube_url', '')
        ];
    }
}
?>

<!-- Tailwind CSS Play CDNの読み込み（ページのhead部分に配置） -->
<?php if (!wp_script_is('tailwind-cdn', 'enqueued')): ?>
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                animation: {
                    'pulse': 'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    'blob': 'blob 7s infinite',
                    'fade-in-up': 'fadeInUp 0.6s ease-out',
                    'bounce-gentle': 'bounceGentle 2s ease-in-out infinite'
                },
                keyframes: {
                    blob: {
                        '0%': {
                            transform: 'translate(0px, 0px) scale(1)'
                        },
                        '33%': {
                            transform: 'translate(30px, -50px) scale(1.1)'
                        },
                        '66%': {
                            transform: 'translate(-20px, 20px) scale(0.9)'
                        },
                        '100%': {
                            transform: 'translate(0px, 0px) scale(1)'
                        }
                    },
                    fadeInUp: {
                        '0%': {
                            opacity: '0',
                            transform: 'translateY(30px)'
                        },
                        '100%': {
                            opacity: '1',
                            transform: 'translateY(0)'
                        }
                    },
                    bounceGentle: {
                        '0%, 100%': {
                            transform: 'translateY(-5%)',
                            animationTimingFunction: 'cubic-bezier(0.8, 0, 1, 1)'
                        },
                        '50%': {
                            transform: 'translateY(0)',
                            animationTimingFunction: 'cubic-bezier(0, 0, 0.2, 1)'
                        }
                    }
                },
                backgroundImage: {
                    'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                }
            }
        }
    }
</script>
<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<?php endif; ?>

    </main>

    <!-- AIアシスタント（全ページ共通） -->
    <div class="ai-assistant-container fixed bottom-6 right-6 z-50 select-none">
        <div class="ai-assistant-bubble bg-gradient-to-r from-blue-600 to-indigo-700 text-white p-4 rounded-2xl shadow-xl max-w-xs relative mb-4 transform transition-all duration-300 opacity-0 scale-95" id="ai-bubble" style="display: none;">
            <div class="absolute bottom-0 right-4 w-0 h-0 border-l-8 border-l-transparent border-r-8 border-r-transparent border-t-8 border-t-blue-600 transform translate-y-full"></div>
            <div class="flex items-start gap-3">
                <div class="ai-icon w-8 h-8 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-robot text-sm"></i>
                </div>
                <div class="ai-message">
                    <p class="text-sm font-medium mb-1">最適な助成金探し、お手伝いします！</p>
                    <p class="text-xs text-blue-100">気になるキーワードをどうぞ。</p>
                </div>
            </div>
            <button class="ai-bubble-close absolute top-2 right-2 w-6 h-6 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-all duration-200 hover:rotate-90" onclick="closeAiBubble()">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
        
        <div class="ai-assistant-avatar w-16 h-16 bg-gradient-to-br from-blue-500 via-indigo-600 to-purple-700 rounded-full flex items-center justify-center text-white text-2xl cursor-pointer shadow-2xl hover:shadow-blue-500/25 transition-all duration-300 hover:scale-110 animate-bounce-gentle relative group" id="ai-avatar">
            <i class="fas fa-robot transition-transform duration-200 group-hover:rotate-12"></i>
            <!-- リング効果 -->
            <div class="absolute inset-0 rounded-full bg-gradient-to-br from-blue-500 via-indigo-600 to-purple-700 animate-pulse opacity-75 scale-110 blur-sm"></div>
        </div>
    </div>

    <footer class="site-footer bg-gray-900 text-gray-300 py-16 relative overflow-hidden">
        <!-- 背景アニメーション -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute w-64 h-64 bg-indigo-500 rounded-full -bottom-32 -left-32 mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
            <div class="absolute w-72 h-72 bg-purple-500 rounded-full -bottom-16 -right-16 mix-blend-multiply filter blur-xl opacity-70 animate-blob" style="animation-delay: 2s;"></div>
            <div class="absolute w-80 h-80 bg-pink-500 rounded-full -top-32 left-1/4 mix-blend-multiply filter blur-xl opacity-70 animate-blob" style="animation-delay: 4s;"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12">
                <!-- サイト情報 -->
                <div class="lg:col-span-2 animate-fade-in-up">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="text-4xl font-extrabold bg-gradient-to-r from-blue-400 via-purple-500 to-pink-500 bg-clip-text text-transparent mb-6 block hover:scale-105 transition-transform duration-200">
                        <?php bloginfo('name'); ?>
                    </a>
                    <p class="text-gray-400 leading-relaxed mb-6 text-base">
                        AIを活用した次世代の補助金・助成金プラットフォーム。あなたの事業に最適な情報を瞬時に発見し、成長を加速させます。
                    </p>
                    <div class="flex space-x-6">
                        <?php
                        $sns_urls = gi_get_sns_urls();
                        $sns_icons = [
                            'twitter' => 'fab fa-twitter',
                            'facebook' => 'fab fa-facebook-f', 
                            'linkedin' => 'fab fa-linkedin-in',
                            'instagram' => 'fab fa-instagram',
                            'youtube' => 'fab fa-youtube'
                        ];
                        ?>
                        <?php foreach ($sns_urls as $platform => $url): ?>
                            <?php if (!empty($url)): ?>
                                <a href="<?php echo esc_url($url); ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="text-gray-400 hover:text-white transition-all duration-200 transform hover:scale-110 hover:-translate-y-1">
                                    <i class="<?php echo $sns_icons[$platform]; ?> text-2xl"></i>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- 補助金を探す -->
                <div class="animate-fade-in-up" style="animation-delay: 0.1s;">
                    <h4 class="font-bold text-white mb-5 flex items-center text-lg">
                        <i class="fas fa-search mr-3 text-indigo-400"></i>補助金を探す
                    </h4>
                    <ul class="space-y-3 text-gray-400 text-sm">
                        <li>
                            <a href="<?php echo esc_url(home_url('/grants/')); ?>" 
                               class="hover:text-white transition-all duration-200 hover:translate-x-2 block transform hover:text-indigo-300">
                                助成金一覧
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/grants/?category=it')); ?>" 
                               class="hover:text-white transition-all duration-200 hover:translate-x-2 block transform hover:text-indigo-300">
                                IT・デジタル化
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/grants/?category=manufacturing')); ?>" 
                               class="hover:text-white transition-all duration-200 hover:translate-x-2 block transform hover:text-indigo-300">
                                ものづくり・製造業
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/grants/?category=startup')); ?>" 
                               class="hover:text-white transition-all duration-200 hover:translate-x-2 block transform hover:text-indigo-300">
                                創業・起業
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/grants/?category=employment')); ?>" 
                               class="hover:text-white transition-all duration-200 hover:translate-x-2 block transform hover:text-indigo-300">
                                雇用・人材育成
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/grants/?category=environment')); ?>" 
                               class="hover:text-white transition-all duration-200 hover:translate-x-2 block transform hover:text-indigo-300">
                                環境・省エネ
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- ツール・サービス -->
                <div class="animate-fade-in-up" style="animation-delay: 0.2s;">
                    <h4 class="font-bold text-white mb-5 flex items-center text-lg">
                        <i class="fas fa-tools mr-3 text-emerald-400"></i>ツール・サービス
                    </h4>
                    <ul class="space-y-3 text-gray-400 text-sm">
                        <li>
                            <a href="<?php echo esc_url(home_url('/tools/')); ?>" 
                               class="hover:text-white transition-all duration-200 hover:translate-x-2 block transform hover:text-emerald-300">
                                診断ツール
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/case-studies/')); ?>" 
                               class="hover:text-white transition-all duration-200 hover:translate-x-2 block transform hover:text-emerald-300">
                                成功事例
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/grant-tips/')); ?>" 
                               class="hover:text-white transition-all duration-200 hover:translate-x-2 block transform hover:text-emerald-300">
                                申請のコツ
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/ai/chat/')); ?>" 
                               class="hover:text-white transition-all duration-200 hover:translate-x-2 block transform hover:text-emerald-300">
                                AIチャット
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/experts/')); ?>" 
                               class="hover:text-white transition-all duration-200 hover:translate-x-2 block transform hover:text-emerald-300">
                                専門家相談
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- サポート -->
                <div class="animate-fade-in-up" style="animation-delay: 0.3s;">
                    <h4 class="font-bold text-white mb-5 flex items-center text-lg">
                        <i class="fas fa-info-circle mr-3 text-purple-400"></i>サポート
                    </h4>
                    <ul class="space-y-3 text-gray-400 text-sm">
                        <li>
                            <a href="<?php echo esc_url(home_url('/about/')); ?>" 
                               class="hover:text-white transition-all duration-200 hover:translate-x-2 block transform hover:text-purple-300">
                                Grant Insightとは
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/faq/')); ?>" 
                               class="hover:text-white transition-all duration-200 hover:translate-x-2 block transform hover:text-purple-300">
                                よくある質問
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/contact/')); ?>" 
                               class="hover:text-white transition-all duration-200 hover:translate-x-2 block transform hover:text-purple-300">
                                お問い合わせ
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/privacy/')); ?>" 
                               class="hover:text-white transition-all duration-200 hover:translate-x-2 block transform hover:text-purple-300">
                                プライバシーポリシー
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/terms/')); ?>" 
                               class="hover:text-white transition-all duration-200 hover:translate-x-2 block transform hover:text-purple-300">
                                利用規約
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- フッター下部 -->
            <div class="border-t border-gray-700 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center animate-fade-in-up" style="animation-delay: 0.4s;">
                <p class="text-gray-500 text-sm mb-4 md:mb-0">
                    &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.
                </p>
                <div class="flex items-center space-x-6 text-sm text-gray-500">
                    <span class="flex items-center hover:text-emerald-400 transition-colors duration-200">
                        <i class="fas fa-shield-alt mr-2 text-emerald-400"></i>SSL暗号化通信
                    </span>
                    <span class="flex items-center hover:text-blue-400 transition-colors duration-200">
                        <i class="fas fa-lock mr-2 text-blue-400"></i>個人情報保護
                    </span>
                    <span class="flex items-center hover:text-purple-400 transition-colors duration-200">
                        <i class="fas fa-award mr-2 text-purple-400"></i>専門家監修
                    </span>
                </div>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>

    <!-- AIアシスタント JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const aiAvatar = document.getElementById('ai-avatar');
        const aiBubble = document.getElementById('ai-bubble');
        let bubbleShown = false;
        let bubbleTimeout;
        
        // 5秒後に吹き出しを表示（初回のみ）
        setTimeout(function() {
            if (!bubbleShown && !localStorage.getItem('ai_bubble_dismissed')) {
                showAiBubble();
                bubbleShown = true;
                
                // 10秒後に自動で非表示
                bubbleTimeout = setTimeout(function() {
                    hideAiBubble();
                }, 10000);
            }
        }, 5000);
        
        // アバターをクリックしたらAIチャットページに遷移
        aiAvatar.addEventListener('click', function() {
            // クリックエフェクト
            aiAvatar.style.transform = 'scale(0.9)';
            setTimeout(() => {
                aiAvatar.style.transform = '';
            }, 150);
            
            // 吹き出しが表示されている場合は非表示にする
            if (aiBubble.style.display !== 'none') {
                hideAiBubble();
            }
            
            // AIチャットページに遷移
            setTimeout(() => {
                window.location.href = '<?php echo esc_url(home_url('/ai/chat/')); ?>';
            }, 200);
        });
        
        // アバターにホバーしたら吹き出しを表示
        aiAvatar.addEventListener('mouseenter', function() {
            clearTimeout(bubbleTimeout);
            if (!bubbleShown || aiBubble.style.display === 'none') {
                showAiBubble();
                bubbleShown = true;
            }
        });
        
        // アバターからマウスが離れたら2秒後に非表示
        aiAvatar.addEventListener('mouseleave', function() {
            bubbleTimeout = setTimeout(function() {
                hideAiBubble();
            }, 2000);
        });
        
        // 吹き出しにホバーしたらタイムアウトをクリア
        aiBubble.addEventListener('mouseenter', function() {
            clearTimeout(bubbleTimeout);
        });
        
        // 吹き出しからマウスが離れたら2秒後に非表示
        aiBubble.addEventListener('mouseleave', function() {
            bubbleTimeout = setTimeout(function() {
                hideAiBubble();
            }, 2000);
        });
        
        function showAiBubble() {
            aiBubble.style.display = 'block';
            // フェードイン効果
            requestAnimationFrame(() => {
                aiBubble.classList.remove('opacity-0', 'scale-95');
                aiBubble.classList.add('opacity-100', 'scale-100');
            });
        }
        
        function hideAiBubble() {
            aiBubble.classList.remove('opacity-100', 'scale-100');
            aiBubble.classList.add('opacity-0', 'scale-95');
            setTimeout(function() {
                aiBubble.style.display = 'none';
            }, 300);
        }
        
        // グローバル関数として公開
        window.closeAiBubble = function() {
            hideAiBubble();
            localStorage.setItem('ai_bubble_dismissed', 'true');
            clearTimeout(bubbleTimeout);
        };
        
        // キーボードショートカット（Alt + A でAIアシスタント起動）
        document.addEventListener('keydown', function(e) {
            if (e.altKey && e.key === 'a') {
                e.preventDefault();
                aiAvatar.click();
            }
        });
        
        // スクロール時のアニメーション
        let lastScrollY = window.scrollY;
        let ticking = false;
        
        function updateAvatarOnScroll() {
            const currentScrollY = window.scrollY;
            
            if (currentScrollY > lastScrollY && currentScrollY > 100) {
                // 下にスクロール時は少し小さく
                aiAvatar.style.transform = 'scale(0.85)';
                aiAvatar.style.opacity = '0.8';
            } else {
                // 上にスクロール時は元のサイズ
                aiAvatar.style.transform = 'scale(1)';
                aiAvatar.style.opacity = '1';
            }
            
            lastScrollY = currentScrollY;
            ticking = false;
        }
        
        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(updateAvatarOnScroll);
                ticking = true;
            }
        });
        
        // ページ読み込み完了時のアニメーション調整
        setTimeout(function() {
            aiAvatar.classList.remove('animate-bounce-gentle');
            aiAvatar.classList.add('animate-pulse');
        }, 3000);
        
        // フェードインアニメーション遅延の実装
        const animatedElements = document.querySelectorAll('.animate-fade-in-up');
        animatedElements.forEach((el, index) => {
            el.style.animationDelay = `${index * 0.1}s`;
        });
        
        // Intersection Observer for scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                }
            });
        }, observerOptions);
        
        animatedElements.forEach(el => {
            el.style.animationFillMode = 'both';
            el.style.animationPlayState = 'paused';
            observer.observe(el);
        });
    });
    </script>

</body>
</html>