const container = document.querySelector('.container');
const registerBtb = document.querySelector('.register-btn');
const loginBtn = document.querySelector('.login-btn');

registerBtb.addEventListener('click', () => {
    container.classList.add('active');
});
loginBtn.addEventListener('click', () => {
    container.classList.remove('active');
});
