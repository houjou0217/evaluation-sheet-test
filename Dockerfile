FROM python:3.12-slim

WORKDIR /app

# 必要なパッケージをインストール（pandasを追加）
RUN apt-get update && apt-get install -y python3-pip && \
    python3 -m pip install --no-cache-dir flask mysql-connector-python pandas


# アプリケーションファイルをコピー
COPY ./flask_app /app

# Flask アプリを起動
CMD ["python", "app.py"]
