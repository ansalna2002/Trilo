// Auth:RYJHWQLCR

var password1 = document.getElementById('password');
var toggler1 = document.getElementById('toggler');
var icon1 = document.getElementById('icon');

function showHidePassword1() {
  if (password1.type === 'password') {
    password1.type = 'text';
    icon1.classList.remove('fa-eye-slash');
    icon1.classList.add('fa-eye');
  } else {
    password1.type = 'password';
    icon1.classList.remove('fa-eye');
    icon1.classList.add('fa-eye-slash');
  }
}

toggler1.addEventListener('click', function(event) {
  event.preventDefault(); 
  showHidePassword1();
});


var password2 = document.getElementById('confirm_pwd');
var toggler2 = document.getElementById('toggler2');
var icon2 = document.getElementById('icon2');

function showHidePassword2() {
  if (password2.type === 'password') {
    password2.type = 'text';
    icon2.classList.remove('fa-eye-slash');
    icon2.classList.add('fa-eye');
  } else {
    password2.type = 'password';
    icon2.classList.remove('fa-eye');
    icon2.classList.add('fa-eye-slash');
  }
}

toggler2.addEventListener('click', function(event) {
  event.preventDefault(); 
  showHidePassword2();
});


var password3 = document.getElementById('new_pwd');
var toggler3 = document.getElementById('toggler3');
var icon3 = document.getElementById('icon3');

function showHidePassword3() {
  if (password3.type === 'password') {
    password3.type = 'text';
    icon3.classList.remove('fa-eye-slash');
    icon3.classList.add('fa-eye');
  } else {
    password3.type = 'password';
    icon3.classList.remove('fa-eye');
    icon3.classList.add('fa-eye-slash');
  }
}

toggler3.addEventListener('click', function(event) {
  event.preventDefault(); 
  showHidePassword3();
});











