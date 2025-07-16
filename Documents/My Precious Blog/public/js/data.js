import { db } from '../../firebase/firebase-config.js';
import {
  collection,
  getDocs,
  doc,
  getDoc,
  updateDoc,
} from 'https://www.gstatic.com/firebasejs/10.12.2/firebase-firestore.js';

const blogGrid = document.getElementById('blogGrid');
const articleModal = document.getElementById('articleModal');
const articleTitle = document.getElementById('articleTitle');
const articleDate = document.getElementById('articleDate');
const articleContent = document.getElementById('articleContent');
const articleAuthor = document.getElementById('articleAuthor');
const authorTemplate = document.getElementById('authorTemplate');

async function fetchArticles() {
  try {
    const querySnapshot = await getDocs(collection(db, 'articles'));
    blogGrid.innerHTML = ''; // Clear existing grid

    querySnapshot.forEach(docSnap => {
      const article = docSnap.data();
      const articleCard = document.createElement('div');
      articleCard.className = 'blog-card';
      articleCard.innerHTML = `
        <h3>${article.title}</h3>
        <p class="excerpt">${article.excerpt}</p>
        <p class="tags">${article.tags ? article.tags.join(', ') : ''}</p>
        <div class="meta">
          <span>${new Date(article.date).toDateString()}</span>
          <span>${article.views || 0} views</span>
        </div>
        <button class="btn btn-secondary" onclick="viewArticle('${docSnap.id}')">Read More</button>
      `;
      blogGrid.appendChild(articleCard);
    });
  } catch (error) {
    console.error('Error fetching articles:', error);
  }
}

window.viewArticle = async function (id) {
  try {
    const docRef = doc(db, 'articles', id);
    const docSnap = await getDoc(docRef);

    if (docSnap.exists()) {
      const data = docSnap.data();

      // Populate modal content
      articleTitle.textContent = data.title;
      articleDate.textContent = new Date(data.date).toLocaleDateString();
      articleContent.innerHTML = data.content;

      // Populate author
      articleAuthor.innerHTML = '';
      const authorNode = authorTemplate.content.cloneNode(true);
      authorNode.querySelector('.author-img').src = data.authorImage || '';
      authorNode.querySelector('.author-name').textContent = data.authorName || '';
      authorNode.querySelector('.author-link').href = data.authorLinkedIn || '#';
      articleAuthor.appendChild(authorNode);

      // Update views in Firestore
      const currentViews = data.views || 0;
      await updateDoc(docRef, { views: currentViews + 1 });

      articleModal.classList.add('show');
    } else {
      console.error('Article not found.');
    }
  } catch (error) {
    console.error('Error viewing article:', error);
  }
};

fetchArticles();
