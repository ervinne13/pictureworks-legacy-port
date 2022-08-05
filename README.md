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

## Development Methodology

If given the chance, the author would always try to opt for a Test Driven Development project.

Let's take requirement #5 for example:

> Parts 1-4 above should be ported with expected functionality using native Laravel behavior (e.g. url “?
id=x” should be available via "/user/{id}").

After planning the tasks, we can go through them one by one and write tests based on each of them:

![Clickup Tasks](./docs/img/tdd/Screenshot%20from%202022-08-06%2001-12-46.png)

![Clickup Task](./docs/img/tdd/Screenshot%20from%202022-08-06%2001-13-22.png)

With a resulting test on "Red State" Feature test:

```php
<?php

namespace Tests\Feature\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserViewingFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @test
     * @return void
     */
    public function it_can_display_a_user_given_id()
    {
        $targetUserName = 'Ervinne Sodusta';
        $user = User::factory()->create(['name' => $targetUserName]);
        $response = $this->get("/user/{$user->id}");

        $response->assertStatus(200);
        $response->assertSee($targetUserName);
    }
}
```

If ever we have a need for some Unit Tests, we write them as needed in between feature tests. For this case scenario though I doubt we have a need for it as this is just direct query with not much regression risk related to logic. Let's apply YAGNI and KISS and stick to just a feature test specifically for this.

Running this should put us on "Red State":

![Red State](./docs/img/tdd/Screenshot%20from%202022-08-06%2001-10-44.png)


### Testing "Green State"

Note that we can forward this to green state with just a simple:

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $user;
    }
}

```

Nothing wrong with that, then we just refactor and add a view according to the original view. But first, let's fill up the actual db with some data:

```
docker exec -it pictureworks-server php artisan migrate:refresh --seed
```