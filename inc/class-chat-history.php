<?php
/**
 * WordPressネイティブチャット履歴管理クラス
 * 
 * @package WordPress_AI_Chatbot
 * @version 2.0.0
 * @author 中澤圭志
 */

if (!defined('ABSPATH')) {
    exit;
}

class Chat_History {
    
    private $session_key = 'ai_chat_history';
    private $max_history_length = 50;
    private $conversation_timeout = 3600; // 1時間
    
    /**
     * コンストラクタ
     */
    public function __construct() {
        // WordPressのネイティブなセッション管理を使用
        if (!function_exists('wp_cache_get')) {
            // キャッシュが利用可能な場合のみ初期化
            $this->initialize_session_storage();
        }
        
        // 古い会話履歴のクリーンアップ
        add_action('init', array($this, 'cleanup_old_conversations'));
    }
    
    /**
     * セッションストレージの初期化
     */
    private function initialize_session_storage() {
        // WordPress Transients APIを使用
        // これはデータベースベースの一時的なストレージ
        if (!wp_cache_get($this->session_key . '_initialized', 'ai_chat')) {
            wp_cache_set($this->session_key . '_initialized', true, 'ai_chat', $this->conversation_timeout);
        }
    }
    
    /**
     * メッセージを追加
     */
    public function add_message($message, $type = 'user', $user_id = null) {
        if (empty($message)) {
            return false;
        }
        
        $user_id = $user_id ?: get_current_user_id();
        $timestamp = current_time('mysql');
        
        $chat_data = array(
            'message' => sanitize_text_field($message),
            'type' => sanitize_text_field($type),
            'timestamp' => $timestamp,
            'message_id' => uniqid('msg_')
        );
        
        // ユーザーベースの履歴管理
        if ($user_id > 0) {
            return $this->add_user_message($user_id, $chat_data);
        } else {
            return $this->add_guest_message($chat_data);
        }
    }
    
    /**
     * ユーザーメッセージを追加
     */
    private function add_user_message($user_id, $chat_data) {
        $history = get_user_meta($user_id, $this->session_key, true);
        if (!is_array($history)) {
            $history = array();
        }
        
        $history[] = $chat_data;
        
        // 履歴の長さ制限
        if (count($history) > $this->max_history_length) {
            $history = array_slice($history, -$this->max_history_length);
        }
        
        return update_user_meta($user_id, $this->session_key, $history);
    }
    
    /**
     * ゲストメッセージを追加（一時的なストレージ）
     */
    private function add_guest_message($chat_data) {
        $guest_id = $this->get_guest_id();
        $transient_key = $this->session_key . '_guest_' . $guest_id;
        
        $history = get_transient($transient_key);
        if (!is_array($history)) {
            $history = array();
        }
        
        $history[] = $chat_data;
        
        // 履歴の長さ制限
        if (count($history) > $this->max_history_length) {
            $history = array_slice($history, -$this->max_history_length);
        }
        
        return set_transient($transient_key, $history, $this->conversation_timeout);
    }
    
    /**
     * 会話履歴を取得
     */
    public function get_history($user_id = null) {
        $user_id = $user_id ?: get_current_user_id();
        
        if ($user_id > 0) {
            return $this->get_user_history($user_id);
        } else {
            return $this->get_guest_history();
        }
    }
    
    /**
     * ユーザーの会話履歴を取得
     */
    private function get_user_history($user_id) {
        $history = get_user_meta($user_id, $this->session_key, true);
        return is_array($history) ? $history : array();
    }
    
    /**
     * ゲストの会話履歴を取得
     */
    private function get_guest_history() {
        $guest_id = $this->get_guest_id();
        $transient_key = $this->session_key . '_guest_' . $guest_id;
        
        $history = get_transient($transient_key);
        return is_array($history) ? $history : array();
    }
    
    /**
     * ゲストIDを生成
     */
    private function get_guest_id() {
        // IPアドレスとユーザーエージェントを使用
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
        $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown';
        
        return substr(md5($ip . $ua), 0, 16);
    }
    
