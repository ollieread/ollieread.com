<div class="notice notice--info">
This tutorial has been superseded by the <a href="/articles/series/laravel-roles-and-permissions">Laravel Roles and Permissions</a> series.
</div>


Access control is everywhere you look. GitHub, BitBucket, Slack, Atlassian, DigitalOcean, and many many more employ the usage of access control to...well...control access.

Does your project have need of multiple levels of admin, or does it support teams whereby users can invite other users to share access/contribute? Perhaps you have a discussion board/forum and you want certain users to administer, while others only moderate or contribute. If that's the case, then you'll want to implement some type of access control. The most common terms used to refer to this kind of functionality, is that of roles & permissions or RBAC.

Much like anything else, everyone and their dog has a Laravel package out there providing roles & permissions, and they're over-engineered or over-complicated. I know this is the second article I've written in as many weeks regarding the over-engineering of features, but it really is an issue. If something is over-engineered or over-complicated it's quite difficult to figure out what exactly is happening, and more often than not, you're not going to have the time to dedicate to figuring out what the developers are doing, so you just end up using the package blindly.

Do you really want to trust code that you don't know, to control something as sensitive as the access that users have to your content? It's a rhetorical question, the answer is no. If you answered yes, you're wrong.

In this article I'm going to run down how to implement a relatively simple, yet flexible and powerful RBAC with Laravel, as well as explaining it as we go.

# What exactly is an RBAC?
RBAC stands for role based access control and is more than likely what you actually think of when you hear the term ACL. I myself have fallen foul of this as I wrote an article back in 2016 about writing a simplified ACL with Laravel. What I wrote, was an RBAC, and that article is essentially the ancestor of this one.

