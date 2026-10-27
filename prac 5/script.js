'use strict';

const form = document.getElementById('registrationForm');
const successMessage = document.getElementById('successMessage');
const fields = ['name', 'email', 'mobile', 'password', 'confirmPassword', 'course', 'year'];
const nameRegex = /^[\p{L}]+(?:[ .'-][\p{L}]+)*$/u;
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
const mobileRegex = /^[6-9]\d{9}$/;
const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9\s])\S{8,}$/;

function showError(id, message) {
  document.getElementById(`${id}Error`).textContent = message;
  const input = document.getElementById(id);
  if (input) input.setAttribute('aria-invalid', message ? 'true' : 'false');
  if (id === 'gender') document.getElementById('genderGroup').setAttribute('aria-invalid', message ? 'true' : 'false');
  if (message) successMessage.textContent = '';
  return !message;
}

function getPasswordScore(value) {
  if (!value) return 0;
  return [value.length >= 8, /[a-z]/.test(value), /[A-Z]/.test(value), /\d/.test(value), /[^A-Za-z0-9\s]/.test(value)]
    .filter(Boolean).length - (value.length >= 8 ? 1 : 0);
}

function updatePasswordStrength() {
  const value = document.getElementById('password').value;
  const score = Math.max(0, getPasswordScore(value));
  const labels = ['Not entered', 'Weak', 'Fair', 'Good', 'Strong'];
  const colors = ['#e5eaf0', '#c62828', '#e88924', '#c5a300', '#218447'];
  const bar = document.getElementById('strengthBar');
  const fill = document.getElementById('strengthFill');
  const strength = value ? Math.max(1, score) : 0;
  bar.setAttribute('aria-valuenow', String(strength));
  bar.setAttribute('aria-valuetext', labels[strength]);
  fill.style.width = `${strength * 25}%`;
  fill.style.backgroundColor = colors[strength];
  document.getElementById('strengthText').textContent = `Password strength: ${labels[strength]}`;
}

function validate(id) {
  const el = document.getElementById(id);
  const value = el.value.trim();
  let message = '';
  switch (id) {
    case 'name':
      if (!value) message = 'Full name is required.';
      else if (value.length < 2 || !nameRegex.test(value)) message = 'Enter a valid name using letters and spaces.';
      break;
    case 'email':
      if (!value) message = 'Email address is required.';
      else if (!emailRegex.test(value)) message = 'Enter a valid email address.';
      break;
    case 'mobile':
      if (!value) message = 'Mobile number is required.';
      else if (!mobileRegex.test(value)) message = 'Enter a valid 10-digit Indian mobile number starting with 6–9.';
      break;
    case 'password':
      if (!value) message = 'Password is required.';
      else if (!passwordRegex.test(value)) message = 'Use at least 8 characters, with uppercase, lowercase, number and special character (no spaces).';
      break;
    case 'confirmPassword':
      if (!value) message = 'Please confirm your password.';
      else if (value !== document.getElementById('password').value) message = 'Passwords do not match.';
      break;
    case 'course': if (!value) message = 'Please select a course.'; break;
    case 'year': if (!value) message = 'Please select a year.'; break;
    case 'terms': if (!el.checked) message = 'You must accept the Terms and Conditions.'; break;
  }
  return showError(id, message);
}

function validateGender() {
  const selected = form.querySelector('input[name="gender"]:checked');
  return showError('gender', selected ? '' : 'Please select your gender.');
}

fields.forEach(id => {
  const el = document.getElementById(id);
  const eventName = el.tagName === 'SELECT' ? 'change' : 'input';
  el.addEventListener(eventName, () => {
    if (id === 'password') {
      updatePasswordStrength();
      if (document.getElementById('confirmPassword').value) validate('confirmPassword');
    }
    validate(id);
  });
  el.addEventListener('blur', () => validate(id));
});
form.querySelectorAll('input[name="gender"]').forEach(radio => radio.addEventListener('change', validateGender));
document.getElementById('terms').addEventListener('change', () => validate('terms'));

form.addEventListener('submit', event => {
  event.preventDefault();
  successMessage.textContent = '';
  const checks = [...fields.map(validate), validateGender(), validate('terms')];
  if (checks.every(Boolean)) {
    successMessage.textContent = 'Registration successful! Your form has been validated successfully.';
    // Frontend demonstration only: no student information is sent or stored.
  } else {
    const firstInvalid = form.querySelector('[aria-invalid="true"]');
    if (firstInvalid) {
      if (firstInvalid.id === 'genderGroup') form.querySelector('input[name="gender"]').focus();
      else firstInvalid.focus();
    }
  }
});

updatePasswordStrength();
