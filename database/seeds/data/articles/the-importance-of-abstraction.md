Abstraction is an important concept when it comes to programming. I'm not just talking about the idea of having an abstract class, I'm talking about the act of abstracting functionality out. If you've encountered me or any of my work before you'll know that it's something I'm quite the fan of, even going as far as writing articles covering simplistic approaches to the abstraction.

There are a lot of mixed feelings about this, and while I can't tell you when and how to use abstraction, I can talk you through why it's necessary and for what reasons you'd use it. All code and references within this article will be PHP, specifically using the Laravel framework, and the patterns I'll be using to cover this topic, will be repositories and service layers.

# "abstraction is pointless, it just complicates matters", I hear you say
This is something I hear a lot. If you're a PHP developer dealing with Laravel, and this is your opinion, you're in for quite a shock.

Laravel is quite literally built on the principal of abstraction. The very code that makes your work so much easier, does this by use of abstraction. Each feature or group of features is abstracted out to its own package. Within that package each part of a feature is abstracted out to its own class, and each chunk of code within there is abstracted out to its own method. Half of the entire framework, parts that you use every single day and praise Laravel for, are nothing but layers of abstraction on top of third party code. The most prominent of which is the HTTP layer, which is primarily built on the top of the `symfony/http-foundation` package.

In some cases, Laravel has unnecessary, as well as rather complex and convoluted abstraction. Don't believe me? Take a look at the default auth scaffolding that comes fresh with every install.

Laravel has all of this abstraction for a couple of reasons, SoC (separation of concern) and extensibility. Since each particular feature is abstracted and that abstraction broken down into further parts, you can quite easily override and extend that functionality without having to duplicate huge chunks of code. It also means that when you do this, you're confident that it's only affecting that feature, and not some other part.

# More abstraction would just complicate matters surely?
No, not really. If your abstraction is making things over complicated, you're not doing it properly. Let's consider repositories for this point. I know I mention them a lot, and at times it seems like I'm the last developer holding on to repositories, but they do have a very real purpose.

Repositories are a way of abstracting out your database interactions into individual classes that represent your data using a collection like interface. Now I've used the word interface here, but I mean the general definition, rather than the programming definition. That being said, interfaces are a prime example of over complication/over-engineering this pattern. Repositories, though they've been around for a while, were popularised in the Laravel ecosystem thanks to a series of tutorials starting with Laracasts I believe. The pattern was presented in a very over complicated manner, meaning that for each repository, you'd require an interface to represent it. This is unnecessary, and though it's there to target a very specific problem, that problem is so rare that it's not even worth considering, YAGNI (You Aren't Gonna Need It).

Repositories are not for every situation, and while I use them in 90% of the code that I write, you only really need to use them if you have multiple points within your codebase where the same queries are being made, or queries that are similar enough that they could be represented by a single method. For example, on this site, there are 5 separate places where I need to get list of blog posts. They are as follows;

- Main blog post listing (index)
- Category blog post listings
- Sitemap generator
- RSS feed generator
- Admin blog post index page

These 5 interactions are almost identical. The main page, category page and admin blog listing page all retrieve a paginated list of results, with category pages having a condition. The sitemap and RSS feed generator pull a full list without pagination. These 5 interactions are handled by two methods within my `PostRepository`. The first, a simple `getPosts()` that returns all entries with no conditionals, and a second `getPaginated(int $count, array $filters = [])`, that lets me get a paginated list that is optionally filtered. Right now that filter only supports categories, which are handled by a single if that adds a `whereHas`. Since I use a base repository as mentioned in my [Using Repositories with Laravel](https://ollieread.com/blog/2018/04/06/using-repositories-with-laravel) article, I've already written less code than writing each of these interactions without abstraction.

Furthermore, if anything ever changes about blog posts and their database interactions, I have a single place to go to. No searching through the codebase to find every interaction with the model, just open up PHPStorm, `cmd + o` type `PostRep` hit enter and bang, all of it, right there. Full control without having to touch any other part of my codebase. If another developer inherits this codebase, or has to do some work on it, they can easily find everything in its designated location. I guarantee a good chunk of you have encountered an issue where you've missed a particular query when adding a new condition.

# A place for everything and everything in its place
I'm well aware that because of my own personality traits I'm very particular about where things go, and how things should be used, and while you don't have to be quite as militant about it as I am, it's worth considering. This of course refers to SoC, separation of concern.

Separation of concern refers to the practise of having code split out (read: abstracted out) into chunks that have a single concern. Now because of the dynamic nature of programming, how you define a single concern can vary wildly. With my codebase I know that;

- My repositories have the singular concern of interacting with the database
- My models have the singular concern of representing the database

I know that my models can technically interact with the database, and that my repositories use the models to achieve this, I never make a model deal with the database intentionally. Now this is somewhat loose because of the way that Eloquent works, but generally, my database interactions are all contained in one place. My controllers and views don't care about the database, and the my repositories don't care about displaying content from the database.

Ultimately, you need to decide whether or not to use any abstraction, and how to implement said abstraction. It's worth giving it a go and exploring the different options, rather than just discounting the entire idea because an influential individual in the industry tweeted something vague (No doubt hypocritical too).

# Don't repeat yourself (DRY)
You have no doubt heard of this by now, it's one of those acronyms that gets thrown around along with KISS, YAGNI, SOLID, etc, etc. Quite simply put, DRY refers to practise of avoiding duplicate code. The description of my `PostRepository` above is a prime example of this, as instead of 5 almost identical database interactions, I have 2 simple methods that handle this for me.

Repositories are a prime example of DRY, but I've already mentioned them enough. To go through this a bit more I'm going to move onto another pattern that I commonly use, and that's the service layer pattern (I know it's not really technically a pattern but shush).

