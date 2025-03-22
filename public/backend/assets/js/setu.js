// document.addEventListener("DOMContentLoaded", function () {
//   document
//     .querySelectorAll(".se--category-section, .se--filter--course-category")
//     .forEach((section) => {
//       const buttons = section.querySelectorAll(".se-category-btn");

//       buttons.forEach((button) => {
//         button.addEventListener("click", function () {
//           // Remove active class from all buttons in the current section
//           buttons.forEach((btn) => btn.classList.remove("se-active"));

//           // Add active class to clicked button
//           this.classList.add("se-active");
//         });
//       });
//     });
// });

document.addEventListener("DOMContentLoaded", function () {
  document
    .querySelectorAll(".se--category-section, .se--filter--course-category")
    .forEach((section) => {
      const buttons = section.querySelectorAll(".se-category-btn");

      // Find the "All" button in the current section and make it active by default
      const allButton = Array.from(buttons).find(
        (btn) => btn.textContent.trim() === "All"
      );
      if (allButton) {
        allButton.classList.add("se-active");
      }

      buttons.forEach((button) => {
        button.addEventListener("click", function () {
          // Remove active class from all buttons in the current section
          buttons.forEach((btn) => btn.classList.remove("se-active"));

          // Add active class to clicked button
          this.classList.add("se-active");
        });
      });
    });
});

function openModal(id) {
  let modal = document.getElementById(id);
  modal.style.display = "flex";
  setTimeout(() => {
    modal.style.opacity = "1";
    modal.querySelector(".se--modal-content").style.transform = "scale(1)";
  }, 10);
}

function closeModal(id) {
  let modal = document.getElementById(id);
  modal.style.opacity = "0";
  modal.querySelector(".se--modal-content").style.transform = "scale(0.9)";
  setTimeout(() => {
    modal.style.display = "none";
  }, 300);
}

function closeOutside(event, id) {
  let modal = document.getElementById(id);
  if (event.target === modal) {
    closeModal(id);
  }
}

// add tages
document.addEventListener("DOMContentLoaded", function () {
  // Get references to the necessary elements
  const addTagsButton = document.querySelector(".se--add-tags");
  const tagInputLayout = document.getElementById("tag-input-add");
  const tagInput = document.querySelector(".se--tag");
  const addTagButton = document.querySelector(".se-add-tag");
  const allTagsSection = document.querySelector(".se--allTags");

  // Array of predefined colors for the tags
  const tagColors = [
    "#FFA640",
    "#29FF65",
    "#FF4040",
    "#40BFFF",
    "#FFD700",
    "#FF69B4",
    "#7B68EE",
    "#32CD32",
    "#FF6347",
    "#8A2BE2",
  ];

  // Initially hide the input field
  tagInputLayout.style.display = "none";

  // Show the input field when the "Add Tags" button is clicked
  addTagsButton.addEventListener("click", function () {
    tagInputLayout.style.display = "flex"; // or 'block' depending on your layout
    tagInput.focus(); // Focus on the input field
  });

  // Add the new tag when the "Add" button is clicked
  addTagButton.addEventListener("click", function () {
    const tagName = tagInput.value.trim();

    if (tagName) {
      // Create a new button element
      const newTagButton = document.createElement("button");
      newTagButton.className = "se-trending-btn";

      // Assign a color from the array (sequential or random)
      const randomColor =
        tagColors[Math.floor(Math.random() * tagColors.length)]; // Random color
      newTagButton.style.backgroundColor = randomColor;

      // Set the text of the button
      newTagButton.innerHTML = `<p>${tagName}</p>`;

      // Insert the new button before the "Add Tags" button
      allTagsSection.insertBefore(newTagButton, addTagsButton);

      // Clear the input field and hide it
      tagInput.value = "";
      tagInputLayout.style.display = "none";
    }
  });

  // Optional: Add the new tag when pressing "Enter" in the input field
  tagInput.addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
      addTagButton.click(); // Trigger the click event on the "Add" button
    }
  });
});

// add category
document.addEventListener("DOMContentLoaded", function () {
  // Get references to the necessary elements for the category section
  const addCategoryButton = document.querySelector(".se--add-category");
  const categoryInputLayout = document.getElementById("category-input-add");
  const categoryInput = document.querySelector("#category-input-add .se--tag"); // Input field for category
  const addCategoryTagButton = document.querySelector(
    "#category-input-add .se-add-tag"
  ); // Add button for category
  const allCategoriesSection = document.querySelector(".se--Category");

  // Initially hide the input field
  categoryInputLayout.style.display = "none";

  // Show the input field when the "Add Category" button is clicked
  addCategoryButton.addEventListener("click", function () {
    categoryInputLayout.style.display = "flex"; // or 'block' depending on your layout
    categoryInput.focus(); // Focus on the input field
  });

  // Add the new category when the "Add" button is clicked
  addCategoryTagButton.addEventListener("click", function () {
    const categoryName = categoryInput.value.trim();

    if (categoryName) {
      // Create a new button element
      const newCategoryButton = document.createElement("button");
      newCategoryButton.className = "se--category-button"; // Use the same class as existing category buttons
      newCategoryButton.textContent = categoryName; // Set the text of the button

      // Insert the new button before the "Add Category" button
      allCategoriesSection.insertBefore(newCategoryButton, addCategoryButton);

      // Clear the input field and hide it
      categoryInput.value = "";
      categoryInputLayout.style.display = "none";
    }
  });

  // Optional: Add the new category when pressing "Enter" in the input field
  categoryInput.addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
      addCategoryTagButton.click(); // Trigger the click event on the "Add" button
    }
  });
});
