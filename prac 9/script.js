// Simple frontend validation - backend validation is also required.
const form = document.getElementById('registrationForm');
const password = document.getElementById('password');
const confirmation = document.getElementById('confirmPassword');
const errorBox = document.getElementById('formError');

password.addEventListener('input', function () {
  let score = 0;
  const value = password.value;
  if (value.length >= 8) score++;
  if (/[a-z]/.test(value) && /[A-Z]/.test(value)) score++;
  if (/[0-9]/.test(value)) score++;
  if (/[^A-Za-z0-9]/.test(value)) score++;
  const labels = ['Not entered', 'Weak', 'Fair', 'Good', 'Strong'];
  document.getElementById('strengthBar').style.width = (value ? score * 25 : 0) + '%';
  document.getElementById('strengthText').textContent = 'Password strength: ' + (value ? labels[score] : labels[0]);
});

form.addEventListener('submit', function (event) {
  errorBox.textContent = '';
  const name = document.getElementById('name').value.trim();
  const username = document.getElementById('username').value.trim();
  const email = document.getElementById('email').value.trim();
  const mobile = document.getElementById('mobile').value.trim();
  const course = document.getElementById('course').value;
  const year = document.getElementById('year').value;
  const gender = document.querySelector('input[name="gender"]:checked');
  let message = '';
  if (!name || !username || !email || !mobile || !password.value || !confirmation.value || !course || !year || !gender || !document.getElementById('terms').checked) {
    message = 'Please fill in all fields and accept the terms.';
  } else if (!/^[A-Za-z][A-Za-z ]{2,}$/.test(name)) {
    message = 'Enter a valid full name (at least 3 letters).';
  } else if (!/^[A-Za-z0-9_]{3,50}$/.test(username)) {
    message = 'Username must be 3 to 50 letters, numbers or underscores.';
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    message = 'Please enter a valid email address.';
  } else if (!/^[0-9]{10}$/.test(mobile)) {
    message = 'Mobile number must have 10 digits.';
  } else if (password.value.length < 8) {
    message = 'Password must be at least 8 characters.';
  } else if (password.value !== confirmation.value) {
    message = 'Passwords do not match.';
  }
  if (message) {
    event.preventDefault();
    errorBox.textContent = message;
  }
});
