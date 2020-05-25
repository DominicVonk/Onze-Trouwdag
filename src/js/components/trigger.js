window.hooks = {};
window.triggerEnabled = true;
document.querySelectorAll("[data-trigger]").forEach(function(e) {
  e.addEventListener("click", function() {
    if (window.triggerEnabled) {
      window.hooks[e.getAttribute("data-trigger").split("/")[0]](
        e,
        e.getAttribute("data-trigger").split("/")[1]
      );
    }
  });
});
