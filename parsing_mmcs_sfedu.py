import requests
from fake_useragent import UserAgent
from bs4 import BeautifulSoup
from datetime import date
import json


def get_news_mmcs_sfedu_ru():
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
                                                 attrs={'class': 'article_title'}).text.strip().replace('\u200b', '')  # название новости
            date_of_create_article = current_article.find('span', attrs={'class', 'createdate'}).find_all('span', attrs={'class': 'numbers'})
            date_of_create_article_in_dt = date(int(date_of_create_article[2].text),
                                                int(date_of_create_article[1].text),
                                                int(date_of_create_article[0].text))
            article_date = date_of_create_article_in_dt.strftime("%d.%m.%Y")  # настраиваем дату публикации новости
            author_of_article = current_article.find('span', attrs={'class': 'createby'}).text.strip()  # автор новости
            article_text = current_article.find('div', attrs={'class': 'newsitem_text'}).text.strip().replace('\u200b', '').replace('\n', ' ')  # текст новости
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

    with open("data.json", "w", encoding="utf-8") as json_file:
        json.dump(result, json_file, indent=4, ensure_ascii=False)


def main():
    get_news_mmcs_sfedu_ru()


if __name__ == '__main__':
    main()
