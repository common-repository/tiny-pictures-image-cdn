<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <form id="optionsForm" action="options.php" method="post">
        <?php
            settings_fields($this->optionName);
            do_settings_sections($this->optionName);
            submit_button();
        ?>
    </form>

    <?php if (!$this->option['user']) { ?>
        <h1>tiny.pictures free registration</h1>
        <p>Get our services deployed for you in an instant! Seriously, it's just these couple of fields and we already filled most of them for you.</p>
        <p>We'll set you up on our Hobby plan, which is <b>free for life</b>. It includes a free SSL certificate, 2&#8239;GB of traffic, 15,000 transformations and 50,000 requests per month. Please see our <a href="https://tiny.pictures/#pricing" target="_blank" rel="noopener">pricing</a> table for more. All our limits are soft limits. If you have more traffic than that, we keep serving your assets and will get in contact with you to select a more suitable plan.</p>
        <form id="registrationForm">
            <input type="hidden" name="plan" value="hobbyMonth">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row">
                            Image source
                        </th>
                        <td>
                            <input type="url" name="source" required value="<?php echo $this->homeUrl; ?>">
                            <p class="description">The base URL of your blog.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            User name
                        </th>
                        <td>
                            <input type="url" name="user" required minlength=3 maxlength=32 pattern="[a-z]*" value="<?php echo sanitize_title(get_bloginfo('name'), '', 'save') ?>">
                            <p class="description">The user name you'd like to register with. Defaults to the slug of your blog's title.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            Email
                        </th>
                        <td>
                            <input type="email" name="email" required value="<?php echo get_bloginfo('admin_email') ?>">
                            <p class="description">The email address you'd like to register with. Defaults to the blog admin's email address.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            Terms
                        </th>
                        <td>
                            <input type="checkbox" name="terms" required>
                            <p class="description">I accept the <a href="https://tiny.pictures/terms" target="_blank" rel="noopener">terms of service</a> and <a href="https://tiny.pictures/#pricing" target="_blank" rel="noopener">pricing information</a>.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div id="registrationFormSuccess" class="notice notice-info" hidden></div>
            <div id="registrationFormError" class="notice-fixed notice-error" hidden></div>
            <p class="submit">
                <input type="submit" name="submit" id="registrationFormSubmit" class="button button-primary g-recaptcha" data-sitekey="6LfPLiAUAAAAAAnDs73WFYGknwq-RiS9JKpizuW7" data-callback="registrationFormSubmitCallback" value="Register for free">
            </p>
        </form>
        <script><?php include 'settings.js'; ?></script>
        <style>
            .notice-fixed {
                margin: 5px 0 15px;
                background: #ffffff;
                border-left-width: 4px;
                border-left-style: solid;
                box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
                padding: 1px 12px;
            }
        </style>
    <?php } ?>
</div>

