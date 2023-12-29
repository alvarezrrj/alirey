# Alirey

A booking management system built with the TALL stack (TailwindCSS, AlpineJS, Laravel & Livewire). This doc attempts to describe what each section does and how it does it.

## TO DO

+ Add "show password" button to sign in form
+ Add price to landing page

+ Test Google Calendar Sync (therapist) in production 

+ Unnecesary modals are being rendered on Booking view

+ Replace booking's day column for from and to (as datetimes)
+ Check for booking overlap by just comparing dateTimes instead of slot_id
+ Select upcoming booking (on RegisteredUserController::dashboard) where 'from' <= Carbon::now()

+ Add link to 'add to google calendar' on booking confirmation email.

+ Clean up instructions height animation on booking form
+ Fix filament dark mode issue with: https://github.com/filamentphp/filament/issues/6647#issuecomment-1569853641

+ Once Rabol merges pull request, delete "repositories" field from composer.json and delete "/packages" folder, uninstall livewire-calendar and reinstall from repo.
+ Add support for english language

### Future releases

+ Color calendar events according to booking/payment status
+ Display holidays and non-working days on calendar
+ Support for booking drag and drop on calendar
+ Use filament tables
+ BookingController->edit()
    - Find another way to see if day is full inside while loop to avoid so many round trips to DB
+ Sort out calendar drop-down positioning
+ Give admin ability to dissallow last minute bookings in config.
+ Add search in bookings table
+ Sort past bookings descendingly in bookings table
+ Auto delete soft deleted slots with no related bookings in a scheduled job
+ Sort out the roles situation: separate 'admin' from 'therapist'
Add an 'opt_out' column on users table (to allow them to opt out of promotional email)
+ Cerrar horarios de forma recurrente.
+ Add option to sync to google Calendar on user's profile

## Notes
Goggle scopes
https://developers.google.com/identity/protocols/oauth2/scopes#calendar

might need to add
```
$table->string('google_id')->nullable();
$table->string('google_token', 12288)->nullable();
```
to users table



### Known issues

+ Slot picker on booking form requires selects the first slot by default which might be unavailable. This could lead to a poor UX if someone goes for that slot and a moment later finds out it's not available.
+ bookings.checkout lags for a few seconds before loading 'pay' button with no loading indicator
+ Line 184 on Livewire\BookingForm `->where('slot_id', $validated['slot_id'])` is wrong: slots may have been soft deleted

### For a multi-therapist scenario

+ Modify App\Http\Livewire\Booking to only show bookings where therapist_id == Auth::user()->id when Auth::user()->isTherapist
+ Add therapist_id to booking form
+ Modify BookingPolicy to only allow therapist to edit/delete their own bookings
+ Allow therapist to access only their own config
+ Rewrite UsersController::index to only show non-admin users instead of everyone but the current user.
+ Restrict admin's ability to delete any user (create a moderator role that can do that)
+ Include therapist name and logo in contact form, do a separate form to contact site admin.
+ Replace the logo in /contact/webmaster
+ Rewrite RegisteredUserController::dashboard to retreive correct therapist instead of 'admin'.
+ Find a prettier solution to this line:
```blade
@php($therapist = App\Models\Role::where('role', SD::admin)->first()->users()->first())
```


## Config (/config)

Can only be accessed by admin. Allows the user to set:

### **Working days**

Toggling switches automatically updates 'config' column on database for the currently logged in admin

### **Dates**

'Always open' switch allows admin to choose between the calendar allways displaying &lt;anticipation&gt; amount of days or keeping the calendar open until a specific date (&lt;open-until&gt;)

### **Price**

Choose the session price. Database value is updated on focus out to avoid clients seeing the wrong number in case someone requests the price as it is beeing updated

### **Holidays**

Allows the admin to display certain days as unavailable on the calendar.
Create holiday plans by choosing a start and end date. If &lt;until&gt; is null or earlier than &lt;from&gt;, a single day holiday will be created.
Delete holiday plans by clicking 'delete' on the table rows.

Single slot closure form allows the user to close a single slot on a particular day


### **Slots**

Allows admin to manage session slots. These will be shown to clients as the available session times.
On deletion, slots are only soft deleted to avoid orphaning bookings.


## Bookings.index (/bookings)

Displays a table which is filterable by booking status and date, and allows for inspection and deletion of bookings (only admin can delete bookings as per BookingPolicy).

## Bookings.show (/bookings/{id})

Displays booking details and allows admin to mark booking as 'complete', refund, mark as paid and edit it.

## Bookings.create (/bookings/create )

This is the booking creation workflow for the different circumstances

![Booking creation flow](./booking_diagram.svg "Booking diagram")

Note: BookingController::getData() is currently retrieving config as if there were only one admin managing the app. Therapist_id should be included in create booking form in a multi-admin scenario.

When admin registers a new user, a password reset link is sent so they can access their account.

