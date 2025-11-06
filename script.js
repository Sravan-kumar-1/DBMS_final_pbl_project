document.addEventListener("DOMContentLoaded", () => {

  // ===== Switch Login / Signup UI =====
  const goSignup = document.getElementById("goSignup");
  const goLogin = document.getElementById("goLogin");
  const loginBox = document.getElementById("loginBox");
  const signupBox = document.getElementById("signupBox");

  if (goSignup) goSignup.addEventListener("click", () => {
    loginBox.classList.add("hidden");
    signupBox.classList.remove("hidden");
  });

  if (goLogin) goLogin.addEventListener("click", () => {
    signupBox.classList.add("hidden");
    loginBox.classList.remove("hidden");
  });

  // ===== LOGIN using Node API =====
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const user = document.getElementById("loginUser").value.trim();
      const pass = document.getElementById("loginPass").value.trim();

      const res = await fetch("/api/auth/login", {
        method: "POST",
        headers: { "Content-Type" : "application/json" },
        body: JSON.stringify({ user, pass })
      });

      const json = await res.json();

      if (json.status === "success") {
        localStorage.setItem("token", json.token);

        if (json.role === "admin") {
          window.location.href = "admin_dashboard.html";
        } else {
          window.location.href = "services.html";
        }
      } else {
        alert("❌ " + json.message);
      }
    });
  }

  // ===== SIGNUP using Node API =====
  const signupForm = document.getElementById("signupForm");
  if (signupForm) {
    signupForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const res = await fetch("/api/auth/signup", {
        method: "POST",
        headers: { "Content-Type":"application/json" },
        body: JSON.stringify({
          name: document.getElementById("signupName").value,
          email: document.getElementById("signupEmail").value,
          phone: document.getElementById("signupPhone").value,
          password: document.getElementById("signupPass").value
        })
      });

      const json = await res.json();

      if (json.status === "success") {
        alert("✅ Signup Successful! Login now.");
        signupBox.classList.add("hidden");
        loginBox.classList.remove("hidden");
      } else {
        alert("⚠️ " + json.message);
      }
    });
  }

});

// ===== BOOKING FORM (Book Page) =====
const form = document.getElementById("bookForm");
if (form) {
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const fd = new FormData(form);

    const svc = document.getElementById("serviceSelect");
    if (svc) fd.set("service_name", svc.options[svc.selectedIndex].text);

    const token = localStorage.getItem("token");

    try {
      const res = await fetch("https://daddys-garage-backend.onrender.com/api/bookings", {
  method: "POST",
  body: fd,
});


      const json = await res.json();

      if (json.status === "success") {
        alert("✅ Booking Successful!");
        form.reset();
        document.getElementById("serviceId").value = "";
      } else {
        alert("⚠️ " + json.message);
      }

    } catch (err) {
      console.error(err);
      alert("❌ Server Error!");
    }
  });
}

// ===== LOGOUT =====
function logout() {
  localStorage.removeItem("token");
  window.location.href = "auth.html";
}
