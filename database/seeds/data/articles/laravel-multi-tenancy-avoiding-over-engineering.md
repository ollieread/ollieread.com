<div class="notice notice--info">
<p>I'm currently working on a <a href="https://multitenancy.dev" target="_blank">Multitenancy with Laravel</a> course and package that includes the topics covered in this article, and more.</p>
</div>

For those of you that follow my posts, packages or ramblings on Twitter, you'd have noticed that I am a big fan of a multi-tenant systems, specifically those that use subdomains. You may even be aware that I had a package for this very purpose. The package itself has been dormant for a while and has as of yesterday, been abandoned. The primary reason for this is that the layer of abstraction that it added, although simple, was barely worth it.

In this article I'm going to go through the ins and outs of creating a multi-tenant system with Laravel, to show that it really isn't that big a deal.

# What is multi-tenancy?
To quote the [multi-tenancy](https://en.wikipedia.org/wiki/Multitenancy) article on Wikipedia;

> The term "software multi-tenancy" refers to a software architecture in which a single instance of software runs on a server and serves multiple tenants. A tenant is a group of users who share a common access with specific privileges to the software instance. With a multi-tenant architecture, a software application is designed to provide every tenant a dedicated share of the instance - including its data, configuration, user management, tenant individual functionality and non-functional properties. Multi-tenancy contrasts with multi-instance architectures, where separate software instances operate on behalf of different tenants.

To put this in simple terms, a multi-tenanted system is one that appears to be a different system when accessed as a different tenant. 

A good example of this would be the Atlassian products with cloud hosting. When I go to `myusername.atlassian.net/wiki` I see Confluence, except it has my data, my themes, my styles and my information. If you were to go `anotheruser.atlassian.net/wiki`, you'd see Confluence, but it would all be configured as they have configured it. This particular example uses domain based tenant identification, specifically, subdomains. The first part of the URL tells Confluence that I want to access the content for `myusername`, and the internal code prevents me from accessing any content or information that isn't linked with me account in some way.

### What is multi-instance?
Multi-instance systems are similar to multi-tenancy, except that rather than have a single instance of the software serving all of the tenants, there is an instance per tenant. This is far more complicated to achieve, but it opens up a lot of possibilities.

With a multi-instance system, you can have particular features built into a specific tenants instance, allowing for completely custom features. You can do this with multi-tenanted systems, but then your codebase becomes a complete mess, and it's not entirely that easy to do properly.

### So which do I use?
Both multi-tenant and multi-instance approaches have their shares of pros and cons.

**Use Multi-Instance if...** 
You want your tenants to have custom bespoke functionality, such as additional features. I'm not talking about tenants having different feature sets, I'm talking about features and functionality that only a particular tenant has, where the code was written specifically for them and doesn't work for other tenants.

**Use Multi-Tenant if...** 
Essentially, you should use multi-tenant if you don't want the above (that's why I put that one first).

The above are designed to be guidelines, as ultimately the choice is yours (or the stakeholders), and I've been intentionally generic because it'd be possible to list all possible use cases.

# Implementing multi-tenancy
Seeing as you're now reading this, I can only assume that you've decided to go for the multi-tenanted approached, so lets look into the different ways we can achieve this.

### I found this package for Laravel that provides multi-tenant support
Now, I can't speak for every multi-tenant package, but I can safely say that of those that I have encountered, none should be used. Yes, that does include my now abandoned package.

Why you ask? Well let me tell you. Over the years of working with multi-tenant systems I researched a lot, not just the concepts of multi-tenancy, but the code that was available out there. After all, multi-tenancy does seem quite daunting when you get into it. The truth is, it's really not that complicated, and all of the packages I found did something or other that just reeked of bad practice, or was over engineered. Here are a couple of things that I found, that should be avoided.

**Nginx/Apache VirtualHost modifications**

A few packages out there actually edit your HTTP server configurations as and when a new tenant is created. This is absolutely a no go. Besides the fact that it's completely pointless, as you can achieve the same result by configuring your server correctly (I will cover this a bit later on), having your web server edit configuration files, specifically core configuration files that you need to run this service, is a HUGE issue, with potentially catastrophic results.

**Merging responsibility**

I understand that not many of you will be quite as fond of 'separation of responsibility' as I am, but it's definitely something to consider. Besides the fact that I genuinely believe that this principal/concept be held to, there are some cases where it just shows shody code. If you're using some sort of URL based tenant identification, Laravels `Request` object will be at the core of that, and will provide you with the necessary details. It is a trivial task to get what you need from this object and pass that a long down the chain, so there's absolutely no need to pass around the entire instance. If the `Request` object is being injected into models, scopes, repositories or generally anything outside of the HTTP layer (middleware, controllers, etc), avoid it. What else could be lurking in the code?

