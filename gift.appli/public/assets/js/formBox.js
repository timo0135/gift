document.getElementById("kdo").addEventListener("change", function() {
    var messageKdoContainer = document.getElementById("message_kdo_container");
    if (this.checked) {
        messageKdoContainer.style.display = "block";
    } else {
        messageKdoContainer.style.display = "none";
    }
});