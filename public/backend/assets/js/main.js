
// toggle sidebar desktop
const sidebar = document.querySelector(".sidebar");
const sidebarCloser = document.querySelector(".desktop-sidebar-closer");
const menuIcon = document.querySelector(".menu-icon");
// Function to check screen width
function isLargeScreen() {
return window.innerWidth > 1200;
}
// Function to update sidebar styles based on screen width
function updateSidebar() {
if (sidebar) {
if (isLargeScreen()) {
sidebar.style.position = "sticky";
sidebar.style.top = "0";
sidebar.style.left = "0";
sidebar.style.width = "300px";
sidebar.style.height = "100vh";
sidebar.style.backgroundColor = "#333";
sidebar.style.transition = "left 0.3s ease-in-out";
} else {
sidebar.style.position = ""; // Reset styles for small screens
sidebar.style.left = "";
sidebar.style.width = "";
sidebar.style.height = "";
sidebar.style.backgroundColor = "";
sidebar.style.transition = "";
}
}
if (sidebarCloser) {
sidebarCloser.style.display = "none";
}
}
// Apply styles on page load
window.onload = updateSidebar;
// Update styles on resize
window.addEventListener("resize", updateSidebar);
// Open sidebar only if screen width is more than 1200px
menuIcon?.addEventListener("click", function () {
if (sidebar && isLargeScreen()) {
sidebar.style.position = "fixed";
sidebar.style.left = "-300px";
}
if (sidebarCloser && isLargeScreen()) {
sidebarCloser.style.display = "block";
}
});
// Close sidebar only if screen width is more than 1200px
sidebarCloser?.addEventListener("click", function () {
if (sidebar && isLargeScreen()) {
sidebar.style.left = "0";
setTimeout(() => {
sidebar.style.position = "sticky";
}, 300);
}
if (sidebarCloser && isLargeScreen()) {
sidebarCloser.style.display = "none";
}
});


// for mobile sidebar
    // Toggle menu and body scroll on menu click
    document.querySelector('.menu-btn-mobile')?.addEventListener('click', function() {
      document.querySelector('.sidebar').classList.toggle('show');
      document.body.classList.toggle('no-scroll');
  });

  // Close the sidebar if clicking outside of it
  document.addEventListener('click', function(event) {
      // Check if the click is outside the sidebar and the menu button
      if (!event.target.closest('.sidebar') && !event.target.closest('.menu-btn-mobile')) {
          if (document.querySelector('.sidebar').classList.contains('show')) {
              document.querySelector('.sidebar').classList.remove('show');
              document.body.classList.remove('no-scroll');
          }
      }
  });


  // for desktop sidebar toggle
    document.querySelector('.menu-icon')?.addEventListener('click', function() {
      // document.querySelector('.sidebar').style.transition='left 0.3s ease-in-out';
      document.querySelector('.sidebar').style.position='fixed';
      document.querySelector('.sidebar').style.left='-300px';

  });