**Over Engineering**

I fell victim to this with my package, though not quite to the extent that I have seen. Later on in this article I'm going to give you some code to show how to implement the different methods of multi-tenancy, and you'll see that they don't involve much code at all. The majority of the packages I have seen over engineer their approaches to the nth level. You may find yourself saying 'But they need be generic, and cover every eventuality'. They do, but again, that can achieved with very little code.

### But multi-tenancy is complicated
As mentioned above, it's really not. It can seem very daunting when reading up, but then again, any pattern will, just look at repositories.

Generally there are a couple of questions you should answer before writing any code.

**How will tenants be identified?**

**Do my tenants need separate databases?**

There are many ways for a tenant to be identified, but for the purpose of this article I'll be covering;

- URL identification *(Identified by part of the URL)*
    - Domain
    - Subdomain
    - Path

I will also be covering both single and multi-database setups.

Now let's take a look at some code.

# Identifying the tenant
The core part of any multi-tenant system is the tenant, so identifying which tenant the request is for is paramount. 

All of the following code will assume the existence of a Tenant model (`App\Tenant`). Before we get into the specific methods of identifying the tenant, we need to create some base classes that all of the methods will use.

### Tenant Manager
We need a way to store the current tenant so that it can be accessed where it is needed. We're going to do this by creating a super simple tenant manager, that we will bind to Laravels IoC containerso that we can access the same instance every time we need it.

```php
<?php
namespace App\Services;

use App\Tenant;

class TenantManager {
    /*
     * @var null|App\Tenant
     */
     private $tenant;
   
    public function setTenant(?Tenant $tenant) {
        $this->tenant = $tenant;
        return $this;
    }
    
    public function getTenant(): ?Tenant {
        return $this->tenant;
    }
    
    public function loadTenant($identifier): bool {
        // Identify the tenant here
    }
 }
```

This is a fairly simple class, as we don't really need anything complex. So what do we have?

- We have a getter (`getTenant()`) that returns the current tenant or null
- We have a setter (`setTenant(?Tenant $tenant)`) in case we need to manually set (or unset) the tenant
- We have a method for loading the tenant (`loadTenant($identifier)`) 

The `loadTenant($identifier)` method is intentionally empty, as that will be the method that we populate for each of our identification methods.

