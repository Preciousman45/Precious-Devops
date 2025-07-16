// Import Firebase modules from CDN
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-analytics.js";
import { getAuth } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js";
import { getFirestore } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";

// Your web app's Firebase configuration
const firebaseConfig = {
  apiKey: "AIzaSyD5iqMEVFheffw7n-HNxGgoFcBzhBZYunY",
  authDomain: "precious-johnson.firebaseapp.com",
  projectId: "precious-johnson",
  storageBucket: "precious-johnson.firebasestorage.app",
  messagingSenderId: "430870194016",
  appId: "1:430870194016:web:f425519d39ef93c56ce005",
  measurementId: "G-LQDS3ZDHYE"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
const auth = getAuth(app);
const db = getFirestore(app);

export { auth, db };