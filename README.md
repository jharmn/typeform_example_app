## Example app for Typeform APIs/Webhooks

This is an example application for a Typeform Master Class at Restart Network Rotterdam. It uses a 'registration'-style form, capturing first name/last name/email/pic for students in the class. 

Additionally, there is a "menu builder" form, which in turn creates a form to pick lunch selections for a week. This emulates the process we use at Typeform for managing our in-office lunch logistics.

This application has no sophisticated error handling and shouldn't be used for real applications in it's current state.

### Configuration

1. Create a typeform

  * Ensure form contains the following fields :

    * `short_text`: First name (ref: fname)
    * `short_text`: Last name (ref: lname)
    * `email`: Email address (ref: email)
    * `image`: Photo of student (ref: pic)


2. Create `application/config/form.php`

  Populate with values form your form

```
<?php
// Typeform personal access token (for accessing your own account)
$config['typeform_access_token'] = 'yjhYDtqyBfrweNy5HqnJI2xLfFOkoxMHbKTHxGEnIfY=';
// Typeform username, for embedding
$config['typeform_username'] = 'jasonharmon';
// Registration form id
$config['form_id'] = 'fda8hrw2';
// Arbitrary developer-defined key, for securing webhook listener
$config['webhook_auth_key'] = 'abc123';

// You can either use form field `id`
$config['fname_field_id'] = 'EwxhuXSL9Tu1';
$config['lname_field_id'] = 'wycpQQkjdmUv';
$config['email_field_id'] = 'VusAj66FK2cR';
$config['image_field_id'] = 'OpnrNJBr1G5K';

// ...or field `ref`:
$config['fname_field_ref'] = 'fname';
$config['lname_field_ref'] = 'lname';
$config['email_field_ref'] = 'email';
$config['image_field_ref'] = 'pic';
?>
```

3. Create database

  Run `create_db.sql` in your local database, configure `application/config/database.php` accordingly.

4. Configure webhooks

  In typeform integration settings, or via Webhook Configuration API, `key` in this case is not your Typeform API key, just something for Typeform to use to authenticate to your webhook listener:

  * Set endpoint to: `http://<your_domain>/index.php/WebhookListener/view?key=<your_made_up_key>` (ngrok recommended)

5. Submit typeform

  Results should be stored in local db.