Laravel has a powerful IoC container that lets us bind objects for injection. Typically this is used to bind a concrete for a given interface, but it can also be used to resolve certain instances that aren't just simply instantiated. If you're new to the container, you can read the documentation [here](https://laravel.com/docs/5.6/container#binding-basics). Here, we're going to leverage the power of this container to;

- Make sure we receive the same instance of `TenantManager` 
- Avoid having to call `TenantManager->getTenant()` everywhere.

Either open up `AppServiceProvider` (`app/Providers/AppServiceProvider.php`) or create and open a new service provider (You can use `php artisan make:provider TenantServiceProvider`). Go to the `register()` method and add the following;

```php
$manager = new TenantManager;

$this->app->instance(TenantManager::class, $manager);
$this->app->bind(Tenant::class, function () use ($manager) {
    return $manager->getTenant();
});
```

Now, when we type hint as `TenantManager` we will receive the same instance, the one that will be populated with the current tenant. We can also now type hint with `Tenant` if we want to retrieve the current tenant, without injecting the manager and calling the get tenant method.

### Identification Middleware
If we're going ahead with URL identification for tenants, we're going to create some middleware to simplify the whole process. Go to your terminal and type `php artisan make:middleware IdentifyTenant`. Once this is done open up `IdentifyTenant` (`app/Http/Middleware/IdentifyTenant.php`) and modify it so it looks like the following;

```php
<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\TenantManager;

class IdentifyTenant
{
    /**
    * @var App\Services\TenantManager
    */
    protected $tenantManager;
    
    public function __construct(TenantManager $tenantManager)
    {
        $this->tenantManager = $tenantManager;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
```

All we did here was add a constructor and a property, so that our `TenantManager` instance is injected.

Now we just need to wrap our tenant routes in a group with our newly created middleware.

```php
Route::group([
    'middleware' => \App\Http\Middleware\IdentifyTenant::class,
    'as'         => 'tenant:',
], function () {
    // Tenant routes here
});
```

I added an `as` here so that all tenant route names are prefixed with `tenant:`. Trust me, you'll find this useful.

We now have our tenant manager, and our identification middleware, lets actually identify some tenants.

### Domain Identification
If you're familiar with Laravel routes you may be tempted to use the `domain` attribute for route groups, but we really don't need that here. If you plan to serve a generic section of your system, such as the initial landing and error page, you should wrap those routes in a group using the `domain` you want them to be accessed from.

First we want to edit our `IdentifyTenant::handle` method;

```php
public function handle($request, Closure $next)
{
    if ($this->tenantManager->loadTenant($request->getHost())) {
        return $next($request);
    }
    
    throw new NotFoundHttpException;
}
```

Here we are grabbing the domain name `$request->getHost()` and passing it to `$this->tenantManager->loadTenant()` which we know will return a bool, then if it succeeds, containing down the middleware chain, or throwing a 404 if no tenant was found. We're doing this all inline inside an if, as there's no need to create unnecessary variables.

Next we're going to edit `TenantManager::loadTenant()` to perform the identification.

```php
public function loadTenant(string $identifier): bool {
    $tenant = Tenant::query()->where('domain', '=', $identifier)->first();
    
    if ($tenant) {
        $this->setTenant($tenant);
        return true;
    }
    
    return false;
}
```

Here we're taking the string identifier, and retrieving the first database match for it. I'm assuming that the tenant model has a `domain` column that contains the domain, and if it does, I'm confident that it has a unique index (If that was too subtle, add a unique index).

There we go, domain based tenant identification done, onto the next.

### Subdomain Identification
Subdomain identification is almost identical to the domain identification, and the rule about not using the `domain` group attribute, still applies.

First we're going to add an env variable to both `.env` and `.env.example`.

```
TENANT_DOMAIN=myawesomeapp.com
```

We're going to use this to identify whether the current URL is a tenant subdomain.

Next we want to edit our `IdentifyTenant::handle` method;

```php
public function handle($request, Closure $next)
{
    $host = $request->getHost();
    $pos = strpos($host, env('TENANT_DOMAIN'));
    
    if ($pos !== false && $this->tenantManager->loadTenant(substr($host, 0, $pos - 1)) {
        return $next($request);
    }
    
    throw new NotFoundHttpException;
}
```

While this is very similar to the domain identification, the difference is the important part. After getting the host we find the position of the `TENANT_DOMAIN` within it, using `strpos()`. We then make sure that the result of this isn't _EXACTLY_ false (`strpos` can return 0, with is equal to false, where as a return of false means it didn't find it), and pass only the first part to our `loadTenant()` method. We're not passing the whole hostname for many reasons, but mostly because it leaves us open to change domains or use this for other things in the future.

Next we're going to edit `TenantManager::loadTenant()` to perform the identification.

```php
public function loadTenant(string $identifier): bool {
    $tenant = Tenant::query()->where('subdomain', '=', $identifier)->first();
    
    if ($tenant) {
        $this->setTenant($tenant);
        return true;
    }
    
    return false;
}
```

As you can see, this is identical to the domain identification, except we are using a different column. Like with the `domain` column, I'm assuming this is a unique column that contains only the tenant 'slug'.

And that's it for subdomain identification. Marginally more complex than domain identification.

### BONUS: Subdomain OR Domain identification
It's entirely possible that you're providing subdomains for all tenants, but domains for those that pay for a higher package (or those that you like). You can modify both of the above options to allow for this.

We're going to create the env variable as mentioned in subdomain identification, then we're going to merge both pieces of code for the middleware.

```php
public function handle($request, Closure $next)
{
    $host = $request->getHost();
    $pos = strpos($host, env('TENANT_DOMAIN'));
    
    if ($this->tenantManager->loadTenant($pos !== false ? substr($host, 0, $pos - 1) : $host, $pos !== false)) {
        return $next($request);
    }
    
    throw new NotFoundHttpException;
}
```

The biggest difference here is that we remove the `$pos` check from the if, because there may be no subdomain. We replace our `loadTenant` argument with a ternary if that passes the 'slug' if present, or the whole host. We're also passing in another argument which is a boolean representing whether or not this is a subdomain.

Now we can't make some minor amendments to our `TenantManager::loadTenant()` method.

```php
public function loadTenant(string $identifier, bool $subdomain): bool {
    $tenant = Tenant::query()->where(subdomain ? 'subdomain' : 'domain', '=', $identifier)->first();
    
    if ($tenant) {
        $this->setTenant($tenant);
        return true;
    }
    
    return false;
}
```

