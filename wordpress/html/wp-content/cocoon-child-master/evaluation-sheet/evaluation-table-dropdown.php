<?php
/*
Template Name: Dropdown CSV Display
*/

get_header();

// CSV ファイルを読み込む関数
function load_csv_data($file_path) {
    $data = [];
    if (($handle = fopen($file_path, 'r')) !== false) {
        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            $data[] = $row;
        }
        fclose($handle);
    } else {
        echo '<p>ファイルを開くことができません: ' . esc_html($file_path) . '</p>';
    }
    return $data;
}

// ディレクトリ内のすべての CSV ファイルを取得
$data_dir = get_stylesheet_directory() . '/evaluation-sheet/data';
$csv_files = glob($data_dir . '/*.csv');

// POST から選択されたファイル名を取得
$selected_file = isset($_POST['csv_file']) ? $_POST['csv_file'] : null;

// プルダウンメニューの表示
echo '<h2>CSV ファイルの選択</h2>';
echo '<form method="post">';
echo '<label for="csv_file">表示する CSV ファイルを選択してください:</label>';
echo '<select id="csv_file" name="csv_file">';
echo '<option value="">選択してください</option>';

if ($csv_files) {
    foreach ($csv_files as $file_path) {
        $file_name = basename($file_path);
        $selected = ($file_name === $selected_file) ? 'selected' : '';
        echo "<option value='$file_name' $selected>$file_name</option>";
    }
} else {
    echo '<option value="">CSV ファイルが見つかりません</option>';
}

echo '</select>';
echo '<button type="submit">表示</button>';
echo '</form>';

// 選択されたファイルを表示
if ($selected_file) {
    $file_path = $data_dir . '/' . $selected_file;

    if (file_exists($file_path)) {
        $csv_data = load_csv_data($file_path);

        if ($csv_data) {
            echo '<h3>' . esc_html($selected_file) . '</h3>';
            echo '<table border="1" cellpadding="5" cellspacing="0">';
            foreach ($csv_data as $row) {
                echo '<tr>';
                foreach ($row as $cell) {
                    echo '<td>' . esc_html($cell) . '</td>';
                }
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p>CSV ファイルのデータを読み込むことができませんでした。</p>';
        }
    } else {
        echo '<p>CSV ファイルが見つかりません: ' . esc_html($file_path) . '</p>';
    }
}

get_footer();
?>
