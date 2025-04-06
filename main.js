
const ipDisplay = document.getElementById("ip-display");
const chatBox = document.getElementById("chat-box");

// 使用 ipapi.co 來判斷地區，不需註冊 token
fetch("https://ipapi.co/json/")
  .then(res => res.json())
  .then(data => {
    const ip = data.ip;
    const country = data.country;
    ipDisplay.innerText = `IP: ${ip} (${country})`;

    if (country !== "TW") {
      document.body.innerHTML = "<h2>此網站僅限台灣地區使用。</h2>";
    }
  });

async function sendMessage() {
  const input = document.getElementById("user-input");
  const message = input.value.trim();
  if (!message) return;

  appendMessage("user", message);
  input.value = "";

  const response = await fetch("https://api.openai.com/v1/chat/completions", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Authorization": "Bearer YOUR_OPENAI_API_KEY"
    },
    body: JSON.stringify({
      model: "gpt-3.5-turbo",
      messages: [{ role: "user", content: message }]
    })
  });

  const data = await response.json();
  const reply = data.choices[0].message.content;
  appendMessage("gpt", reply);
}

function appendMessage(sender, text) {
  const msg = document.createElement("div");
  msg.innerHTML = `<strong>${sender === "user" ? "你" : "ChatGPT"}:</strong> ${text}`;
  chatBox.appendChild(msg);
  chatBox.scrollTop = chatBox.scrollHeight;
}
