let profile = document.querySelector('.header .flex .profile');
let userButton = document.querySelector("#user-btn");

userButton.addEventListener("click", () => {
   if(profile.classList.contains("active")) {
      profile.classList.remove("active");
   }
   else {
      profile.classList.add("active");
   }
});


document.querySelectorAll('input[type="number"]').forEach(inputNumber => {
   inputNumber.oninput = () =>{
      if(inputNumber.value.length > inputNumber.maxLength) inputNumber.value = inputNumber.value.slice(0, inputNumber.maxLength);
   };
});