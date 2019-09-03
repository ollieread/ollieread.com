Eloquent is a well thought out powerful ORM. It makes the initial steps of any Laravel build easy, but at this point it has become somewhat bloated. 

Many a time I've encountered a wall when I tried to do something with Eloquent that it absolutely didn't like. Sure, it's flexible enough that I can work around, and after some time digging through the masses of functionality and methods that come with every Model, I was able to find what I needed, but it all just felt a bit...dirty.

After a while I noticed that some of my codebases were rife with 'work arounds' (read: hacks) to achieve what I needed while still within the confines of Eloquent. That didn't sit well with me, so I started exploring methods to get away from this way of doing things. Then came...

# Doctrine
Doctrine again, is a super powerful ORM and while more complex to setup, it can simplify your life. For a while, I swore by Doctrine and used it in place of Eloquent whenever I could. The plus side of Doctrine, much like Eloquent, is that it can do everything. The down side, also like Eloquent, is that it can do everything. Doctrine, as good as it is, is a monolithic ORM that dwarfs Eloquent in comparison.

While Eloquent uses the Active Record pattern, Doctrine uses the Entity Mapper pattern, something that due to my days with Java, I was instantly in love with.

# Bringing Entities to Eloquent
I didn't want to write my own ORM, because ORMs are complicated and hard. But what if I could bring the approach that I love, to Eloquent?

This is actually where Articulate started, hence the name Articulate. It was originally designed to be a wrapper for Eloquent, that let me return entities instead of models. The models were still there, but were treated as query objects that were just used to hydrate my simple data object entities. This worked well, and appeased my need for something different, for a while. After the initial high of working with entities wore off, I realised that the underlying problem was still there, I was still having to add 'work arounds' here and there, and the underlying code was still ugly to look at. The only logical next step, was to remove the models entirely...

# Stripping out Models
So all Eloquent models are essentially fancy wrappers for the underlying query builder, and that's cool, because I really like that query builder. However, in removing the models from the equation, I found that I had no way to specify tables, columns or their data types. No way to provide relationship configuration.

For a brief time, this didn't bother me. I was happy hard coding that stuff in repositories, because it all seemed..nicer. I'd build my query, run it, then throw the results at an entity manager that would hydrate an entity for me. Slowly I started adding getters and setters that cast and transformed data to the correct types.

Eventually I realised that although simpler than Eloquent models, my entities still had loads of code that they probably didn't have any right to have. So I introduced mappings, Doctrine uses mappings, so I'll go with that...

# Mapping my Entities
So I created some mappings, and column/attribute classes to handle the different types. In my mappings you'd provide the table name, the entity class name, and then map each column that you wanted mapping, to a type. An example of one of the earlier mappings is below;

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

It's relatively straight forward, you set the attributes for the key, tell it what repository to use and then map all the other attributes you care about.

Again, this worked well, I'd use the laravel query builder and then throw the result at an entity manager. The entity manager didn't care where the data came from, just that it was there. I created myself a base repository that had some persistence code in there so I didn't have to write update or insert queries, or manually populate the timestamp attributes. This was all good and well, but checking for the presence and value of the `updated_at` column wasn't a very efficient way of telling whether something should be an update, or insert, since I had entities that didn't have timestamps.

To achieve this, I'd need to introduce some sort of basic state to my entities...

# Introducing Entity States
Persistence state is super simple, just add a boolean flag and a method that checks whether the entity is persisted. But I didn't want to litter my entities with this. So instead, I created a base entity that had this functionality in. I added another method to mark an entity as persisted, and then rolled with it. 

Eventually a time came when I only wanted updates to be performed if the entity was dirty. So in goes another state. Super simple, keeps track of which attribute has changed, but that's going to get annoying having to do that in every setter. So I made getters and setters optional, and instead introduced an attributes array to the base entity. It worked quite well, and with a `__get()` and `__set()` implementation I was able to use dynamic properties (which I defined in my docblocks). The base entity would check for a getter so that I could support completely dynamic attributes that didn't exist in my attributes array, and all was well.

The only problem now, was that when I hydrated an entity, it was dirty? So in goes a method to clean the dirty state, that would be called after initial hydration.

I was quite happy with what I had, and I was especially happy with the fact that I had created a data source agnostic system. The only part of my code that knew about the data source, was the repository, and those could be anything. After a while, I realised that the mappings made the code specific to Laravels query builder, or at least, a database....

# Abstracting Data Sources
My next step was to abstract out data sources. I created myself an `Illuminate` source that had a builder (overridden Laravel query builder), base repository, source definition, and even its own mapping where you could provide the table name and connection name.

Although only one data source existed, Articulate was finally, a data source agnostic package, or as I like to refer to it, a data source agnostic entity mapper pseudo ORM. Bit of a mouth full, but I like it.

Off I went to build some projects using my new pseudo ORM, and things were wonderful. My code seemed cleaner, there was a bigger separation between data source and domain objects, and again, life was wonderful. I couldn't however, shake the idea that Articulate couldn't be data source agnostic with only one data source. Fortunately, I had a pet project where I'd been trying to abstract out the way that we interact with REST APIs, since there seemed to be no standard. So I created Respite (you can [read about it here](https://ollieread.com/blog/2018/07/19/treating-apis-like-any-other-data-source)), a package that provided a fluent builder to build up API requests for RESTful APIs. I decided to go a step further and introduce a Respite source into Articulate, because as far as I was concerned, it'd be super cool to treat APIs like they're just a database!