We've add in support for the second argument, as well as another ternary if statement to change the column name we query against.

This is just the basic identification and doesn't include any logic about whether or not a tenant can have domain. Even though that is unfortunately outside the scope of this article, and that I'd never condone the adding of methods to the eloquent model (because it's already bloated), the following is a quick example of how you'd modify your `TenantManager::loadTenant()` method to achieve this.

```php
if ($tenant) {
    if (! $subdomain && !$tenant->canHaveDomain()) {
        throw new NotFoundHttpException;
    }
    $this->setTenant($tenant);
    return true;
}
```

### Path Identification
Path identification would be identifying the tenant based on a part of the URL path, specifically the first section after the domain. An example of this would be `http://myawesome.app/ollieread`, where `ollieread` is the tenant identifier.

For this example, we're actually going to make use of Laravels route parameters, and a little known route instance method that allows us to remove the parameter once it has been processed, so that we don't need to expect it on our controllers. Our tenant route group is going to be slightly different to the one up the top.

```php
Route::group([
    'prefix'     => '/{tenant}',
    'middleware' => \App\Http\Middleware\IdentifyTenant::class,
    'as'         => 'tenant:',
], function () {
    // Tenant routes here
});
```

All we've done is add a prefix using a route variable `tenant`. Now we can modify our `IdentifyTenant::handle()` method.

```php
public function handle($request, Closure $next)
{    
    if ($this->tenantManager->loadTenant($request->route('tenant'))) {
        $request->route()->forgetParameter('tenant');
        return $next($request);
    }
    
    throw new NotFoundHttpException;
}
```

Your instance of Laravels `Request` object will, if for a matching route, contain an instance of `Route` that allows us to access the parameters like you see above. Here we're assuming that all of the URLs must be prefixed with `/{tenant}` so we throw a 404 if no tenant was found. If the tenant was found, we remove the parameter (we don't need it again), and continue down the chain of middleware.

If you plan to support other none tenant specific pages that are also accessible, you'll want to make sure that your tenant route group is defined _AFTER_ all of the others.

Lets modify our `TenantManager::loadTenant()` method.

```php
public function loadTenant(string $identifier): bool {
    $tenant = Tenant::query()->where('slug', '=', $identifier)->first();
    
    if ($tenant) {
        $this->setTenant($tenant);
        return true;
    }
    
    return false;
}
```

Like in all of the other examples, I'm assuming that `slug` is a unique column containing the tenants specific identifier.

There we go, domain, subdomain and path tenant identification, with a bonus subdomain and domain example thrown in.

# How do I generate tenant routes?
Ahh, this is something that's quite easily overlooked. Since in all of the examples above the URL requires a specific piece of identifying information, your routes just simply won't work properly. In the case of the path identification, it won't even be able to find the routes as there will be a missing parameter.

While I'm going to regret suggesting adding a method to your `App\Tenant` model, it's the simplest example. So open up your model and add the following method;

```php
public function route($name, $parameters = [], $absolute = true) {
    return app('url')->route($name, $parameters, $absolute);
}
```

This is a 1:1 copy of the `route()` helper method, so that you can do things like `$tenant->route('home.index')` or whatever you please. Now, depending on the method of identification that you're using, there are some changes we need to make to this method.

### Domain & Subdomain Identification
For domain and subdomain identification, either supporting both or only one, we're going to do the following;

**Domain**

```php
public function route($name, $parameters = []) {
    return 'https://' . $this->domain . app('url')->route($name, $parameters, false);
}
```

**Subdomain**

```php
public function route($name, $parameters = []) {
    return 'https://' . $this->subdomain . app('url')->route($name, $parameters, false);
}
```

**Both**

```php
public function route($name, $parameters = []) {
    $host = $this->domain ?? $this->subdomain;
    return 'https://' . $host . app('url')->route($name, $parameters, false);
}
```

We're removing the third argument from the method and passing `false` in its place to the real `route()` method. The reason for this is that when that is `true`, it will prefix the returned route with the URL, which we're doing manually. I'm using `https://` because everything should be `https://` but the choice is yours.

### Path Identification
Path identification is different, yet still as simple. All we need to do is add the tenant path to the start of the arguments.

```php
public function route($name, $parameters = [], $absolute = true) {
    return app('url')->route($name, array_merge([$this->slug], $parameters), $absolute);
}
```

There we go. Since our little array containing the tenant slug/path is numerically keyed (automatically), it'll be at the start of the resulting array, whether `$parameters` is numerically keyed or not.

