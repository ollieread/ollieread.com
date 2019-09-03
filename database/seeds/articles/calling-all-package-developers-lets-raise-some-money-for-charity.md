Most of you will be familiar with the apps that are around that let you buy a coffee or a beer for the author of an open source project. These systems are great, and help a lot of developers continue to work on the project by helping to cover the cost of the time it takes to maintain.

Some of us are fortunate enough to not need to implement a service like this. For example, some of us maintain packages that we actively use for client work, so in essence, it's covered by any cost from the clients, meaning that you all benefit from that as a side effect.

I'm currently working on an as of yet to be named pet project that works in a similar way to the aforementioned services. Instead of giving a couple of quid to the developers of the package, the service will ask you to make a small donation, however much you want, to a charity on behalf of the developers.

# How will it work?
The system is going to be super simple.

The service will have an account on JustGiving that will be used to help run it. Outside of that, the steps will be as follows;

- You go the site and auth with GitHub
- You select the repository that you want to start generating donations for
- You select the charity that you'd like to have the donations made to
- The system will create a Fundraising page in the background for you and your project
- You will be given a little button that you can put on your package readme, or on its documentation

The process of making the donation is also as simple;

- User clicks the button
- User is taken to the site and seemlessly redirected to the JustGiving donation form
- User makes a donation
- User is redirected back to the system with a thank you page, where some basic stats are grabbed and logged (no personal or payment information)

### But I have multiple packages
That's fine, a fundraising page will be created for each package/repository that you select.

# Why are you logging stats?
The main aim is to get this project out as an MVP, but also with some functionality ready to support future features. I'd like to eventually introduce some more features;

- Stats pages where you can see how many donations a package has raised
- Stats pages where you can see how many donations a vendor has raised
- The ability to search for a package to make a donation
- Some nice little badges to put on the readme showing donation count, amount, charity, etc, etc.

# Are you a package developer?
It'd be great if I could launch with some packages/developers already on board, so If you're a package developer, and are interested in being part of this, please send an email to [ollie+donations(at)sprocketbox.io](mailto:ollie+donations@sprocketbox.io) with a bit of information about your packages and the sort of charities you'd like to support.