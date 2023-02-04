# Alirey

A booking management system. This doc describes what each section does.


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

Displays booking details and allows admin to mark booking as 'complete', refund and edit it.

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