# Handling tenant data
By now you've probably encountered two groups of people, polarised by this particular part of multi-tenancy. On one hand you have those that demand that you use a single database for your tenant data (Database as in `CREATE DATABASE`), while their rivals, demand that you use the multi-database approach.

In reality it's not as clear cut as that. I'm a rather large proponent of the multi-database approach, but that's primarily because it made sense with what I was building. In fact recently, while working on my [WorldBuilder Online](https://worldbuilder.online) project, I came to realise that the requirements had shifted so much, that my multi-database approach was no longer viable and I had to go back to the drawing board on that one.

While you can't entirely avoid having to refactor weeks worth of code, you can help reduce to possibility of doing so, by thinking about it ahead of time, because ultimately, the approach depends entirely on what your system is doing. Generally speaking, I find it boils down to a couple of reasons, so like the choice between multi-tenancy and multi-instance, I've come up with the basic examples below.

**Use Multi-Database if...** You require the added security of each tenants data being entirely separate. This will allow you to implement a few extra security measures with ease, such as public key encryption. You should also use multi-database if you're using a NoSQL solution (such as MongoDB) and are creating very complex and independent collections/table.

**Use Single-Database if...** Your users are not tenant specific, you have global community features that tie the tenants together, or the data you're storing isn't that complex or in need of higher security.

Whichever way you go, I've written the below to help you implement it inside your Laravel codebase.

### Single Database
The single database approach is arguable more complex than the multi-database approach. The reason for this is that you have to take a lot of extra steps. As with all other examples, I'll be assuming the existence of `App\Tenant`.

Any primary model that requires tenant specific data should have a `tenant_id` foreign key that references `id` on your `tenants` table. You can skip the foreign key on any secondary models. By now you're probably wondering what the difference between a primary and a secondary model is? Well wonder no more!

**A primary model is...** A model that belongs to a tenant, and cannot exist without its parent tenant. If your tenants can create posts, then your `App\Post` model should have a `tenant_id` foreign key.

**A secondary model is...** Any model that has ancestors that belong to a tenant. Much like with primary models, this model must only exist with its parent hierarchy. If your users can comment on posts, then you `App\Posts\Comment` model doesn't need a foreign key, as you can only ever access those comments with a valid instance of `App\Post`, which will always belong to a tenant.

Outside of this you have your other global models which we shall refer to as your tertiary models. This could be things like `App\Category` if all of your tenant posts must belong to a generic list of categories. These models don't need a foreign key as they're globally available and not tenant specific.

The simplest way to automate all of this is with the user of traits and Laravel scopes, specifically the global scopes rather than the query based scopes.

So to get started, lets create our global scope. Typically I would create this is `app/Scopes`, but you can create it wherever you like. We'll call it `TenantOwnedScope`.

```php
<?php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use App\Services\TenantManager;

class TenantOwnedScope implements Scope {
    public function apply(Builder $builder, Model $model) {
        $manager = app(TenantManager::class);
        $builder->where('tenant_id', '=', $manager->getTenant()->id);
    }
    
    public function extend(Builder $builder) {
        $this->addWithoutTenancy($builder);
    }
    
    protected function addWithoutTenancy(Builder $builder) {
        $builder->macro('withoutTenancy', function (Builder $builder) {
            return $builder->withoutGlobalScope($this);
        });
    }
}
```

Here we have our tenant scope, raring and ready to go. The `apply()` method will apply the given scope to the query automatically, it accepts an instance of the builder and the current model. We have our `extend()` method which allows us to add some optional scopes. In this example we add `withoutTenancy()` added by `addWithoutTenancy()` so that you can skip the tenant foreign key and retrieve all rows. This is particularly useful for admin areas where you want to see anything and everything.

The reason that we're using a where on the foreign key directly is that bogging everything down with one of Laravels automatically relationship checks is going to be slower, especially if the majority of your models have this scope. The relationship checks (`has()` and `whereHas()`) create subqueries which obviously add to a queries overhead.

Now, you can add this scope by overriding a models `boot()` method, but we don't want to do that, as we're going to do a little bit more magic.

We're going to create ourselves a trait, or a concern as Laravel refers to them. Typically I'd create this in `app/Concerns`, but you can create it wherever you like. We'll call it `OwnedByTenant`.

```php
<?php
namespace App\Concerns;

use App\Scopes\TenantOwnedScope;

trait OwnedByTenant {
    public static function bootOwnedByTenant() {
        static::addGlobalScope(new TenantOwnedScope);
    }
    
    public function tenant() {
        $this->belongsTo(Tenant::class, 'tenant_id');
    }
}
```

