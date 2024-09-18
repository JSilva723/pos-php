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

## Create tenant service
```sh
make ssh
```
```sh
bin/console landlord:create-tenant-db tenant_name
```
```sh
bin/console landlord:run-migrations tenant_name
```
```sh
bin/console landlord:load-initial-data tenant_name username
```