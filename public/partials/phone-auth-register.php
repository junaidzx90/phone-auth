<?php
if(is_user_logged_in(  ) && !current_user_can( 'administrator' )){
    return;
}
?>
<div class="wrapper">
    <div class="container">
        <div class="col-left">
            <div class="login-text">
                <h2>স্বাগতম</h2>
                <p>আগে থেকে একটি একাউন্ট থাকলে<br></p>
                <a class="btn loginbtn" href="<?php echo the_permalink(  ) ?>">প্রবেশ করুন</a>
            </div>
        </div>
        <div class="col-right">
            <div class="login-form">
                <h2>নিবন্ধন ফর্ম</h2>
                <form method="post">
                    <div class="form_contents">
                        <p>
                            <label>মোবাইল নাম্বার<span>*</span></label>
                            <input maxlength="10" autocomplete="off" onkeypress="return /[0-9]/i.test(event.key)"
                                id="reg_phone" type="text" placeholder="+880" required>
                        </p>
                        <p class="select-wrapper">
                            <label>একাউন্ট টাইপ<span>*</span></label>
                            <select class="selectitem" name="user_role" id="user_role">
                                <option value="driver">পার্টনার</option>
                                <option value="client">কাস্টমার</option>
                            </select>
                        </p>
                        <p>
                            <label>পাসওয়ার্ড<span>*</span></label>
                            <input autocomplete="new-password" type="password" id="password" placeholder="পাসওয়ার্ড" required>
                            <span id="passstatus"></span>
                        </p>
                        <p>
                            <label>
                                <input id="indeterminate-checkbox" class="terms_conditions" type="checkbox" />
                                <span>আমি সমস্ত <a target="_blank" href="https://bahak.com.bd/privacy-policy/">শর্তাবলীর</a> সাথে একমত</span>
                            </label>
                        </p>
                        <p>
                            <input disabled onclick="registerAction(event)" id="register_btn" type="submit" value="নিবন্ধন করুন" />
                        </p>
                    </div>

                    <div class="code_verification"></div>

                    <div id="recaptcha-container"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="authLoader authnone">
    <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="50px" height="50px" viewBox="0 0 40 40" enable-background="new 0 0 40 40" xml:space="preserve">
        <path opacity="0.2" fill="#000" d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946
        s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634
        c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z"></path>
        <path fill="<?php echo ((get_option('lr_selected_color')) ? get_option('lr_selected_color') : '#00bcd4') ?>" d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0
        C22.32,8.481,24.301,9.057,26.013,10.047z">
        <animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 20 20" to="360 20 20" dur="0.9s" repeatCount="indefinite"></animateTransform>
        </path>
    </svg>
</div>