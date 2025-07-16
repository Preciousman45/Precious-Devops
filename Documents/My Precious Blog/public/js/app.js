import { db } from "../firebase/firebase-config.js";
import {
  collection,
  addDoc,
  getDocs,
  doc,
  updateDoc,
  deleteDoc,
  query,
  orderBy,
  serverTimestamp,
  where
} from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";

const blogGrid = document.getElementById("blogGrid");
const articleForm = document.getElementById("articleForm");
const articlesList = document.getElementById("articlesList");
const totalArticlesElem = document.getElementById("totalArticles");
const recentArticlesElem = document.getElementById("recentArticles");
const totalViewsElem = document.getElementById("totalViews");

const articlesCollection = collection(db, "articles");

function createArticleCard(article) {
  const card = document.createElement("div");
  card.className = "blog-card";
  card.innerHTML = `
    <h3>${article.title}</h3>
    <p class="excerpt">${article.excerpt}</p>
    <a href="#" class="read-more" data-id="${article.id}">Read More</a>
  `;
  card.querySelector(".read-more").addEventListener("click", (e) => {
    e.preventDefault();
    viewArticle(article.id);
  });
  return card;
}

async function viewArticle(id) {
  const docRef = doc(db, "articles", id);
  const docSnap = await getDocs(query(collection(db, "articles"), where("__name__", "==", id)));

  if (!docSnap.empty) {
    const data = docSnap.docs[0].data();
    document.getElementById("articleTitle").textContent = data.title;
    document.getElementById("articleDate").textContent = new Date(data.createdAt.seconds * 1000).toLocaleDateString();
    document.getElementById("articleContent").innerHTML = data.content;

    // Author info
    const authorContainer = document.getElementById("articleAuthor");
    authorContainer.innerHTML = "";
    if (data.authorName) {
      const authorTemplate = document.getElementById("authorTemplate").content.cloneNode(true);
      authorTemplate.querySelector(".author-img").src = data.authorImage || "https://via.placeholder.com/50";
      authorTemplate.querySelector(".author-name").textContent = data.authorName;
      authorTemplate.querySelector(".author-link").href = data.authorLinkedIn || "#";
      authorContainer.appendChild(authorTemplate);
    }

    // Show modal
    document.getElementById("articleModal").style.display = "block";

    // Increment views count safely
    await incrementViews(id);
  }
}

// Increment views function with updateDoc
async function incrementViews(id) {
  const articleRef = doc(db, "articles", id);
  try {
    await updateDoc(articleRef, {
      views: (await getViews(id)) + 1
    });
    refreshStats();
  } catch (error) {
    console.error("Failed to update views:", error);
  }
}

async function getViews(id) {
  const docSnap = await getDocs(query(collection(db, "articles"), where("__name__", "==", id)));
  if (!docSnap.empty) {
    return docSnap.docs[0].data().views || 0;
  }
  return 0;
}

async function loadArticles() {
  blogGrid.innerHTML = "";
  const q = query(articlesCollection, orderBy("createdAt", "desc"));
  const querySnapshot = await getDocs(q);
  let totalViews = 0;

  querySnapshot.forEach((docSnap) => {
    const article = { id: docSnap.id, ...docSnap.data() };
    blogGrid.appendChild(createArticleCard(article));
    totalViews += article.views || 0;
  });

  totalArticlesElem.textContent = querySnapshot.size;
  totalViewsElem.textContent = totalViews;
  recentArticlesElem.textContent = await getRecentArticlesCount(querySnapshot.docs);
}

async function getRecentArticlesCount(docs) {
  const oneMonthAgo = new Date();
  oneMonthAgo.setMonth(oneMonthAgo.getMonth() - 1);

  let count = 0;
  docs.forEach(docSnap => {
    const createdAt = docSnap.data().createdAt;
    if (createdAt && createdAt.toDate() >= oneMonthAgo) count++;
  });
  return count;
}

// Publish new article with validation
articleForm.addEventListener("submit", async (e) => {
  e.preventDefault();

  const title = articleForm.title.value.trim();
  const excerpt = articleForm.excerpt.value.trim();
  const content = articleForm.content.value.trim();
  const tags = articleForm.tags.value.trim();
  const authorImage = articleForm.authorImage.value.trim();
  const authorName = articleForm.authorName.value.trim();
  const authorLinkedIn = articleForm.authorLinkedIn.value.trim();

  // Simple client-side validation
  if (!title || !excerpt || !content) {
    alert("Title, excerpt and content are required.");
    return;
  }
  if (authorLinkedIn && !isValidUrl(authorLinkedIn)) {
    alert("Please enter a valid URL for LinkedIn profile.");
    return;
  }

  try {
    await addDoc(articlesCollection, {
      title,
      excerpt,
      content,
      tags: tags ? tags.split(",").map(t => t.trim()) : [],
      authorImage,
      authorName,
      authorLinkedIn,
      views: 0,
      createdAt: serverTimestamp()
    });

    articleForm.reset();
    alert("Article published successfully!");
    loadArticles();
  } catch (error) {
    alert("Failed to publish article: " + error.message);
  }
});

function isValidUrl(string) {
  try {
    new URL(string);
    return true;
  } catch {
    return false;
  }
}

// Listen for refresh event from auth.js
window.addEventListener('refreshArticles', loadArticles);

loadArticles();

// Helper to close modal
window.closeModal = function(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) modal.style.display = "none";
};

// Tab switching in admin panel
window.showTab = function(tabName) {
  const tabs = document.querySelectorAll(".tab-content");
  const buttons = document.querySelectorAll(".tab-btn");

  tabs.forEach(tab => {
    tab.classList.remove("active");
    if (tab.id === tabName) tab.classList.add("active");
  });

  buttons.forEach(btn => {
    btn.classList.remove("active");
    if (btn.textContent.toLowerCase() === tabName) btn.classList.add("active");
  });
};