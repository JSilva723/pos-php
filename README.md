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
5. Install dependencies
```sh
make composer-install
```
6. Run migrations
```sh
make composer-install
```
7. Open broeser -> http://localhost:3000

## Create user - (admin, admin)
1. Insert in DB - opent other terminal
```sh
make db-ssh
```
```sh
mysql -u root -p main-app # pass -> root
```
```sql
insert into user (username, roles, password) values ('admin','["ROLE_USER"]' ,'$2y$13$O6TxZOXywpOYKmgzc1Zn4uU9dtvCMWlHT1p/8.aFRYn2k7AidSOPO');
```