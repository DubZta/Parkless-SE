const loginText = document.querySelector(".title-text .login");
const loginForm = document.querySelector("form.login");
const loginBtn = document.querySelector("label.login");
const signupBtn = document.querySelector("label.signup");
const signupLink = document.querySelector("form .signup-link a");

if (loginForm) {
  signupBtn.onclick = () => {
    loginForm.style.marginLeft = "-50%";
    loginText.style.marginLeft = "-50%";
  };
  loginBtn.onclick = () => {
    loginForm.style.marginLeft = "0%";
    loginText.style.marginLeft = "0%";
  };
  signupLink.onclick = () => {
    signupBtn.click();
    return false;
  };

  loginForm.addEventListener("submit", (event) => {
    event.preventDefault(); // Prevent default form submission
  
    // Get the email and password fields
    const email = loginForm.querySelector("input[type='email']").value.trim();
    const password = loginForm.querySelector("input[type='password']").value.trim();
  
    // Check if fields are filled
    if (email && password) {
      window.location.href = "/"; // Redirect to root of Laravel app
    } else {
      alert("Please fill in both the email and password fields."); // Show an alert if fields are empty
    }
  });
}