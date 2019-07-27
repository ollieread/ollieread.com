> **NOTE: This article is now out of date! Articulate has come on leaps and bounds since this was written. Check out the [WIP docs](https://sprocketbox.github.io/articulate) until I get chance to write a new article.**
> **There's an [article up now](https://ollieread.com/blog/2018/08/07/how-i-accidentally-created-an-orm-to-avoid-using-eloquent) covering the idea behind Articulate.**

Lately I've found myself getting frustrated with Eloquent, needing more than Laravels default ORM offers. I'm a big fan of the DataMapper pattern, but I become disheartened with the overhead and complexity of Doctrine. My time spent with Java has brought out a love for objects, and absolutely everything being objects. With this in mind, I created myself a super basic lightweight ORM named Articulate.

## What is Articulate?
The aim was for an entity based system that sits nicely somewhere between Eloquent and Doctrine. Articulate doesn't enforce any particular database implementation, but comes with an abstract repository for using Laravels DBAL. This has afforded me far more control over the database interactions of my applications.

The package itself is available [on packagist](https://packagist.org/packages/sprocketbox/articulate), for those of you that would like to take a look.

I've been testing the package thoroughly and I've developed it alongside two systems. The most notable of which, is the system that you're currently reading this post on, my personal blog.

## What makes up Articulate?
Articulate has three core elements, Entities, Mappings and Repositories. 

### Entities
Much like Eloquents models, Articulate has entities, similar to Doctrine. However, unlike Doctrine, the entities are a bit more fluid. They all extend a base entity providing a very simple `get(string $name)` and `set(string $name, mixed $value)` method. The EntityManager will use individual getters and setters if they are set, for example, `getId()` and `setId(int $id)`.

Now lets see what an entity representing an admin user would look like;
```php
namespace Ollieread\Entities;

use Illuminate\Contracts\Auth\Authenticatable;
use Ollieread\Articulate\Concerns\HasTimestamps;
use Ollieread\Articulate\Entities\BaseEntity;

class Admin extends BaseEntity implements Authenticatable
{
    use HasTimestamps;

    public function getId(): int {
        return $this->get('id');
    }

    public function setId(int $value): self {
        $this->set('id', $value);
        return $this;
    }

    public function getName(): string {
        return $this->get('name');
    }

    public function setName(string $value): self {
        $this->set('name', $value);
        return $this;
    }

    public function getEmail(): string {
        return $this->get('email');
    }

    public function setEmail(string $value): self {
        $this->set('email', $value);
        return $this;
    }

    public function getPassword(): string {
        return $this->get('password');
    }

    public function setPassword(string $value): self {
        $this->set('password', $value);
        return $this;
    }
    // Authenticatable methods below
    public function getRememberToken(): string {
        return $this->get('remember_token');
    }

    public function setRememberToken($value): self {
        $this->set('remember_token', $value);
        return $this;
    }
    
    public function getAuthIdentifierName() {
        return 'id';
    }
    
    public function getAuthIdentifier() {
        return $this->getId();
    }
    
    public function getAuthPassword() {
        return $this->getPassword();
    }
    
    public function getRememberTokenName() {
        return 'remember_token';
    }
}
```

I've defined getters and setters for all attributes, but you would only really need to do that if you wish to transform the input/output in some way, or if you'd like syntactic sugar. You'll also see that I've implemented the Laravel `Authenticatable` contract.

### Mappings
The EntityManager provides the ability to hydrate an entity from a given collection data. The mapping allows you to map the attribute in the entity, to the column/index in the provided data, as well as the data type.

The mapping for the above entity would be as follows;
```php
namespace Ollieread\Mappings;

use Ollieread\Articulate\EntityMapping;
use Ollieread\Articulate\Contracts\Mapping;
use Ollieread\Entities\Admin;
use Ollieread\Repositories\AdminRepository;

class AdminMapping extends EntityMapping
{
    public function entity(): string {
        return Admin::class;
    }

    public function table(): string {
        return 'admins';
    }

    public function map(Mapping $mapper) {
        $mapper->setKey('id');
        $mapper->setRepository(AdminRepository::class);
        $mapper->mapInt('id')->setImmutable();
        $mapper->mapString('name');
        $mapper->mapString('email');
        $mapper->mapString('password');
        $mapper->mapString('remember_token');
        $mapper->mapTimestamp('created_at');
        $mapper->mapTimestamp('updated_at');
    }
}
```

It's a fairly simple mapping as far as they go. We map the `id` column as an `int` meaning that when retrieving, this value will be cast to an `int`. We also set this field as immutable, so that any changes (`set('id', 20)` || `setId(20)`) are not persisted. Other notable mappings would be the timestamps, meaning that `$entity->getCreatedAt()` will return an instance of `Carbon`.

### Repositories
All Articule entities should have a repositorty. The repository follows the pattern by the same name, and exists to retrieve entities. Two abstract repositories are provided, `EntityRepository`, the generic one, and `DatabaseRepository` which has built in support for Laravels query builder.

The repository for the admin entity would be as follows;
```php
namespace Ollieread\Repositories;

use Illuminate\Contracts\Auth\Authenticatable;
use Ollieread\Articulate\Contracts\EntityAuthRepository;
use Ollieread\Articulate\Repositories\DatabaseRepository;

class AdminRepository extends DatabaseRepository implements EntityAuthRepository 
{
    public function retrieveById($identifier): ?Authenticatable {
        return $this->getOneBy('id', $identifier);
    }
    
    public function retrieveByToken($identifier, $token): ?Authenticatable {
        $result = $this
            ->query($this->entity())
            ->where('id', '=', $identifier)
            ->where('remember_token', '=', $token)
            ->first();

        if ($result) {
            return $this->hydrate($result);
        }

        return null;
    }
    
    public function updateRememberToken(Authenticatable $user, $token): void {
        $id = $user->getAuthIdentifier();

        $this->query($this->entity())
            ->where('id', '=', $id)
            ->update(['remember_token' => $token]);
    }
    
    public function retrieveByCredentials(array $credentials): ?Authenticatable {
        $credentials = array_except($credentials, ['password']);

        $query = $this->query($this->entity());

        foreach ($credentials as $key => $value) {
            $query->where($key, '=', $value);
        }

        $result = $query->first();

        if ($result) {
            return $this->hydrate($result);
        }

        return null;
    }
}
```

Articulates auth support actually allows you full control through repositories. You can setup several guards, and have totally different logic for the retrieval of users, all through the `EntityAuthRepository` contract.

## What Next?
This is just a brief overview of what Articulate is, but more information can be found [within the README](https://github.com/ollieread/articulate).

I'll hopefully be covering this more in the future, with tutorials covering the Articulate approach.