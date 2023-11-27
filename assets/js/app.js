/** @format */

(() => {
  document.querySelectorAll('input[type="number"]').forEach((inputNumber) => {
    inputNumber.oninput = () => {
      if (inputNumber.value.length > inputNumber.maxLength) {
        inputNumber.value = inputNumber.value.slice(0, inputNumber.maxLength);
      }
    };
  });

  //ヘッダープロフィール設定
  const $profile = document.querySelector(('.header .flex  .profile'));
  const $userBtn = document.querySelector('.header .flex .navbar #user-btn');

  $userBtn.addEventListener('click', ()=>{
    $profile.classList.toggle('active');
  })

  window.addEventListener('scroll', ()=>{
    $profile.classList.remove('active');
  })
})();
