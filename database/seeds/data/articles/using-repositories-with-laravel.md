The repository pattern is one that appears to polarize developers, with half swearing by and half swearing against. It's also something that seems to have been spoken about a lot, though I feel that more often than not, it's an over complicated over engineered approach.

I've spent a good amount of time working with repositories, and these days, it's rare that my projects don't have them. I've also encountered a lot of projects, written by others, with repositories straight out of a tutorial, that is to say, full of interfaces.

### What is the repository pattern?

To quote *Patterns of Enterprise Application Architecture*;

> Mediates between the domain and data mapping layers using a collection-like interface for accessing domain objects.

Simply put, a repository is an abstraction of the interaction with a data source, and/or the persistence layer. The idea is that your database interactions are sufficiently abstracted as to be easily modified and swapped out with little to no overhead.

The approach itself is amazingly useful, for applications of all sizes.

### Why should I use a repository?

Simply put, because you want your code to be more manageable. Sure you can add lots of methods to your models, but if you've ever looked at the full structure of a model, they're already pretty full.

### The interface approach

A lot of tutorials will approach repositories in Laravel by having you create an interface per repository, and then binding your concrete instance to the interface, using Laravels IoC container.

The goal with this approach, is that you've defined the API of your repository, and should you need to change data source in the future, you can quite easily create a new repository and implement your interface. This is all good and well in principal, but it'll end up filling your application with interfaces, and somewhat over complicating the process.

The chances of you changing your data source at a later date are pretty low. On top of that, most modern IDEs will let you very easily extract an interface from a repository, so you can create one when you need one. Further more, even with the best planning in the world, your repositories will not be the same at the end of the project, as they were at the start. This means that you're constantly having to update the interfaces, as well as the repositories themselves.

### A simpler approach

The most common reason for not using repositories, is that it's a lot of extra effort, for no real benefit. The answer to that, is to simplify the approach.

