// Search functionality for header search box
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    
    if (searchInput) {
        // Add event listener for input changes
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            if (query.length > 0) {
                searchProducts(query);
            } else {
                hideSearchResults();
            }
        });
        
        // Hide results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                hideSearchResults();
            }
        });
        
        // Handle form submission
        const searchForm = document.querySelector('.search-box');
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const query = searchInput.value.trim();
                if (query) {
                    window.location.href = `search_direct.php?query=${encodeURIComponent(query)}`;
                }
            });
        }
    }
});

// Function to search products via AJAX
function searchProducts(query) {
    if (query.length < 2) return;
    
    fetch(`search_ajax.php?query=${encodeURIComponent(query)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                displaySearchError(data.error);
            } else {
                displaySearchResults(data);
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            displaySearchError('Search failed. Please try again.');
        });
}

// Function to display search results
function displaySearchResults(results) {
    const container = document.getElementById('searchResults');
    if (!container) return;
    
    container.innerHTML = '';
    
    if (results.length === 0) {
        container.innerHTML = '<div class="p-3 text-center text-muted">No results found</div>';
        container.style.display = 'block';
        return;
    }
    
    const resultsList = document.createElement('div');
    resultsList.className = 'search-results-list';
    
    results.forEach(result => {
        const resultItem = document.createElement('div');
        resultItem.className = 'search-result-item p-3 border-bottom';
        
        const link = document.createElement('a');
        link.href = `shop.php?type=${encodeURIComponent(result.type)}&cat=${result.id}`;
        link.className = 'd-block text-decoration-none text-dark';
        link.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>${escapeHtml(result.name)}</strong>
                    <br>
                    <small class="text-muted">${escapeHtml(result.type)}</small>
                </div>
                <i class="ph ph-arrow-right text-muted"></i>
            </div>
        `;
        
        // Add click event to redirect immediately
        link.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = this.href;
        });
        
        resultItem.appendChild(link);
        resultsList.appendChild(resultItem);
    });
    
    container.appendChild(resultsList);
    container.style.display = 'block';
}

// Function to display search errors
function displaySearchError(errorMessage) {
    const container = document.getElementById('searchResults');
    if (!container) return;
    
    container.innerHTML = `<div class="p-3 text-center text-danger">${escapeHtml(errorMessage)}</div>`;
    container.style.display = 'block';
}

// Function to hide search results
function hideSearchResults() {
    const container = document.getElementById('searchResults');
    if (container) {
        container.style.display = 'none';
    }
}

// Function to escape HTML to prevent XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Add CSS for search results styling
const searchStyles = `
<style>
.search-results-container {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 1000;
    max-height: 400px;
    overflow-y: auto;
    display: none;
}

.search-results-list {
    margin: 0;
    padding: 0;
}

.search-result-item {
    transition: background-color 0.2s ease;
}

.search-result-item:hover {
    background-color: #f8f9fa;
}

.search-result-item:last-child {
    border-bottom: none !important;
}

.search-result-item a:hover {
    text-decoration: none;
}

.text-danger {
    color: #dc3545 !important;
}
</style>
`;

// Inject styles into head
document.head.insertAdjacentHTML('beforeend', searchStyles);
