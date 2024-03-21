function showSideBar(){
    const sideBar = document.querySelector('.nascondi')
    sideBar.classList.remove("nascondi") 
    sideBar.classList.add("sideBar")
}

function hideSideBar(){
    const sideBar = document.querySelector('.sideBar')
    sideBar.classList.remove("sideBar")
    sideBar.classList.add("nascondi")
}