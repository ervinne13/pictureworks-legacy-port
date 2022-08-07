# Pictureworks Legacy System Porting

The deliverables said to provide "A single laravel application that can replace the legacy application provided.". But the applicant, Ervinne Sodusta would like to take this opportunity to highlight making full use of postman and docker as well to showcase his experience with dealing with legacy systems and porting them.

__With a budget of 8 hours, let's try to go above and beyond just providing a new Laravel app and try to showcase how we would handle legacy systems like we do with our usual work.__ Development time is documented via Clickup.

## Available Routes

We ran out of time but ideally, we should've used something like: https://beyondco.de/docs/laravel-apidoc-generator/getting-started/generating-documentation.

For now, let's manually do the documentation:

|Method|Path|Dest|
|-|-|-|
|GET|users/{id}|UserController@show|
|POST|api/v1/users/{user}/comments|UserCommentController@store|
|POST|api/v1/comments|UserCommentController@storeWithLegacyRequest|

### Web Route GET `/users{1}`

This displays the user and his comments if it exists. it should look something like:

![users/1 Screenshot](./docs/img/Screenshot%20from%202022-08-07%2014-53-50.png)

### API Route POST `api/v1/users/{user}/comments`

This is the API route designed if the restrictions of 1:1 is lifted. It uses a proper route where the subject of the update is in the route param - `user`, and only the `comment` and the `password` is in the request body. Notice the use of singular `comment` instead of `comments`.

__This is NOT the answer to the instructions__, merely an additional route to represent what could be some improvements we can do if we are not constrained by a 1:1 copy of the old API signature.

![Port API](./docs/img/postman/Screenshot%20from%202022-08-07%2023-28-11.png)


Example uri: `api/v1/users/1/comments` where 1 is the id of the user we will attach the comment to.
|Parameter|Desc|
|-|-|
|comment|String, max 100 characters, the comment to attach to the {user}|
|password|A static value that serves as the application key|

The applicant also suggests that instad of a static application key passed in the body of the request, we can either do usual oath or jwt keys in the bearer token representing the current user (and we will have to update the route so that it wont contiain the user id). Or use something like this: https://github.com/ervinne13/mutual-authentication-protected-server

### API Route POST `api/v1/comments`

This is a 1:1 copy of the original controller.php route relating to writing a comment.
This is the actual answer to the ask in the PDF file instructions.

![Legacy Ported API](./docs/img/postman/Screenshot%20from%202022-08-07%2023-32-11.png)

|Parameter|Desc|
|-|-|
|id|The id of the user where we will attach the comment to|
|comment|String, max 100 characters, the comment to attach to the {user}|
|password|A static value that serves as the application key|

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