Non-admin users creating bookings are redirected to checkout page (bookings.checkout) after the booking is validated and stored on DB. Users with pending payment are shown a notification badge on their username and prompted to pay or cancel the booking before being allowed to create a new one.

Once the payment goes trhough, MP redirects users to confirmation page. This controller removes preference details from booking to avoid it being deleted by booking purger and sets payment status to 'awaiting confirmation from MP' (changing payment status to anything other than 'pending' allows the BookingPolicy to keep the booking from beeing checked out twice). Payment confirmation is done by webhook, which sets payment status to 'MercadoPago', paid amount and mp_id (MercadoPago's payment ID, needed for refunds).

### Unpaid booking purge

A scheduled job runs every minute and deletes bookings with expired preferences. This prevents unpaid bookings from staying on the database. Scheduler logs are in /logs/scheduler.log

### Booking payment statuses

SD::PAYMENT_PENDING: Booking needs to be paid for.
SD::PAYMENT_CASH: Payment was confirmed by admin.
SD::PAYMENT_MP and mp_id == null: Booking was paid for, but notification from MP hasn't arrived yet.
SD::PAYMENT_MP and mp_id != null: Booking paid for and confirmation from MP received.o

## Booking checkout (bookings/{id}/checkout)

Allows non-admin user to pay for their pending booking only if payment status is pending and booking status is not cancelled. Creates mp preference on first visit and retrieves it on subsequent visits. If preference is expired, booking is deleted and user notified via UI.

## Bookings.update (/bookings/{id}/)

Displays a form for inserting bookings and updating those that have a state of BOOKING_PENDING

'date' &lt;select&gt; shows days as disabled if there's a corresponding holiday or if there are as many bookings for the day as slots in config table.

'time' &lt;select&gt; updates itself when a new day is selected, disabling any slots that have a booking on them, except if it corresponds to current booking.

'restore' button restores date and time.

## BookingController::send_reminder()

This static function is called by the scheduler and sends a reminder email to anyone who's booking starts within 20'.

## Contact (/contact/{therapist}/query)

Allows a logged in user to get in touch with the therapist, both the client's message and confirmation email get queued to keep response times low.

## Contact webmaster (/contact/webmaster)

Allows anyone to contact the site admin, includes an optional screenshot field (usefull for bug reporting).


## Deployment

- Deffer loading of CSS when possible
- Add 'verified' middleware to routes ✔
- Uncomment line 139 on MercadoPagoController `NewBookingEvent::dispatch($booking);` ✔
- Create database on server
- Updata database variables in .env file 
- Store app's url in [Google console](https://console.cloud.google.com/apis/credentials?project=alirey) to allow redirect after Google sign in ✔
- Install composer dependencies with
``` 
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
```

- Install npm dependencies and bundle assets with
```
npm ci
npm run build
```
- Migrate database with
```
php artisan migrate --force
```
Use `--force` to allow migrating in production without receiving warnings about it

- Seed db with
```
php artisan db:seed [-n]
```
Use `-n` to "do not ask any interactive question"

- Create resources/svg directory (It can be empty - necesary for Blade Icons)

- Clear cache with
```
php artisan cache:clear
```

- Cache things with
```
php artisan icons:cache
php artisan view:cache
php artisan config:cache
```

- Write cron entry on server to run scheduler
```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

- Run queue worker with
```
php artisan queue:work database --tries=3 --backoff=60
```

- Run the worker [supervisor](https://git config --global --add safe.directory /home/forge/alirey.arlaravel.com/docs/10.x/queues#supervisor-configuration) with
``` 
supervisorctl start worker-538346:*
supervisorctl start worker-538355:*
```

- When making changes to the code, stop all workers with
```
php artisan queue:restart
```
This will stop all workers and allow supervisor te restart them, picking up the code changes.




## Development

To start developing, from the root folder run the following to serve the app
```
$ php artisan serve
```

In a separate terminal run
``` 
$ npm run dev
```
to run the Vite server, which does the Tailwind magic and serves static assets

Run the scheduler locally
```
$ php artisan schedule:work
```

To call the MP webhook use
```
$ curl -X POST -H 'Content-Type: application/json' 'http://127.0.0.1:8000/api/webhooks/mp' -d '{"data": {"id": 1312183340},"type":"payment"}'
```

Start the queue worker with
``` 
php artisan queue:work
```
Needs to be restarted after changing the codebase
Learn how to keep the process running with [Supervisor](https://laravel.com/docs/10.x/queues#supervisor-configuration)

## Built With

* [Laravel 10](https://laravel.com/) - To make it work
* [Tailwind CSS](https://maven.apache.org/) - To make it look cool
* [Alpine JS](https://alpinejs.dev/) - For the browser to have something to do
* [Livewire](https://laravel-livewire.com/) - For reactivity
* [Filament](https://filamentphp.com/) - For building forms


## Authors

* **Rodrigo Alvarez** - [website](https://rodrigoalvarez.co.uk)


## Acknowledgments

* Hat tip to asantibanez for an awesome livewire calendar
* Love to the open source community