It was at this point, having introduced a second data source support, and made Articulate truly data source agnostic in my mind, that I realised what I had done. I had, for all intents and purposes, built an ORM. Truly, I had accidentally built an entire ORM. Sure, it's far simpler than Doctrine, and quite different to Eloquent. There may be certain parts that you'd expect to be there that aren't, but it's flexible. It's easily customisable. And you know what? I love it!

# Accepting my Fate
With the realisation that I had in fact, created an ORM, I decided to just go full hog and treat it as such, introducing the following;

### Components
Components are objects that encapsulate one or more columns. A primary example of this would be an `Address` component that contains the following attributes;

- `address_1`
- `address_2`
- `address_3`
- `address_city`
- `address_county`
- `address_country`
- `address_postcode`

In this case I can pass my address component anywhere that needs an address, so I can create more flexible code, and rather than different implementations for different address sources, I can have one implementation.

On top of that, it means that I don't have to map those attributes on each entity. Instead I can create one global component mapping, meaning that I just add;

```php
$mapping->component('address', Address::class)
```

And there we go, my entity has inherited the mapping, so instead of `$entity->address_line_1` I can just do `$entity->address->line_1`. It will also work for writes, providing that the component isn't immutable (value object).

### Multiple Inheritance 
While similar to the feature of Doctrine with the same name, it's slightly different. It's closer to single table inheritance, but since it doesn't care about tables, it's about the entities. 

A good example of this is the [discord api](https://github.com/ollieread/discord-api) package I've been working on which utilises Articulates Respite source. The discord API will return channels, but a channel can be a DM, text channel or voice channel. There is an attribute that specifies the type, and with each type, there are different sets of attributes. So I define this in my mapping like so;

```php
$mapping
    ->setChildClasses(Channel\Text::class, Channel\DM::class, Channel\Voice::class, Channel\Category::class)
    ->setMultipleInheritance(function (array $data) {
        switch ((int)$data['type']) {
            case self::TEXT:
                return Channel\Text::class;
            case self::DM:
                return Channel\DM::class;
            case self::VOICE:
                return Channel\Voice::class;
            case self::GROUP_DM:
                return Channel\DM::class;
            case self::CATEGORY:
                return Channel\Category::class;
        }
        return Channel::class;
    });
$mapping->snowflake('id')
    ->setImmutable();
$mapping->int('type')
    ->setImmutable();
$mapping->snowflake('guild_id')
    ->for(Channel\Voice::class, Channel\Text::class, Channel\Category::class);
$mapping->int('position')
    ->for(Channel\Voice::class, Channel\Text::class, Channel\Category::class);
$mapping->entity('permission_overwrites', Channel\Overwrite::class, true)
    ->belongsTo(Channel\Voice::class, Channel\Text::class);
$mapping->string('name');
$mapping->string('topic')
    ->for(Channel\Text::class);
$mapping->bool('nsfw')
    ->for(Channel\Voice::class, Channel\Text::class);
$mapping->snowflake('last_message_id')
    ->for(Channel\Text::class, Channel\DM::class);
$mapping->int('bitrate')
    ->for(Channel\Voice::class);
$mapping->int('user_limit')
    ->for(Channel\Voice::class);
$mapping->array('recipients')
    ->for(Channel\DM::class);
$mapping->string('icon');
$mapping->snowflake('owner_id')
    ->for(Channel\DM::class);
$mapping->snowflake('application_id');
$mapping->snowflake('parent_id')
    ->for(Channel\Voice::class, Channel\Text::class, Channel\Category::class);
$mapping->timestamp('last_pin_timestamp', 'c')
    ->setImmutable()
    ->for(Channel\Text::class);
```

### Relationships
A core part of any ORM is relationships. I'm currently working on an implementation of this, but in a simpler way than Laravel. Since you can already specify entity attributes, like in the example above, I simply add a `setResolver()` method to entity attributes, that takes an instance of `Sprocketbox\Articulate\Contracts\Resolver` or `\Closure`.

It's still a WIP, and I've added a few Illuminate specific resolvers, but my local code looks like this for the entity definition;

```php
$mapping->entity('user', User::class)
        ->setResolver(new BelongsTo('user_id', 'id'))
        ->setColumnName('user_id');
```

So when querying the entity that this mapping is for (`Assignments`) I can simply add `$query->with('user')` and it'll use the resolver to load and populate the user.

The wonderful side effect of this particular piece of functionality, is that it will work across data sources. I could have `$query->with('stripe_customer')` in my query, and after retrieving my user from the database, it'd call the Stripe API, retrieve the customer data, build it into a previously define entity, and inject it. How marvelous!

# Should I use Articulate?
If you want to. I enjoy it far more than Eloquent, and I find that it keeps my codebase tidier, but it's not everyones cup of tea. It's primary reason for existence is that some of the things I was working on required some quite complex queries, along with some complex functionality, so I was trying to find a way to make the codebase less daunting.

If you want to use Articulate, you can find it [here](https://github.com/sprocketbox/articulate) and you can find WIP documentation [here](https://sprocketbox.github.io/articulate).


I'm interested in hearing your thoughts on the package, whether you've used it or would consider it, or even your thoughts on Eloquent.