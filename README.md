# Alirey

A booking management system. This doc describes what each section does.

## TO DO

config
- Dissallow deleting slots with pending bookings

bookings.upsert
- Display dates in spanish

bookings.show
- Display refund button according to payment status:
    + payment == PAYMENT_MP -> 'refund'
    + payment == PAYMENT_PENDING -> 'mark as paid'
    + payment == PAYMENT_REFUNDED || payment == PAYMENT_CASH -> remove button
- Implement 'refund'
- Hide 'edit' button when status == BOOKING_COMPLETED || status == BOOKING_CANCELLED

bookings.destroy
- Test it

bookings.index
- Test filter()

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

## Bookings.upsert (/bookings/{id}/edit & /bookings/create)

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
