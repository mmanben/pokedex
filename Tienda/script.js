document.addEventListener("DOMContentLoaded", () => {
    // Simular pantalla de carga
    const loadingScreen = document.getElementById("loading-screen");
    const mainContainer = document.getElementById("main-container");

    setTimeout(() => {
        loadingScreen.style.display = "none";
        mainContainer.style.display = "block";
    }, 3000); // Simula un tiempo de carga

    // Manejo del carrito
    const cartSection = document.getElementById("cart-section");
    const cartButton = document.getElementById("cart");

    cartButton.addEventListener("click", () => {
        cartSection.style.display = cartSection.style.display === "none" ? "block" : "none";
    });
});
document.addEventListener("DOMContentLoaded", () => {
    // Simular pantalla de carga
    const loadingScreen = document.getElementById("loading-screen");
    const mainContainer = document.getElementById("main-container");

    setTimeout(() => {
        loadingScreen.style.display = "none";
        mainContainer.style.display = "block";
    }, 3000); // Simula un tiempo de carga

    // Manejo del carrito
    const cartSection = document.getElementById("cart-section");
    const cartButton = document.getElementById("cart");

    cartButton.addEventListener("click", () => {
        cartSection.style.display = cartSection.style.display === "none" ? "block" : "none";
    });
});
