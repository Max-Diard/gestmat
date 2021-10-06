// MENU BURGER
$btnToClose = document.querySelector(".btn-opened");
$btnToOpen = document.querySelector(".btn-closed");
$menu = document.querySelector(".languette-menu");

if ($btnToOpen) {
    $btnToOpen.addEventListener('click', function() {
        $menu.classList.add("menu-opened");
        setTimeout(function(){ 
            $btnToOpen.style.display = "none"; 
            $btnToClose.style.display = "block"; 
        }, 0750);
    });
}

if ($btnToClose) {
    $btnToClose.addEventListener('click', function() {
        $menu.classList.remove("menu-opened");
        setTimeout(function(){ 
            $btnToClose.style.display = "none"; 
            $btnToOpen.style.display = "block"; 
        }, 0500);
    });
}