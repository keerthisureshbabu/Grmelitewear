
document.addEventListener("DOMContentLoaded", function() {
    // Get all elements with class 'nft-tag'
    const nftTags = document.querySelectorAll('.nft-tag');

    nftTags.forEach(tag => {
        // Add click event listener to each 'nft-tag'
        tag.addEventListener('click', function() {
            // Remove 'active' class from all nft-tags
            nftTags.forEach(t => t.classList.remove('active'));
            
            // Add 'active' class to the clicked nft-tag
            this.classList.add('active');
        });
    });
});