To quote the [RBAC](https://en.wikipedia.org/wiki/Role-based_access_control) Wikipedia entry;

> Role-based-access-control (RBAC) is a policy neutral access control mechanism defined around roles and privileges. The components of RBAC such as role-permissions, user-role and role-role relationships make it simple to perform user assignments.

As you'll see from the above, this falls more inline with our role and permission setup. The idea is that a user, or a given authenticated entity is assigned one or more roles. Each of those roles will have a set of permissions which directly correlate to actions within the system, or at least, represent them. It's rare that we care whether or not a user has a particular role as they're essentially just a way to group permissions.

### But what about ACL?
The terms ACL and RBAC are commonly mixed up, and while the definitions are theoretically interchangeable, they're technically quite different. The Wikipedia entry for [ACL](https://en.wikipedia.org/wiki/Access_control_list) says;

> An access control list (ACL), with respect to a computer file system, is a list of permissions attached to an object. An ACL specifies which users or system processes are granted access to objects, as well as what operations are allowed on given objects.

With an ACL you're controlling access to individual objects, or in our case, rows. You could arguably consider Laravels Policies to be a form of ACL, as you check per object/model instance, rather than as a whole on the resource. It's a very loose comparison as Policies are open to interpretation and implementation.

# Creating an RBAC
Creating role based access control within Laravel is actually quite a simple matter. Much like multi-tenancy, it's one of those things that appears far more complicated than it actually is.

Here's what we know of RBACs so far;

- It has users
    - Users have 1 or more roles
- It has roles
    - Roles have permissions
- It has permissions

This particular list translates almost directly to Eloquent relationships, so lets start there. We're going to need three models to achieve this, but I'm assuming that you already have a `User` model or some other authenticated model, whether it's an admin or what.

### Creating our Role model
Our first model to create is a `Role` model, aha role model!

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {
    protected $table = 'roles';
    protected $fillable = [
        'name', 'ident', 'description', 'active', 'level',
    ];
    protected $casts = [
        'active' => 'bool',
        'level' => 'int',
    ];
}
```

It's a straight forward model, though you may be curious about 3 of the fields I've added here. The `ident` field is essentially a sluggified version of the `name` and exists solely in case we do care what role a user has. The `active` field is one I add to almost everything these days, as this allows me to enable or disable a role, and trust me, there are plenty of reasons you'd do this.

The last field, `level`, is there to define a loose hierarchy of roles. For example, you may have three different roles with permission to edit users, and without this level control, a user with a lower role could swap out their roles, or even edit users above them in the hierarchy. For the purpose of this article, I'm going to consider `0` to be the highest, because ascending order is simplest.

### Creating our Permission model
Now that we've got our roles, we need to add permissions. 

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model {
    protected $table = 'permissions';
    protected $fillable = [
        'name', 'ident', 'description', 'active',
    ];
    protected $casts = [
        'active' => 'bool',
    ];
}
```

Again, relatively straight forward. It's almost identical to the `Role` model, save for the `level` field. I've kept the `active` field because disabling a permission is a brilliant way of quickly disabling features. The feature technically isn't disabled, but no one can get access to it.

I'd like to talk about the `ident` field for a minute. So, I like to name my permissions in the same way that I name routes, and those follow the pattern `resource[.child-resource].action`. In fact, I have a direct correlation between 90% of my permissions and the route names. The reason I use the dot notation, is that if I were to add the permission `blog.post.index`, my system would add the following extra permissions;

- `blog.*`
- `blog.post.*`

This would allow me to give a particular role blanket permissions for the `blog` namespace or the `blog.post` namespace. I also make sure to add a `*` permission which is essentially a wildcard that's acceptable by any and all. Normally this is assigned to an `admin.super` role that only I have access to.

On top of wildcard permissions, I'll also often create other permissions that don't strictly follow the pattern. A good example of this would be `user.email`, whereby only users/admins with this permission can see the email address of a user. With some CMS systems I've built, I've created a `Page` model that has a `structure_edit` and a `has_content` flag, with the former controlling whether or not the slug can be edited, and the latter being whether or not to display the WYSIWYG editor. Now, I don't want to go around editing the database to change these, so I hide the form checkboxes in an if statement that checks for `page.admin`, allowing me to assign an extra layer of granular permissions on top of `page.edit`.

Now that we have our models, we're going to need to define the relationships between them.

# Defining the relationships
Your `Role` model is going to need to know that it has permissions. Add the following method to your `Role` model.

```php
public function permissions() {
    return $this->belongsToMany(App\Models\Permission::class, 'role_permissions', 'role_id', 'permission_id');
}
```

To define the inverse, add the following to your `Permission` model.

```php
public function roles() {
    return $this->belongsToMany(App\Models\Role::class, 'role_permissions', 'permission_id', 'role_id');
}
```

This relationship is called a [many to many](https://laravel.com/docs/5.6/eloquent-relationships#many-to-many) relationship, and utilises something called a pivot table. In this case, our pivot table is  called `role_permission` and will simply contain `permission_id` and `role_id` columns.

Now you need to go to your user model, or whatever your authenticated entity is, and define the relationships. Now, I always go for users having more than 1 role, but you don't have to.

To allow for multiple roles, add the following to your model;

```php
public function roles() {
    return $this->belongsToMany(App\Models\Role::class, 'user_roles', 'user_id', 'role_id');
}
```

Then on your `Role` model add the inverse;

```php
public function users() {
    return $this->belongsToMany(App\Models\User::class, 'user_roles', 'role_id', 'user_id');
}
```

If you only want users to have 1 role, you can add the following instead to your user model;

```php
public function role() {
    return $this->belongsTo(App\Models\Role::class, 'role_id');
}
```

Then the inverse on your `Role` model would be;

```php
public function users() {
    return $this->hasMany(App\Models\User::class, 'role_id');
}
```

Unlike all of the other relationships, this is a [one to many](https://laravel.com/docs/5.6/eloquent-relationships#one-to-many) relationship. Your user model will need to contain the foreign key `role_id` to define which role they belong to. Honestly, I don't like this method as it feels too limiting. There's realistically more complexity in only allowing one role than there is allowing for multiple.

### BONUS: Direct user permissions
It's entirely plausible to want to assign a particular permission to a user, without creating a separate role or giving them all of the permissions that'd come with being assigned to a role. If you wish to do this, you can have a direct relationship between a user and additional permissions. To do so, add the following to your user model;

```php
public function permissions() {
    return $this->belongsToMany(App\Models\Permission::class, 'user_permissions', 'user_id', 'permission_id');
}
```

Then add the inverse to your `Permission` model;

```php
public function users() {
    return $this->belongsToMany(App\Models\User::class, 'user_permissions', 'permission_id', 'user_id');
}
```

# Creating our migrations
I'm also assuming here that you're familiar with migrations, so I'll only be giving you the blueprint configuration for the inside of the closure.

### Creating our Role migration
To create your role migration, run the following command;

```bash
php artisan make:migration create_roles_table
```

Open up the `database/migrations/YYYY_MM_DD_HHMMSS_create_roles_table.php` file and add the following to the closure inside the `up()` method.

```php
$table->increments('id');
$table->string('name');
$table->string('ident')->unique();
$table->text('description');
$table->boolean('active')->default(0); // Default is an int because Laravel will create a TINYINT column
$table->integer('level')->default(99); // Default can be whatever you want really
$table->timestamps();
```

### Creating our Permission migration
Much like the role migration, run the following command in your terminal;

```bash
php artisan make:migration create_permissions_table
```

Now open up the `database/migrations/YYYY_MM_DD_HHMMSS_create_permissions_table.php` file and add the following to the closure inside the `up()` method.

```php
$table->increments('id');
$table->string('name');
$table->string('ident')->unique();
$table->text('description');
$table->boolean('active')->default(0); // Default is an int because Laravel will create a TINYINT column
$table->timestamps();
```

### Pivot table migrations
Now that we have our migrations for both the `roles` and the `permissions` table we need to add a migration for the pivot tables, `role_permission` and `user_roles`.

Run the following command;

```bash
php artisan make:migration create_role_permissions_table
```

Now open up the `database/migrations/YYYY_MM_DD_HHMMSS_create_role_permissions_table.php` file and add the following to the closure inside the `up()` method.

```php
$table->unsignedInteger('role_id');
$table->unsignedInteger('permission_id');

$table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
$table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
$table->unique(['role_id', 'permission_id']);
```

We're adding foreign keys here as we only want valid entries. We're also adding a multi-column unique key so that we don't end up adding permissions to roles more than once.

If you opted to have your users capable of holding more than one role, you can repeat the above like so.

Run the following command;

```bash
php artisan make:migration create_user_roles_table
```

Now open up the `database/migrations/YYYY_MM_DD_HHMMSS_create_user_roles_table.php` file and add the following to the closure inside the `up()` method.

```php
$table->unsignedInteger('role_id');
$table->unsignedInteger('user_id');

$table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
$table->unique(['role_id', 'user_id']);
```

If instead you want your users to have only one role, you'll need to generate a migration that adds the following;

```php
$table->unsignedInteger('role_id');
$table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
```

To run these migrations simply run the `php artisan migrate` command.

### BONUS: Direct user permission migration
If you've chosen to allow for users to have specific permissions assigned to them, you're going to need to create that pivot table too, much like we did for `user_roles`.

Run the following command;

```bash
php artisan make:migration create_user_permissions_table
```

Now open up the `database/migrations/YYYY_MM_DD_HHMMSS_create_user_permissions_table.php` file and add the following to the closure inside the `up()` method.

```php
$table->unsignedInteger('user_id');
$table->unsignedInteger('permission_id');

$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
$table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
$table->unique(['user_id', 'permission_id']);
```

Again we're adding foreign key relationships, and a multi-column unique key so that we don't assign the same permission to a user multiple times.

# Implementing our RBAC
Laravel ships with some [authorisation](https://laravel.com/docs/5.6/authorization) functionality, including the policies that I mentioned above. While we don't care about policies, we do care about the [gate](https://laravel.com/docs/5.6/authorization#gates).

Laravels Gates allow us to define the resolution logic for checking that a user has a given 'gate', which in this case is synonymous for permission. Now, it's highly likely that you're going to be checking more than one permission per request, and while you could pull all permissions down, define gates, and then query for each permission, that's not really ideal. Normally I would actually implement my own version of the `Gate` contract, because it doesn't work for the way that I handle permissions. That's somewhat outside the scope of this article, and to keep things simple I'm going to leave the default one in place.

### Registering our Permissions
Because of the way the default `Gate` works, you need to register all of your permissions for it know whether or not a user has them. You may think that we can get away with just registering the specific permissions that a user has, but that isn't the case. Because of the wildcard permissions that we add, the gate needs to know of them all. We'll never actually check for the wildcard permissions directly, but we will definitely use permissions that are covered by them.

Here you can either use `AppServiceProvider` or create your own provider with `make:provider`. Once you've opened your provider of choice you're going to want to put the following in the boot method;

```php
$permissions = Permission::pluck('ident');
$permissions->each(function(string $ident) {
    Gate::define($ident, function (User $user) {
        // todo: Add permission checking
    });
```

We put this in the boot method because we want to make sure all the other services are registered, services such as the DB and the the auth gates. If you wanted to avoid having to perform a query on every request, you could take this a bit further and cache the results. If you wanted to do this, you could do the following;

```php
$cacheKey = 'permissions';
$permissions = Cache::get($cacheKey);

if (! $permissions) {
    $permissions = Permission::pluck('ident');
    Cache::put($cacheKey, $permissions->toArray());
} else {
    $permissions = collect($permissions);
}

$permissions->each(function(string $ident) {
    Gate::define($ident, function (User $user) {
        // todo: Add permission checking
    });
```

Here we are checking for the permissions in the cache before hitting the database, and if there are no permissions in the cache, we grab the results from the database and then cache those for future use. Since your actual permissions are unlikely to change frequently it's okay to cache the results. However, if you add a new permission or edit the ident of an existing permission, you'll need to invalidate this cache with `Cache::forget('permissions')`.

(I'm using `User` here, but that should be whatever your model is)

You'll also notice that we've left a todo in there. That's because there is something other stuff we need to do before we can actually check a users permissions.

### Alternative Permissions
Alternative permissions are the wildcard permissions we've spoken about previously. If you're checking whether or not a user can `blog.post.create`, that should also accept the following;

- `*`
- `blog.*`
- `blog.post.*`

To actually get the alternative permissions we need to build this list up. There are several ways we can go about this, and none of them are pretty. I'm open to suggestions for this, but this is generally how I'd go about it;

```php
$altPermissions = ['*', $permission];
$permParts = explode('.', $permission);

if ($permParts && count($permParts) > 1) {
    $currentPermission = '';
    for ($i = 0; $i < (count($permParts) - 1); $i++) {
        $currentPermission .= $permParts[$i] . '.';
        $altPermissions[] = $currentPermission . '*';
    }
}

return $altPermissions;
```

Let me run through what this is doing. 

I'm assuming that the value of `$permission` is a string, and contains the permission ident we want to get the alternative permissions for. The first thing we do is start building up an array of permissions, and since we can be certain that `*` is accepted here, and the actual permission, we can add those to the array without worrying. Next we're going to [explode](http://php.net/explode) the permission by the `.` character, which essentially splits it by that character and gives us an array containing the parts. If the `.` character is not found, we'll get an array containing the whole permission, which we don't care about as we already have the full permission.

Next we loop through all of the parts using a for loop. I'm using a for loop here because we want to run through all parts but the last. We build up a `$currentPermission` by just appending the current part, plus `.` to the end, before added that to our array with a `*` on the end. If I were to now call `$permissions = altPermissions('blog.post.create')` I'd get back an array with the following;

- `*`
- `blog.post.create`
- `blog.post.*`
- `blog.*`

In my example there I have the helper function `altPermissions(string $permission): array` so I'll be going with that for the rest of my examples. You should replace this with whatever you're using. You could put this as a method on the `Permission` model, or have it as a helper function somewhere, perhaps even put in your service provider.

Now we can go back to our todo in `Gate::define()`.

### Defining our Gates
```php
$permissions->each(function(string $ident) {
    Gate::define($ident, function (User $user) use($ident) {
        $cacheKey = 'user.' . $user->id . '.permissions';
        $userPermissions = Cache::get($cacheKey);
        
        if (! $userPermissions) {
            $userClosure = function ($query) use ($user) {
                $query->where('users.id', '=', $user->id);
            };

            $userPermissions = Permission::query()
                                     ->whereHas('roles', function ($query) use($userClosure) {
                                         $query->where('active', '=', 1)
                                                      ->whereHas('users', $userClosure);
                                     });
                                     ->orWhereHas('users', $userClosure)
                                     ->groupBy('permissions.id')
                                     ->where('active', '=', 1)
                                     ->pluck('ident');
             Cache::put($cacheKey, $userPermissions->toArray());
         } else {
             $userPermissions = collect($userPermissions);
         }
         
         if ($userPermissions) {
             $altPermissions = altPermissions($ident);
             return null !== $userPermissions->first(function (string $ident) use($altPermissions) {
                 return \in_array($ident, $altPermissions, true);
             });
         }
         
         return false;
    });
```

This has turned into quite a bit of code, so let me break this down for you. 

First thing we're doing is setting the `$cacheKey` so that we can use the cached results of a user permissions. The reason for this is that we'll likely be checking for multiple permissions during the course of one single request, and we don't want to query the database every time. 

The next part of the code goes on to retrieve the values and populate the cache if it isn't set. We're creating a `$userClosure` since the logic for the `roles.users` and `users` `whereHas` are the same, so rather that repeating ourselves we use a variable. What this query actually does, is grab all permissions that are associated with a role, that in turn is associated with the given user, as well as any that are associated directly to the user. We're grouping by `permissions.id` as we don't want repeat permissions, seeing that it's possible that one or more roles could have the same permission, or the user could have a direct permission that's also present in a role. The `pluck('ident')` method, tells it that we don't care about retrieving an instance of `Permission`, we just want the permission `ident`. Oh, and we're also only pulling those that are active, or belong to active roles.

We cache the value as an array, because our `pluck()` method returns an instance of `Illuminate\Support\Collection`, which is useful, but not for serialising. You'll also see that if we pulled the value from cache, we're transforming that to a collection.

Next we grab our alternative permissions using the methods we covered earlier, before cycling through the permission idents until we find one that is in our array. Once we do, we return it, whereby we immediately return whether or not the value is null. This will give us a nice boolean return, just like the `return false` we have at the bottom in case any of the above fails.

# Checking users permissions
Now that we have all the above in place, we can actually check whether or not a given user has the desired permission. We can do this by one several ways.

### Using Gate
To use Gate, we can simply do the following;

```php
if (Gate::allows('blog.post.create')) {
    ...
}
```

If you wanted to check for multiple permissions, you can use `check()` to make sure they have all, or `any()` to see if they have at least one;

```php
if (Gate::check(['blog.post.create', 'blog.post.admin'])) {
    ...
}
```

And

```php
if (Gate::any(['blog.post.create', 'blog.post.admin'])) {
    ...
}
```

There is also a `denies()` method on the gate, that just does the inverse of `allows()` so you can check that a user _DOESN'T_ have the given permission. You can read the [Laravel docs](https://laravel.com/docs/5.6/authorization#authorizing-actions-via-gates) on dealing with Gates if you want to know anything further.

### Middleware
The middleware in question does actually use gate, but you don't. See the [docs](https://laravel.com/docs/5.6/authorization#via-middleware) on this. Just add the following to any routes you want protected;

```php
$route->...->middleware('can:blog.post.create');
```

### Controllers
If you're extending the default controller, which you most likely are, you have access to an `authorize()` method that you can use inside your controller actions;

```php
public function create() {
    $this->authorize('blog.post.create');
    ...
}
```

Be aware that this will throw an exception in the form of `Illuminate\Auth\Access\AuthorizationException`. To read more about this, check the [docs](https://laravel.com/docs/5.6/authorization#via-controller-helpers).

### Your User model
If you want to be able to check a users permissions directly from an instance of their user model you need to make sure that it implements the `Illuminate\Contracts\Auth\Access\Authorizable` contract, and uses the `Illuminate\Foundation\Auth\Access\Authorizable` trait. You'll have to import these classes as aliases since they have the same name and PHP won't let you do that. If you're using the default `User` model, or extending the one that comes with the `Auth` service (`Illuminate\Foundation\Auth\User`) this is already done for you, and you don't need to do anything to your model.

Once you have these in places, you can simply do;

```php
$user->can('blog.create.post');
```

You can read more about this in the [docs](https://laravel.com/docs/5.6/authorization#via-the-user-model).

# Now what?
You now have a simple yet powerful RBAC for your Laravel application, so get to and create those permissions. Feel free to play around with the code as much as you please. Perhaps you don't like using dot notation? Perhaps you've a better idea about how to build up the alternative permission list? Well give them a go!

You'll notice that I haven't added anything about the creation of roles, permissions or their assignments. This is entirely up to you and would depend greatly on how your administration system works. Perhaps in the future I'll follow up with how to implement this with Nova, once it's finally released that is.

As always, let me know any feedback or questions you have.

As a side note, the comments are being replaced soon, as I've grown somewhat disenchanted with disqus, so I'm going to roll my own :P