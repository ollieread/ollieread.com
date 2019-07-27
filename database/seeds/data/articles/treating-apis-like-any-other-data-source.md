When dealing with third party APIs we don't really care about the endpoints for the data, or how exactly the implementation needs to take place, we just care about the resources returned by the API.

## Data source abstraction
Data source abstraction has been an interest of mine for a while now, ever since I authored the initial version of [Articulate](https://github.com/sprocketbox/articulate), a data source agnostic entity mapper. Unfortunately, the initial versions of Articulate didn't work exactly as I had planned, and I have since reworked the entire package to better support different data sources. With the introduction of sources, I've managed to create a uniform way to interact with data, meaning that my application logic can deal with entities regardless of whether they've come from a database, an API or a file.

The first source that I built support for is called `Illuminate` and essentially wraps the Laravel query builder in a tiny abstraction layer, allowing Articulate entities to use it as its data source.

## API wrapper abstraction
Now, my second source was a little bit more complicated, as I wanted to abstract out API wrappers so that I could interact with them in the same manner as I can with the database using the `Illuminate` source. Rather than build out some mammoth source that did this, I decided to split this out into its own package.

This package is called [Respite](https://github.com/sprocketbox/respite), and if you're wondering why that name, it's because `Respite` is a synonym for `Rest`. This package in its initial rudimentary form allows me to register OAuth2 providers so that I may use a generic request builder to query the API. An example provider is as follows;

```php
<?php

namespace Sprocketbox\Respite\Providers\GitHub;

use GuzzleHttp\Client;
use Sprocketbox\Respite\Providers\OAuth2Provider;
use Sprocketbox\Respite\Request\Builder;

class GitHubProvider extends OAuth2Provider {
    public function newBuilder(): Builder {
        $client = new Client(['base_uri' => $this->config['base_url'] ?? '']);
        if (! $this->accessToken) {
            throw new \RuntimeException('No access token provided');
        }
        return new Builder($client, ['Authorization' => 'Bearer ' . $this->accessToken]);
    }
}
```

The only other method available on the provider is `setAccessToken(string|AccessToken $accessToken): self`, which allows me to set the access token for the current builder.

With this provider registered like so;

```php
<?php
respite()->extend('github', GithubProvider::class);
```

I am able to create a nice little builder that lets me interact with the GitHub API.

```php
<?php
$respite = app(Respite::class);

$response = $respite
    ->for('github')
    ->setAccessToken('ACCESS_TOKEN')
    ->get('/users/{username}/repos', ['ollieread'])
    ->contents();
```

In the above code, `$response` will contain a collection of arrays, each representing a GitHub repository resource.

This is all good and well, but I don't want arrays, I want entities.

## APIs as abstract data sources
Using my newly created [Respite](https://github.com/sprocketbox/respite) package I was able to build a source into [Articulate](https://github.com/sprocketbox/articulate) that allowed my basic GitHub implementation to have entities and even repositories, allowing me to do away with API endpoint references and deal with exactly what I wanted, the resources.

First, I created myself an entity to represent the GitHub `User` resource;

```php
<?php

namespace Sprocketbox\Github\Entities;

use Sprocketbox\Articulate\Entities\Entity;

/**
 * Class User
 *
 * @property-read int       $id
 * @property string         $login
 * @property string         $nodeId
 * @property string         $avatarUrl
 * @property string         $gravatarId
 * @property string         $url
 * @property string         $htmlUrl
 * @property string         $followersUrl
 * @property string         $followingUrl
 * @property string         $gistsUrl
 * @property string         $starredUrl
 * @property string         $subscriptionsUrl
 * @property string         $organizationsUrl
 * @property string         $reposUrl
 * @property string         $eventsUrl
 * @property string         $receivedEventsUrl
 * @property string         $type
 * @property bool           $siteAdmin
 * @property string         $name
 * @property string         $company
 * @property string         $blog
 * @property string         $location
 * @property string         $email
 * @property bool           $hireable
 * @property string         $bio
 * @property int            $publicRepos
 * @property int            $publicGists
 * @property int            $followers
 * @property int            $following
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 *
 * @package Sprocketbox\Github\Entities
 */
class User extends Entity
{
}
```

Then I created a mapper so that the attributes could be correctly mapped.

```php
<?php

namespace Sprocketbox\Github\Mappers;

use Sprocketbox\Articulate\Contracts\EntityMapping;
use Sprocketbox\Articulate\Entities\EntityMapper;
use Sprocketbox\Github\Entities\User;
use Sprocketbox\Github\Repositories\UserRepository;

class UserMapper extends EntityMapper {

    public function entity(): string {
        return User::class;
    }

    public function source(): string {
        return 'respite';
    }

    /**
     * @param \Sprocketbox\Articulate\Sources\Respite\RespiteEntityMapping $mapping
     */
    public function map(EntityMapping $mapping) {
        $mapping->setKey('id');
        $mapping->setProvider('github');
        $mapping->setRepository(UserRepository::class);
        $mapping->int('id');
        $mapping->string('login');
        $mapping->string('node_id');
        $mapping->string('avatar_url');
        $mapping->string('gravatar_id');
        $mapping->string('url');
        $mapping->string('html_url');
        $mapping->string('followers_url');
        $mapping->string('following_url');
        $mapping->string('gists_url');
        $mapping->string('starred_url');
        $mapping->string('subscriptions_url');
        $mapping->string('organizations_url');
        $mapping->string('repos_url');
        $mapping->string('events_url');
        $mapping->string('received_events_url');
        $mapping->string('type');
        $mapping->bool('site_admin');
        $mapping->string('name');
        $mapping->string('company');
        $mapping->string('blog');
        $mapping->string('location');
        $mapping->string('email');
        $mapping->bool('hireable');
        $mapping->string('bio');
        $mapping->int('public_repos');
        $mapping->int('public_gists');
        $mapping->int('followers');
        $mapping->int('following');
        $mapping->timestamp('created_at', 'Y-m-d\TH:i:s\Z');
        $mapping->timestamp('updated_at', 'Y-m-d\TH:i:s\Z');
    }
}
```

Then I created a nice and simple repository;

```php
<?php

namespace Sprocketbox\Github\Repositories;

use Sprocketbox\Articulate\Sources\Respite\RespiteRepository;
use Sprocketbox\Github\Entities\User;
use Sprocketbox\Respite\Request\Builder;

/**
 * Class UserRepository
 *
 * @method \Sprocketbox\Github\Entities\User|null getOne(Builder $builder, ?string $key = null)
 * @method \Sprocketbox\Articulate\Support\Collection get(Builder $builder, ?string $key = null)
 *
 * @package Sprocketbox\Github\Repositories
 */
class UserRepository extends RespiteRepository
{
    /**
     * @return null|\Sprocketbox\Github\Entities\User
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCurrent(): ?User
    {
        return $this->getOne($this->builder()->get('/user'));
    }
}
```

Now all I need to do is test the implementation;

```php
<?php
entities()->registerEntity(new UserMapper);
respite('github')->setAccessToken('ACCESS_TOKEN');
$userRepository = app(UserRepository::class);
$current = $userRepository->getCurrent();
```

Now the `$current` entity contains an instance of my `User` entity, with all of the data populated from the GitHub API and cast accordingly thanks to the mapping.

## What's next?
Now that I have proved that there is at least both a theoretical and practical approach to this problem, I'm going to keep digging and experimenting.

As part of this little experiment I'm going to build out the GitHub package, as well as look into a few others (namely Discord as I need one for a pet project). I'm also going to keep testing the implementation and the theory, as well as improving upon both [Articulate](https://github.com/sprocketbox/articulate) , and [Respite](https://github.com/sprocketbox/respite).

If you have any APIs you'd like me to use as a test case for these packages let me know. I'm also open to any suggestions and feedback.