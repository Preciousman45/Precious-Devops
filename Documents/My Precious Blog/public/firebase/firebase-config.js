// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
import { getAuth } from "firebase/auth";
import { getFirestore } from "firebase/firestore";

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
