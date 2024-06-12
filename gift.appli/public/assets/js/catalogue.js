document.addEventListener('DOMContentLoaded', function() {
    var sidebar = document.getElementById('sidebar');
    var toggleBtn = document.getElementById('toggle-btn');
    var catalogueContent = document.getElementById('catalogue-content');
    var header = document.querySelector('header');
    var nav = document.querySelector('nav');
    var footer = document.querySelector('footer');

    toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('open');
        catalogueContent.classList.toggle('shifted');
        header.classList.toggle('shifted');
        nav.classList.toggle('shifted');
        footer.classList.toggle('shifted');
    });
});
