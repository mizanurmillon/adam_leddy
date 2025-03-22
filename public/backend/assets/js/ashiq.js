document.addEventListener("DOMContentLoaded", () => {
    const buttons = document.querySelectorAll(".robi-select-tags-buttons button");

    buttons.forEach(button => {
        button.addEventListener("click", () => {
            if (button.id !== "select-all" && button.id !== "unselect-all") {
                button.classList.toggle("active");
            }
        });
    });

});