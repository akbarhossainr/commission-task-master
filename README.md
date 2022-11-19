# Comission Task Master
## Description

[Click Here](https://gist.github.com/PayseraGithub/ef2a59d0a6d6e680af2e46ccff1bca37)

## Installation
- Clone the repository.
```bash
git clone https://github.com/akbarhossainr/commission-task-master
```
- Access to the directory.
```bash
cd commission-task-master
```

### using PHP & Composer

**Requirements**
- PHP CLI (version>=7.4)
- Composer (Recommended version=2.2.2)

**Steps**

1. Install application dependencies.
```bash
composer install
```
2. Run the application
```bash
php src/index.php input.csv
```
>Note: Instead of the `input.csv` you can use your `path/to/your/input.csv`
3. Run the test
```bash
./vendor/bin/phpunit
```

### using Docker

**Requirements**
- Docker
- docker-compose

**Steps**
1. Build the docker container.
```bash
docker-compose up --build -d
```
2. Install application dependencies.
```bash
docker exec commission-task-master-php composer install
```
3. Run the application.
```bash
docker exec commission-task-master-php php src/index.php input.csv
```
>Note: Instead of the `input.csv` you can use your `path/to/your/input.csv`
4. Run the test.
```bash
docker exec commission-task-master-php ./vendor/bin/phpunit
```
