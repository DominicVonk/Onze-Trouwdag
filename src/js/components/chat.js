let chat = document.querySelector(".chat");

let messages = document.querySelector(".chat .messages");
let options = document.querySelector(".chat .options");
function write(_message, outgoing = false) {
  let message = document.createElement("div");
  message.innerHTML = _message;
  message.classList.add("message");
  if (outgoing) {
    message.classList.add("out");
  }
  messages.appendChild(message);
  messages.scrollTop = messages.scrollHeight;
}

export default {
  write,
  clear() {
    messages.innerHTML = "";
    options.innerHTML = "";
  },
  async options(_options) {
    return new Promise(function(resolve) {
      if (Array.isArray(_options)) {
        for (var i = 0; i < _options.length; i++) {
          let option = document.createElement("div");
          if (Array.isArray(_options[i])) {
            option.innerHTML = _options[i][0];
            option.dataValue = _options[i][1];
          } else {
            option.innerHTML = _options[i];
            option.dataValue = _options[i];
          }
          option.classList.add("option");
          option.onclick = function() {
            resolve(option.dataValue);
            write(option.innerHTML, true);
            options.innerHTML = "";
          };
          options.appendChild(option);
        }
      } else if (typeof _options === "object") {
        for (var i in _options) {
          if (_options.hasOwnProperty(i)) {
            let option = document.createElement("div");
            option.classList.add("option");
            option.innerHTML = i;
            option.dataValue = _options[i];
            option.onclick = function() {
              resolve(option.dataValue);
              write(option.innerHTML, true);
              options.innerHTML = "";
            };
            options.appendChild(option);
          }
        }
      }
    });
  }
};
