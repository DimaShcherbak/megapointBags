#!/bin/bash
# Указываем файлы sitemap
SITEMAP_UA="https://megapoint.com.ua/media/sitemap/sitemap_ua.xml"
#SITEMAP_RU="https://megapoint.com.ua/media/sitemap/sitemap_ru.xml"

# Временный файл для хранения ссылок
TMP_FILE="/tmp/urls.txt"

# Функция для загрузки ссылок из sitemap
fetch_urls() {
    curl -s "$1" | grep -oP '(?<=<loc>).*?(?=</loc>)' >> "$TMP_FILE"
}

# Функция для прогрева кэша с передачей языка
warm_cache() {
    local lang="$1"
    local store="$2"
    while read -r url; do
        echo "Прогреваю $lang: $url"
        # Передаем заголовки Accept-Language и кукиstore
        curl -s -H "Accept-Language: $lang" \
             -H "Cookie: store=$store" \
             "$url" > /dev/null
    done < "$TMP_FILE"
}

# Очищаем временный файл перед записью новых URL
> "$TMP_FILE"

# Загружаем ссылки с обоих sitemap
fetch_urls "$SITEMAP_UA"
fetch_urls "$SITEMAP_RU"

# Прогреваем кэш для украинской версии
warm_cache "uk-UA" "ukrain"

# Очищаем временный файл
> "$TMP_FILE"

# Загружаем ссылки заново
fetch_urls "$SITEMAP_UA"
fetch_urls "$SITEMAP_RU"

# Прогреваем кэш для русской версии
warm_cache "ru-RU" "russ"

echo "✅ Кэш успешно прогрет для обоих языков!"
