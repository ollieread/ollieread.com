It has been while (almost two years) since I wrote an article here and I’ve decided to make my triumphant return with an article I never managed to get around to writing.

As the title may suggest, this article is about streamlining validation with Laravel. While the final aim is to explain and present you with a quick and simple abstracted validator that requires minimal code in the actual implementation stage, before we get there I will need to cover the usual methods of validation which are default validation, form request validation and model validation. So let’s start.

### Default Validation

When I say default validation I’m referring to Laravels base validator with no packages or abstraction around it. If you’re unfamiliar with this or need a recap, [check out the documentation here](https://laravel.com/docs/5.2/validation).

Lets take a look at the example used on the documentation.

```php
$this->validate($request, [
    'title' => 'required|unique:posts|max:255',
    'body'  => 'required',
]);
```

As you can see this is relying on the usage of the ValidatesRequests trait which is used in the default Laravel controller, but what exactly does this method do? Let’s take a look.

```php
public function validate(Request $request, array $rules, array $messages = array()) {
    $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages);

    if ($validator->fails()) {
        $this->throwValidationException($request, $validator);
    }
}
```

There are some serious levels of abstraction here but let’s break this down into something you’d be more familiar with from previous versions of Laravel.

```php
$validator = Validator::make($input, $results);

if($validator->fails()) {
    throw new \Exception('Validation failed');
}
```

This approach is something that I have favoured since the days of Laravel 4.2. This approach actually covers the majority of the streamlining approach that I want to show you, simply by throwing an exception you’ve removed the need to manually check the status of the a validator every time you want to run one.

That being said, there are a couple of problems I have with this approach.

Firstly, while it’s nice to throw in an entire object representing the request and have the validator validate all input, it’s not something that you want to find yourself getting into the habit of. For a while now I’ve seen a lot of people using the `$request->all()` method when collecting user input, and while I’m aware that Laravel by default has some superb security and that a validator isn’t really doing anything that could be exploited by some malicious input, being lazy and just throwing handfuls of user input at parts of your application is a bad habit that you shouldn’t let yourself get into.

Secondly, this approach also requires that you define the rules for the validation in the controller, and while this isn’t really a security issue or a bad habit, you’ll find that later down the road when sifting through the controllers of a large application trying to find the definition of the validation rules you’ll wish that these were abstracted out.

In conclusion, Laravels default validation will suffice in a pinch, it’s good for getting started and rapid prototyping but it feels somewhat lazy and not very optimal, so lets move into the next one, form request validation.

### Form Request Validaiton

Form request validation is a relatively new feature that was brought to us in Laravel 5.0, and while some of the underlying core code may have changed, the actual implementation and usage of this feature has remained somewhat static. If you’re unfamiliar with this approach or need a recap, [check out the documentation here](https://laravel.com/docs/5.2/validation#form-request-validation).

For a brief explanation of what exactly this is, let’s quote the documentation.

> or more complex validation scenarios, you may wish to create a “form request”. Form requests are custom request classes that contain validation logic.

Essentially the idea behind this is that you have your own class that extends the Laravel `Request` class, except this class is capable of defining rules so that it may be automatically validated.

While in theory this is a pretty cool approach, and while it does provide us with a level of abstraction so that we don’t have all of this validation code within our controllers, it does mean that we’re offloading the task of validating data to the HTTP layer, and don’t forget, that some of Laravels validation rules will actually communicate with the database, so now we have the HTTP layer communicating with the database before it even hits our controllers, which should realistically be the start of our logic. This is bad, bad bad bad!

By all means have a play with this feature, see what it’s like and get your own feel for it, but I highly recommend that you do not use this is an actual production application, clear definitions and responsibilities are important for maintaining your codebase and limiting the amount of work required to change and/or fix something in the future. If you have to use an out of the box solution, use Laravels default validation.

Now we go from the start of the lifecycle to near the end, while somehow still being as bad.

### Model Validation

Model validation is something that’s not part of Laravels core, but instead something often provided by packages or implemented at a basic level by the developer working with the system.

The standard approach for model validation often has you create models that look like the following.

```php
class MyModel extends Model {

    protected $fillable = ['field1', 'field2'];

    protected $rules = [
        'create' => [
            'field1' => ['required'],
            'field2' => ['required']
         ],
    ];

}
```

Nice and simple right? The rules are defined in your model and would be called automatically by events hooking into `Model::creating()` or `Model::saving()`. This approach doesn’t actually take into consideration one of Eloquents wonderful features, mutators. For example, when creating a `User` model with Eloquent I will always create the following mutator.

```php
public function setPasswordAttribute($value) {
    $this->attributes['password'] = Hash::make($value);
}
```

If I were using this approach the validator would validate the hash of the password, rather than the actual password. The same goes for any other field that you may have a mutator for.

You may find yourself thinking

>Hey, why can’t the validation on the model happen when a user calls fill()?

The answer is simple, because you shouldn’t be using the fill method, it’s lazy and when I see it used it’s either just having a whole mess of user input thrown into it (Usually from `$request->all()`) or it’s through so many levels of abstraction that I genuinely have no idea what it is actually saving to the model.

Another thing to consider is that like the structure of your response, user input can quite often be totally different to how the data is stored. Maybe the user is submitting one form, but this data actually creates two maybe three models, that’s two or three separate instances of validation there, rather than the one that should be. If you believe there should be that many, remember that the validation of data (user input) has very little to do with the storage of the data. This actually brings us quite nicely to my final point about this approach.

**YOUR PERSISTENCE LAYER SHOULD NOT BE RESPONSIBLE FOR VALIDATING DATA**. Feel free to reread that a couple of times and let it settle in.

Got that? Well have another few more reads of it to make sure that we’re on the same page.

For those of you that are curious as to what a “persistence layer” is, the persistence layer is where data is persisted, typically this will be in a database but we’re in a place now where databases are not that only way to store data. This layer is sometimes called the “data layer”, “datastore layer” or “database layer”. The naming isn’t important, what is important is what it does.

This layers job is basically to take data and persist it, whether that means throwing it an API, into a database, datastore, whatever, that is its sole job, retrieving and saving data. Validating data is a logic step, and can be considered business and/or application logic (I’m not getting into the argument of definition) so it belongs somewhere else.

Just like the response layer or view layer has its own specific logic often referred to as “display logic” this layer has its own level of “data logic” which I think is a name that confuses a lot of people and leads to this. The logic that happens in this layer is specific to the storage of the data and/or the communication with the datastore. It doesn’t and shouldn’t care how valid or invalid the contents actually is.

Do not play with, use or contemplate this approach. While it sounds nice on paper, and it may “make your life easier” you’ll regret it in the long run, and you’ll find yourself getting into bad habits. I don’t know why I dislike this approach more than form requests, but I do.

FINALLY we’ve made it all the way through to the part you’ve all been waiting for, some of my wonderful code.

### The streamlinked approach

Now that we’ve covered Laravels default validation, form request validation and even the third party model validation let me show the fourth and final approach of the day. This approach combines the one good part of form requests with the streamline approach of default validation, but throws in some nice magic in a more optimal way that doesn’t involve encroaching into the territory of your other layers.

For our examples, lets create it for users. Now you’ve got your application setup, you have the following classes.

- User (model/entity)
- UserController
- UserRepository

In the lifecycle of our example application here the data will go `UserController -> UserRepository -> User`. So where exactly do we perform the validation? Well, my approach sits in the UserController but realistically could even sit in the UserRepository if you so wish. It doesn’t add an extra step, but instead provides a fire and forget approach just like the default laravel validation.

For this approach, we’ll want to create ourselves a UserValidator.

```php
class UserValidator extends BaseValidator {
    public static $rules = [
        'create'  => [...],
        'update'   => [...],
        'password' => [...]
    ];
}
```

As you can see, the implementation of this approach so far, is really simple. You extend a base class and just define your rules as you see fit. To actually use this class in your controller you would have a method like the following.

```php
public function store(Request $request, UserValidator $validator, UserRepository $repository) {
    $input = $request->only(['username', 'password']);
    $validator->validForCreate($input);

    if($user = $repository->create($input)) {
        Auth::login($user);
        return redirect()->route('user.dashboard');
    }

    return redirect()->back()->with(['error' => 'Unable to create user']);
}
```

You may be able to figure out what’s going on here, but I’ll clarify for those of you that are still uncertain. Firstly I’m collecting the user input, and only the user input that I need before passing it along to a method within the validator, which will validate against matching rules and throw an exception if it fails. The final part of the `validFor` method corresponds directly to the defined list of rules within my validator.

Usually as a final step of the validation I like to add an automated response for a validation exception inside the render method of the `Exceptions\Handler` class provided by Laravel.

```php
public function render($request, Exception $e)
{
    if($e instanceof ValidationException) {
        return redirect()->back()->withErrors($e->errors());
    }

    return parent::render($request, $e);
}
```

I’m aware that the above error handler only works for non api/ajax calls but that’s not our concern right now. The idea behind it is that I don’t have to fanny around creating validation logic for every part of the application, I just simply define my rules and the small amount of work that I did on this at the start guarantees that my validation is as automated as can be with users being taken back to the form with the validation errors, allowing me to focus on the actual functionality.

That’s it for the implementation of the method, but now comes the fun bit, the creation of the `BaseValidator` class.

```php
abstract class BaseValidator
{

    public static $rules    = ['create' => [], 'update' => []];
    public static $messages = ['create' => [], 'update' => []];

    /**
     * @var \Illuminate\Validation\Factory
     */
    protected $validator;

    /**
     * @param Factory $validator
     */
    public function __construct(Factory $validator)
    {
        $this->validator = $validator;
    }

    public function __call($method, $arguments)
    {
        if (starts_with($method, 'validFor')) {
            $name = snake_case(substr($method, 8));
            if (isset(static::$messages[$name])) {
                $messages = static::$messages[$name];
            } else {
                $messages = [];
            }

            if (isset(static::$rules[$name])) {
                $rules = static::$rules[$name];
                if (isset($arguments[1]) && is_array($arguments[1])) {
                    $rules = array_merge($rules, $arguments[1]);
                }
                return $this->fire($arguments[0], $rules, $messages);
            }
        }
    }
    /**
     * Generic method
     *
     * @param String $action   The action that define the validation. It corresponds to the array key on the Validator file.
     * @param array  $data     Array of data to validate against.
     * @param array  $rules    Additionnal rules that could come from the controller.
     * @param array  $messages Additionnal messages that could come from the controller.
     *
     * @return bool
     * @throws \Exception
     */
    public function validFor($action, array $data, array $rules = [], array $messages = [])
    {
        if (!isset($action) || is_array($action) || !is_string($action)) {
            throw new \Exception('Invalid validation rulset for ' . $action);
        }

        $rules    = array_key_exists($action, static::$rules)    ? array_merge(static::$rules[$action], $rules) : $rules;
        $messages = array_key_exists($action, static::$messages) ? array_merge(static::$messages[$action], $messages) : $messages;

        return $this->fire($data, $rules, $messages);
    }

    /**
     * Trigger validation
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @return bool
     */
    private function fire(array $data, array $rules = [], array $messages = [])
    {
        $validation = $this->validator->make($data, $rules, $messages);

        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        return true;
    }
}
```

To be honest with you, there’s not much I can explain about this that hasn’t already been or is covered by the docblocks.

It’s really quite simple, you have a class that represents the validation of an object/resource within your system, within there you define named rulesets such as create, update, login as well optionally creating messages for those rulesets following the [Laravel validation message](https://laravel.com/docs/5.2/validation#custom-error-messages) approach and keeping the structure the same as the defined rulesets. This then allows you to call a method with a `validFor{ruleset}` structure and have the data validated and the user automatically returned if they messed it up.

It’s actually a really simple approach, it’s streamlined and relatively powerful, it combines the good points from the existing Laravel validation into a nice little approach that you can reuse as many times as you see fit.

I hope this helps some of you with your application development and hopefully my first article after almost two years break isn’t full of mistakes and unstructured ramblings (more so than normal).