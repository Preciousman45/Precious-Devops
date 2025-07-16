// UI helper functions

// Show login modal
window.showLogin = function () {
  const loginModal = document.getElementById("loginModal");
  loginModal.style.display = "block";
};

// Close any modal by id
window.closeModal = function (modalId) {
  const modal = document.getElementById(modalId);
  if (modal) modal.style.display = "none";
};

// Show admin modal
window.openAdminPanel = function () {
  const adminModal = document.getElementById("adminModal");
  if (adminModal) adminModal.style.display = "block";
};

// Close admin modal
window.closeAdminPanel = function () {
  const adminModal = document.getElementById("adminModal");
  if (adminModal) adminModal.style.display = "none";
};

// Show edit modal and populate form
window.showEditModal = function (article) {
  const editModal = document.getElementById("editModal");
  document.getElementById("editId").value = article.id;
  document.getElementById("editTitle").value = article.title;
  document.getElementById("editExcerpt").value = article.excerpt;
  document.getElementById("editContent").value = article.content;
  document.getElementById("editTags").value = (article.tags || []).join(", ");
  document.getElementById("editAuthorImage").value = article.authorImage || "";
  document.getElementById("editAuthorName").value = article.authorName || "";
  document.getElementById("editAuthorLinkedIn").value = article.authorLinkedIn || "";
  editModal.style.display = "block";
};

// Close admin modal and refresh articles list (triggered by auth.js)
window.closeAdminAndRefresh = function () {
  window.closeAdminPanel();
  window.dispatchEvent(new Event('refreshArticles'));
};

// Function to populate articles list in admin panel (for edit/delete)
export function populateAdminArticles(articles) {
  const articlesList = document.getElementById("articlesList");
  articlesList.innerHTML = "";

  articles.forEach((article) => {
    const div = document.createElement("div");
    div.className = "admin-article-item";
    div.innerHTML = `
      <h4>${article.title}</h4>
      <button class="btn btn-secondary edit-btn">Edit</button>
      <button class="btn btn-danger delete-btn">Delete</button>
    `;
    div.querySelector(".edit-btn").addEventListener("click", () => {
      window.showEditModal(article);
    });
    div.querySelector(".delete-btn").addEventListener("click", async () => {
      if (confirm(`Are you sure you want to delete "${article.title}"?`)) {
        await deleteArticle(article.id);
        window.dispatchEvent(new Event('refreshArticles'));
      }
    });
    articlesList.appendChild(div);
  });
}

// Delete article function (imports from app.js or implement here if needed)
import { deleteDoc, doc } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";
import { db } from "../../firebase/firebase-config.js";

async function deleteArticle(id) {
  try {
    await deleteDoc(doc(db, "articles", id));
    alert("Article deleted successfully");
  } catch (error) {
    alert("Error deleting article: " + error.message);
  }
}