This particular concept is very vague and open to interpretation, and although I'm not hugely fond of the term service as it doesn't accurately describe what each class does, I implement this by creating multiple services to represent the grouped functionality/logic. Imagine a system with the following models;

- Products
- Product Attributes
- Product Options
- Categories
- Basket
- Orders
- Payments
- Users

As you can probably guess, this is an ecommerce example. Now since I'm using repositories, each of these models has a corresponding repository. The biggest mistake that people make at this point, is to create an individual service for each of these. That really doesn't help, and is most definitely one of the reasons that people think abstraction is just over complicated and pointless. Instead, what I'd do, is create two services which have the responsibilities below;

- Catalogue
    - Products
    - Product Attributes
    - Product Options
    - Categories
- Shop
    - Basket
    - Orders
    - Payments

My `Catalogue` service is responsible for the models that represent the catalogue functionality, catalogue being the listing of products in their categories, with their attributes and options. My `Shop` service is responsible for the building and placement of orders. There's a certain level of overlap, but anything that the `Shop` service needs from the `Catalogue` service would be provided externally.

I personally like this approach, because it allows me to group logic by its particular purpose, and it lets me keep my repositories simple without adding loads of logic into it. I appreciate that not everyone will like that reasoning, but there is another reason you'd use services.

Much like how you'd use repositories if you were repeating queries, you'd create a service if you were repeating functionality. You probably have an admin area that lets admins create users, and I'd wager that the code for that is almost identical to a user registering the account themselves. In this instance, I'd abstract that functionality out to a service, so that I didn't have duplicate code. You may even add an API to an existing codebase, and while you don't plan to consume your own API internally, you want it to work exactly like your normal browser based frontend. In this situation you'd again, abstract out the functionality to a service, allowing both the browser and API to work identically.

# It's still so much extra complexity!
If everything still appears too complex, you need to rethink your approach. Perhaps you could break down your abstraction into multiple methods. In the above example about creating a user in the admin and registering a user, I'd probably have `Users::create()` and `Users::register()` methods, where the `register()` method calls the `create()` one internally. That lets me do something like sending an email to a registering user, without repeating the creation code or adding if statements everywhere.

Now I know that I referred to this codebase as having models, but it doesn't. My above examples were mostly because the majority if you will be using Eloquent. I'm not, I'm using Articulate, but that's not hugely important. I say this because below is the exact code that I use to add a new post in the admin area.

First I have my controller that essentially just serves as an interface and way to collect data;

```php
public function store(Request $request)
{
    $input = $request->all([
        'title', 'slug', 'excerpt', 'content', 'post_at', 'categories', 'published',
    ]);

    $post = $this->blog->createPost($input);

    if ($post) {
        return redirect()->route('admin:post.index');
    }

    return redirect()->back()->withInput();
}
```

Then I have the `create()` method in my `Blog` service;

```php
public function createPost(array $input): ?Post
{
    if (empty($input['slug'])) {
        $input['slug'] = str_slug($input['title']);
    }

    Validators\PostValidator::validate($input);

    if ($input['post_at'] || $input['published']) {
        if (empty($input['post_at'])) {
            $input['post_at'] = Carbon::now();
        } else {
            $input['post_at'] = Carbon::createFromFormat('Y-m-d\TH:i', $input['post_at']);
        }
    }

    $post            = new Post;
    $post->title     = $input['title'];
    $post->slug      = $input['slug'];
    $post->excerpt   = $input['excerpt'];
    $post->content   = $input['content'];
    $post->postAt    = $input['post_at'] ?? null;
    $post->published = ((bool)($input['published'] ?? false));

    if ($this->postRepository->persist($post)) {
        $this->postRepository->syncCategories($post, $input['categories']);

        if ($post->published && $post->postAt && $post->postAt->isPast()) {
            $this->sitemap->invalidate();
            $this->feed->invalidate();
        }

        return $post;
    }

    return null;
}
```

As you can see, it's relatively straight forward. The `persist()` method you see there is inherited by the repository from the base that they all extend, so no need to worry about. I also have my `syncCategories()` method that just makes sure the pivot table matches the selection. My controller doesn't care where the data goes or what is done with it, my service doesn't care where it came from or how it is persisted, and my repository doesn't care where it came from or where it's going.