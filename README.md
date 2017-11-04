## Example app for Typeform APIs/Webhooks

Example application uses a 'contest entry' form, capturing first name/last name/email. Random winners of the contest are displayed on the 'Winners' page.

This application has no sophisticated error handling and shouldn't be used for real applications in it's current state.

### Configuration

1. Create a typeform

  * Ensure form contains the following fields:

    * `short_text`: First name
    * `short_text`: Last name
    * `email`: Email address (obviously)


2. Create `application/config/form.php`

  Populate with values form your form

```
<?php
# Example values
$config['webhook_form_id'] = 'fda8hrw2';
$config['webhook_auth_key'] = 'abc123';
$config['webhook_fname_field_id'] = 'EwxhuXSL9Tu1';
$config['webhook_lname_field_id'] = 'wycpQQkjdmUv';
$config['webhook_email_field_id'] = 'VusAj66FK2cR';

?>
```

3. Create database

  Run `create_db.sql` in your local database, configure `application/config/database.php` accordingly.

4. Configure webhooks

  In typeform integration settings, or via Webhook Configuration API, `key` in this case is not your Typeform API key, just something for Typeform to use to authenticate to your webhook listener:

  * Set endpoint to: `http://<your_domain>/index.php/WebhookListener/view?key=<your_made_up_key>` (ngrok recommended)

5. Submit typeform

  Results should be stored in local db.
