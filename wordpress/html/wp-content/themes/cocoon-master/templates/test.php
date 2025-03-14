<?php
/*
Template Name: flask api で　テーブル取得する
*/

get_header();

$response = wp_remote_get('http://flask:5001/get_userformat');

if (is_wp_error($response)) {
    echo 'データ取得に失敗しました。';
} else {
    $data = json_decode(wp_remote_retrieve_body($response), true);

    if (!empty($data)) {
        echo '<ul>';
        // 最初の行（カラム名）をスキップして、2行目以降のデータを処理
        for ($i = 1; $i < count($data); $i++) {
            $user = $data[$i];
            
            // ここでキーの存在を確認
            if (isset($user['COL 2'], $user['COL 3'], $user['COL 4'])) {
                echo '<li>ユーザーID: ' . esc_html($user['COL 2']) . 
                     ' | キー: ' . esc_html($user['COL 3']) . 
                     ' | 値: ' . esc_html($user['COL 4']) . '</li>';
            } else {
                echo '<li>データが不完全です</li>';
            }
        }
        echo '</ul>';
    } else {
        echo 'データがありません。';
    }
}


get_footer();
?>
