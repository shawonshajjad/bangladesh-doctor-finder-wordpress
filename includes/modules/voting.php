<?php
// ৮. Vote Handler — IP + Cookie based switchable recommend/not recommend
add_action('wp_ajax_master_vote_v37', 'master_vote_v37_handler');
add_action('wp_ajax_nopriv_master_vote_v37', 'master_vote_v37_handler');
function master_vote_v37_handler() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'master_vote_nonce')) {
        wp_send_json(array('success'=>false, 'message'=>'Invalid request'));
    }

    $pid  = intval($_POST['id'] ?? 0);
    $type = sanitize_text_field($_POST['type'] ?? '');
    if (!$pid || !in_array($type, array('up','down'), true)) {
        wp_send_json(array('success'=>false, 'message'=>'Invalid vote'));
    }

    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $cookie_key = 'dr_vote_' . $pid;
    $cookie_vote = isset($_COOKIE[$cookie_key]) ? sanitize_text_field($_COOKIE[$cookie_key]) : '';

    $history = get_post_meta($pid, 'dr_vote_history', true);
    if (!is_array($history)) $history = array();

    $voter_key = md5($ip . '|' . $pid);
    $previous_vote = isset($history[$voter_key]) ? $history[$voter_key] : $cookie_vote;
    $score = (int)get_post_meta($pid, 'dr_votes', true);

    if ($previous_vote === $type) {
        setcookie($cookie_key, $type, time() + YEAR_IN_SECONDS, COOKIEPATH ?: '/', COOKIE_DOMAIN, is_ssl(), true);
        wp_send_json(array('success'=>true, 'score'=>$score, 'status'=>'same'));
    }

    if ($previous_vote === 'up' && $type === 'down') {
        $score = max(0, $score - 1);
    } elseif ($previous_vote === 'down' && $type === 'up') {
        $score = $score + 1;
    } else {
        if ($type === 'up') $score = $score + 1;
    }

    $history[$voter_key] = $type;
    update_post_meta($pid, 'dr_vote_history', $history);
    update_post_meta($pid, 'dr_votes', $score);
    setcookie($cookie_key, $type, time() + YEAR_IN_SECONDS, COOKIEPATH ?: '/', COOKIE_DOMAIN, is_ssl(), true);

    wp_send_json(array('success'=>true, 'score'=>$score, 'status'=>'changed'));
}
