# Alirey

A booking management system. This doc describes what each section does.

## TO DO

update.blade.php
- Delete it

config
- Dissallow deleting slots with pending bookings. Offer to create dummy bookings on this slot forever

bookings.update
- Migrate form to match bookings.create

bookings.update
- Ensure booking is not being saved on same day and slot as another one

bookings.show
- Implement 'refund'
- Display 'cancel booking' button when status == BOOKING_PENDING
- Move 'edit' button to oposite side of booking number, leave action buttons at the bottom
- Turn the view into a Livewire component to avoid refreshing the hole page when clicking 'refund', 'confirm payment', 'mark as complete' or 'cancel'.
- Display confirm payment button. Show modal for user to input paid ammount.

bookings.destroy
- Test it

bookings.index
- Test filter()

BookingController->edit()
- Find another way to see if day is full inside while loop to avoid so many round trips to DB

config.slots
- These are rendering as am/pm instead of 24h format. Take a look


## Config (/config)

Can only be accessed by admin

### **Working days**

Toggling each switches automatically updates 'config' column on database for the currently logged in admin

### **Dates**

'Always open' switch allows admin to choose between the calendar allways displaying &lt;anticipation&gt; amount of days or keeping the calendar open until a specific date (&lt;open-until&gt;)

### **Price**

Choose the session price. Database value is updated on focus out to avoid clients seeing the wrong number in case someone requests the price as it is beeing updated

### **Holidays**

Allows the admin to display certain days as unavailable on the calendar.
Create holiday plans by choosing a start and end date. If &lt;until&gt; is null or earlier than &lt;from&gt;, a single day holiday will be created.
Delete holiday plans by clicking 'delete' on the table rows.


### **Slots**

Allows admin to manage session slots. These will be shown to clients and made available for booking.


## Bookings.index (/bookings)

Can only be accessed by admin, displays a table which is filterable by booking status and allows for inpection and deletion of bookings (only admin can delete bookings as per BookingPolicy).

## Bookings.show (/bookings/{id})

Displays booking details and allows admin to mark booking as 'complete', refund, mark as paid and edit it.

## Bookings.create (/bookings/create)

This is the booking creation workflow for the different circumstances

|User status          | User role | Display form                   | Action | Outcome                              |             |               |               |
| ---                 | ---       | ---                            | ---    | ---                                  |---          |---            |---            |
|A2:User is logged in | client    | C2: Booking type, date and slot|continue|Validate, check overlap, store booking|             |               |               |
|                     | admin     | C3: User details + C2          |continue|Validate, check overlap               |user exists  | store booking |               |
|                     |           |                                |        |                                      |user !exists | create user   | store booking |
|User ! logged in     |           |'log in', 'sing up' or guest    |log in  |Go to A2                              |             |               |               |
|                     |           |                                |sign up |Go to A2                              |             |               |               |
|                     |           |                                |guest   |Go to C3                              |             |               |               |

Note: CreateBookingRequest is currently retrieving config as if there were only one admin managing the app. Therapist_id should be included in create booking form in a multi-admin scenario.

When admin creates a new booking for a non existing user, an acount is opened with a random password. If user desires to access their account, they can be sent a password reset email from users table on admin panel.

When admin is creating new booking, user details will be filled automatically after entering email address if a user exists for that email address.
If a user's details have changed, admin could update them from users panel (the booking form cannot update a users details, if a user exists for the given email address, DB details will be used, esentially overriding the form's data for the user).

## Bookings.update (/bookings/{id}/)

Displays a form for updating/inserting bookings that have a state of BOOKING_PENDING

'date' &lt;select&gt; shows days as disabled if there's a corresponding holiday or if there are as many bookings for the day as slots in config table.

'time' &lt;select&gt; updates itself when a new day is selected, disabling any slots that have a booking on them, except if it corresponds to current booking.

'restore' button restores date and time.

## Deployment TO DO

Cache views with 

```
php artisan view:cache
```

Cache icons with

```
php artisan icons:cache
```

## Built With

* [Laravel 9](https://laravel.com/) - To make it work
* [Tailwind CSS](https://maven.apache.org/) - To make it look cool


## Authors

* **Rodrigo Alvarez** - *Initial work* - [portfolio](https://rodrigoalvarez.co.uk)


## Acknowledgments

* Hat tip to anyone whose code was used
* Inspiration
* etc
