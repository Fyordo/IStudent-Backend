import requests
from config import TOKEN
from datetime import datetime
import json


def get_posts(count_posts):
    """функция для парсинга новостей из вк группы СИЦ ИММиКН: https://vk.com/sic_mmcs
        с каждого поста берём:
        дату публикации
        текст поста
        фото/видео
    """
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
        date_of_publication_post = post['date']  # время в unix формате (в секундах, прошедших с 1 января 1970 года)
        # переводим в читаемое время
        date_of_publication = datetime.utcfromtimestamp(int(date_of_publication_post) + 10800).strftime(
            '%d.%m.%Y %H:%M')
        text_post = post['text'].replace('\n', ' ')

        files_to_post = []

        files = post['attachments']
        for file in files:
            if 'photo' in file:
                picture = file['photo']['sizes'][0]['url']
                files_to_post.append(picture)
            elif 'link' in file:
                link = file['link']['url']
                files_to_post.append(link)

        result.append(
            {
                "date": date_of_publication,
                "text": text_post,
                "files_to_post": files_to_post
            }
        )

    with open("data_vk.json", "w", encoding="utf-8") as json_file:
        json.dump(result, json_file, indent=4, ensure_ascii=False)


def main():
    get_posts(15)


if __name__ == '__main__':
    main()
