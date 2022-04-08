from flask import Flask, jsonify
import requests
from datetime import datetime, date
from fake_useragent import UserAgent
from bs4 import BeautifulSoup

TOKEN = "ccbf755c874dc20594f793c6a899a35d8cbe8bbe933f9d7ca2c08dcaa43ec6a4df58d4ba4eaec166d5398"
app = Flask(__name__)
app.config['JSON_AS_ASCII'] = False


@app.route('/api/news/vk', methods=['GET'])
def get_vk_posts():
    # парсинг 5 публикаций в группе вк
    count_posts = 5
    response = requests.get('https://api.vk.com/method/wall.get',
                            params={
                                'access_token': TOKEN,
                                'v': 5.92,
                                'domain': 'sic_mmcs',
                                'count': count_posts
                            })
    data = response.json()['response']['items']

    result = []

    for post in data:
        post_link = f"https://vk.com/sic_mmcs?w=wall{post['owner_id']}_{post['id']}"
        date_of_publication_post = post['date']  # время в unix формате (в секундах, прошедших с 1 января 1970 года)
        # переводим в читаемое время
        date_of_publication = datetime.utcfromtimestamp(int(date_of_publication_post) + 10800).strftime(
            '%d.%m.%Y %H:%M')
        text_post = post['text'].replace('\n', ' ')

        files_to_post = []
        try:
            files = post['attachments']
            for file in files:
                if 'photo' in file:
                    picture = file['photo']['sizes'][0]['url']
                    files_to_post.append(picture)
                elif 'link' in file:
                    link = file['link']['url']
                    files_to_post.append(link)
        except Exception:
            pass

        result.append(
            {
                "date": date_of_publication,
                "text": text_post,
                "files_to_post": files_to_post,
                "post_link": post_link
            }
        )
    return jsonify({'result': result})


@app.route('/api/news/mmcs', methods=['GET'])
def get_mmcs_news():
    """функция для получения новостей с сайта http://mmcs.sfedu.ru/"""
    ua = UserAgent()
    url = "http://mmcs.sfedu.ru/"
    response = requests.get(url=url,
                            headers={"User-Agent": ua.random}
                            )
    html = response.text
    # отправили запрос на сайт

    result = []
    soup = BeautifulSoup(html, 'lxml')
    articles = soup.find('div', attrs={'class': 'teaserarticles multicolumns'})  # список новостей первой страницы
    for i in range(1, len(articles) + 1, 2):
        try:
            current_article = list(articles)[i]
            article_title = current_article.find('h2',
                                                 attrs={'class': 'article_title'}).text.strip().replace('\u200b',
                                                                                                        '')  # название новости
            date_of_create_article = current_article.find('span', attrs={'class', 'createdate'}).find_all('span',
                                                                                                          attrs={
                                                                                                              'class': 'numbers'})
            date_of_create_article_in_dt = date(int(date_of_create_article[2].text),
                                                int(date_of_create_article[1].text),
                                                int(date_of_create_article[0].text))
            article_date = date_of_create_article_in_dt.strftime("%d.%m.%Y")  # настраиваем дату публикации новости
            author_of_article = current_article.find('span', attrs={'class': 'createby'}).text.strip()  # автор новости
            article_text = current_article.find('div', attrs={'class': 'newsitem_text'}).text.strip().replace('\u200b',
                                                                                                              '').replace(
                '\n', ' ')  # текст новости
            article_text += f" Оригинал статьи: http://mmcs.sfedu.ru/"

            result.append(
                {
                    "title": article_title,
                    "date": article_date,
                    "author": author_of_article,
                    "text": article_text
                }
            )
            # храним все данные в json формате
        except (IndexError, AttributeError):
            continue
    return jsonify({'result': result})


if __name__ == '__main__':
    app.run(debug=True, port=33507)
