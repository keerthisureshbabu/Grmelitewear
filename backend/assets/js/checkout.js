document.getElementById("personal-details-trigger").onclick= ()=>{
    document.getElementById("confirmed-tab").click()
}

document.getElementById("payment-trigger").onclick= ()=>{
    document.getElementById("shipped-tab").click()
}

document.getElementById("back-shipping-trigger").onclick= ()=>{
    document.getElementById("order-tab").click()
}

document.getElementById("back-personal-trigger").onclick= ()=>{
    document.getElementById("confirmed-tab").click()
}

document.getElementById("continue-payment-trigger").onclick= ()=>{
    document.getElementById("delivered-tab").click()
}


// Select all elements with the 'close-item' class (close icons)
const closeButtons = document.querySelectorAll('.close-item');

// Loop through each button and add a click event listener
closeButtons.forEach(button => {
    button.addEventListener('click', function(event) {
        // Find the parent 'list-group-item' element and remove it
        const listItem = event.target.closest('.list-group-item');
        listItem.remove();
    });
});