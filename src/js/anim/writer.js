export default function writer(
  [element, htmlToDraw],
  { charTime = 40, charsPerTime = 1 },
) {
  return new Promise(function (resolve) {
    var chars = 0;
    var dt = +new Date();
    if (!sessionStorage.loaded) {
      window.requestAnimationFrame(function caller() {
        var _dt = +new Date();
        if (_dt - dt < charTime) {
          window.requestAnimationFrame(caller);
          return;
        }
        dt = _dt;
        chars += charsPerTime;
        if (chars <= htmlToDraw.length) {
          element.innerHTML = htmlToDraw.substr(0, chars);
          window.requestAnimationFrame(caller);
        } else {
          element.style.height = "auto";
          element.style.width = "auto";
          resolve();
        }
      });
    } else {
      element.innerHTML = htmlToDraw;
      element.style.height = "auto";
      element.style.width = "auto";
      resolve();
    }
  });
}
