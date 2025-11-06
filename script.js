document.addEventListener("DOMContentLoaded", () => {
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

  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const user = document.getElementById("loginUser").value;
      const pass = document.getElementById("loginPass").value;
      if (user === "admin" && pass === "1234") {
    alert("Admin Login Successful!");
    window.location.href = "admin_dashboard.html";
}
       else {
        alert("Invalid credentials!");
      }
    });
  }

  const signupForm = document.getElementById("signupForm");
  if (signupForm) {
    signupForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const name = document.getElementById("signupName").value;
      alert(`Welcome ${name}! Signup Successful.`);
      window.location.href = "services.html";
    });
  }

  // Booking Modal
  const modal = document.getElementById("bookingModal");
  const openBtn = document.getElementById("bookServiceBtn");
  const closeBtn = document.getElementById("closeModal");

  if (openBtn) {
    openBtn.onclick = () => modal.style.display = "block";
  }
  if (closeBtn) {
    closeBtn.onclick = () => modal.style.display = "none";
  }
  window.onclick = (event) => {
    if (event.target == modal) modal.style.display = "none";
  };

  const bookingForm = document.getElementById("bookingForm");
  if (bookingForm) {
    bookingForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const model = document.getElementById("carModel").value;
      const type = document.getElementById("serviceType").value;
      alert(`Booking Confirmed!\nCar: ${model}\nService: ${type}`);
      modal.style.display = "none";
      bookingForm.reset();
    });
  }
});

const form = document.getElementById("bookForm");

form.addEventListener("submit", async (e) => {
  e.preventDefault(); // stop normal form reload

  const fd = new FormData(form);
  // also send the readable service name
  const svc = document.getElementById("serviceSelect");
  if (svc) fd.set("service_name", svc.options[svc.selectedIndex].text);

  try {
    const res = await fetch("backend/book_service.php", {
      method: "POST",
      body: fd,
    });
    const json = await res.json(); // expect JSON back
    console.log("Server response:", json);

    if (json.status === "success") {
      alert("✅ Booking successful!");
      form.reset(); // clear the fields
    } else {
      alert("⚠️ " + (json.message || "Booking failed"));
    }
  } catch (err) {
    console.error("Fetch error:", err);
    alert("❌ Could not reach the server. Check console.");
  }
});
