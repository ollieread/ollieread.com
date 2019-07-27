Any of you that frequent this site, or are privy to my public interactions on Twitter, Slack or Reddit, will know that although I'm primarily a Laravel based PHP developer, I have my fair share of issues with the framework. The most common response to my ramblings (read: rants) is;

> Building a framework is hard, I'd like to see you manage it.
 
So you know what? You're going to.

Throughout the coming months, maybe even years, I'm going to build out a modern PHP framework called Contraption. I've made a basic start and you can find that and all future code [here](https://github.com/contraption).

# Why are you building a framework?
Simply put, I am building a framework to see if I can, and if I can, to say that I have. The whole idea is that Contraption is an experiment. I'll be setting myself goals and aims and then working towards those, exploring more about what it actually takes to build a framework. I also figured that it'd be a good subject for a series of articles, detailing problems that I encounter, alternate solutions and my own take on various matters.

# So what are your goals?
The goals thus far are quite simple.

## Keep it simple
While my goal isn't to build a 'lightweight framework' like Lumen or Slim, I do intend to keep it as simple as possible, avoiding the over engineering and over complication that inherently comes with an undertaking so massive.

## PSR compliance
There are mixed feelings regarding PHP-FIG and their PSRs, but I will be attempting to introduce compliance to all current (and future if they release before I'm finished) PSRs.

## Make it truly flexible
There are a lot of frameworks out there that claim to be flexible, but realistically, they're not. Laravel was originally sold as a truly flexible framework, allowing for the swapping out of components, and even using the components in non Laravel codebases. Unfortunately, Laravel has grown to a point where there's not really feasible. The core is rammed full of functionality that few codebases use half of, and each package has many dependencies. 

My goal with Contraption is to build something that can easily have parts replaced, and those parts can easily be used elsewhere. I'm aware of how difficult this will be to achieve without breaking my first goal, but I'll give it a try.

## Reinvent all the wheels
It's no good coming out at the end and being like "Hey, I built a framework, look how cool it is" if half of it is just a wrapper for a load of others peoples work. It's not really going to be much of an experiment or challenge if I'm not solving the problems myself. 

It's also worth noting that during my research into external packages to handle various parts, I was left with a bad taste in my mouth **cough** symfony/http-foundation **cough**, so figured why the hell not, I'll do it myself. There will be certain widely used packages that I won't recreate, flysystem and fast-routes for example.

## Experiment
My goal is to have fun, and while it may sound strange that someone is building an entire framework for fun, it's going to be fun none the less. One of the things I plan to do is experiment with different approaches, and just generally introduce bleeding edge features. I already plan to implement [php-ds](https://github.com/php-ds), the PECL extension that introduces several new data-structures to PHP.

# Will you launch as a proper product?
Possibly. Really it depends what I have when I've finished, and whether anyone will have use for it. It'll most likely end up just being a framework that I use for some personal projects, though only the future will tell.

# When can we expect articles?
Right now, there's no definitive timeframe for development or writing. It'll be entirely based on getting chance to write code, and then getting chance to write articles about the code. Since I have a baby arriving at some point in the next 6 weeks, my time will be somewhat limited.

If you want to see what I have so far, check out the [develop branch](https://github.com/contraption/contraption/tree/develop) on github. At the time of writing this, it just contains a simple 501 line Container implementation.

# It looks similar to Laravel
It will do. One of the many things that makes Laravel great, is that for the most part, it uses modern and sensible approaches to problems, meaning that 9 times out of 10, if you're writing some code to achieve a purpose that Laravel does, there will be a decent amount of similarity. While I will be using Laravel as a comparison, I will not just be copying chunks of code. I may borrow approaches if I feel that they are mostly correct, but I'll be slimming them down.

# Can I contribute?
Right now I'm still in the early days, so I wouldn't really want any code contributions just yet, but if you think it's absolutely necessary, make a PR and I'll take a look. Otherwise, if you want to contribute still, or just don't want to write code, I'm more than welcome to criticism, input, suggestions and just general chit chat regarding this project.

Amusingly, this is a shorter article than normal, but a far larger subject. If you've any thoughts, suggestions or input in general, let me know below or pop up on [twitter](https://twitter.com/ollieread).