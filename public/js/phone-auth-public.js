// Initialize Firebase
const config = authajax.config;
if (config) {
  const app = firebase.initializeApp(config);
  firebase.analytics();

  if (document.getElementById('recaptcha-container')) {
    window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier(
      'recaptcha-container',
      {
        size: 'invisible',
        callback: (response) => {
          // reCAPTCHA solved, allow signInWithPhoneNumber.
          onSignInSubmit();
        },
      },
      app
    );
  }

  // Phone number 0 remove
  if (document.getElementById('reg_phone')) {
    let ph = document.getElementById('reg_phone');
    ph.addEventListener('keyup', function () {
      if (this.value !== '' && Number.isInteger(parseInt(this.value))) {
        document.getElementById('reg_phone').value = parseInt(this.value);
      }
    });
  }
  if (document.getElementById('login_phone')) {
    let ph1 = document.getElementById('login_phone');
    ph1.addEventListener('keyup', function () {
      if (this.value !== '' && Number.isInteger(parseInt(this.value))) {
        document.getElementById('login_phone').value = parseInt(this.value);
      }
    });
  }

  function testPassword(pwString) {
    var strength = 0;

    strength += /[A-Z]+/.test(pwString) ? 1 : 0;
    strength += /[a-z]+/.test(pwString) ? 1 : 0;
    strength += /[0-9]+/.test(pwString) ? 1 : 0;
    strength += /[\W]+/.test(pwString) ? 1 : 0;

    switch (strength) {
      case 3:
        return 'Medium password';
        break;
      case 4:
        return 'Strong password';
        break;
      default:
        return 'Weak password';
        break;
    }
  }

  // Password test
  if (document.getElementById('password')) {
    let pass = document.getElementById('password');
    pass.addEventListener('keyup', function () {
      if (this.value.length > 0 && document.getElementById('passstatus')) {
        document.getElementById('passstatus').innerText = testPassword(
          this.value
        );
      }
    });
  }

  if (document.querySelector('.terms_conditions') !== null) {
    let terms = document.querySelector('.terms_conditions');
    terms.addEventListener('change', function () {
      if (this.checked) {
        document.getElementById('register_btn').removeAttribute('disabled');
      } else {
        document
          .getElementById('register_btn')
          .setAttribute('disabled', 'disabled');
      }
    });
  }

  var coderesult;
  var number = null;
  var user_role = null;
  var password = null;
  var homeUrl = null;

  function sendConfirmation(number) {
    //it takes two parameter first one is number and second one is recaptcha
    firebase
      .auth()
      .signInWithPhoneNumber(number, window.recaptchaVerifier)
      .then(function (confirmationResult) {
        //s is in lowercase
        window.confirmationResult = confirmationResult;
        coderesult = confirmationResult;

        if (coderesult.verificationId) {
          let verificationEls = `<p>
					<label>Verification Code<span>*</span></label>
					<input autocomplete="off" onkeypress="return /[0-9]/i.test(event.key)" id="verificationCode" type="text" required>
				</p>
				<p>
					<input id="verify_code" onclick="codeverify(event)" type="submit" value="Verify" />
				</p>`;

          document.querySelector('.form_contents').style.display = 'none';
          document.querySelector('.code_verification').innerHTML =
            verificationEls;
          document.querySelector('.authLoader').classList.add('authnone');
        }
      })
      .catch(function (error) {
        alert(error.message);
      });
  }

  function loginAction(e) {
    e.preventDefault();
    //get the number
    number = document.getElementById('login_phone').value;
    number = '+880' + number;
    password = document.getElementById('password').value;

    if (number !== '' && password !== '') {
      let ajdata = { number, password };
      jQuery.ajax({
        type: 'post',
        url: authajax.ajaxurl,
        data: {
          action: 'login_user',
          nonce: authajax.nonce,
          data: ajdata,
        },
        beforeSend: function () {
          document.querySelector('.authLoader').classList.remove('authnone');
        },
        dataType: 'json',
        success: function (response) {
          document.querySelector('.authLoader').classList.add('authnone');

          if (response.success) {
            location.href = response.success;
          }
          if (response.error) {
            alert('Invalid Credentials!');
          }
        },
      });
    } else {
      alert('All fields are required.');
    }
  }

  function registerAction(e) {
    e.preventDefault();

    number = document.getElementById('reg_phone').value;
    number = '+880' + number;
    user_role = document.getElementById('user_role').value;
    password = document.getElementById('password').value;

    if (number !== '' && user_role !== '' && password !== '') {
      if (password.length >= 6) {
        jQuery.ajax({
          type: 'get',
          url: authajax.ajaxurl,
          data: {
            action: 'check_user_existing',
            nonce: authajax.nonce,
            number: number,
          },
          beforeSend: function () {
            document.getElementById('register_btn').disabled = true;
            document.querySelector('.authLoader').classList.remove('authnone');
          },
          dataType: 'json',
          success: function (response) {
            if (response.success) {
              sendConfirmation(number);
            }
            if (response.error) {
              document.getElementById('register_btn').disabled = false;
              document.querySelector('.authLoader').classList.add('authnone');
              alert(response.error);
            }
          },
        });
      } else {
        alert('পাসওয়ার্ড 6 অক্ষর বা তার বেশি হওয়া উচিত');
      }
    } else {
      alert('সমস্ত ক্ষেত্র প্রয়োজন.');
    }
  }

  function codeverify(e) {
    e.preventDefault();
    let code = document.getElementById('verificationCode').value;

    coderesult
      .confirm(code)
      .then(function (result) {
        let data = null;
        let loginUrl = document.querySelector('a.loginbtn').href;

        data = { number, user_role, password };

        jQuery.ajax({
          type: 'post',
          url: authajax.ajaxurl,
          data: {
            action: 'create_user',
            nonce: authajax.nonce,
            data: data,
          },
          beforeSend: function () {
            document.getElementById('verify_code').disabled = true;
            document.querySelector('.authLoader').classList.remove('authnone');
          },
          dataType: 'json',
          success: function (response) {
            document.getElementById('verify_code').disabled = false;
            document.querySelector('.authLoader').classList.add('authnone');

            if (response.success) {
              location.href = loginUrl;
            }
            if (response.error) {
              alert(response.alert);
            }
          },
        });
      })
      .catch(function (error) {
        alert(error.message);
      });
  }
}