    /**
     * 会話履歴をクリア
     */
    public function clear_history($user_id = null) {
        $user_id = $user_id ?: get_current_user_id();
        
        if ($user_id > 0) {
            return delete_user_meta($user_id, $this->session_key);
        } else {
            return $this->clear_guest_history();
        }
    }
    
    /**
     * ゲストの会話履歴をクリア
     */
    private function clear_guest_history() {
        $guest_id = $this->get_guest_id();
        $transient_key = $this->session_key . '_guest_' . $guest_id;
        return delete_transient($transient_key);
    }
    
    /**
     * 古い会話履歴をクリーンアップ
     */
    public function cleanup_old_conversations() {
        // 古いトランジェントを削除（1時間以上前のもの）
        global $wpdb;
        
        $expired_time = time() - $this->conversation_timeout;
        
        $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s AND option_name LIKE %s",
            $wpdb->esc_like('_transient_' . $this->session_key) . '%',
            $wpdb->esc_like('_transient_timeout_' . $this->session_key) . '%'
        ));
        
        return true;
    }
    
    /**
     * 統計情報を取得
     */
    public function get_stats($user_id = null) {
        $user_id = $user_id ?: get_current_user_id();
        $history = $this->get_history($user_id);
        
        if (!is_array($history) || empty($history)) {
            return array(
                'total_messages' => 0,
                'user_messages' => 0,
                'ai_messages' => 0,
                'first_message' => null,
                'last_message' => null
            );
        }
        
        $total_messages = count($history);
        $user_messages = 0;
        $ai_messages = 0;
        
        foreach ($history as $message) {
            if (isset($message['type'])) {
                if ($message['type'] === 'user') {
                    $user_messages++;
                } elseif ($message['type'] === 'ai') {
                    $ai_messages++;
                }
            }
        }
        
        return array(
            'total_messages' => $total_messages,
            'user_messages' => $user_messages,
            'ai_messages' => $ai_messages,
            'first_message' => isset($history[0]['timestamp']) ? $history[0]['timestamp'] : null,
            'last_message' => isset($history[$total_messages - 1]['timestamp']) ? $history[$total_messages - 1]['timestamp'] : null
        );
    }
    
    /**
     * 履歴をエクスポート
     */
    public function export_history($format = 'json', $user_id = null) {
        $user_id = $user_id ?: get_current_user_id();
        $history = $this->get_history($user_id);
        
        if (!is_array($history) || empty($history)) {
            return false;
        }
        
        switch ($format) {
            case 'json':
                return json_encode($history, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                
            case 'csv':
                return $this->export_to_csv($history);
                
            case 'txt':
                return $this->export_to_txt($history);
                
            default:
                return false;
        }
    }
    
    /**
     * CSV形式でエクスポート
     */
    private function export_to_csv($history) {
        $csv = "日時,タイプ,メッセージ\n";
        
        foreach ($history as $message) {
            $csv .= sprintf(
                '"%s","%s","%s"' . "\n",
                isset($message['timestamp']) ? $message['timestamp'] : '',
                isset($message['type']) ? $message['type'] : '',
                isset($message['message']) ? str_replace('"', '""', $message['message']) : ''
            );
        }
        
        return $csv;
    }
    
    /**
     * テキスト形式でエクスポート
     */
    private function export_to_txt($history) {
        $txt = "=== AIチャット履歴 ===\n\n";
        
        foreach ($history as $message) {
            $type = isset($message['type']) ? $message['type'] : 'unknown';
            $timestamp = isset($message['timestamp']) ? $message['timestamp'] : '';
            $content = isset($message['message']) ? $message['message'] : '';
            
            $txt .= sprintf("[%s] %s:\n%s\n\n", $timestamp, ucfirst($type), $content);
        }
        
        return $txt;
    }
}

// WordPressフックの登録
add_action('wp_ajax_ai_chat_cleanup', array('Chat_History', 'cleanup_old_conversations'));
add_action('wp_ajax_nopriv_ai_chat_cleanup', array('Chat_History', 'cleanup_old_conversations'));