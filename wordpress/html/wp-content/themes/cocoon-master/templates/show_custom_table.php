<?php
/*
 * Template Name: show_custom_table
 */

get_header();

// Flask API からデータを取得
$response = wp_remote_get('http://flask:5001/get_usermeta');

if (is_wp_error($response)) {
    echo '<p>データを取得できませんでした。</p>';
} else {
    $body = wp_remote_retrieve_body($response);

    // JSONデータをデコード
    $data = json_decode($body, true);

    if (empty($data)) {
        echo '<p>データがありません。</p>';
    } else {
        // テーブルの開始
        echo '<h2>Flask API からのデータ一覧</h2>';
        echo '<table border="1" cellpadding="10" cellspacing="0">';
        echo '<thead>';
        echo '<tr>';
        
        // ヘッダー行を動的に作成
        $header = array_keys($data[0]); // 最初のデータを基にヘッダーを作成
        foreach ($header as $column) {
            echo '<th>' . esc_html($column) . '</th>';
        }
        
        echo '</tr>';
        echo '</thead>';
        
        // テーブルボディを作成
        echo '<tbody>';
        foreach ($data as $item) {
            echo '<tr>';
            foreach ($item as $value) {
                echo '<td>' . esc_html($value) . '</td>';
            }
            echo '</tr>';
        }
        echo '</tbody>';

        // テーブルの終了
        echo '</table>';
    }
}

get_footer();
?>
