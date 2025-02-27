<?php
/*
Template Name: テーブル操作 1/17
*/

get_header();

global $wpdb;

// テーブル名を動的に取得
$answer_count_table = $wpdb->prefix . 'jp_answer_count';
$field_average_table = $wpdb->prefix . 'jp_field_average';

// データ取得
$answer_counts = $wpdb->get_results("SELECT * FROM $answer_count_table");
$field_averages = $wpdb->get_results("SELECT * FROM $field_average_table");

?>

<h1>Evaluation Tables</h1>

<h2>Answer Count</h2>
<table border="1">
    <tr>
        <th>wp_user_id</th>
        <th>is_completed</th>
    </tr>
    <?php foreach ($answer_counts as $row): ?>
        <tr>
            <td><?php echo $row->wp_user_id; ?></td>
            <td><?php echo $row->is_completed ? 'Yes' : 'No'; ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>Field Average</h2>
<table border="1">
    <tr>
        <th>wp_user_id</th>
        <th>average_score</th>
    </tr>
    <?php foreach ($field_averages as $row): ?>
        <tr>
            <td><?php echo $row->wp_user_id; ?></td>
            <td><?php echo $row->average_score; ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php
get_footer();
?>
