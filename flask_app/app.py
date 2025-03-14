from flask import Flask, jsonify
import mysql.connector
import pandas as pd
from mysql.connector import Error

app = Flask(__name__)

def fetch_raw_usermeta():
    try:
        # MySQL に接続
        conn = mysql.connector.connect(
            host="db",
            user="root",
            password="root_pass_fB3uWvTS",
            database="wordpress_db"
        )
        cursor = conn.cursor(dictionary=True)
        cursor.execute("SELECT * FROM wp_usermeta_1")
        results = cursor.fetchall()
        cursor.close()
        conn.close()
        return results
    except Error as e:
        return {"error": f"Error connecting to MySQL: {e}"}

def fetch_and_process_usermeta():
    try:
        # MySQL に接続
        conn = mysql.connector.connect(
            host="db",
            user="root",
            password="root_pass_fB3uWvTS",
            database="wordpress_db"
        )
        cursor = conn.cursor(dictionary=True)
        cursor.execute("SELECT * FROM wp_usermeta_1")
        results = cursor.fetchall()
        cursor.close()
        conn.close()

        if not results:
            return []

        # DataFrameに変換
        df = pd.DataFrame(results)

        # デバッグ用: カラム名を確認
        print("Columns in DataFrame:", df.columns.tolist())

        # 必要なカラムが存在するか確認
        required_columns = {'COL 2', 'COL 3', 'COL 4'}
        if not required_columns.issubset(df.columns):
            return {"error": f"Missing required columns: {required_columns - set(df.columns)}"}

        # meta_key に対応するデータを収集
        keys = {
            'group': 'group',
            'group_id_alphabet': 'group_id_alphabet',
            'nickname': 'nickname',
            'first_name': 'first_name',
            'user_nationality': 'nationality',
            'level': 'level',
            'sll_lineid': 'sll_lineid'
        }

        # COL 3（meta_key）に対応するデータを抽出
        extracted_dfs = [
            df[df['COL 3'] == key][['COL 2', 'COL 4']].rename(columns={'COL 2': 'user_id', 'COL 4': new_key})
            for key, new_key in keys.items()
        ]

        # ユーザーごとに統合
        parsed_df = extracted_dfs[0]
        for other_df in extracted_dfs[1:]:
            parsed_df = pd.merge(parsed_df, other_df, on='user_id', how='outer')

        # 欠損値を空文字列に置換
        parsed_df.fillna('', inplace=True)

        return parsed_df.to_dict(orient='records')

    except Error as e:
        return {"error": f"Error processing MySQL data: {e}"}

@app.route('/')
def home():
    return 'Flask API is running!'

@app.route('/get_usermeta', methods=['GET'])
def get_usermeta():
    data = fetch_and_process_usermeta()
    return jsonify(data)

@app.route('/get_userformat', methods=['GET'])
def get_userformat():
    data = fetch_raw_usermeta()
    return jsonify(data)

@app.route('/get_conversion_table', methods = ['GET'])
def get_conversion_table():
    data  = convesion_data()
    return jsonify(data)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5001)
