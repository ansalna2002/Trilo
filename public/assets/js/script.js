// Script

// Sidebar Toggle JS
const sidebarToggle = document.querySelector("#sidebar-toggle");
sidebarToggle.addEventListener("click",function(){
    document.querySelector("#sidebar").classList.toggle("collapsed");
})


// Clone sidebar into Offcanvas
document.addEventListener("DOMContentLoaded", function() {
    var sidebarContents = document.getElementById('sidebar-contents');
    var clonedSidebarContents = sidebarContents.cloneNode(true);
    var offcanvasBody = document.querySelector('.offcanvas-body');
    offcanvasBody.appendChild(clonedSidebarContents);
    clonedSidebarContents.removeAttribute('id');
});
// Clone sidebar into Offcanvas Ends


//  Offcanvas show and hide function under 500px 
window.addEventListener('DOMContentLoaded', function() {
    var button = document.getElementById('sidebar-toggle');
    var screenWidth = window.innerWidth;

    function addAttributes() {
        button.setAttribute('data-bs-toggle', 'offcanvas');
        button.setAttribute('data-bs-target', '#offcanvasScrolling');
        button.setAttribute('aria-controls', 'offcanvasScrolling');
    }

    function removeAttributes() {
        button.removeAttribute('data-bs-toggle');
        button.removeAttribute('data-bs-target');
        button.removeAttribute('aria-controls');
    }

    function handleScreenWidth() {
        if (screenWidth < 500) {
            addAttributes();
        } else {
            removeAttributes();
        }
    }

    handleScreenWidth();

    window.addEventListener('resize', function() {
        screenWidth = window.innerWidth;
        handleScreenWidth();
    });
});

// Ends


// Sidebar Page Aciveness
// document.addEventListener("DOMContentLoaded", function () {
//     var currentPagePath = window.location.pathname;
//     var sidebarLinks = document.querySelectorAll('.sidebar-link');
//     sidebarLinks.forEach(function(link) {
//         link.classList.remove('active');
//         link.closest('.sidebar-item').classList.remove('active');
//     });
//     for (var i = 0; i < sidebarLinks.length; i++) {
     
//         if (currentPagePath.includes(sidebarLinks[i].getAttribute('href'))) {
         
//             sidebarLinks[i].classList.add('active');
//             sidebarLinks[i].closest('.sidebar-item').classList.add('active');
          
//             break;
//         }
//     }
// });

// Sidebar Toggle JS Ends



// JS for Sidebar Navlink Active/Inactive
document.addEventListener("DOMContentLoaded", function() {
  var currentPageURL = window.location.href;
  var sidebarLinks = document.querySelectorAll('.sidebar-link');
  
  sidebarLinks.forEach(function(link) {
      var linkURL = link.href;
      
      // Remove trailing slashes for consistent comparison
      var normalizedCurrentPageURL = currentPageURL.replace(/\/+$/, '');
      var normalizedLinkURL = linkURL.replace(/\/+$/, '');

      // Check for exact match or match with query parameters/fragments
      if (normalizedCurrentPageURL === normalizedLinkURL || normalizedCurrentPageURL.startsWith(normalizedLinkURL + "?") || normalizedCurrentPageURL.startsWith(normalizedLinkURL + "#")) {
          link.classList.add('active'); 
      }
  });
});

// JS for Sidebar Navlink Active/Inactive Ends

// Sub Menu Active / Inactive
document.addEventListener("DOMContentLoaded", function() {
  function addCollapsedShow(menuId) {
      var menu = document.getElementById(menuId);
      if (menu.querySelector('.sidebar-link.active')) {
          menu.classList.add("show");
          var parentLink = menu.parentElement.querySelector('.sidebar-link.dropdown-submenu-link');
          if (parentLink) {
              parentLink.classList.add("active");
          }
      }
  }

  addCollapsedShow("ProductMaster");
  addCollapsedShow("ProductOrders");
});


// JS for Modal Starts
document.addEventListener("DOMContentLoaded", function() {
    var modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
      modal.addEventListener('hide.bs.modal', function () {
        setTimeout(function() {
          modal.classList.add('zoom-out');
        }, 12); 
      });
      modal.addEventListener('show.bs.modal', function () {
        modal.classList.remove('zoom-out');
      });
    });
  });
  // JS for Modal Ends







// Breadcrumb

function redirectToPreviousPage() {
    window.history.back();
}







// Tooltip

document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});





// Toast

    document.addEventListener('DOMContentLoaded', function() {
    var toast = document.querySelector('.toast');
    if (toast) {
        setTimeout(function() {
        toast.classList.add('hidden');
        }, 3000); // 3 seconds
    }
    });






      // Update icon

      document.addEventListener('DOMContentLoaded', function() {
        var updateBtn = document.getElementById('update-btn');
        var updateIcon = document.getElementById('update-icon');
      
        updateBtn.addEventListener('click', function() {
          updateIcon.classList.add('rotate');
      
          // Stop rotation after 4 seconds
          setTimeout(function() {
            updateIcon.classList.remove('rotate');
          }, 4000);
        });
      });



      // Reset icon

      document.addEventListener('DOMContentLoaded', function() {
        var resetBtn = document.getElementById('reset-btn');
        var resetIcon = document.getElementById('reset-icon');
      
        resetBtn.addEventListener('click', function() {
          resetIcon.classList.add('rotate');
      
          // Stop rotation after 4 seconds
          setTimeout(function() {
            resetIcon.classList.remove('rotate');
          }, 3000);
        });
      });




