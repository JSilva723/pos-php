## Init
1. Clone repo
```sh 
git clone git@github.com:JSilva723/pos-php.git src
```
2. Create dir and move src
```sh
mkdir pos && mv ./src ./pos && cd pos/src
```
3. Copy env file
```sh
cp .env.example .env
```
4. Move to src and build image
```sh
make build
```
5. Start container
```sh
make start
```
5. Install dependencies and run migrations
```sh
make prepare
```
6. Open broeser -> http://localhost:3000

## Create user
1. Gen pass - open terminal
```sh
make ssh 
```
```sh
bin/console security:hash-password admin
```
2. Insert in DB - opent other terminal
```sh
make db-ssh
```
```sh
mysql -u root -p main-app # pass -> root
```