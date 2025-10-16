const chatBox = document.querySelector(".chat-box");
const kode_unik = document.querySelector(".kode_unik").value;

chatBox.onmouseenter = () => {
  chatBox.classList.add("active");
};

chatBox.onmouseleave = () => {
  chatBox.classList.remove("active");
};

window.addEventListener("load", () => {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "../chats/get-chat.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        chatBox.innerHTML = data;
        if (!chatBox.classList.contains("active")) {
          scrollToBottom();
        }
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("incoming_id=" + incoming_id);
});

function scrollToBottom() {
  chatBox.scrollTop = chatBox.scrollHeight;
}
