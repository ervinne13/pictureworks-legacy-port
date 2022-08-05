# Pictureworks Legacy System Porting

## Requirements

In order to make the most of this project. The author, Ervinne Sodusta recommends to run this with `docker` and `docker compose`:

https://docs.docker.com/get-docker/
https://docs.docker.com/compose/install/

If you cannot install docker in your machine, then run the project inside `pictureworks-server` as you would a normal laravel application

## Quickstart

Simply execute the following commands:

```
cp .env.example .env
sudo ./setup.sh
docker compose up
```

You also have the option to run `docker compose up -d` to run it detached from the terminal but the author suggest to run this without `-d` the first time at least to double check any issues if ever the default .env.example configuration had conflicts with your docker network.

## Tearing Down / Cleanup

The `./setup.sh` script would edit your `/etc/hosts` file in order to put aliases to `pictureworks-legacy.local.com` and `pictureworks.local.com`. Please remove these entries manually to clean up.

## Validating The Legacy Application

Access the alias in your browser: https://pictureworks-legacy.local.com?id=1

![Error due to self signing](./docs/img/Screenshot%20from%202022-08-05%2022-44-24.png)

You will be prompted with a "Your connection is not private" error as we are using self signed certificates to implement a locally enabled ssl. Simply click on "Advanced" and "Proceed to pictureworks-legacy.local.com (unsafe)" to proceed to the application and you should see the following if `./setup.sh` did it's job right.

If the proctor wishes so, the applicant may discuss in the interview about his reasoning why he would normally develop with ssl right away starting from his local environment.

![John Smith's Profile](./docs/img/Screenshot%20from%202022-08-05%2022-28-42.png)

## Validating The Port's Database

The new port will use Postgres instead of MySQL.
The docker setup should automatically create a database and a pgadmin for you automatically. You just need to access [http://pictureworks-db.local.com/](http://pictureworks-db.local.com/) or whatever the value of `PGADMIN_ALIAS` in the `.env`.

![PGAdmin](./docs/img/Screenshot%20from%202022-08-06%2000-16-17.png)

Once you're able to access pgadmin, simply register a new server using the details and credentials you set in your `.env`.

## Validating the Laravel Port

WIP