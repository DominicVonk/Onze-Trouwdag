import "./reset/js";
import writer from "./anim/writer";
import "./components/pincode";
import "./components/trigger";
import triggerHookModal from "./components/trigger/hooks/modal";
import chat from "./components/chat";
window.hooks["modal"] = triggerHookModal;
window.triggerEnabled = false;
if (document.querySelector(".welcome")) {
  (async function () {
    document.querySelector(".welcome").innerHTML = document
      .querySelector(".welcome")
      .innerHTML.replace(
        "[greeting]",
        new Date().getHours() >= 0 && new Date().getHours() < 5
          ? "Goedenacht"
          : new Date().getHours() >= 5 && new Date().getHours() < 12
          ? "Goedemorgen"
          : new Date().getHours() >= 12 && new Date().getHours() < 18
          ? "Goedemiddag"
          : "Goedenavond",
      );
    var h1 = writerPrepare(
      document.querySelector(".welcome", { charTime: 64 }),
    );
    await h1();
    await waitFor(1000);
    await new Promise(function (resolve) {
      TweenMax.to(".welcome", 0.7, {
        css: {
          top: window.innerWidth >= 960 ? "40%" : "30%",
          fontSize: window.innerWidth >= 960 ? "24px" : "10px",
        },
        ease: Power2.easeOut,
        onComplete: resolve,
      });
    });

    await waitFor(200);
    new Promise(function (resolve) {
      TweenMax.to(".card:nth-child(1)", 0.7, {
        opacity: 1,
        y: 0,
        ease: Power2.easeOut,
        onComplete: resolve,
      });
    });
    await waitFor(200);
    new Promise(function (resolve) {
      TweenMax.to(".card:nth-child(2)", 0.7, {
        opacity: 1,
        y: 0,
        ease: Power2.easeOut,
        onComplete: resolve,
      });
    });
    await waitFor(200);
    new Promise(function (resolve) {
      TweenMax.to(".card:nth-child(3)", 0.7, {
        opacity: 1,
        y: 0,
        ease: Power2.easeOut,
        onComplete: resolve,
      });
    });
    await waitFor(200);
    new Promise(function (resolve) {
      TweenMax.to(".card:nth-child(4)", 0.7, {
        opacity: 1,
        y: 0,
        ease: Power2.easeOut,
        onComplete: resolve,
      });
    });
    await waitFor(200);
    new Promise(function (resolve) {
      TweenMax.to(".card:nth-child(5)", 0.7, {
        opacity: 1,
        y: 0,
        ease: Power2.easeOut,
        onComplete: resolve,
      });
    });
    await waitFor(200);
    new Promise(function (resolve) {
      TweenMax.to(".card:nth-child(6)", 0.7, {
        opacity: 1,
        y: 0,
        ease: Power2.easeOut,
        onComplete: resolve,
      });
    });
    window.triggerEnabled = true;
  })();
}
async function waitFor(time) {
  return new Promise(function (r) {
    setTimeout(function () {
      r();
    }, time);
  });
}
function writerPrepare(element, options) {
  options = Object.assign(
    {
      breakDoubleSpace: true,
    },
    options,
  );
  element.style.height = element.clientHeight + "px";
  element.style.width = element.clientWidth + "px";
  var htmlToDraw = element.innerText;
  if (options.breakDoubleSpace) {
    htmlToDraw = htmlToDraw.split("  ").join("\n");
  }
  element.innerText = "";
  element.style.opacity = 1;
  return () => writer([element, htmlToDraw], options);
}

async function sendChoice(option) {
  await fetch("/api/attendence", {
    headers: {
      "Content-Type": "application/json",
    },
    method: "POST",
    body: JSON.stringify(option),
    credentials: "same-origin",
  });
  if (option.status) {
    code.status = option.status + "";
  }
  if (typeof option.volwassene !== "undefined") {
    code.adults = option.volwassene;
  }
  if (typeof option.kinderen !== "undefined") {
    code.children = option.kinderen;
  }
  chat.write("Wij hebben je keuze opgeslagen.");
}

window.aanwezigheid = async function (force) {
  let options;
  if (!force) {
    if (code.status === "1") {
      var jullieofje = parseInt(code.adults) + parseInt(code.children) > 1
        ? "jullie komen"
        : "je komt";
      chat.write(
        `Leuk dat ${jullieofje}. Wij rekenen op ` +
          ((code.adults == 1 ? "1 volwassene" : code.adults + " volwassenen") +
            (code.children > 0
              ? " en " +
                (code.children == 1 ? "1 kind" : code.children + " kinderen")
              : "")) +
          ", klopt dat?",
      );
      options = await chat.options({ Ja: false, Nee: true });

      if (!options) {
        chat.write(
          `Gezellig! Mocht dit veranderen, dan kun je dit voor ${datum} via deze pagina doorgeven.`,
        );

        return;
      }
    } else if (code.status === "2") {
      chat.write("Jammer dat je niet kunt komen. Is dit nu veranderd?");
      options = await chat.options({ Ja: true, Nee: false });
      if (!options) {
        chat.write(
          "Oh jammer, wanneer dit wel mocht veranderen, kun je dit vóór " +
            datum +
            " via deze pagina doorgeven. PS Neem later nog eens een kijkje op onze website voor de foto's!",
        );
        return;
      }
    }
  }

  chat.write("We hopen deze bijzondere dag met je te vieren. Ben je erbij? ");
  options = await chat.options({ Ja: true, Nee: false });
  chat.write(
    options
      ? " Geweldig. 😊 Neem je nog andere volwassenen of kinderen vanaf 12 jaar mee? "
      : "Jammer dat jullie niet kunnen komen. Mocht dit nog veranderen, dan kun je dit vóór " +
        datum +
        " via deze pagina doorgeven. PS Neem later nog eens een kijkje op onze website voor de foto's!",
  );

  if (!options) {
    sendChoice({ status: 2 });
    return;
  }
  let volwassene = await chat.options([
    ["Nee", 1],
    [1, 2],
    [2, 3],
    [3, 4],
    [4, 5],
  ]);
  chat.write("En kom je met kinderen onder de 12 jaar?");
  let kinderen = await chat.options([
    ["Nee", 0],
    [1, 1],
    [2, 2],
    [3, 3],
    [4, 4],
  ]);
  var jullieofje = parseInt(volwassene) + parseInt(kinderen) > 1
    ? "jullie komen"
    : "je komt";

  chat.write(
    `Leuk dat ${jullieofje}. Wij rekenen op ` +
      ((volwassene == 1 ? "1 volwassene" : volwassene + " volwassenen") +
        (kinderen > 0
          ? " en " + (kinderen == 1 ? "1 kind" : kinderen + " kinderen")
          : "")) +
      ", klopt dat?",
  );
  options = await chat.options({ Ja: true, Nee: false });
  chat.write(
    options
      ? `Gezellig. We voegen je toe aan de gastenlijst.  Mocht dit nog veranderen, dan kun je dit vóór ${datum} via deze pagina doorgeven.`
      : "Wil je de keuze veranderen?",
  );
  if (options) {
    sendChoice({ volwassene, kinderen, status: 1 });
    return;
  }
  options = await chat.options({ Ja: true, Nee: false });
  if (!options) {
    chat.write(
      `Gezellig. We voegen je toe aan de gastenlijst.  Mocht dit nog veranderen, dan kun je dit vóór ${datum} via deze pagina doorgeven.`,
    );
    await sendChoice({ volwassene, kinderen, status: 1 });
  } else {
    chat.clear();
    window.aanwezigheid();
  }
};

window.aanwezigheidSluiten = async function () {
  chat.clear();
};