I have a package that I use in almost all Laravel projects, I called it [my toolkit](https://packagist.org/packages/ollieread/laravel-toolkit). Amongst other things, it contains a base repository that I use for everything that uses Eloquent. It's relatively simple, and the source can be [viewed here](https://github.com/ollieread/toolkit/blob/master/src/Repositories/BaseRepository.php).

When I start a new project, I can create a fully functional repository that works for most cases by simply extending my base repository like so;

```php
/**
 * @method Post make()
 * @method null|Post getOneById(int $id)
 */
class PostRepository extends BaseRepository
{
    protected $model = Post::class
}
```

The above, is a legitimate repository, lifted directly from a working codebase. Because of the helper methods (you'll see them later on in this post), I'm able to perform almost all of the actions I need, using the generic approaches. Should I require a specific method that does something different, I can define it here.

You'll also notice that I redefined some of the methods from the base repository, using the php docblocks. The reason for this is so that my IDE knows the exact instance expected.

Lets break down this base repository.

```php
abstract class BaseRepository
{
    /**
     * @var string
     */
    protected $model;
    /**
     * @return Model
     */
    protected function make(): Model
    {
        return new $this->model;
    }
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function query(): Builder
    {
        return $this->make()->newQuery();
    }
}
```

You'll notice that the class is `abstract` so that it must be extended, and the child classes should redefine the `$model` property with the class name of the model in question.

Next you'll see two methods, `make()` and `query()`. If not by their names, the body of these methods provides a sufficient explanation as to what they do.

Realistically, this is all that you need for a repository, and anything on top of this would just be nice helper methods. An example implementation of this would be as follows;

```php
class PostRepository extends BaseRepository
{
    protected $model = Post::class;

    public function getPaginated(int $count = 20): LengthAwarePaginator
    {
        return $this->query()
            ->orderBy('created_at', 'desc')
            ->paginate($count);
    }
}
```

The class itself provides a nice method for creating a new instance of the model in question, as well as query builder instance for it. Some may argue that the `query()` method isn't needed, as technically the Model will pass it through, but I'm a fan of IDEs being able to detect what's happening. Magic is all good and well in small doses, but I prefer to be aware of exactly what's going on.

If you've taken a look at the source of my repository, you'll also notice a few other methods in there. These methods are purely to simplify my life, and implementing things like this, could aid you too.

The first method we have is the `getId()` method.

```php
protected function getId($model): int
{
    return $model instanceof Model ? $model->getKey() : $model;
}
```

Sometimes, you may want to pass in either the full model, or just the id. There are plenty of reasons for wanting to do this, which is why this method was born. It's essentially a normalisation method, that will return the key (within reason). The assumption is that the value is either a model, or the id/key. I can be certain this is the case, because of how the code is accessed.

```php
public function persist(array $input, $model = null)
{
    if ($model) {
        $model = $this->getOneById($model);
    } else {
        $model = $this->make();
    }
    if ($model instanceof $this->model) {
        $model->fill($input);
        if ($model->save()) {
            return $model;
        }
    }
    return null;
}
```

This is a recent addition to my base repository, but I find it very useful. One of things I always do with my Eloquent models, is make sure the `$fillable` array is correctly configured. I know that the processing of my model data has happened outside of the repository (because it's not the repositories job), so I can pass an array to the method, to persist the data to the database. If I was performing a create action, the second argument would be null, and if it was an update, I'd pass in the model (or the id).

This isn't strictly needed, but it's helpful. It has made partial updates simpler, and has stopped me having to write specific create and/or update methods.

```php
public function delete($model): ?bool
{
    if ($model instanceof Model) {
        return $model->delete();
    }
    $id    = $model;
    $model = $this->make();
    return $model->newQuery()
        ->where($model->getKeyName(), $id)
        ->delete();
}
```

For every 10 repositories I write, I probably need 1, maybe 2 custom delete methods. For the rest, I just use this inherited method. Simple and tidy.

```php
public function getBy(): ?Collection
{
    $model = $this->query();
    if (\func_num_args() === 2) {
        list($column, $value) = \func_get_args();
        $method = \is_array($value) ? 'whereIn' : 'where';
        $model  = $model->$method($column, $value);
    } elseif (\func_num_args() === 1) {
        $columns = func_get_arg(0);
        if (\is_array($columns)) {
            foreach ($columns as $column => $value) {
                $method = \is_array($value) ? 'whereIn' : 'where';
                $model  = $model->$method($column, $value);
            }
        }
    }
    return $model->get();
}
```

This method is a super useful helper function that lets me retrieve multiple rows by basic column => value conditions. For example, I can do the following;

```php
$inactive = $repository->getBy(['active' => 0]);
```

This method is actually accompanied by two others;

```php
public function getOneBy(): ?Model
{
    $model = $this->query();
    if (\func_num_args() === 2) {
        list($column, $value) = \func_get_args();
        $method = \is_array($value) ? 'whereIn' : 'where';
        $model  = $model->$method($column, $value);
    } elseif (\func_num_args() === 1) {
        $columns = \func_get_args();
        if (\is_array($columns)) {
            foreach ($columns as $column => $value) {
                $method = \is_array($value) ? 'whereIn' : 'where';
                $model  = $model->$method($column, $value);
            }
        }
    }
    return $model->first();
}

public function __call(string $name, array $arguments = [])
{
    if (\count($arguments) > 1) {
        // TODO: Should probably throw an exception here
        return null;
    }
    if (0 === strpos($name, 'getBy')) {
        return $this->getBy(snake_case(substr($name, 5)), $arguments[0]);
    }
    if (0 === strpos($name, 'getOneBy')) {
        $column = snake_case(substr($name, 8));
        return \call_user_func([$this->make(), 'where'], $column, $arguments[0])->first();
    }
}
```

The `getOneBy` method is the sister of the `getBy`, essentially performing a `first()` on the query, rather than a `get()`.

The `__call` method allows you to do things like `getByActive(0)`, `getOneById(1)` and so on and so forth. The magic method mapping only allows for one condition. As I write this, I also realise that the `getOneBy` and `getBy` can abstracted out, as there's some duplicate code there.

### Conclusion

Either using a base repository that's already available, or creating yourself one, can simplify the process of using a repository. There's no need to complicate everything with as many interfaces as there are repositories.

I hope this has helped those of you there were on the fence or unsure. If you have any further questions, feel free to leave a comment.