![John Smith's Profile](./docs/img/Screenshot%20from%202022-08-05%2022-28-42.png)

If the proctor wishes so, the applicant may discuss in the interview about his reasoning why he would normally develop with ssl right away starting from his local environment.

## Validating The Port's Database

The new port will use Postgres instead of MySQL.
The docker setup should automatically create a database and a pgadmin for you automatically. You just need to access [http://pictureworks-db.local.com/](http://pictureworks-db.local.com/) or whatever the value of `PGADMIN_ALIAS` in the `.env`.

![PGAdmin](./docs/img/Screenshot%20from%202022-08-06%2000-16-17.png)

Once you're able to access pgadmin, simply register a new server using the details and credentials you set in your `.env`.

## Validating the Laravel Port

Set it up first with:

```
docker exec -it pictureworks-server php artisan key:generate
docker exec -it pictureworks-server php artisan migrate:refresh --seed
```

After setting up, visiting `https://pictureworks.local.com/users/1` (or use the virtual host you specified in the `NGINX_ALIAS` if you changed it). You should see the following:

![users/1 Screenshot](./docs/img/Screenshot%20from%202022-08-07%2014-53-50.png)

## Postman and Testing Comment Creation (both on Legacy & Port)

Teardown and reset the databases first with:

__Legacy:__
```
docker exec -it pictureworks-legacy-mysql-db /bin/sh -c 'mysql -p<root pw> pictureworks < /docker-entrypoint-initdb.d/init.sql'
```

replace `<root pw>` with the value of `LEGACY_DB_ROOT_PASSWORD` in our main `.env`.

__Port:__

```
docker exec pictureworks-server php artisan migrate:refresh --seed
```

docker exec -it pictureworks-legacy-mysql-db mysql -p 



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

Testing here should put us to green. Now we just refactor so that it will return a view instead of the data directly, run the tests again, and we're done.

__Comments__

We do a similar approach to comments and write our first test:

```php
<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Tests\TestCase;

class CommentCreationFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @test
     * @return void
     */
    public function it_can_write_a_comment_for_a_user()
    {
        $targetUserName = 'Ervinne Sodusta';
        $user = User::factory()->create(['name' => $targetUserName]);

        $pw = env("APPLICATION_KEY");
        $ts = Carbon::now()->toDateTimeString();
        $comment = "This is a test comment {$ts}";

        $response = $this->post("/users/{$user->id}/comments", [
            'comment' => $comment,
            'password' => $pw
        ]);

        $response->assertStatus(200);
        $response->assertSeeText("OK");

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'comment' => $comment
        ]);
    }
}
```

In this case though we'll be breaking the rule:

> As a general overarching rule: regardless of access method, as long as your code has the exact
same input and output as the legacy app, you have done the right thing.

Let's do this first and then add another route solely for `comments` to satisfy the rules. The applicant will present both solutions and negotiate if the first implementation can be used.

We just then do the same thing and implement everything, add a few more negative tests to test something like `it_fails_if_user_does_not_exist` and we're done.

## Retro:

Legacy systems are usually hard to setup locally, especially in the applicant's case where we deal with at least 2 (used to be 3, one was already ported) legacy systems at the same time, which all have different requirements, installed dependencies, etc.

Docker will be an indispensable tool when we start working with legacy systems, whether that would be to keep them and still develop features on it, or have developers run a copy locally and port it.

### Docker

The applicant Ervinne Sodusta, is used to dealing with multiple legacy systems (mostly MERN nowadays) at the same time and have used docker extensively to resolve issues about having multiple versions of the databases for example in his own machine without interfering with each other. 

### Helper Bash Files

With a little bit more of effort of writing bash files on top of our docker. We can now make it easier for multiple developers to work on the project and run it locally without much hassle and turnover, saving senior developer's (or whoever the guy who knows the legacy app by heart) time.

### Postman

We'll showcase it's API testing feature and environment feature to showcase a 1:1 comparison of 2 project's APIs.

### Cutting Corners and Technical Debt of this Project

__init.sql and Database Setup__

`init.sql` containing schema updates are fine, but __updates to database like inserting data should not be in it or in the repository at all__.

The normal way to do this is to upload it somewhere and automate downloading it via `aws s3 cp` (or curl if we don't have AWS). Since it only had 1 entry without sensitive information, we just included it in the `init.sql` anyway.

Files required by the legacy app is nice to be stored in an s3 bucket since we can just write an IAM user with limited capability and we can now suddenly securely download it with the credentials of that IAM user saved in our `.env`. The applicant haven't personally done it anywhere else, but if we would use `curl` for it and be unable to put security measures in it aside from password protecting the zip file and saving the password via `.env`.

### Passwords on POST Request Body

Ask this from the proctor, maybe this is not the case really in the actual legacy system but if it is, propose the use of mutual authentication: [Mutual Authentication Protected Server](https://github.com/ervinne13/mutual-authentication-protected-server).

Since the document said that:

> provided the 'password' is a given static value

the `password` must be some sort of application key. While SSL encrypts the request body, it's still not recommended to put it here. We can do mutual authentication for server to server communications, or application keys saved in the Authorization header of the request.

### Postman Suplementary Setup & Teardown db

Another reason of using docker is that we can just setup and teardown databases for postman testing. We didn't do that here (or at least not yet, check if we still have time).

Also, there could be better tools than postman for the job and the applicant haven't explored them yet so this is all he can showcase for now.