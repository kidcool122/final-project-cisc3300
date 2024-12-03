console.log("homepage.js loaded successfully");

// Function to animate the fruit and navigate
function animateAndNavigate(page) {
    // Get the fruit element
    const fruit = document.getElementById(page).querySelector(".fruit");

    // Add the squeezing animation class
    fruit.classList.add("squeezing");

    // Wait for the animation to complete before navigating
    setTimeout(() => {
        fruit.classList.remove("squeezing");
        navigateTo(page); // Navigate to the selected page
    }, 600); // Matches the animation duration (0.6s)
}

// Function to handle navigation
function navigateTo(page) {
    const baseUrl = 'http://localhost:8888'; // Adjust for your MAMP setup
    const url = `${baseUrl}/${page}`;
    console.log(`Navigating to: ${url}`);
    window.location.href = url;
}