Once we've created this we can add it to any of our primary models like so;

```php
<?php

use App\Concerns\OwnedByTenant;

class PrimaryModel extends Model {
    use OwnedByTenant;
}
```

We've done it like this because of a nice little feature of Eloquent. Eloquent will, upon the booting of a model, inspect its traits for a `bootTheirName()` method (where `TheirName` is the name of the trait, so that it can boot any additional things, such as scopes. This is how Laravel soft deletes work. This actually automates the adding of our scope, so that you don't have to manually do anything. We've also added a `tenant()` method with automatically provide the belongs to relationship.

Now, this all works and we can query our primary models and only receive those that belong to the current tenant. But what about inserts? It's all good and well automatically appended where clauses to read, but our writes should have the `tenant_id` automatically populated.

We achieve this by making use of model events, specifically the `creating` event. We aren't going to worry about `update`, as providing that you've written your code properly, it'll be impossible for a model to not currently belong to a tenant. 

Go back to your `OwnedByTenant` trait and edit the `bootOwnedByTenant()` method to look like the following;

```php
public static function bootOwnedByTenant() {
    static::addGlobalScope(new TenantOwnedScope);
    
    static::creating(function ($model) {
        if (! $model->tenant_id && ! $model->relationLoaded('tenant')) {
            $model->setRelation('tenant', app(TenantManager::class)->getTenant());
        }
        
        return $model;
    }
}
```

Here we are binding a closure to the creating event. Laravel fires the `creating` event before performing the actual query, so it's the ideal place for this sort of thing. You may be wondering about my usage of `relationLoaded()` and `setRelation()`, but the reason for this is that if we simply did `if (! $model->tenant)` Laravel would generate and run a query to eager load that relationship, adding a completely unnecessary query. The usage of `setRelation()` is because Laravel doesn't entirely like having relationships set using their magic properties. It will also mark the relationship has loaded, preventing Laravel from to retrieve the tenant from the database if the relationship is accessed on this instance.

There we have it, a working single database implementation. The only final point of consideration for this approach is validation and unique indexes. You'll find that as you add more tenants, any unique indexes you have in the database will start shouting if multiple tenants add the same thing. A primary case of this would be if a user has an account with multiple tenants, and your users are tenant specific.

To get around this, add the parent foreign key to your unique indexes. For primary models it'd be `$table->unique(['tenant_id', 'slug'])` and for secondary models like `App\Posts\Comment`, it'd be `$table->unique(['post_id', 'user_id'])`.

Now you're going to have an issue with any validation rule that queries the database (`unique`, `exists`, etc), unless you define the rules like `Rule::unique('posts', 'slug')->where('tenant_id', app(TenantManager::class)->getTenant()->id)` which is long winded and takes away the automation that we want to gain from all of this. To get around this, we're going to define a method on our `TenantManager` class, that essentially does this for us. So open up your `TenantManager` and add the following methods;

```php
public static function unique($table, $column = 'NULL')
{
    return (new Rules\Unique($table, $column))->where('tenant_id', $this->getTenant()->id);
}

public static function exists($table, $column = 'NULL')
{
    return (new Rules\Exists($table, $column))->where('tenant_id', $this->getTenant()->id);
}
```

As you can see, these methods are direct copies of `Illuminate\Validation\Rule::unique()` and `Illuminate\Validation\Rule::exists()`. I'm only using these two here as they're the most commonly used, but the same method can be applied to any rules. Now when you define your validation rules you can just put `TenantManager::unique()`, but remember not to use this on secondary or tertiary models.

So now you have an automated single database approach to multi-tenancy, with database based validation support.

### Multiple Databases
The multi-database approach is very simple, with the exception of migrations and the actual creation of the tenant databases. Unlike the single database approach though, we don't have to bother with scopes or traits, because we know that a tenants database will contain only its data, and that's a beautiful thing.

First things first, we want to tell Laravel about our tenant databases by creating a new connection. For simplicity, I'm going to assume that it's on the same database server. 

Open up `app/config/database.php` and added the following **AFTER** the `mysql` connection. It's important to keep the original, as that's where our `tenants` table will exist, and any other global data.

