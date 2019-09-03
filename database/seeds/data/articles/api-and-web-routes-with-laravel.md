Routes are an integral part of any application that deals with HTTP requests, but they're also very easily overlooked. Laravels default route files don't really promote any sort of organisation, and it's too easy to open you `routes/web.php` and see an absolute mess. In this article I'm going to go through a few tips, tricks, and suggestions that'll help you keep on top of your routes, as well as going through the big differences between routes belonging to an API, and routes belonging to a standard web app.

# General routes
All of the following applies to both API and Web routes.

### The router is available
I've often been asked the following question;

> *Ollie, why does your routes file look like Lumen?*
 
It's simple. They look like Lumen because the router is right there, so why would I use a facade? If you're unsure of what I mean, most of my routes files look like this;

```php
<?php

use Illuminate\Routing\Router;

/**
 * @var \Illuminate\Routing\Router $router
 */

$router->get('/sitemap.xml', function () {
    // Code and stuff
});

$router->get('/feed.rss', function () {
    // Code and stuff
});
```

Laravel loads your routes in a file where the variable `$router` contains an instance of `Illuminate\Routing\Router`, and because of the good ol' scoping, that variable is available in the route files. I often like to import the class, so that I don't have to deal with FQNs everywhere, and I add the docblock to tell my IDE that the variable exists, and what it contains.

This way, I'm dealing with a concrete instance, and can make the most of the tab completion without the need for an IDE helper. Sure you can use `Route`, but I've no love for facades and their magic wrapped mysteries.

Now, you may think that you need to pass this variable into closures for groups, but that's not necessary. By default, an instance of `Illuminate\Routing\Router` will be passed in as the first argument to any group closure. Don't believe me? Here's the core code that actually handles your closure;

```php
protected function loadRoutes($routes)
{
    if ($routes instanceof Closure) {
        $routes($this);
    } else {
        $router = $this;

        require $routes;
    }
}
```

This method is in `Illuminate\Routing\Router` and called by the `group()` method on line 349.

Whether you actually use this is entirely up to you, and while I'd suggest using it over a facade, the purpose here was to point out that it's available. It's very easy to miss, and it's not covered in the documentation.

### Name your routes
If you don't give your routes names, how will Santa know who to send the presents to?

Route names are a wondrous thing. A wondrously neglected thing. It doesn't take long to give a route a name, and your code will be much nicer for it. Calling the `route('route.name')` method is so much easier than all the `to()`s and `action()`s.

Now actually naming the route is only half the battle. The other half, is naming it sensibly. Go with the age old `resource.action` method of naming, that expands out to `resource.childResource.action`. Here are some examples;

- `/login` - `auth.create`/`auth.store`
- `/logout` - `auth.destroy`
- `/register` - `user.create`/`user.store`
- `/profile/update` - `user.profile.edit`/`user.profile.update`

I know in my first example I used `childResource` but generally, if you need to use multiple words it could probably do with a `.` to break it up a bit. Remember, you're unlikely to be the only one to see this code, and others won't have your knowledge of building it.

### Group your routes

### OOP your routes