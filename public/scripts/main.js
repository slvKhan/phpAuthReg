(function () {
  const errorLang = {
    ru: {
      empty: 'Поле обязательно к заполению.',
      login: 'Логин должен быть не короче 3 символов и не длиннее 12 символов.',
      email: 'Вы ввели некорректную почту.',
      phone: 'Телефон должне состоять из 12 символов.',
      password: 'Пароль должен быть не короче 6 и не длиннее 14 символов.',
      passwordConfirmation: 'Пароли не совпадают.'
    },
    en: {
      empty: 'This field cannot be empty',
      login: 'The userlogin must be no shorter than 3 characters and no longer than 12 characters.',
      email: 'Value is not a valid email, please enter again.',
      phone: 'Value is not a valid phone, please enter again',
      password: 'The password must be no shorter than 6 characters and no longer than 14 characters',
      passwordConfirmation: 'Password confirmation does not match to password.'
    },
  }
  
  const lang = document.querySelectorAll('html')[0]['lang'];
  console.log(lang);
  const errorMessages = errorLang[lang];
 
  const state = {
    fields: {
      login: '',
      email: '',
      phone: '',
      password: '',
      password_confirm: '',
    },
    errors: {},
  }
  
  const form = document.querySelector('[data-form="sign-up"]');
  const confirmPass = form.querySelector('#InputPassword2');
  const submitButton = form.querySelector('#submitButton');
  const fieldElements = {
    login: document.getElementById('InputLogin'),
    email: document.getElementById('InputEmail1'),
    phone: document.getElementById('InputPhone'),
    password: document.getElementById('InputPassword1'),
    password_confirm: document.getElementById('InputPassword2'),
  };

  form.addEventListener('input', changeHandler);
  confirmPass.addEventListener('input', (e) => {
    changeHandler(e);
    if (Object.keys(state.errors).length === 0) {
      submitButton.disabled = false;
    } else {
      submitButton.disabled = true;
    }
  });
  addEventListener('load', function() {
    bsCustomFileInput.init();
  });

  function changeHandler(e) {
    const target = e.target;
    state.fields[target.name] = target.value;
    state.errors = {};
    state.errors = validate(state.fields);
    if (Object.keys(state.errors).length === 0) {
      submitButton.disabled = false;
    } else {
      submitButton.disabled = true;
    }
    render();
  }

  function render() {
    Object.entries(fieldElements).forEach(([name, element]) => {
      const errorElement = element.nextElementSibling;
      const errorMessage = state.errors[name];
      const value = state.fields[name];
      if (errorElement) {
        element.classList.remove('is-invalid');
        errorElement.remove();
      }
      if (!errorMessage || value === '') {
        return;
      }
      const feeedBack = document.createElement('div');
      feeedBack.classList.add('invalid-feedback');
      feeedBack.innerHTML = errorMessage;
      element.classList.add('is-invalid');
      element.after(feeedBack);
    });
  }

 function validate(fields) { // pure function
  const errors = {};
  if (fields.login.length < 3 || fields.login.length > 12) {
    errors.login= fields.login.length === 0 ? errorMessages.empty : errorMessages.login;
  }
  if (fields.email.indexOf('@') === -1 || fields.email.length <= 4) {
    errors.email = fields.email.length === 0 ? errorMessages.empty : errorMessages.email;
  }
  if (fields.phone !== '') {
    if (fields.phone.length !== 12) {
      errors.phone = errorMessages.phone;
    }
  }
  if (fields.password.length < 6 || fields.password.length > 14) {
    errors.password = fields.password.length === 0 ? errorMessages.empty : errorMessages.password;
  }
  if (fields.password !== fields.password_confirm) {
    errors.password_confirm = errorMessages.passwordConfirmation;
  }
  return errors;
};
}());