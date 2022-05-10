# Scrutia API

[![Linters](https://github.com/AloisChristen/Scrutia/actions/workflows/linter.yml/badge.svg)](https://github.com/AloisChristen/Scrutia/actions/workflows/linter.yml)  [![dev_api_test](https://github.com/AloisChristen/Scrutia/actions/workflows/dev_api_test.yml/badge.svg)](https://github.com/AloisChristen/Scrutia/actions/workflows/dev_api_test.yml) [![main_api_test](https://github.com/AloisChristen/Scrutia/actions/workflows/main_api_test.yml/badge.svg)](https://github.com/AloisChristen/Scrutia/actions/workflows/main_api_test.yml)

## Mise en place :wrench:

### PHP

Afin de mettre en place l'environnement de développement pour l'API de Scrutia, il faut installer PHP8.1.

##### Pour Windows

L'installer se trouve ici : https://windows.php.net/download/.

Ajouter votre installation PHP8.1 dans votre **PATH**.

Il faudra **enable** l'extension *php-fileinfo* dans le fichier **php.ini**.

##### Pour Ubuntu / WSL

```bash
$ sudo apt update && sudo apt upgrade -y
$ sudo apt install software-properties-common && sudo add-apt-repository ppa:ondrej/php -y
$ sudo apt update
$ sudo apt upgrade -y
$ sudo apt install php8.1 php8.1-curl php8.1-xml 
```

Si la deuxième commande ne passe pas, cette commande obtient l'heure la plus récente du RTC de votre machine Windows et règle l'heure du système sur celle-ci.

```bash
$ sudo hwclock --hctosys 
```

### Composer

Pour générer les fichiers importants pour le framework Laravel, il nous faut installer Composer, qui est le packet manager de PHP (comme npm pour NodeJS).

##### Pour Windows

Vous trouverez l'installeur ici : https://getcomposer.org/Composer-Setup.exe

##### Pour Ubuntu / WSL

```bash
$ php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
$ php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
$ php composer-setup.php
$ php -r "unlink('composer-setup.php');"
$ sudo mv composer.phar /usr/local/bin/composer
```

### Finition

Enfin pour l'installation des dépendances , il faut être à la racine de l'API (Scrutia/scrutia-api):

```bash
$ composer install
```

Pour lancer l'API :

##### Pour Windows

Les utilisateurs sous Windows sont péjorés car toutes les fonctionnalités ne sont pas présentes. Vous pouvez tout de même lancer le docker-compose :

```bash
$ docker-composer up
```

##### Pour Ubuntu / WSL

Afin de se faciliter la tache dans les commandes, nous pouvons ajouter cet alias : 

```bash
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
```

Pour lancer l'API, il suffit de faire la commande suivante : 

```bash
$ sail up
# ./vendor/bin/sail up si l'alias n'a pas été créé
```



Pour lancer l'API **en background**, il suffit de faire la commande suivante : 

```bash
$ sail up -d
# ./vendor/bin/sail up si l'alias n'a pas été créé
```


## tests

pour lancer les tests en local

```bash
sail artisan test
```


# commandes php artisan 

en local il faut utiliser `sail` à la place de `php` (selon alias)

```bash
sail artisan migrate:refresh --seed
```


# a faire lors de changement dans les routes

```bash
sail artisan optimize
```

# good example for error return and parameter input

```php
/**
     * Update the specified resource in storage.
     *
     * @param UpdateQuestionRequest $request
     * @param int $question
     * @return JsonResponse
     */
    public function update(int $id, UpdateQuestionRequest $request): JsonResponse
    {
        $question = Question::find($id);
        if($question == null){
            return response()->json(["message" => "Not Found", "errors" => [
                "Question id does not exist"
            ]], 404);
        }
        $question->title = $request->title;
        $question->description = $request->description;
        $question->save();
        return response()->json($question);
    }
```

# pour debug facilement

```php
    dd($tags);
```

# Exécuter un seul fichier test (exemple : ProjectTest)
```
    sail artisan test --filter ProjectTest
```

# Exécuter une méthode test dans un fichier (exemple ProjectTest, méthode test_can_create_a_project)
```
    sail artisan test --filter ProjectTest::can_create_user
```