```
'tenant' => [
    'driver'      => 'mysql',
    'host'        => env('DB_TENANT_HOST', '127.0.0.1'),
    'port'        => env('DB_TENANT_PORT', '3306'),
    'username'    => env('DB_TENANT_USERNAME', 'forge'),
    'password'    => env('DB_TENANT_PASSWORD', ''),
    'unix_socket' => env('DB_TENANT_SOCKET', ''),
    'charset'     => 'utf8mb4',
    'collation'   => 'utf8mb4_unicode_ci',
    'prefix'      => '',
    'strict'      => false,
    'engine'      => null,
],
```

You'll notice that here we're referring to some env variables that don't exist yet, so go ahead and define these in both your `.env` and `.env-example` files. Also pay special attention to the fact that we don't have a `database` entry in our config array.

So how will Laravel know what the database name is? Well, we're going to go back to our provider (`AppServiceProvider`/`TenantServiceProvider`) and add the following to bottom of the `register()` method.

```php
$this->app['db']->extend('tenant', function ($config, $name) use ($manager) {
    $tenant = $manager->getTenant();
    
    if ($tenant) {
        $config['database'] = 'tenant_' . $tenant->id;
    }
    
    return $this->app['db.factory']->make($config, $name);
});
```

So what we're doing is extending the default Laravel database resolver, and telling it that to create a new connection for our `tenant` connection, we should run this closure. This closure will load the current tenant, and set the database name. In this case I'm using `tenant_{ID}` as the format. We're not throwing an exception if no tenant is set, because there are times when we just want the connection, without a database, which you'll find out about later on.

To have our tenant specific models, both primary and secondary know what to do, we're going open them up and add `protected $connection = 'tenant';` to the top of the class.

Now we have a working multi-database approach to multi-tenancy. Our models know about the tenant specific database, and Laravel knows how to get the correct database. What we don't have however, is a way of creating the database, and a way of running tenant specific migrations.

Creating migrations the standard way is not going to work, as that's going to end up migrating them on our global database, which we don't want. Create a `tenants` directory within `database/migrations`, and append the `--path` option when creating a migration, like so;

```
php artisan make:migration create_posts_table ---path=database/migrations/tenants`
```

By default the migrator is going to ignore this directory, so we don't need to worry about it. But how do we run our tenant migrations? To do so, we're going to need to create a command by running the following in the terminal;

```
php artisan make:command TenantMigrate
```

This will create the file `app/Console/Commands/TenantMigrate.php`, so open it and edit it like so;

<div class="tabs">
<div class="tab__bar">
<div class="tab tab--active" data-target="code-57">Laravel 5.7</div>
<div class="tab" data-target="code-58">Laravel 5.8</div>
</div>
<div class="tab__content tab__content--active" data-name="code-57">

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TenantManager;
use App\Tenant;

class TenantMigrate extends Command {

    protected $signature = 'tenants:migrate';
    
    protected $description = 'Migrate tenant databases';
    
    protected $tenantManager;
    
    protected $migrator;
    
    public function __construct(TenantManager $tenantManager) {
        parent::__construct();

        $this->tenantManager = $tenantManager;
        $this->migrator = app('migrator');
    }
    
    public function handle() {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $this->tenantManager->setTenant($tenant);
            \DB::connection('tenant')->reconnect();
            $this->migrate();
        }
    }

    private function migrate() {
        $this->prepareDatabase();
        $this->migrator->run(database_path('migrations/tenants'), []);

        foreach ($this->migrator->getNotes() as $note) {
            $this->output->writeln($note);
        }
    }

    protected function prepareDatabase() {
        $this->migrator->setConnection('tenant');

        if (! $this->migrator->repositoryExists()) {
            $this->call('migrate:install');
        }
    }
}
```

</div>
<div class="tab__content" data-name="code-58">

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TenantManager;
use App\Tenant;

class TenantMigrate extends Command {

    protected $signature = 'tenants:migrate';
    
    protected $description = 'Migrate tenant databases';
    
    protected $tenantManager;
    
    protected $migrator;
    
    public function __construct(TenantManager $tenantManager) {
        parent::__construct();

        $this->tenantManager = $tenantManager;
        $this->migrator = app('migrator');
    }
    
