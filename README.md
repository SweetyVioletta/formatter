# formatter

Установка Docker
----------------
[Инструкция](https://docs.docker.com/install/linux/docker-ce/ubuntu/#set-up-the-repository) для установки Docker CE

Чтобы добавить репозиторий на Linux Mint используйте команду:
```
sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu xenial stable"
```

Установка Docker Compose
------------------------
[Инструкция](https://docs.docker.com/compose/install/#install-compose) 


Запуск программы
------------------------
Добавить исходные файлы в папку `formatter/sources`

Запустить в консоли  `make`

Результаты в `formatter/formatted` папке