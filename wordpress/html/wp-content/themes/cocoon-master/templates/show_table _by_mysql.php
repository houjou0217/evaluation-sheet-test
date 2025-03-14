<?php
/*
Template Name: mysqlから直接データ取得
*/

get_header();

// グローバル変数 $wpdb を利用してクエリを実行
global $wpdb;
$results = $wpdb->get_results("SELECT * FROM wp_usermeta_1", ARRAY_A);
?>

<div class="content">
    <h1>wp_usermeta_1 テーブルの内容</h1>
    <?php if ( ! empty( $results ) ) : ?>
        <table border="1" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <?php 
                    // 先頭行のカラム名を表示
                    foreach ( array_keys( $results[0] ) as $column ) : ?>
                        <th><?php echo esc_html( $column ); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $results as $row ) : ?>
                    <tr>
                        <?php foreach ( $row as $value ) : ?>
                            <td><?php echo esc_html( $value ); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>データがありません。</p>
    <?php endif; ?>
</div>

<?php
get_footer();
?>
