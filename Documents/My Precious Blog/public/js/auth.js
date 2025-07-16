import { auth } from "../../firebase/firebase-config.js";
import {
  signInWithEmailAndPassword,
  signOut,
  onAuthStateChanged
} from "firebase/auth";

// Elements
const loginModal = document.getElementById("loginModal");
const loginForm = document.getElementById("loginForm");
const adminBtn = document.querySelector(".admin-btn");

// Show login modal
window.showLogin = function() {
  loginModal.style.display = "block";
};

// Close modal function
window.closeModal = function(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) modal.style.display = "none";
};

// Handle login
loginForm.addEventListener("submit", async (e) => {
  e.preventDefault();
  const email = loginForm.email.value.trim();
  const password = loginForm.password.value.trim();

  try {
    await signInWithEmailAndPassword(auth, email, password);
    loginForm.reset();
    closeModal("loginModal");
  } catch (error) {
    alert("Login failed: " + error.message);
  }
});

// Handle logout
window.logout = async function() {
  await signOut(auth);
  alert("Logged out successfully");
};

// Auth state listener to toggle admin button visibility
onAuthStateChanged(auth, (user) => {
  if (user) {
    adminBtn.style.display = "none";
    openAdminPanel();
  } else {
    adminBtn.style.display = "inline-block";
    closeAdminPanel();
  }
});

function openAdminPanel() {
  const adminModal = document.getElementById("adminModal");
  if (adminModal) adminModal.style.display = "block";
}

function closeAdminPanel() {
  const adminModal = document.getElementById("adminModal");
  if (adminModal) adminModal.style.display = "none";
}

// Close admin and refresh blog list
window.closeAdminAndRefresh = function() {
  closeAdminPanel();
  // Custom event or call a refresh function if needed
  window.dispatchEvent(new Event('refreshArticles'));
};