    public function handle() {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $this->tenantManager->setTenant($tenant);
            \DB::connection('tenant')->purge();
            $this->migrate();
        }
    }

    private function migrate() {
        $this->prepareDatabase();
        $this->migrator->run(database_path('migrations/tenants'), []);
    }

    protected function prepareDatabase() {
        $this->migrator->setConnection('tenant');

        if (! $this->migrator->repositoryExists()) {
            $this->call('migrate:install');
        }
    }
}
```

</div>
</div>

This command is a modified version of the default migration command. The main differences are that we're injecting the tenant manager, and running all tenant migrations for all tenants. The most important bit of this class is the `\DB::connection('tenant')->reconnect()` call. When we change to the next tenant in the collection, the old connection is going to still exist, so Laravel won't run our closure, so we're run the migrations on the old tenants database.

The `prepareDatabase` method will create the standard `migrations` table within the tenant database, meaning that each tenant has a record of the migrations ran. 

Now that we have our command, register it by opening up `app/Console/Kernel.php` and adding `App\Console\Commands\TenantMigrate::class` to the`$commands` property.

Now, when you want to run your tenant migrations you can run the following;

```
php artisan tenants:migrate
```

You may wondering why we didn't just use the following;

```
php artisan migrate --path=database/migrations/tenants --database=tenant
```

The reason, is that that won't cycle through the accounts and update the connection with the tenant database name.

The only thing we have left to do, is create the tenant database in the first place. Typically, I've created myself a job called `TenantDatabase`, that runs the query. There are lots of ways of doing this, and lots of views and approaches, but the simplest is this.

Run the following command;

```
php artisan make:job TenantDatabase
```

This will create the file `app/Jobs/TenantDatabase.php`, so open it and edit it like so;

<div class="tabs">
<div class="tab__bar">
<div class="tab tab--active" data-target="code-57">Laravel 5.7</div>
<div class="tab" data-target="code-58">Laravel 5.8</div>
</div>
<div class="tab__content tab__content--active" data-name="code-57">

```php
<?php

namespace App\Jobs;

use App\Services\TenantManager;
use App\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class SetupAccountDatabase implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable;

    protected $tenant;

    protected $tenantManager;

    public function __construct(Tenant $tenant, TenantManager $tenantManager) {
        $this->tenant        = $tenant;
        $this->tenantManager = $tenantManager;
    }

    public function handle() {
        $database    = 'tenant_' . $this->tenant->id;
        $connection  = \DB::connection('tenant');
        $createMysql = $connection->statement('CREATE DATABASE ' . $database);

        if ($createMysql) {
            $this->tenantManager->setTenant($this->tenant);
            $connection->reconnect();
            $this->migrate();
        } else {
            $connection->statement('DROP DATABASE ' . $database);
        }
    }

    private function migrate() {
        $migrator = app('migrator');
        $migrator->setConnection('tenant');

        if (! $migrator->repositoryExists()) {
            $migrator->getRepository()->createRepository();
        }

        $migrator->run(database_path('migrations/tenants'), []);
    }
}
```

</div>
<div class="tab__content" data-name="code-58">

```php
<?php

namespace App\Jobs;

use App\Services\TenantManager;
use App\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class SetupAccountDatabase implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable;

    protected $tenant;

    protected $tenantManager;

    public function __construct(Tenant $tenant, TenantManager $tenantManager) {
        $this->tenant        = $tenant;
        $this->tenantManager = $tenantManager;
    }

    public function handle() {
        $database    = 'tenant_' . $this->tenant->id;
        $connection  = \DB::connection('tenant');
        $createMysql = $connection->statement('CREATE DATABASE ' . $database);

        if ($createMysql) {
            $this->tenantManager->setTenant($this->tenant);
            \DB::connection('tenant')->purge();
            $this->migrate();
        } else {
            $connection->statement('DROP DATABASE ' . $database);
        }
    }

    private function migrate() {
        $migrator = app('migrator');
        $migrator->setConnection('tenant');

        if (! $migrator->repositoryExists()) {
            $migrator->getRepository()->createRepository();
        }

        $migrator->run(database_path('migrations/tenants'), []);
    }
}
```

</div>
</div>

Don't worry, you don't need to have a queue setup if you don't want one, as this will just run inline when called. I created a job because a command shouldn't be called on the web, which is also why we have the `migrate()` method here. What this class does, is grab the tenant connection, run the statement to create the database, then set the current tenant and reconnect the connection so we're referencing the newly created database, and then run the migration so the database is up to date.

You can run the job by calling `App\Jobs\TenantDatabase::dispatch($tenant, app(App\Services\TenantManager::class))` wherever you need it to be ran.

So there you go, a fully functioning multi-database approach to multi-tenancy, that handles the database creation as well as tenant database migrations.

# What now?
Now you build out your system using the methods above. Have a play with the code I've provided and see if you can improve upon it, or tweak it.

I'm considering touching on a few follow ups at some point where I cover basic template customisation per tenant, as well as tenant specific storage.

Let me know in the comments below if there's anything you think I missed, anything you'd like to see, or even to show off something you built by following this.
